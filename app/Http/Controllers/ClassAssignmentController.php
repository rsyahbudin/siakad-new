<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassroomAssignment;
use App\Models\AcademicYear;
use App\Models\ClassStudent;
use App\Models\Classroom;
use App\Models\Major;
use App\Services\ClassPlacementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClassAssignmentController extends Controller
{
    // Tampilkan form pembagian kelas (per tahun ajaran aktif)
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();

        if (!$activeYear) {
            return back()->withErrors(['error' => 'Tidak ada tahun ajaran aktif.']);
        }

        $classroomAssignments = ClassroomAssignment::with('classroom', 'homeroomTeacher')
            ->where('academic_year_id', $activeYear->id)
            ->get();

        // Query siswa yang aktif (bukan lulus atau pindah)
        $query = Student::where('status', 'Aktif')
            ->with(['user', 'classStudents' => function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            }, 'classStudents.classroomAssignment.classroom']);

        // Filter by search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nis', 'like', "%$q%")
                    ->orWhere('full_name', 'like', "%$q%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%$q%");
                    });
            });
        }

        // Filter by kelas
        if ($request->filled('kelas_filter')) {
            $assignmentId = $request->kelas_filter;
            $query->whereHas('classStudents', function ($c) use ($assignmentId) {
                $c->where('classroom_assignment_id', $assignmentId);
            });
        }

        // Filter by status (sudah ditempatkan/belum)
        if ($request->filled('status_filter')) {
            if ($request->status_filter === 'placed') {
                $query->whereHas('classStudents', function ($c) use ($activeYear) {
                    $c->where('academic_year_id', $activeYear->id);
                });
            } elseif ($request->status_filter === 'not_placed') {
                $query->whereDoesntHave('classStudents', function ($c) use ($activeYear) {
                    $c->where('academic_year_id', $activeYear->id);
                });
            }
        }

        $students = $query->orderBy('full_name')->paginate(20)->withQueryString();

        // Get class placement statistics
        $placementStats = $this->getPlacementStatistics($activeYear);

        return view('admin.pembagian-kelas', compact(
            'students',
            'classroomAssignments',
            'activeYear',
            'activeSemester',
            'placementStats'
        ));
    }

    // Proses bulk assign siswa ke kelas
    public function store(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return back()->withErrors(['error' => 'Tidak ada tahun ajaran aktif.']);
        }

        $assignmentIds = ClassroomAssignment::where('academic_year_id', $activeYear->id)->pluck('id')->toArray();
        $data = $request->input('assignments', []);

        $successCount = 0;
        $errorCount = 0;

        try {
            DB::beginTransaction();

            foreach ($data as $studentId => $assignmentId) {
                if (empty($assignmentId)) {
                    continue; // Skip if no assignment selected
                }

                $student = Student::where('status', 'Aktif')->find($studentId);
                if ($student && in_array($assignmentId, $assignmentIds)) {
                    // Hapus penempatan lama di tahun ajaran aktif
                    ClassStudent::where('student_id', $studentId)
                        ->where('academic_year_id', $activeYear->id)
                        ->delete();

                    // Assign baru
                    ClassStudent::create([
                        'classroom_assignment_id' => $assignmentId,
                        'academic_year_id' => $activeYear->id,
                        'student_id' => $studentId,
                    ]);

                    $successCount++;
                    Log::info("Student {$student->full_name} assigned to class via manual assignment");
                } else {
                    $errorCount++;
                }
            }

            DB::commit();

            $message = "Pembagian kelas berhasil disimpan. {$successCount} siswa berhasil ditempatkan.";
            if ($errorCount > 0) {
                $message .= " {$errorCount} siswa gagal ditempatkan.";
            }

            return redirect()->route('pembagian.kelas')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to assign students to classes: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan pembagian kelas.']);
        }
    }

    // Auto-place students based on their major/grade
    public function autoPlaceStudents(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return back()->withErrors(['error' => 'Tidak ada tahun ajaran aktif.']);
        }

        try {
            DB::beginTransaction();

            // Get active students without class placement
            $unplacedStudents = Student::where('status', 'Aktif')
                ->whereDoesntHave('classStudents', function ($q) use ($activeYear) {
                    $q->where('academic_year_id', $activeYear->id);
                })->get();

            $successCount = 0;
            $errorCount = 0;

            foreach ($unplacedStudents as $student) {
                // Try to place based on existing data or default to X IPA
                $targetGrade = 'X';
                $targetMajor = 'IPA';

                // Check if student has any existing class data
                if ($student->classrooms->isNotEmpty()) {
                    $lastClass = $student->classrooms->last();
                    $targetGrade = $lastClass->grade;
                    $targetMajor = $lastClass->major->name;
                }

                $placementSuccess = ClassPlacementService::placeTransferStudent($student, $targetGrade, $targetMajor);

                if ($placementSuccess) {
                    $successCount++;
                } else {
                    $errorCount++;
                }
            }

            DB::commit();

            $message = "Auto-placement selesai. {$successCount} siswa berhasil ditempatkan otomatis.";
            if ($errorCount > 0) {
                $message .= " {$errorCount} siswa gagal ditempatkan.";
            }

            return redirect()->route('pembagian.kelas')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to auto-place students: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat auto-placement.']);
        }
    }

    // Get placement statistics
    private function getPlacementStatistics($activeYear)
    {
        // Hanya hitung siswa yang aktif
        $totalStudents = Student::where('status', 'Aktif')->count();
        $placedStudents = Student::where('status', 'Aktif')
            ->whereHas('classStudents', function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })->count();
        $unplacedStudents = $totalStudents - $placedStudents;

        // Get class statistics using classroom assignments
        $classroomAssignments = ClassroomAssignment::with(['classroom', 'classStudents' => function ($q) use ($activeYear) {
            $q->where('academic_year_id', $activeYear->id);
        }])
            ->where('academic_year_id', $activeYear->id)
            ->get();

        $classStats = $classroomAssignments->map(function ($assignment) {
            $studentCount = $assignment->classStudents->count();
            return [
                'name' => $assignment->classroom->name,
                'student_count' => $studentCount,
                'capacity' => $assignment->classroom->capacity ?? 36,
                'percentage' => ($assignment->classroom->capacity ?? 36) > 0 ? round(($studentCount / ($assignment->classroom->capacity ?? 36)) * 100, 1) : 0
            ];
        });

        return [
            'total_students' => $totalStudents,
            'placed_students' => $placedStudents,
            'unplaced_students' => $unplacedStudents,
            'placement_percentage' => $totalStudents > 0 ? round(($placedStudents / $totalStudents) * 100, 1) : 0,
            'class_stats' => $classStats
        ];
    }
}

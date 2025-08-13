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
use App\Models\TransferStudent; // Added this import

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

        // Query siswa yang aktif dan pindahan (bukan lulus atau keluar)
        $query = Student::whereIn('status', ['Aktif', 'Pindahan'])
            ->with(['user', 'classStudents' => function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            }, 'classStudents.classroomAssignment.classroom', 'ppdbApplication']);

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

        // Filter by minat jurusan (dari PPDB atau major_interest untuk transfer)
        if ($request->filled('major_filter')) {
            $major = $request->major_filter;
            $query->where(function ($q) use ($major) {
                $q->whereHas('ppdbApplication', function ($p) use ($major) {
                    $p->where('desired_major', $major);
                })->orWhere('major_interest', $major);
            });
        }

        // Filter by status siswa (Aktif/Pindahan)
        if ($request->filled('student_status_filter')) {
            $query->where('status', $request->student_status_filter);
        }

        // Preload transfer student data for students with status 'Pindahan'
        $students = $query->orderBy('full_name')->paginate(20)->withQueryString();

        // Load transfer data for students with status 'Pindahan'
        $transferNisns = $students->where('status', 'Pindahan')->pluck('nisn')->toArray();
        $transferData = collect();
        if (!empty($transferNisns)) {
            $transferData = TransferStudent::whereIn('nisn', $transferNisns)->get()->keyBy('nisn');
        }

        // Attach transfer data to students
        $students->getCollection()->transform(function ($student) use ($transferData) {
            if ($student->status === 'Pindahan') {
                $student->transfer_data = $transferData->get($student->nisn);
            }
            return $student;
        });

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

        $request->validate([
            'assignments' => 'required|array',
            'assignments.*' => 'nullable|exists:classroom_assignments,id'
        ]);

        $assignmentIds = ClassroomAssignment::where('academic_year_id', $activeYear->id)->pluck('id')->toArray();
        $data = $request->input('assignments', []);

        // Validate that all assignment IDs are valid for current academic year
        $invalidAssignments = array_filter($data, function ($assignmentId) use ($assignmentIds) {
            return !empty($assignmentId) && !in_array($assignmentId, $assignmentIds);
        });

        if (!empty($invalidAssignments)) {
            return back()->withErrors(['error' => 'Beberapa ID kelas tidak valid untuk tahun ajaran aktif.']);
        }

        // Debug: Log the data being processed
        Log::info('Class assignment data received:', [
            'total_assignments' => count($data),
            'valid_assignment_ids' => $assignmentIds,
            'data' => $data
        ]);

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        try {
            DB::beginTransaction();

            foreach ($data as $studentId => $assignmentId) {
                if (empty($assignmentId)) {
                    continue; // Skip if no assignment selected
                }

                // Debug: Log each student being processed
                Log::info("Processing student assignment:", [
                    'student_id' => $studentId,
                    'assignment_id' => $assignmentId
                ]);

                $student = Student::whereIn('status', ['Aktif', 'Pindahan'])->find($studentId);
                if ($student && in_array($assignmentId, $assignmentIds)) {
                    // Debug: Log successful assignment
                    Log::info("Student found and assignment valid:", [
                        'student_id' => $studentId,
                        'student_name' => $student->full_name,
                        'student_status' => $student->status,
                        'assignment_id' => $assignmentId
                    ]);

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
                    Log::info("Student {$student->full_name} (Status: {$student->status}) assigned to class via manual assignment");
                } else {
                    $errorCount++;
                    if ($student) {
                        $errorMsg = "Gagal menempatkan {$student->full_name} (Status: {$student->status}) - Kelas tidak valid";
                        $errors[] = $errorMsg;
                        Log::warning("Failed to assign student {$student->full_name} (Status: {$student->status}) - Invalid assignment ID: {$assignmentId}");
                    } else {
                        $errorMsg = "Siswa dengan ID {$studentId} tidak ditemukan atau status tidak valid";
                        $errors[] = $errorMsg;
                        Log::warning("Failed to assign student ID {$studentId} - Student not found or invalid status");
                    }
                }
            }

            DB::commit();

            $message = "Pembagian kelas berhasil disimpan. {$successCount} siswa berhasil ditempatkan.";
            if ($errorCount > 0) {
                $message .= " {$errorCount} siswa gagal ditempatkan.";
                return redirect()->route('pembagian.kelas')
                    ->with('success', $message)
                    ->with('errors', $errors);
            }

            return redirect()->route('pembagian.kelas')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to assign students to classes: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // Return more detailed error for debugging
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan pembagian kelas: ' . $e->getMessage(),
                'debug' => 'File: ' . $e->getFile() . ' Line: ' . $e->getLine()
            ]);
        }
    }

    // Bulk actions for class assignment
    public function bulkAction(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return response()->json(['success' => false, 'message' => 'Tidak ada tahun ajaran aktif.']);
        }

        $action = $request->input('action');
        $studentIds = $request->input('student_ids', []);
        $targetClassId = $request->input('target_class_id');

        if (empty($studentIds)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada siswa yang dipilih.']);
        }

        try {
            DB::beginTransaction();

            $successCount = 0;
            $errorCount = 0;

            if ($action === 'move') {
                if (empty($targetClassId)) {
                    return response()->json(['success' => false, 'message' => 'Kelas tujuan tidak dipilih.']);
                }

                // Verify target class exists and belongs to active year
                $targetAssignment = ClassroomAssignment::where('id', $targetClassId)
                    ->where('academic_year_id', $activeYear->id)
                    ->first();

                if (!$targetAssignment) {
                    return response()->json(['success' => false, 'message' => 'Kelas tujuan tidak valid.']);
                }

                foreach ($studentIds as $studentId) {
                    $student = Student::whereIn('status', ['Aktif', 'Pindahan'])->find($studentId);
                    if ($student) {
                        // Remove existing assignment
                        ClassStudent::where('student_id', $studentId)
                            ->where('academic_year_id', $activeYear->id)
                            ->delete();

                        // Create new assignment
                        ClassStudent::create([
                            'classroom_assignment_id' => $targetClassId,
                            'academic_year_id' => $activeYear->id,
                            'student_id' => $studentId,
                        ]);

                        $successCount++;
                        Log::info("Student {$student->full_name} moved to class via bulk action");
                    } else {
                        $errorCount++;
                    }
                }

                $message = "Berhasil memindahkan {$successCount} siswa ke kelas yang dipilih.";
                if ($errorCount > 0) {
                    $message .= " {$errorCount} siswa gagal dipindahkan.";
                }
            } elseif ($action === 'remove') {
                foreach ($studentIds as $studentId) {
                    $student = Student::whereIn('status', ['Aktif', 'Pindahan'])->find($studentId);
                    if ($student) {
                        // Remove from all classes in active year
                        ClassStudent::where('student_id', $studentId)
                            ->where('academic_year_id', $activeYear->id)
                            ->delete();

                        $successCount++;
                        Log::info("Student {$student->full_name} removed from class via bulk action");
                    } else {
                        $errorCount++;
                    }
                }

                $message = "Berhasil menghapus {$successCount} siswa dari kelas mereka.";
                if ($errorCount > 0) {
                    $message .= " {$errorCount} siswa gagal dihapus.";
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Aksi tidak valid.']);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to execute bulk action: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menjalankan aksi bulk.']);
        }
    }

    // Get placement statistics
    private function getPlacementStatistics($activeYear)
    {
        // Hitung siswa yang aktif dan pindahan
        $totalStudents = Student::whereIn('status', ['Aktif', 'Pindahan'])->count();
        $activeStudents = Student::where('status', 'Aktif')->count();
        $transferStudents = Student::where('status', 'Pindahan')->count();

        $placedStudents = Student::whereIn('status', ['Aktif', 'Pindahan'])
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
            'active_students' => $activeStudents,
            'transfer_students' => $transferStudents,
            'placed_students' => $placedStudents,
            'unplaced_students' => $unplacedStudents,
            'placement_percentage' => $totalStudents > 0 ? round(($placedStudents / $totalStudents) * 100, 1) : 0,
            'class_stats' => $classStats
        ];
    }
}

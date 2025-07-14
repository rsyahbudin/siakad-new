<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\StudentPromotion;
use App\Models\Classroom;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\ClassroomAssignment;
use App\Models\Semester;
use App\Models\Student;

class PromotionController extends Controller
{
    /**
     * Display the promotion/graduation management dashboard.
     */
    public function index()
    {
        $activeSemester = Semester::where('is_active', true)->first();

        if (!$activeSemester || $activeSemester->name !== 'Genap') {
            return view('admin.promotions.disabled', [
                'message' => 'Proses kenaikan dan kelulusan massal hanya dapat dijalankan pada akhir semester Genap.'
            ]);
        }

        $academicYear = $activeSemester->academicYear;
        $assignments = ClassroomAssignment::with('classroom', 'homeroomTeacher.user')
            ->where('academic_year_id', $academicYear->id)
            ->get();

        $promotionStatus = $assignments->map(function ($assignment) use ($academicYear) {
            $studentCount = $assignment->classStudents()->count();

            if ($studentCount === 0) {
                return (object) [
                    'assignment' => $assignment,
                    'student_count' => 0,
                    'promotion_count' => 0,
                    'is_ready' => true,
                    'status_message' => 'Kelas kosong',
                    'count_naik' => 0,
                    'count_tidak_naik' => 0,
                    'count_belum' => 0,
                    'is_last_grade' => false
                ];
            }

            $promotions = \App\Models\StudentPromotion::where('from_classroom_id', $assignment->classroom_id)
                ->where('promotion_year_id', $academicYear->id)
                ->get();
            $promotionCount = $promotions->count();

            // Tentukan apakah kelas ini tingkat akhir
            $isLastGrade = str_starts_with($assignment->classroom->name, 'XII');
            $countNaik = $promotions->where('final_decision', $isLastGrade ? 'Lulus' : 'Naik Kelas')->count();
            $countTidakNaik = $promotions->where('final_decision', $isLastGrade ? 'Tidak Lulus' : 'Tidak Naik Kelas')->count();
            $countBelum = $studentCount - ($countNaik + $countTidakNaik);

            $isReady = $studentCount === $promotionCount;

            return (object) [
                'assignment' => $assignment,
                'student_count' => $studentCount,
                'promotion_count' => $promotionCount,
                'is_ready' => $isReady,
                'status_message' => $isReady ? 'Siap diproses' : 'Menunggu keputusan wali kelas',
                'count_naik' => $countNaik,
                'count_tidak_naik' => $countTidakNaik,
                'count_belum' => $countBelum,
                'is_last_grade' => $isLastGrade
            ];
        });

        $allReady = $promotionStatus->every('is_ready', true);
        return view('admin.promotions.index', compact('academicYear', 'promotionStatus', 'allReady'));
    }

    /**
     * Process the mass promotion and graduation.
     */
    public function process()
    {
        DB::beginTransaction();
        try {
            $activeSemester = Semester::where('is_active', true)->firstOrFail();
            $currentYear = $activeSemester->academicYear;

            $nextYearName = (explode('/', $currentYear->year)[0] + 1) . '/' . (explode('/', $currentYear->year)[1] + 1);
            $nextYear = AcademicYear::firstOrCreate(['year' => $nextYearName]);

            Semester::firstOrCreate(['academic_year_id' => $nextYear->id, 'name' => 'Ganjil']);
            Semester::firstOrCreate(['academic_year_id' => $nextYear->id, 'name' => 'Genap']);

            $promotions = StudentPromotion::with('student', 'fromClassroom')
                ->where('promotion_year_id', $currentYear->id)->get();

            if ($promotions->isEmpty()) {
                return redirect()->route('admin.promotions.index')->with('error', 'Tidak ada data kenaikan/kelulusan untuk diproses.');
            }

            foreach ($promotions as $promotion) {
                $student = $promotion->student;
                $fromClass = $promotion->fromClassroom;

                if ($promotion->final_decision === 'Lulus') {
                    $student->status = 'Lulus';
                    $student->save();
                    continue; // Lanjut ke siswa berikutnya
                }

                $nextClassroom = $fromClass; // Default: siswa tetap di kelas yang sama (untuk tahun ajaran baru)

                // Jika siswa naik kelas dan belum di tingkat akhir
                if ($promotion->final_decision === 'Naik Kelas' && $fromClass->grade_level < 12) {
                    $nextGradeLevel = $fromClass->grade_level + 1;

                    // Buat nama kelas berikutnya yang diharapkan
                    $nextClassName = $fromClass->name;
                    if ($fromClass->grade_level == 10 && str_starts_with($fromClass->name, 'X ')) {
                        $nextClassName = Str::replaceFirst('X ', 'XI ', $fromClass->name);
                    } elseif ($fromClass->grade_level == 11 && str_starts_with($fromClass->name, 'XI ')) {
                        $nextClassName = Str::replaceFirst('XI ', 'XII ', $fromClass->name);
                    }

                    // Cari kelas untuk tingkat selanjutnya
                    $foundNextClass = Classroom::where('grade_level', $nextGradeLevel)
                        ->where('name', $nextClassName)
                        ->where('major_id', $fromClass->major_id)
                        ->first();

                    if ($foundNextClass) {
                        $nextClassroom = $foundNextClass;
                    }
                }

                // Jika kelas tujuan ditemukan (baik untuk naik maupun tinggal kelas)
                if ($nextClassroom) {
                    $nextAssignment = ClassroomAssignment::firstOrCreate(
                        ['classroom_id' => $nextClassroom->id, 'academic_year_id' => $nextYear->id],
                        ['homeroom_teacher_id' => null] // Admin bisa atur wali kelas nanti
                    );

                    DB::table('class_student')->insert([
                        'student_id' => $student->id,
                        'classroom_assignment_id' => $nextAssignment->id,
                        'classroom_id' => $nextClassroom->id,
                        'academic_year_id' => $nextYear->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            $currentYear->update(['is_active' => false]);
            $nextYear->update(['is_active' => true]);
            $activeSemester->update(['is_active' => false]);
            Semester::where(['academic_year_id' => $nextYear->id, 'name' => 'Ganjil'])->update(['is_active' => true]);

            DB::commit();
            return redirect()->route('admin.dashboard')->with('success', "Proses kenaikan kelas untuk tahun ajaran {$nextYear->year} berhasil dijalankan.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.promotions.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

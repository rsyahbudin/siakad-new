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

class PromotionController extends Controller
{
    public function processPromotions(Request $request)
    {
        $activeYear = AcademicYear::getActive();
        $nextYear = $activeYear->getNext();

        if (!$nextYear) {
            return redirect()->route('kenaikan-kelas.index')->with('error', 'Tahun ajaran berikutnya belum dibuat. Silakan buat terlebih dahulu.');
        }

        DB::transaction(function () use ($activeYear, $nextYear) {
            $promotions = StudentPromotion::where('promotion_year_id', $activeYear->id)->with(['student', 'fromClassroom'])->get();

            foreach ($promotions as $promotion) {
                $student = $promotion->student;
                $fromClassroom = $promotion->fromClassroom;

                if ($promotion->final_decision == 'Naik Kelas') {
                    // Cek kelulusan (jika dari kelas 12)
                    if (Str::startsWith($fromClassroom->name, 'XII')) {
                        $student->status = 'Lulus';
                        $student->save();
                        // Hapus dari tabel pivot
                        $student->classrooms()->detach($fromClassroom->id);
                    } else {
                        // Proses kenaikan kelas reguler
                        $nextLevel = $this->getNextClassLevel($fromClassroom->level);
                        $nextClassName = str_replace($fromClassroom->level, $nextLevel, $fromClassroom->name);

                        $nextClassroom = Classroom::firstOrCreate(
                            [
                                'name' => $nextClassName,
                                'major_id' => $fromClassroom->major_id,
                                'academic_year_id' => $nextYear->id,
                            ],
                            [
                                'level' => $nextLevel,
                                // Homeroom teacher bisa di-set null dulu atau di-assign nanti
                                'homeroom_teacher_id' => null,
                            ]
                        );

                        // Pindahkan siswa
                        $student->classrooms()->detach($fromClassroom->id);
                        $student->classrooms()->attach($nextClassroom->id);
                    }
                } elseif ($promotion->final_decision == 'Tidak Naik Kelas') {
                    // Siswa tinggal di kelas dengan level yang sama di tahun ajaran baru
                    $retainedClassroom = Classroom::firstOrCreate(
                        [
                            'name' => $fromClassroom->name,
                            'major_id' => $fromClassroom->major_id,
                            'academic_year_id' => $nextYear->id,
                        ],
                        [
                            'level' => $fromClassroom->level,
                            'homeroom_teacher_id' => $fromClassroom->homeroom_teacher_id, // Bisa dipertahankan atau diubah
                        ]
                    );

                    $student->classrooms()->detach($fromClassroom->id);
                    $student->classrooms()->attach($retainedClassroom->id);
                }
            }
            // Non-aktifkan tahun ajaran lama dan aktifkan yang baru
            $activeYear->update(['is_active' => false]);
            $nextYear->update(['is_active' => true]);
        });

        return redirect()->route('kenaikan-kelas.index')->with('success', 'Proses kenaikan kelas dan kelulusan telah berhasil diselesaikan.');
    }

    private function getNextClassLevel(string $currentLevel): string
    {
        $levels = ['X' => 'XI', 'XI' => 'XII'];
        return $levels[$currentLevel] ?? $currentLevel;
    }

    public function index(Request $request)
    {
        $activeYear = \App\Models\AcademicYear::getActive();
        if (!$activeYear) {
            return redirect()->route('kenaikan-kelas.index')->with('error', 'Tahun ajaran aktif belum diatur.');
        }
        $activeSemester = Semester::where('is_active', true)->first();
        $classroomAssignments = \App\Models\ClassroomAssignment::with('classroom')
            ->where('academic_year_id', $activeYear?->id)
            ->get();
        $selectedKelas12 = $request->kelas12_id;
        $selectedKelasNon12 = $request->kelasnon12_id;
        $promotions = \App\Models\StudentPromotion::with(['student', 'fromClassroom'])
            ->where('promotion_year_id', $activeYear->id)
            ->get();
        // Filter kelas 12
        $kelas12Promotions = $promotions->where('fromClassroom.grade_level', 12);
        if ($selectedKelas12) {
            $kelas12Promotions = $kelas12Promotions->where('fromClassroom.id', $selectedKelas12);
        }
        $kelas12 = $kelas12Promotions->groupBy('fromClassroom.id')->map(function ($group) {
            return $group->filter(fn($p) => is_object($p) && $p instanceof \App\Models\StudentPromotion);
        });
        // Filter kelas 10&11
        $kelasNon12Promotions = $promotions->whereIn('fromClassroom.grade_level', [10, 11]);
        if ($selectedKelasNon12) {
            $kelasNon12Promotions = $kelasNon12Promotions->where('fromClassroom.id', $selectedKelasNon12);
        }
        $kelasNon12 = $kelasNon12Promotions->groupBy('fromClassroom.id')->map(function ($group) {
            return $group->filter(fn($p) => is_object($p) && $p instanceof \App\Models\StudentPromotion);
        });
        return view('admin.kenaikan-kelas', [
            'activeYear' => $activeYear,
            'activeSemester' => $activeSemester,
            'classroomAssignments' => $classroomAssignments,
            'selectedKelas12' => $selectedKelas12,
            'selectedKelasNon12' => $selectedKelasNon12,
            'kelas12' => $kelas12,
            'kelasNon12' => $kelasNon12,
        ]);
    }
}

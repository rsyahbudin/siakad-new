<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\SemesterWeight;
use Illuminate\Http\Request;

class NilaiSiswaController extends Controller
{
    public function show($id)
    {
        $student = Student::with('user')->findOrFail($id);
        // Ambil semua nilai siswa ini, group by mapel dan semester, semua tahun ajaran
        $grades = Grade::with(['subject', 'semester'])
            ->where('student_id', $id)
            ->orderBy('academic_year_id')
            ->orderBy('semester_id')
            ->orderBy('subject_id')
            ->get();
        // Group nilai per tahun ajaran, semester, mapel
        $rekap = [];
        foreach ($grades as $nilai) {
            $th = $nilai->academic_year_id;
            $sm = $nilai->semester->name;
            $mp = $nilai->subject->name;
            // Ambil KKM hanya berdasarkan subject_id dan academic_year_id
            $kkm = \App\Models\SubjectSetting::where('subject_id', $nilai->subject_id)
                ->where('academic_year_id', $nilai->academic_year_id)
                ->value('kkm');
            $rekap[$th][$sm][$mp] = [
                'final_grade' => $nilai->final_grade,
                'kkm' => $kkm !== null ? $kkm : 'Belum diatur',
                'subject_id' => $nilai->subject_id,
                'tugas' => $nilai->assignment_grade ?? null,
                'uts' => $nilai->uts_grade ?? null,
                'uas' => $nilai->uas_grade ?? null,
                'attitude_grade' => $nilai->attitude_grade ?? null,
            ];
        }
        // Ambil kelas aktif siswa pada tahun ajaran aktif
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        $kelas = null;
        if ($activeSemester) {
            $activeYearId = $activeSemester->academic_year_id;
            $classStudent = $student->classStudents()->where('academic_year_id', $activeYearId)->first();
            if ($classStudent && $classStudent->classroomAssignment && $classStudent->classroomAssignment->classroom) {
                $kelas = $classStudent->classroomAssignment->classroom;
            }
        }
        // Kirim juga grades mentah jika ingin tabel custom
        $tahunAjaranIds = array_keys($rekap);
        $tahunAjaranMap = \App\Models\AcademicYear::whereIn('id', $tahunAjaranIds)->pluck('year', 'id');
        return view('admin.nilai-siswa-detail', compact('student', 'rekap', 'grades', 'tahunAjaranMap', 'kelas'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Grade;
use App\Models\AcademicYear;

class GuruNilaiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeYear = AcademicYear::where('is_active', true)->first();
        $assignments = $teacher ? Schedule::with(['classroom', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('classroom', function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear?->id);
            })->get() : collect();
        $kelasMapel = $assignments->map(function ($a) {
            return [
                'classroom_id' => $a->classroom_id,
                'classroom_name' => $a->classroom->name,
                'subject_id' => $a->subject_id,
                'subject_name' => $a->subject->name,
            ];
        })->unique(function ($item) {
            return $item['classroom_id'] . '-' . $item['subject_id'];
        });
        $selectedClass = $request->kelas_id;
        $selectedSubject = $request->mapel_id;
        $students = collect();
        $grades = collect();
        $bobot = null;
        if ($selectedClass && $selectedSubject) {
            $students = Student::whereHas('classrooms', function ($q) use ($selectedClass) {
                $q->where('classroom_id', $selectedClass);
            })->with('user')->orderBy('full_name')->get();
            $grades = Grade::where('classroom_id', $selectedClass)
                ->where('subject_id', $selectedSubject)
                ->where('academic_year_id', $activeYear?->id)
                ->get()->keyBy('student_id');
            $bobot = \App\Models\SubjectSetting::where('subject_id', $selectedSubject)
                ->where('academic_year_id', $activeYear?->id)
                ->first();
        }
        return view('guru.input-nilai', compact('kelasMapel', 'selectedClass', 'selectedSubject', 'students', 'grades', 'activeYear', 'bobot'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeYear = AcademicYear::where('is_active', true)->first();
        $request->validate([
            'kelas_id' => 'required|exists:classrooms,id',
            'mapel_id' => 'required|exists:subjects,id',
            'nilai' => 'required|array',
        ]);
        foreach ($request->nilai as $student_id => $nilai) {
            $grade = Grade::updateOrCreate([
                'student_id' => $student_id,
                'classroom_id' => $request->kelas_id,
                'subject_id' => $request->mapel_id,
                'academic_year_id' => $activeYear?->id,
            ], [
                'assignment_grade' => isset($nilai['tugas']) ? ($nilai['tugas'] === null || $nilai['tugas'] === '' ? 0 : $nilai['tugas']) : 0,
                'uts_grade' => isset($nilai['uts']) ? ($nilai['uts'] === null || $nilai['uts'] === '' ? 0 : $nilai['uts']) : 0,
                'uas_grade' => isset($nilai['uas']) ? ($nilai['uas'] === null || $nilai['uas'] === '' ? 0 : $nilai['uas']) : 0,
            ]);
            // Hitung dan simpan nilai akhir & status
            $grade->calculateFinalGrade();
        }
        return redirect()->route('nilai.input', ['kelas_id' => $request->kelas_id, 'mapel_id' => $request->mapel_id])->with('success', 'Nilai berhasil disimpan.');
    }
}

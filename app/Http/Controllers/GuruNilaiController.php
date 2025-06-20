<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\ClassroomAssignment;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Semester;

class GuruNilaiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $activeYearId = $activeSemester?->academic_year_id;
        $assignments = ClassroomAssignment::with('classroom')
            ->where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeYearId)
            ->get();
        $selectedAssignment = $request->assignment_id;
        $selectedSubject = $request->subject_id;
        $students = collect();
        $grades = collect();
        $bobot = null;
        if ($selectedAssignment && $selectedSubject) {
            $assignment = ClassroomAssignment::find($selectedAssignment);
            $students = $assignment->classStudents()->with('student.user')->get()->pluck('student');
            $grades = Grade::where('classroom_assignment_id', $selectedAssignment)
                ->where('subject_id', $selectedSubject)
                ->where('semester_id', $activeSemester?->id)
                ->get()->keyBy('student_id');
            $bobot = \App\Models\SubjectSetting::where('subject_id', $selectedSubject)
                ->where('academic_year_id', $activeYearId)
                ->first();
        }
        // Ambil subjects hanya yang diampu guru pada assignment terpilih
        if ($selectedAssignment) {
            $subjects = Schedule::where('classroom_assignment_id', $selectedAssignment)
                ->where('teacher_id', $teacher->id)
                ->with('subject')
                ->get()
                ->pluck('subject')
                ->unique('id')
                ->sortBy('name')
                ->values();
        } else {
            $subjects = collect();
        }
        return view('guru.input-nilai', compact('assignments', 'selectedAssignment', 'selectedSubject', 'students', 'grades', 'activeSemester', 'bobot', 'subjects'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $activeYearId = $activeSemester?->academic_year_id;
        $request->validate([
            'assignment_id' => 'required|exists:classroom_assignments,id',
            'subject_id' => 'required|exists:subjects,id',
            'nilai' => 'required|array',
        ]);
        foreach ($request->nilai as $student_id => $nilai) {
            $assignment = \App\Models\ClassroomAssignment::find($request->assignment_id);
            $grade = Grade::updateOrCreate([
                'student_id' => $student_id,
                'classroom_assignment_id' => $request->assignment_id,
                'classroom_id' => $assignment?->classroom_id,
                'subject_id' => $request->subject_id,
                'semester_id' => $activeSemester?->id,
                'academic_year_id' => $activeYearId,
            ], [
                'assignment_grade' => isset($nilai['tugas']) ? ($nilai['tugas'] === null || $nilai['tugas'] === '' ? 0 : $nilai['tugas']) : 0,
                'uts_grade' => isset($nilai['uts']) ? ($nilai['uts'] === null || $nilai['uts'] === '' ? 0 : $nilai['uts']) : 0,
                'uas_grade' => isset($nilai['uas']) ? ($nilai['uas'] === null || $nilai['uas'] === '' ? 0 : $nilai['uas']) : 0,
            ]);
            $grade->calculateFinalGrade();
        }
        return redirect()->route('nilai.input', ['assignment_id' => $request->assignment_id, 'subject_id' => $request->subject_id])->with('success', 'Nilai berhasil disimpan.');
    }
}

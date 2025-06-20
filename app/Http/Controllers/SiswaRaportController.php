<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\SubjectSetting;
use App\Models\Raport;
use App\Models\Student;
use App\Models\Semester;
use App\Models\Attendance;

class SiswaRaportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;
        if (!$student) {
            // Handle case where user is not a student
            return redirect()->route('dashboard')->with('error', 'Hanya siswa yang dapat mengakses halaman ini.');
        }

        $activeSemester = Semester::where('is_active', true)->first();
        if (!$activeSemester) {
            return view('siswa.raport-empty', ['message' => 'Tahun ajaran/semester aktif belum ditentukan.']);
        }
        $activeYear = $activeSemester->academicYear;
        $assignment = $student->classStudents()->where('academic_year_id', $activeYear->id)->first();
        $kelas = $assignment?->classroomAssignment?->classroom;
        $waliKelas = $assignment?->classroomAssignment?->homeroomTeacher;
        if (!$kelas) {
            return view('siswa.raport-empty', ['message' => 'Anda tidak terdaftar di kelas manapun pada tahun ajaran ini.']);
        }
        $semesterInt = $activeSemester->name === 'Ganjil' ? 1 : 2;
        $raport = Raport::where('student_id', $student->id)
            ->where('classroom_id', $kelas->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester', $semesterInt)
            ->first();
        $allGrades = Grade::with('subject')
            ->where('student_id', $student->id)
            ->where('semester_id', $activeSemester->id)
            ->get();
        $grades = $allGrades->unique('subject_id')->values();
        $subjectSettings = SubjectSetting::where('academic_year_id', $activeYear->id)
            ->whereIn('subject_id', $grades->pluck('subject_id'))
            ->get()
            ->keyBy('subject_id');
        // Absensi: rekap langsung jika raport kosong atau field absensi kosong
        if (!$raport || (!$raport->attendance_sick && !$raport->attendance_permit && !$raport->attendance_absent)) {
            $attendance = Attendance::where('student_id', $student->id)
                ->where('semester_id', $activeSemester->id)
                ->selectRaw(
                    "SUM(status = 'Sakit') as sakit, SUM(status = 'Izin') as izin, SUM(status = 'Alpha') as alpha"
                )->first();
            $attendance_sick = $attendance->sakit ?? 0;
            $attendance_permit = $attendance->izin ?? 0;
            $attendance_absent = $attendance->alpha ?? 0;
        } else {
            $attendance_sick = $raport->attendance_sick;
            $attendance_permit = $raport->attendance_permit;
            $attendance_absent = $raport->attendance_absent;
        }
        return view('siswa.raport', compact('student', 'activeSemester', 'kelas', 'waliKelas', 'raport', 'grades', 'subjectSettings', 'attendance_sick', 'attendance_permit', 'attendance_absent'));
    }
}

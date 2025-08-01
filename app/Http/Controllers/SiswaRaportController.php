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
use App\Models\ClassStudent;
use App\Services\AttendanceService;

class SiswaRaportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Hanya siswa yang dapat mengakses halaman ini.');
        }

        // Ambil semua tahun ajaran yang pernah diikuti siswa
        $academicYears = AcademicYear::whereHas('classroomAssignments.classStudents', function ($query) use ($student) {
            $query->where('student_id', $student->id);
        })->orderBy('year', 'desc')->get();

        if ($academicYears->isEmpty()) {
            return view('siswa.raport-empty', ['message' => 'Anda belum terdaftar di tahun ajaran manapun.']);
        }

        // Ambil tahun ajaran yang dipilih atau default ke yang aktif
        $selectedYearId = $request->get('academic_year_id');
        $selectedYear = null;

        if ($selectedYearId) {
            $selectedYear = $academicYears->firstWhere('id', $selectedYearId);
        }

        if (!$selectedYear) {
            $selectedYear = $academicYears->first(); // Default ke tahun ajaran terbaru
        }

        // Ambil semester yang dipilih atau default ke semester aktif
        $selectedSemester = $request->get('semester');
        if (!$selectedSemester) {
            $activeSemester = Semester::where('is_active', true)->first();
            $selectedSemester = $activeSemester ? ($activeSemester->name === 'Ganjil' ? 1 : 2) : 1;
        }

        // Ambil data assignment untuk tahun ajaran yang dipilih
        $assignment = $student->classStudents()->where('academic_year_id', $selectedYear->id)->first();
        $kelas = $assignment?->classroomAssignment?->classroom;
        $waliKelas = $assignment?->classroomAssignment?->homeroomTeacher;

        if (!$kelas) {
            return view('siswa.raport-empty', ['message' => 'Anda tidak terdaftar di kelas manapun pada tahun ajaran ' . $selectedYear->year . '.']);
        }

        // Ambil data raport
        $raport = Raport::where('student_id', $student->id)
            ->where('classroom_id', $kelas->id)
            ->where('academic_year_id', $selectedYear->id)
            ->where('semester', $selectedSemester)
            ->first();

        // Ambil semester untuk query grades
        $semester = Semester::where('academic_year_id', $selectedYear->id)
            ->where('name', $selectedSemester == 1 ? 'Ganjil' : 'Genap')
            ->first();

        if (!$semester) {
            return view('siswa.raport-empty', ['message' => 'Data semester tidak ditemukan untuk tahun ajaran ' . $selectedYear->year . '.']);
        }

        // Ambil data nilai
        $allGrades = Grade::with('subject')
            ->where('student_id', $student->id)
            ->where('semester_id', $semester->id)
            ->get();
        $grades = $allGrades->unique('subject_id')->values();

        // Ambil pengaturan mata pelajaran
        $subjectSettings = SubjectSetting::where('academic_year_id', $selectedYear->id)
            ->whereIn('subject_id', $grades->pluck('subject_id'))
            ->get()
            ->keyBy('subject_id');

        // Ambil data absensi menggunakan attendance service
        $attendanceService = new AttendanceService();
        $semesterStats = $attendanceService->getSemesterStats($student->id, $semester->id);

        if (!$raport || (!$raport->attendance_sick && !$raport->attendance_permit && !$raport->attendance_absent)) {
            $attendance_sick = $semesterStats['sakit'];
            $attendance_permit = $semesterStats['izin'];
            $attendance_absent = $semesterStats['alpha'];
        } else {
            $attendance_sick = $raport->attendance_sick;
            $attendance_permit = $raport->attendance_permit;
            $attendance_absent = $raport->attendance_absent;
        }

        return view('siswa.raport', compact(
            'student',
            'selectedYear',
            'selectedSemester',
            'academicYears',
            'kelas',
            'waliKelas',
            'raport',
            'grades',
            'subjectSettings',
            'attendance_sick',
            'attendance_permit',
            'attendance_absent'
        ));
    }

    public function allRaports(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Hanya siswa yang dapat mengakses halaman ini.');
        }

        // Ambil semua raport yang pernah dibuat
        $allRaports = Raport::where('student_id', $student->id)
            ->with(['academicYear', 'classroom'])
            ->orderBy('academic_year_id', 'desc')
            ->orderBy('semester', 'asc')
            ->get()
            ->groupBy('academic_year_id');

        // Ambil semua tahun ajaran yang pernah diikuti
        $academicYears = AcademicYear::whereHas('classroomAssignments.classStudents', function ($query) use ($student) {
            $query->where('student_id', $student->id);
        })->orderBy('year', 'desc')->get();

        return view('siswa.all-raports', compact('student', 'allRaports', 'academicYears'));
    }
}

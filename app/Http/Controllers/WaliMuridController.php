<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Raport;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Services\AttendanceService;
use App\Models\Semester;

class WaliMuridController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $waliMurid = $user->waliMurid;

        if (!$waliMurid || !$waliMurid->student) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $student = $waliMurid->student;

        // Get student's grades (recent)
        $grades = Grade::where('student_id', $student->id)
            ->with(['subject', 'classroom'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Active semester stats
        $activeSemester = Semester::where('is_active', true)->first();
        $attendanceService = new AttendanceService();
        $attendanceStats = $activeSemester
            ? $attendanceService->getSemesterStats($student->id, $activeSemester->id)
            : ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0, 'percentage' => 0, 'total_days' => 0];

        // Attendance summaries (last entries)
        $attendance = Attendance::where('student_id', $student->id)
            ->with(['semester'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Simple average final grade from recent grades
        $avgFinalGrade = round((float) (clone $grades)->getCollection()->avg('final_grade'), 1);

        return view('wali-murid.dashboard', compact('user', 'student', 'grades', 'attendance', 'attendanceStats', 'activeSemester', 'avgFinalGrade'));
    }

    public function nilaiAnak()
    {
        $user = Auth::user();
        $waliMurid = $user->waliMurid;

        if (!$waliMurid || !$waliMurid->student) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $student = $waliMurid->student;

        // Get all grades for the student
        $grades = Grade::where('student_id', $student->id)
            ->with(['subject', 'classroom', 'semester'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('wali-murid.nilai-anak', compact('student', 'grades'));
    }

    public function raportAnak()
    {
        $user = Auth::user();
        $waliMurid = $user->waliMurid;

        if (!$waliMurid || !$waliMurid->student) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $student = $waliMurid->student;

        // Get student's raports
        $raports = Raport::where('student_id', $student->id)
            ->with(['classroom', 'semester'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('wali-murid.raport-anak', compact('student', 'raports'));
    }

    public function jadwalAnak()
    {
        $user = Auth::user();
        $waliMurid = $user->waliMurid;

        if (!$waliMurid || !$waliMurid->student) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $student = $waliMurid->student;

        // Get active semester to scope classroom assignment to the correct year
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();

        // Get student's class via classroom assignment and load classroom relation
        $classStudent = $student->classStudents()
            ->when($activeSemester, fn($q) => $q->where('academic_year_id', $activeSemester->academic_year_id))
            ->with('classroomAssignment.classroom')
            ->first();

        $schedules = collect();
        $classroom = $classStudent?->classroomAssignment?->classroom;

        if ($classroom) {
            $schedules = Schedule::where('classroom_id', $classroom->id)
                ->with(['subject', 'teacher', 'classroom'])
                ->orderByRaw("FIELD(day, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
                ->orderBy('time_start')
                ->get();
        }

        return view('wali-murid.jadwal-anak', compact('student', 'schedules'));
    }

    public function absensiAnak(Request $request)
    {
        $user = Auth::user();
        $waliMurid = $user->waliMurid;

        if (!$waliMurid || !$waliMurid->student) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $student = $waliMurid->student;

        // Get active semester
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        if (!$activeSemester) {
            return redirect()->back()->with('error', 'Tidak ada semester aktif.');
        }

        // Get month and year from request, default to current month
        $selectedMonth = $request->get('month', now()->month);
        $selectedYear = $request->get('year', now()->year);
        $currentMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1);

        // Get student's attendance using the new service
        $attendanceService = new AttendanceService();
        $semesterStats = $attendanceService->getSemesterStats($student->id, $activeSemester->id);

        // Get semester attendance summaries
        $attendance = Attendance::where('student_id', $student->id)
            ->where('semester_id', $activeSemester->id)
            ->with(['semester', 'semester.academicYear'])
            ->get();

        // Get detailed attendance records
        $query = \App\Models\StudentAttendance::where('student_id', $student->id)
            ->with(['schedule.subject', 'schedule.classroom', 'teacher.user']);

        // Apply month/year filter if provided
        if ($request->filled('month') && $request->filled('year')) {
            $query->whereYear('attendance_date', $selectedYear)
                ->whereMonth('attendance_date', $selectedMonth);
        } else {
            // Show data from last 3 months by default
            $threeMonthsAgo = now()->subMonths(3);
            $query->where('attendance_date', '>=', $threeMonthsAgo);
        }

        $attendanceRecords = $query->orderBy('attendance_date', 'desc')->paginate(20);

        return view('wali-murid.absensi-anak', compact(
            'student',
            'attendanceRecords',
            'semesterStats',
            'activeSemester',
            'attendance',
            'attendanceService',
            'currentMonth',
            'selectedMonth',
            'selectedYear'
        ));
    }
}

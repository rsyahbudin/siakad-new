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

        // Get student's grades
        $grades = Grade::where('student_id', $student->id)
            ->with(['subject', 'classroom'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get attendance
        $attendance = Attendance::where('student_id', $student->id)
            ->with(['semester'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('wali-murid.dashboard', compact('user', 'student', 'grades', 'attendance'));
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
            ->get();

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
            ->get();

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

        // Get student's class and schedule
        $classStudent = $student->classStudents()->with('classroom')->first();
        $schedules = collect();

        if ($classStudent && $classStudent->classroom) {
            $schedules = Schedule::where('classroom_id', $classStudent->classroom->id)
                ->with(['subject', 'teacher'])
                ->orderBy('day_of_week')
                ->orderBy('start_time')
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

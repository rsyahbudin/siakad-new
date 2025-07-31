<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Raport;
use App\Models\Attendance;
use App\Models\Schedule;

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
            ->with(['semester', 'classroom'])
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

    public function absensiAnak()
    {
        $user = Auth::user();
        $waliMurid = $user->waliMurid;

        if (!$waliMurid || !$waliMurid->student) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $student = $waliMurid->student;

        // Get student's attendance
        $attendance = Attendance::where('student_id', $student->id)
            ->with(['semester', 'classroom'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('wali-murid.absensi-anak', compact('student', 'attendance'));
    }
}

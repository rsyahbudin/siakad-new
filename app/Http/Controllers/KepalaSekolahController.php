<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Raport;
use App\Models\Grade;

class KepalaSekolahController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Get active academic year
        $activeYear = AcademicYear::where('is_active', true)->first();

        // Get statistics
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalClassrooms = Classroom::count();
        $totalRaports = Raport::where('is_finalized', true)->count();
        $totalActiveStudents = Student::where('status', 'Aktif')->count();
        $totalPindahanStudents = Student::where('status', 'Pindahan')->count();

        // Get recent grades
        $recentGrades = Grade::with(['student', 'subject', 'classroom'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get recent raports
        $recentRaports = Raport::with(['student', 'classroom', 'semester'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get classroom statistics
        $classroomStats = Classroom::withCount('students')->get();

        return view('kepala-sekolah.dashboard', compact(
            'user',
            'activeYear',
            'totalStudents',
            'totalTeachers',
            'totalClassrooms',
            'totalRaports',
            'totalActiveStudents',
            'totalPindahanStudents',
            'recentGrades',
            'recentRaports',
            'classroomStats'
        ));
    }

    public function laporanAkademik()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        // Get academic reports
        $classrooms = Classroom::with(['students', 'homeroomTeacher'])
            ->get();

        return view('kepala-sekolah.laporan-akademik', compact('activeYear', 'classrooms'));
    }

    public function laporanKeuangan()
    {
        // Placeholder for financial reports
        return view('kepala-sekolah.laporan-keuangan');
    }

    public function pengaturanSekolah()
    {
        // Placeholder for school settings
        return view('kepala-sekolah.pengaturan-sekolah');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Extracurricular;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;

class StudentExtracurricularController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Hanya siswa yang dapat mengakses halaman ini.');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();

        // Get all available extracurriculars
        $availableExtracurriculars = Extracurricular::where('is_active', true)
            ->with(['teacher', 'students'])
            ->get()
            ->filter(function ($extracurricular) use ($student, $activeYear) {
                // Check if student is not already in this extracurricular for this academic year
                $isEnrolled = $extracurricular->students()
                    ->wherePivot('student_id', $student->id)
                    ->wherePivot('academic_year_id', $activeYear->id)
                    ->exists();

                return !$isEnrolled && !$extracurricular->isFull();
            });

        // Get student's current extracurriculars
        $myExtracurriculars = $student->getActiveExtracurriculars($activeYear->id);

        return view('siswa.extracurricular.index', compact('availableExtracurriculars', 'myExtracurriculars', 'activeYear'));
    }

    public function show(Extracurricular $extracurricular)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Hanya siswa yang dapat mengakses halaman ini.');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();

        // Check if student is enrolled in this extracurricular
        $enrollment = $extracurricular->students()
            ->wherePivot('student_id', $student->id)
            ->wherePivot('academic_year_id', $activeYear->id)
            ->first();

        // Get other students in this extracurricular
        $otherStudents = $extracurricular->students()
            ->wherePivot('academic_year_id', $activeYear->id)
            ->wherePivot('student_id', '!=', $student->id)
            ->get();

        return view('siswa.extracurricular.show', compact('extracurricular', 'enrollment', 'otherStudents', 'activeYear'));
    }

    public function enroll(Request $request, Extracurricular $extracurricular)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Hanya siswa yang dapat mengakses halaman ini.');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        // Check if student is already enrolled in this extracurricular
        $existing = $extracurricular->students()
            ->wherePivot('student_id', $student->id)
            ->wherePivot('academic_year_id', $activeYear->id)
            ->exists();

        if ($existing) {
            return back()->with('error', 'Anda sudah terdaftar di ekstrakurikuler ini.');
        }

        // Check if student is already enrolled in another extracurricular
        $otherEnrollment = $student->extracurriculars()
            ->wherePivot('academic_year_id', $activeYear->id)
            ->wherePivot('status', 'Aktif')
            ->exists();

        if ($otherEnrollment) {
            return back()->with('error', 'Anda hanya dapat mengikuti 1 ekstrakurikuler per tahun ajaran. Silakan keluar dari ekstrakurikuler yang sedang diikuti terlebih dahulu.');
        }

        // Check if extracurricular is full
        if ($extracurricular->isFull()) {
            return back()->with('error', 'Ekstrakurikuler sudah penuh.');
        }

        // Enroll student
        $extracurricular->students()->attach($student->id, [
            'academic_year_id' => $activeYear->id,
            'position' => 'Anggota',
            'status' => 'Aktif',
            'join_date' => now(),
        ]);

        return redirect()->route('siswa.extracurricular.index')
            ->with('success', 'Berhasil mendaftar ke ekstrakurikuler ' . $extracurricular->name . '.');
    }

    public function leave(Request $request, Extracurricular $extracurricular)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Hanya siswa yang dapat mengakses halaman ini.');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        // Check if student is enrolled
        $enrollment = $extracurricular->students()
            ->wherePivot('student_id', $student->id)
            ->wherePivot('academic_year_id', $activeYear->id)
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'Anda tidak terdaftar di ekstrakurikuler ini.');
        }

        // Check if student has other active extracurriculars
        $otherActiveExtracurriculars = $student->extracurriculars()
            ->wherePivot('academic_year_id', $activeYear->id)
            ->wherePivot('status', 'Aktif')
            ->wherePivot('extracurricular_id', '!=', $extracurricular->id)
            ->count();

        if ($otherActiveExtracurriculars == 0) {
            return back()->with('error', 'Anda wajib mengikuti minimal 1 ekstrakurikuler selama tahun ajaran ini. Silakan daftar ke ekstrakurikuler lain terlebih dahulu sebelum keluar dari ekstrakurikuler ini.');
        }

        // Leave extracurricular
        $extracurricular->students()->wherePivot('student_id', $student->id)
            ->wherePivot('academic_year_id', $activeYear->id)
            ->updateExistingPivot($student->id, [
                'status' => 'Tidak Aktif',
                'leave_date' => now(),
            ]);

        return redirect()->route('siswa.extracurricular.index')
            ->with('success', 'Berhasil keluar dari ekstrakurikuler ' . $extracurricular->name . '.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Extracurricular;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;

class ExtracurricularController extends Controller
{
    public function index()
    {
        $extracurriculars = Extracurricular::with(['teacher', 'students'])
            ->orderBy('name')
            ->get();

        return view('admin.extracurricular.index', compact('extracurriculars'));
    }

    public function create()
    {
        $teachers = Teacher::orderBy('full_name')->get();
        $categories = ['Umum', 'Olahraga', 'Seni', 'Akademik', 'Keagamaan', 'Teknologi', 'Bahasa', 'Lainnya'];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('admin.extracurricular.create', compact('teachers', 'categories', 'days'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'day' => 'nullable|string|max:255',
            'time_start' => 'nullable|date_format:H:i',
            'time_end' => 'nullable|date_format:H:i|after:time_start',
            'location' => 'nullable|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
            'max_participants' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        Extracurricular::create($request->all());

        return redirect()->route('extracurricular.index')
            ->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    public function show(Extracurricular $extracurricular)
    {
        $extracurricular->load(['teacher', 'students.user']);

        $activeStudents = $extracurricular->students()
            ->wherePivot('status', 'Aktif')
            ->get();

        $inactiveStudents = $extracurricular->students()
            ->wherePivot('status', '!=', 'Aktif')
            ->get();

        return view('admin.extracurricular.show', compact('extracurricular', 'activeStudents', 'inactiveStudents'));
    }

    public function edit(Extracurricular $extracurricular)
    {
        $teachers = Teacher::orderBy('full_name')->get();
        $categories = ['Umum', 'Olahraga', 'Seni', 'Akademik', 'Keagamaan', 'Teknologi', 'Bahasa', 'Lainnya'];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('admin.extracurricular.edit', compact('extracurricular', 'teachers', 'categories', 'days'));
    }

    public function update(Request $request, Extracurricular $extracurricular)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'day' => 'nullable|string|max:255',
            'time_start' => 'nullable|date_format:H:i',
            'time_end' => 'nullable|date_format:H:i|after:time_start',
            'location' => 'nullable|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
            'max_participants' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $extracurricular->update($request->all());

        return redirect()->route('extracurricular.index')
            ->with('success', 'Ekstrakurikuler berhasil diperbarui.');
    }

    public function destroy(Extracurricular $extracurricular)
    {
        $extracurricular->delete();

        return redirect()->route('extracurricular.index')
            ->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }

    // Student management methods
    public function addStudent(Request $request, Extracurricular $extracurricular)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'position' => 'required|in:Anggota,Ketua,Wakil Ketua,Sekretaris,Bendahara',
            'notes' => 'nullable|string',
        ]);

        // Check if student is already in this extracurricular for this academic year
        $existing = $extracurricular->students()
            ->wherePivot('student_id', $request->student_id)
            ->wherePivot('academic_year_id', $request->academic_year_id)
            ->exists();

        if ($existing) {
            return back()->with('error', 'Siswa sudah terdaftar di ekstrakurikuler ini untuk tahun ajaran tersebut.');
        }

        // Check if extracurricular is full
        if ($extracurricular->isFull()) {
            return back()->with('error', 'Ekstrakurikuler sudah penuh.');
        }

        $extracurricular->students()->attach($request->student_id, [
            'academic_year_id' => $request->academic_year_id,
            'position' => $request->position,
            'notes' => $request->notes,
            'join_date' => now(),
            'status' => 'Aktif',
        ]);

        return back()->with('success', 'Siswa berhasil ditambahkan ke ekstrakurikuler.');
    }

    public function removeStudent(Request $request, Extracurricular $extracurricular)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $extracurricular->students()->wherePivot('student_id', $request->student_id)
            ->wherePivot('academic_year_id', $request->academic_year_id)
            ->updateExistingPivot($request->student_id, [
                'status' => 'Tidak Aktif',
                'leave_date' => now(),
            ]);

        return back()->with('success', 'Siswa berhasil dikeluarkan dari ekstrakurikuler.');
    }

    public function updateStudentStatus(Request $request, Extracurricular $extracurricular)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'status' => 'required|in:Aktif,Tidak Aktif,Lulus',
            'position' => 'required|in:Anggota,Ketua,Wakil Ketua,Sekretaris,Bendahara',
            'achievements' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $extracurricular->students()->wherePivot('student_id', $request->student_id)
            ->wherePivot('academic_year_id', $request->academic_year_id)
            ->updateExistingPivot($request->student_id, [
                'status' => $request->status,
                'position' => $request->position,
                'achievements' => $request->achievements,
                'notes' => $request->notes,
            ]);

        return back()->with('success', 'Status siswa berhasil diperbarui.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\StudentPromotion;
use Illuminate\Http\Request;

class StudentPromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $classrooms = \App\Models\Classroom::with('students.user')
            ->where('academic_year_id', $activeYear?->id)
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get();
        // Ambil semua siswa kelas 12 dan non-12
        $kelas12 = $classrooms->where('grade_level', 12);
        $kelasNon12 = $classrooms->where('grade_level', '<', 12);
        return view('admin.kenaikan-kelas', compact('classrooms', 'kelas12', 'kelasNon12', 'activeYear'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Proses batch kenaikan kelas dan kelulusan
        $action = $request->input('action'); // 'naik' atau 'lulus'
        $studentIds = $request->input('student_ids', []);
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        if ($action === 'naik') {
            // Naik kelas: update class_student ke kelas berikutnya
            $nextYear = $activeYear->getNext();
            foreach ($studentIds as $studentId) {
                $student = \App\Models\Student::find($studentId);
                $currentClass = $student->classrooms()->where('academic_year_id', $activeYear->id)->first();
                if ($student && $currentClass && $nextYear) {
                    // Cari kelas berikutnya (grade_level + 1, jurusan sama)
                    $nextClass = \App\Models\Classroom::where('academic_year_id', $nextYear->id)
                        ->where('grade_level', $currentClass->grade_level + 1)
                        ->where('major_id', $currentClass->major_id)
                        ->first();
                    if ($nextClass) {
                        // Detach kelas tahun ajaran aktif, attach ke tahun berikutnya
                        $student->classrooms()->detach($currentClass->id);
                        $student->classrooms()->attach($nextClass->id);
                    }
                }
            }
            return redirect()->route('kenaikan-kelas.index')->with('success', 'Kenaikan kelas berhasil diproses.');
        } elseif ($action === 'lulus') {
            // Kelulusan: update status siswa menjadi "Lulus"
            foreach ($studentIds as $studentId) {
                $student = \App\Models\Student::find($studentId);
                if ($student) {
                    $student->status = 'Lulus';
                    $student->save();
                }
            }
            return redirect()->route('kenaikan-kelas.index')->with('success', 'Kelulusan siswa berhasil diproses.');
        }
        return redirect()->route('kenaikan-kelas.index')->with('error', 'Aksi tidak valid.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentPromotion $studentPromotion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentPromotion $studentPromotion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentPromotion $studentPromotion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentPromotion $studentPromotion)
    {
        //
    }
}

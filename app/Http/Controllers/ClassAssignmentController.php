<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class ClassAssignmentController extends Controller
{
    // Tampilkan form pembagian kelas (per tahun ajaran aktif)
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $classrooms = Classroom::where('academic_year_id', $activeYear?->id)->orderBy('name')->get();
        $query = Student::with(['user', 'classrooms' => function ($q) use ($activeYear) {
            $q->where('academic_year_id', $activeYear?->id);
        }]);
        // Filter by search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nis', 'like', "%$q%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%$q%");
                    });
            });
        }
        // Filter by kelas
        if ($request->filled('kelas_filter')) {
            $kelasId = $request->kelas_filter;
            $query->whereHas('classrooms', function ($c) use ($kelasId) {
                $c->where('classroom_id', $kelasId);
            });
        }
        $students = $query->orderBy('user_id')->paginate(20)->withQueryString();
        return view('admin.pembagian-kelas', compact('students', 'classrooms', 'activeYear'));
    }

    // Proses bulk assign siswa ke kelas
    public function store(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $classrooms = Classroom::where('academic_year_id', $activeYear?->id)->pluck('id')->toArray();
        $data = $request->input('assignments', []);
        foreach ($data as $studentId => $classroomId) {
            $student = Student::find($studentId);
            if ($student && in_array($classroomId, $classrooms)) {
                // Detach semua kelas tahun ajaran aktif, lalu attach yang baru
                $student->classrooms()->detach($classrooms);
                $student->classrooms()->attach($classroomId);
            }
        }
        return redirect()->route('pembagian.kelas')->with('success', 'Pembagian kelas berhasil disimpan.');
    }
}

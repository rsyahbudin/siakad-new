<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassroomAssignment;
use App\Models\AcademicYear;
use App\Models\ClassStudent;
use Illuminate\Http\Request;

class ClassAssignmentController extends Controller
{
    // Tampilkan form pembagian kelas (per tahun ajaran aktif)
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $classroomAssignments = ClassroomAssignment::with('classroom', 'homeroomTeacher')
            ->where('academic_year_id', $activeYear?->id)
            ->get();
        $query = Student::with(['user', 'classStudents' => function ($q) use ($activeYear) {
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
            $assignmentId = $request->kelas_filter;
            $query->whereHas('classStudents', function ($c) use ($assignmentId) {
                $c->where('classroom_assignment_id', $assignmentId);
            });
        }
        $students = $query->orderBy('user_id')->paginate(20)->withQueryString();
        return view('admin.pembagian-kelas', compact('students', 'classroomAssignments', 'activeYear'));
    }

    // Proses bulk assign siswa ke kelas
    public function store(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $assignmentIds = ClassroomAssignment::where('academic_year_id', $activeYear?->id)->pluck('id')->toArray();
        $data = $request->input('assignments', []);
        foreach ($data as $studentId => $assignmentId) {
            $student = Student::find($studentId);
            if ($student && in_array($assignmentId, $assignmentIds)) {
                // Hapus penempatan lama di tahun ajaran aktif
                ClassStudent::where('student_id', $studentId)
                    ->where('academic_year_id', $activeYear->id)
                    ->delete();
                // Assign baru
                ClassStudent::create([
                    'classroom_assignment_id' => $assignmentId,
                    'academic_year_id' => $activeYear->id,
                    'student_id' => $studentId,
                ]);
            }
        }
        return redirect()->route('pembagian.kelas')->with('success', 'Pembagian kelas berhasil disimpan.');
    }
}

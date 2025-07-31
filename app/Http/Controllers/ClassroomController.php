<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Major;
use App\Models\Teacher;
use App\Models\ClassroomAssignment;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        $activeYearId = $activeSemester?->academic_year_id;

        $query = \App\Models\Classroom::with(['major', 'classroomAssignments' => function ($q) use ($activeYearId) {
            $q->where('academic_year_id', $activeYearId)->with('homeroomTeacher');
        }]);

        // Search functionality
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('major', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('short_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('classroomAssignments.homeroomTeacher', function ($q) use ($search) {
                        $q->where('full_name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by major
        if ($request->filled('major_id')) {
            $query->where('major_id', $request->major_id);
        }

        $classrooms = $query->orderBy('name')->paginate(12);
        $majors = Major::orderBy('short_name')->get();

        return view('master.kelas.index', compact('classrooms', 'activeSemester', 'majors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $majors = Major::orderBy('short_name')->get();
        $teachers = Teacher::orderBy('full_name')->get();
        return view('master.kelas.form', compact('majors', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:classrooms,name',
            'homeroom_teacher_id' => 'required|exists:teachers,id',
            'major_id' => 'required|exists:majors,id',
        ], [
            'name.required' => 'Nama kelas wajib diisi.',
            'name.unique' => 'Nama kelas sudah digunakan.',
            'homeroom_teacher_id.required' => 'Wali kelas wajib dipilih.',
            'major_id.required' => 'Jurusan wajib dipilih.',
        ]);
        $kelas = Classroom::create($request->only('name', 'grade_level', 'capacity', 'major_id'));
        $activeYear = AcademicYear::getActive();
        ClassroomAssignment::create([
            'classroom_id' => $kelas->id,
            'academic_year_id' => $activeYear->id,
            'homeroom_teacher_id' => $request->homeroom_teacher_id,
        ]);
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $kelas)
    {
        $majors = Major::orderBy('short_name')->get();
        $teachers = Teacher::orderBy('full_name')->get();
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        $activeYearId = $activeSemester?->academic_year_id;
        $assignment = ClassroomAssignment::where('classroom_id', $kelas->id)
            ->where('academic_year_id', $activeYearId)
            ->first();
        $homeroom_teacher_id = $assignment?->homeroom_teacher_id;
        return view('master.kelas.form', [
            'kelas' => $kelas,
            'majors' => $majors,
            'teachers' => $teachers,
            'homeroom_teacher_id' => $homeroom_teacher_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $kelas)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:classrooms,name,' . $kelas->id,
            'homeroom_teacher_id' => 'required|exists:teachers,id',
            'major_id' => 'required|exists:majors,id',
        ], [
            'name.required' => 'Nama kelas wajib diisi.',
            'name.unique' => 'Nama kelas sudah digunakan.',
            'homeroom_teacher_id.required' => 'Wali kelas wajib dipilih.',
            'major_id.required' => 'Jurusan wajib dipilih.',
        ]);
        $kelas->update($request->only('name', 'grade_level', 'capacity', 'major_id'));
        $activeYear = AcademicYear::getActive();
        $assignment = ClassroomAssignment::where('classroom_id', $kelas->id)
            ->where('academic_year_id', $activeYear->id)
            ->first();
        if ($assignment) {
            $assignment->homeroom_teacher_id = $request->homeroom_teacher_id;
            $assignment->save();
        } else {
            ClassroomAssignment::create([
                'classroom_id' => $kelas->id,
                'academic_year_id' => $activeYear->id,
                'homeroom_teacher_id' => $request->homeroom_teacher_id,
            ]);
        }
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diupdate.');
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy(Classroom $kelas)
    {
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Major;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classrooms = Classroom::with(['major', 'homeroomTeacher'])->orderBy('name')->get();
        return view('master.kelas.index', compact('classrooms'));
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
        Classroom::create($request->only('name', 'homeroom_teacher_id', 'major_id'));
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
        return view('master.kelas.form', ['kelas' => $kelas, 'majors' => $majors, 'teachers' => $teachers]);
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
        $kelas->update($request->only('name', 'homeroom_teacher_id', 'major_id'));
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $kelas)
    {
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}

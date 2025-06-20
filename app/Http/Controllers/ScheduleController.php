<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $classrooms = Classroom::where('academic_year_id', $activeYear?->id)->orderBy('name')->get();
        $selectedClass = $request->kelas_id ?? $classrooms->first()?->id;
        $schedules = $selectedClass ? Schedule::where('classroom_id', $selectedClass)->get() : collect();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('full_name')->get();
        return view('admin.jadwal', compact('classrooms', 'selectedClass', 'schedules', 'subjects', 'teachers', 'activeYear'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $classroom = Classroom::findOrFail($request->kelas_id);
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('full_name')->get();
        return view('admin.jadwal-form', compact('classroom', 'subjects', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
        ]);
        Schedule::create($request->only('classroom_id', 'subject_id', 'teacher_id', 'day', 'time_start', 'time_end'));
        return redirect()->route('jadwal.admin.index', ['kelas_id' => $request->classroom_id])->with('success', 'Slot jadwal berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $jadwal)
    {
        $classroom = $jadwal->classroom;
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('full_name')->get();
        return view('admin.jadwal-form', compact('jadwal', 'classroom', 'subjects', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $jadwal)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
        ]);
        $jadwal->update($request->only('subject_id', 'teacher_id', 'day', 'time_start', 'time_end'));
        return redirect()->route('jadwal.admin.index', ['kelas_id' => $jadwal->classroom_id])->with('success', 'Slot jadwal berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $jadwal)
    {
        $kelasId = $jadwal->classroom_id;
        $jadwal->delete();
        return redirect()->route('jadwal.admin.index', ['kelas_id' => $kelasId])->with('success', 'Slot jadwal berhasil dihapus.');
    }
}

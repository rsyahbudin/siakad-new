<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ClassroomAssignment;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Semester;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activeSemester = Semester::where('is_active', true)->first();
        $activeYearId = $activeSemester?->academic_year_id;
        $assignments = ClassroomAssignment::with('classroom')
            ->where('academic_year_id', $activeYearId)
            ->get();
        $selectedAssignment = $request->assignment_id ?? $assignments->first()?->id;
        $schedules = $selectedAssignment ? Schedule::where('classroom_assignment_id', $selectedAssignment)->get() : collect();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('full_name')->get();
        return view('admin.jadwal', compact('assignments', 'selectedAssignment', 'schedules', 'subjects', 'teachers', 'activeSemester'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $assignment = ClassroomAssignment::findOrFail($request->assignment_id);
        $classroom = $assignment->classroom;
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('full_name')->get();
        $day = $request->day;
        $jam = $request->jam;
        return view('admin.jadwal-form', compact('assignment', 'classroom', 'subjects', 'teachers', 'day', 'jam'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'classroom_assignment_id' => 'required|exists:classroom_assignments,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
        ]);
        Schedule::create($request->only('classroom_assignment_id', 'subject_id', 'teacher_id', 'day', 'time_start', 'time_end'));
        return redirect()->route('jadwal.admin.index', ['assignment_id' => $request->classroom_assignment_id])->with('success', 'Slot jadwal berhasil ditambahkan.');
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

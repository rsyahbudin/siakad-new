<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $classrooms = \App\Models\Classroom::where('academic_year_id', $activeYear?->id)->orderBy('name')->get();
        $selectedClass = $request->kelas_id ?? $classrooms->first()?->id;
        $grades = [];
        if ($selectedClass) {
            $grades = \App\Models\Grade::with(['student.user', 'subject'])
                ->where('classroom_id', $selectedClass)
                ->where('academic_year_id', $activeYear?->id)
                ->orderBy('student_id')
                ->orderBy('subject_id')
                ->get();
        }
        return view('admin.nilai', compact('classrooms', 'selectedClass', 'grades', 'activeYear'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        //
    }
}

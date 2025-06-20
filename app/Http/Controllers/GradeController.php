<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\ClassroomAssignment;
use App\Models\Semester;
use Illuminate\Http\Request;

class GradeController extends Controller
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
        $grades = [];
        if ($selectedAssignment) {
            $grades = Grade::with(['student.user', 'subject'])
                ->where('classroom_assignment_id', $selectedAssignment)
                ->where('semester_id', $activeSemester?->id)
                ->orderBy('student_id')
                ->orderBy('subject_id')
                ->get();
        }
        return view('admin.nilai', compact('assignments', 'selectedAssignment', 'grades', 'activeSemester'));
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

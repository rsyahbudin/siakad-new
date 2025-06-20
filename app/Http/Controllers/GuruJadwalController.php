<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\AcademicYear;

class GuruJadwalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeYear = AcademicYear::where('is_active', true)->first();
        $schedules = $teacher ? Schedule::with(['classroom', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('classroomAssignment', function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear?->id);
            })->get() : collect();
        return view('guru.jadwal', compact('schedules', 'activeYear'));
    }
}

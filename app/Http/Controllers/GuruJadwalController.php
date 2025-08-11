<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\AcademicYear;
use App\Models\Semester;

class GuruJadwalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        // Check if user has teacher data
        if (!$teacher) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        // Get active semester and academic year
        $activeSemester = Semester::where('is_active', true)->first();
        $activeYear = $activeSemester ? $activeSemester->academicYear : null;

        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif yang ditemukan.');
        }

        // Build query with proper filtering
        $query = Schedule::with(['classroom', 'subject', 'classroomAssignment.academicYear'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('classroomAssignment', function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            });

        // Add search/filter functionality
        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }

        if ($request->filled('classroom')) {
            $query->whereHas('classroomAssignment', function ($q) use ($request) {
                $q->where('classroom_id', $request->classroom);
            });
        }

        if ($request->filled('subject')) {
            $query->where('subject_id', $request->subject);
        }

        // Get schedules with pagination for better performance
        $schedules = $query->orderBy('day')
            ->orderBy('time_start')
            ->paginate(25) // Limit to 25 items per page for better performance with large datasets
            ->withQueryString();

        // Get statistics for cards (clone query to avoid affecting pagination)
        $statsQuery = clone $query;
        $totalSchedules = $statsQuery->count();
        $totalClassrooms = $statsQuery->distinct('classroom_id')->count();
        $totalSubjects = $statsQuery->distinct('subject_id')->count();

        // Get unique values for filters
        $availableDays = Schedule::where('teacher_id', $teacher->id)
            ->whereHas('classroomAssignment', function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })
            ->distinct()
            ->pluck('day')
            ->sort()
            ->values();

        $availableClassrooms = \App\Models\Classroom::whereHas('classroomAssignments', function ($q) use ($activeYear) {
            $q->where('academic_year_id', $activeYear->id);
        })
            ->orderBy('name')
            ->get();

        $availableSubjects = \App\Models\Subject::whereHas('schedules', function ($q) use ($teacher, $activeYear) {
            $q->where('teacher_id', $teacher->id)
                ->whereHas('classroomAssignment', function ($ca) use ($activeYear) {
                    $ca->where('academic_year_id', $activeYear->id);
                });
        })
            ->orderBy('name')
            ->get();

        return view('guru.jadwal', compact(
            'schedules',
            'activeYear',
            'activeSemester',
            'totalSchedules',
            'totalClassrooms',
            'totalSubjects',
            'availableDays',
            'availableClassrooms',
            'availableSubjects'
        ));
    }
}

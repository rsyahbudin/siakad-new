<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicCalendar;
use App\Models\AcademicYear;
use Carbon\Carbon;

class AcademicCalendarController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $query = AcademicCalendar::with(['academicYear', 'createdBy'])
            ->where('academic_year_id', $activeYear->id)
            ->active();

        // Filter by type
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        $events = $query->orderBy('start_date')->paginate(15);

        // Check if user is admin for CRUD operations
        $isAdmin = Auth::user()->role === 'admin';

        return view('academic-calendar.index', compact(
            'events',
            'activeYear',
            'isAdmin'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        return view('academic-calendar.create', compact('activeYear'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'type' => 'required|in:academic,holiday,exam,meeting,other',
            'priority' => 'required|in:low,medium,high',
            'color' => 'required|string|max:7',
            'is_all_day' => 'boolean',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        AcademicCalendar::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date ?: $request->start_date,
            'start_time' => $request->is_all_day ? null : $request->start_time,
            'end_time' => $request->is_all_day ? null : $request->end_time,
            'type' => $request->type,
            'priority' => $request->priority,
            'is_all_day' => $request->boolean('is_all_day'),
            'academic_year_id' => $activeYear->id,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('academic-calendar.index')
            ->with('success', 'Event kalender akademik berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicCalendar $academicCalendar)
    {
        $isAdmin = Auth::user()->role === 'admin';
        return view('academic-calendar.show', compact('academicCalendar', 'isAdmin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicCalendar $academicCalendar)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        return view('academic-calendar.edit', compact('academicCalendar', 'activeYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicCalendar $academicCalendar)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'type' => 'required|in:academic,holiday,exam,meeting,other',
            'priority' => 'required|in:low,medium,high',
            'is_all_day' => 'boolean',
        ]);

        $academicCalendar->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date ?: $request->start_date,
            'start_time' => $request->is_all_day ? null : $request->start_time,
            'end_time' => $request->is_all_day ? null : $request->end_time,
            'type' => $request->type,
            'priority' => $request->priority,
            'is_all_day' => $request->boolean('is_all_day'),
        ]);

        return redirect()->route('academic-calendar.index')
            ->with('success', 'Event kalender akademik berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicCalendar $academicCalendar)
    {
        $academicCalendar->delete();

        return redirect()->route('academic-calendar.index')
            ->with('success', 'Event kalender akademik berhasil dihapus.');
    }
}

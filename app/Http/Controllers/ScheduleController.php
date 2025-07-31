<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ClassroomAssignment;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Semester;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Get time slot configuration
     */
    private function getTimeSlots()
    {
        return config('siakad.time_slots');
    }

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

        // Get conflict information
        $conflicts = $this->getScheduleConflicts($activeYearId);

        return view('admin.jadwal', compact('assignments', 'selectedAssignment', 'schedules', 'subjects', 'teachers', 'activeSemester', 'conflicts'));
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

        // Get available time slots for the selected day
        $availableSlots = $this->getAvailableTimeSlots($day, $assignment->id);

        return view('admin.jadwal-form', compact('assignment', 'classroom', 'subjects', 'teachers', 'day', 'jam', 'availableSlots'));
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
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
        ]);

        // Check if the time slot conflicts with break times
        $breakConflicts = $this->checkBreakTimeConflicts($request->time_start, $request->time_end);
        if (!empty($breakConflicts)) {
            return back()->withErrors(['time_start' => 'Jadwal tidak boleh bentrok dengan waktu istirahat: ' . implode(', ', $breakConflicts)]);
        }

        // Check for teacher conflicts
        $conflicts = $this->checkTeacherConflicts(
            $request->teacher_id,
            $request->day,
            $request->time_start,
            $request->time_end,
            null
        );

        if (!empty($conflicts)) {
            return back()->withErrors(['teacher_id' => 'Guru ini sudah memiliki jadwal pada waktu yang sama: ' . implode(', ', $conflicts)]);
        }

        // Check for classroom conflicts
        $classroomConflicts = $this->checkClassroomConflicts(
            $request->classroom_assignment_id,
            $request->day,
            $request->time_start,
            $request->time_end,
            null
        );

        if (!empty($classroomConflicts)) {
            return back()->withErrors(['time_start' => 'Kelas ini sudah memiliki jadwal pada waktu yang sama']);
        }

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

        // Get available time slots for the selected day
        $availableSlots = $this->getAvailableTimeSlots($jadwal->day, $jadwal->classroom_assignment_id, $jadwal->id);

        return view('admin.jadwal-form', compact('jadwal', 'classroom', 'subjects', 'teachers', 'availableSlots'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $jadwal)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
        ]);

        // Check if the time slot conflicts with break times
        $breakConflicts = $this->checkBreakTimeConflicts($request->time_start, $request->time_end);
        if (!empty($breakConflicts)) {
            return back()->withErrors(['time_start' => 'Jadwal tidak boleh bentrok dengan waktu istirahat: ' . implode(', ', $breakConflicts)]);
        }

        // Check for teacher conflicts (excluding current schedule)
        $conflicts = $this->checkTeacherConflicts(
            $request->teacher_id,
            $request->day,
            $request->time_start,
            $request->time_end,
            $jadwal->id
        );

        if (!empty($conflicts)) {
            return back()->withErrors(['teacher_id' => 'Guru ini sudah memiliki jadwal pada waktu yang sama: ' . implode(', ', $conflicts)]);
        }

        // Check for classroom conflicts (excluding current schedule)
        $classroomConflicts = $this->checkClassroomConflicts(
            $jadwal->classroom_assignment_id,
            $request->day,
            $request->time_start,
            $request->time_end,
            $jadwal->id
        );

        if (!empty($classroomConflicts)) {
            return back()->withErrors(['time_start' => 'Kelas ini sudah memiliki jadwal pada waktu yang sama']);
        }

        $jadwal->update($request->only('subject_id', 'teacher_id', 'day', 'time_start', 'time_end'));
        return redirect()->route('jadwal.admin.index', ['assignment_id' => $jadwal->classroom_assignment_id])->with('success', 'Slot jadwal berhasil diupdate.');
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy(Schedule $jadwal)
    {
        $assignmentId = $jadwal->classroom_assignment_id;
        $jadwal->delete();
        return redirect()->route('jadwal.admin.index', ['assignment_id' => $assignmentId])->with('success', 'Slot jadwal berhasil dihapus.');
    }

    /**
     * Check for teacher conflicts
     */
    private function checkTeacherConflicts($teacherId, $day, $timeStart, $timeEnd, $excludeId = null)
    {
        $query = Schedule::where('teacher_id', $teacherId)
            ->where('day', $day)
            ->where(function ($q) use ($timeStart, $timeEnd) {
                $q->where(function ($q) use ($timeStart, $timeEnd) {
                    $q->where('time_start', '<', $timeEnd)
                        ->where('time_end', '>', $timeStart);
                });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $conflicts = $query->with(['subject', 'classroom'])->get();

        return $conflicts->map(function ($schedule) {
            return $schedule->subject->name . ' (' . $schedule->classroom->name . ')';
        })->toArray();
    }

    /**
     * Check for classroom conflicts
     */
    private function checkClassroomConflicts($assignmentId, $day, $timeStart, $timeEnd, $excludeId = null)
    {
        $query = Schedule::where('classroom_assignment_id', $assignmentId)
            ->where('day', $day)
            ->where(function ($q) use ($timeStart, $timeEnd) {
                $q->where(function ($q) use ($timeStart, $timeEnd) {
                    $q->where('time_start', '<', $timeEnd)
                        ->where('time_end', '>', $timeStart);
                });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get available time slots for a specific day
     */
    private function getAvailableTimeSlots($day, $assignmentId, $excludeId = null)
    {
        $existingSchedules = Schedule::where('classroom_assignment_id', $assignmentId)
            ->where('day', $day);

        if ($excludeId) {
            $existingSchedules->where('id', '!=', $excludeId);
        }

        $existingSchedules = $existingSchedules->get();

        $availableSlots = [];
        foreach ($this->getTimeSlots() as $slotNumber => $slot) {
            $isAvailable = true;
            foreach ($existingSchedules as $schedule) {
                if ($this->timeOverlaps($slot['start'], $slot['end'], $schedule->time_start, $schedule->time_end)) {
                    $isAvailable = false;
                    break;
                }
            }
            if ($isAvailable) {
                $availableSlots[$slotNumber] = $slot;
            }
        }

        return $availableSlots;
    }

    /**
     * Check if two time ranges overlap
     */
    private function timeOverlaps($start1, $end1, $start2, $end2)
    {
        return $start1 < $end2 && $start2 < $end1;
    }

    /**
     * Check if time slot conflicts with break times
     */
    private function checkBreakTimeConflicts($timeStart, $timeEnd)
    {
        $breakTimes = config('siakad.break_times');
        $conflicts = [];

        foreach ($breakTimes as $breakKey => $break) {
            if ($this->timeOverlaps($timeStart, $timeEnd, $break['start'], $break['end'])) {
                $conflicts[] = $break['name'] . ' (' . $break['start'] . '-' . $break['end'] . ')';
            }
        }

        return $conflicts;
    }

    /**
     * Get schedule conflicts for the current academic year
     */
    private function getScheduleConflicts($academicYearId)
    {
        $conflicts = [];

        // Get all schedules for the academic year
        $schedules = Schedule::whereHas('classroomAssignment', function ($q) use ($academicYearId) {
            $q->where('academic_year_id', $academicYearId);
        })->with(['teacher', 'subject', 'classroom'])->get();

        // Group by teacher and day
        $teacherSchedules = $schedules->groupBy(['teacher_id', 'day']);

        foreach ($teacherSchedules as $teacherId => $daySchedules) {
            foreach ($daySchedules as $day => $daySchedule) {
                // Check for overlapping schedules for the same teacher on the same day
                for ($i = 0; $i < count($daySchedule); $i++) {
                    for ($j = $i + 1; $j < count($daySchedule); $j++) {
                        $schedule1 = $daySchedule[$i];
                        $schedule2 = $daySchedule[$j];

                        if ($this->timeOverlaps($schedule1->time_start, $schedule1->time_end, $schedule2->time_start, $schedule2->time_end)) {
                            $conflicts[] = [
                                'teacher' => $schedule1->teacher->full_name,
                                'day' => $day,
                                'conflict1' => $schedule1->subject->name . ' (' . $schedule1->classroom->name . ') ' . $schedule1->time_start . '-' . $schedule1->time_end,
                                'conflict2' => $schedule2->subject->name . ' (' . $schedule2->classroom->name . ') ' . $schedule2->time_start . '-' . $schedule2->time_end,
                            ];
                        }
                    }
                }
            }
        }

        return $conflicts;
    }
}

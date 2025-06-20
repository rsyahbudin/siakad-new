<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AcademicYear;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Attendance;

class GuruAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user()->teacher;
        $activeYear = AcademicYear::getActive();

        // Ambil jadwal mengajar guru pada tahun ajaran aktif
        $schedules = Schedule::where('teacher_id', $teacher->id)
            ->whereHas('classroom', function ($query) use ($activeYear) {
                // Pastikan kita hanya mengambil jadwal dari tahun ajaran yang aktif
                if ($activeYear) {
                    $query->where('academic_year_id', $activeYear->id);
                } else {
                    // Jika tidak ada tahun ajaran aktif, jangan return apa-apa.
                    $query->whereRaw('1 = 0');
                }
            })
            ->with('classroom', 'subject')
            ->get();

        $scheduleMap = $schedules->map(function ($schedule) {
            return [
                'schedule_id' => $schedule->id,
                'classroom_id' => $schedule->classroom->id,
                'classroom_name' => $schedule->classroom->name,
                'subject_id' => $schedule->subject->id,
                'subject_name' => $schedule->subject->name,
                'day' => $schedule->day,
            ];
        })->unique('classroom_id')->values();

        $selectedScheduleId = $request->input('schedule_id');
        $students = collect();
        $attendances = collect();

        if ($selectedScheduleId) {
            $selectedSchedule = Schedule::findOrFail($selectedScheduleId);
            $students = $selectedSchedule->classroom->students()->orderBy('full_name')->get();

            // Cek absensi yg sudah ada untuk hari ini
            $attendances = Attendance::where('schedule_id', $selectedScheduleId)
                ->where('attendance_date', now()->format('Y-m-d'))
                ->get()
                ->keyBy('student_id');
        }

        return view('guru.input-absensi', compact('activeYear', 'scheduleMap', 'selectedScheduleId', 'students', 'attendances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'attendances' => 'required|array',
            'attendances.*.status' => 'required|in:Hadir,Sakit,Izin,Alpha',
            'attendances.*.notes' => 'nullable|string|max:255',
        ]);

        $teacher = Auth::user()->teacher;
        $activeYear = AcademicYear::getActive();
        $scheduleId = $request->input('schedule_id');
        $attendanceDate = now()->format('Y-m-d');

        DB::transaction(function () use ($request, $teacher, $activeYear, $scheduleId, $attendanceDate) {
            foreach ($request->attendances as $studentId => $data) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'schedule_id' => $scheduleId,
                        'attendance_date' => $attendanceDate,
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'academic_year_id' => $activeYear->id,
                        'status' => $data['status'],
                        'notes' => $data['notes'],
                    ]
                );
            }
        });

        return redirect()->back()->with('success', 'Absensi berhasil disimpan.');
    }
}

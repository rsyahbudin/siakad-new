<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;
use App\Models\StudentAttendance;
use App\Models\Student;
use App\Models\ClassroomAssignment;
use App\Models\Semester;
use Carbon\Carbon;

class TeacherAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()->back()->with('error', 'Akses ditolak. Anda bukan guru.');
        }

        $activeSemester = Semester::where('is_active', true)->first();
        if (!$activeSemester) {
            return redirect()->back()->with('error', 'Tidak ada semester aktif.');
        }

        // Get teacher's schedules for the active semester
        $schedules = Schedule::where('teacher_id', $teacher->id)
            ->whereHas('classroomAssignment', function ($query) use ($activeSemester) {
                $query->where('academic_year_id', $activeSemester->academic_year_id);
            })
            ->with(['subject', 'classroom', 'classroomAssignment'])
            ->orderBy('day')
            ->orderBy('time_start')
            ->get();

        // Group schedules by day
        $schedulesByDay = $schedules->groupBy('day');

        return view('guru.attendance.index', compact('schedulesByDay', 'activeSemester'));
    }

    public function takeAttendance(Request $request, $scheduleId)
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()->back()->with('error', 'Akses ditolak. Anda bukan guru.');
        }

        $schedule = Schedule::with(['subject', 'classroom', 'classroomAssignment.classStudents.student.user'])
            ->where('id', $scheduleId)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$schedule) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan.');
        }

        $date = $request->get('date', now()->format('Y-m-d'));
        $attendanceDate = Carbon::parse($date);

        // Check if it's a valid school day (Monday-Friday)
        if ($attendanceDate->isWeekend()) {
            return redirect()->back()->with('error', 'Tidak dapat mengambil absensi pada hari libur.');
        }

        // Get students in this class
        $students = $schedule->classroomAssignment->classStudents()
            ->with('student.user')
            ->orderBy('student_id')
            ->get();

        // Get existing attendance for this date and schedule
        $existingAttendance = StudentAttendance::where('schedule_id', $scheduleId)
            ->where('attendance_date', $date)
            ->get()
            ->keyBy('student_id');

        return view('guru.attendance.take', compact('schedule', 'students', 'existingAttendance', 'date'));
    }

    public function storeAttendance(Request $request, $scheduleId)
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()->back()->with('error', 'Akses ditolak. Anda bukan guru.');
        }

        $schedule = Schedule::where('id', $scheduleId)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$schedule) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan.');
        }

        $request->validate([
            'attendance_date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:hadir,izin,sakit,alpha',
            'attendances.*.notes' => 'nullable|string|max:255',
        ]);

        $attendanceDate = $request->attendance_date;
        $attendanceTime = now()->format('H:i:s');

        // Check if attendance already exists for this date and schedule
        $existingCount = StudentAttendance::where('schedule_id', $scheduleId)
            ->where('attendance_date', $attendanceDate)
            ->count();

        if ($existingCount > 0) {
            return redirect()->back()->with('error', 'Absensi untuk tanggal ini sudah diisi.');
        }

        DB::transaction(function () use ($request, $schedule, $attendanceDate, $attendanceTime) {
            foreach ($request->attendances as $data) {
                StudentAttendance::create([
                    'student_id' => $data['student_id'],
                    'schedule_id' => $schedule->id,
                    'teacher_id' => $schedule->teacher_id,
                    'subject_id' => $schedule->subject_id,
                    'classroom_id' => $schedule->classroom_id,
                    'attendance_date' => $attendanceDate,
                    'attendance_time' => $attendanceTime,
                    'status' => $data['status'],
                    'notes' => $data['notes'] ?? null,
                ]);
            }
        });

        return redirect()->route('teacher.attendance.index')
            ->with('success', 'Absensi berhasil disimpan.');
    }

    public function viewAttendance(Request $request, $scheduleId)
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()->back()->with('error', 'Akses ditolak. Anda bukan guru.');
        }

        $schedule = Schedule::with(['subject', 'classroom'])
            ->where('id', $scheduleId)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$schedule) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan.');
        }

        $month = $request->get('month', now()->format('Y-m'));
        $startDate = Carbon::parse($month . '-01');
        $endDate = $startDate->copy()->endOfMonth();

        // Get all attendance records for this schedule in the selected month
        $attendances = StudentAttendance::where('schedule_id', $scheduleId)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->with(['student.user'])
            ->orderBy('attendance_date')
            ->orderBy('student_id')
            ->get();

        // Group by date
        $attendanceByDate = $attendances->groupBy('attendance_date');

        // Get students in this class
        $students = $schedule->classroomAssignment->classStudents()
            ->with('student.user')
            ->orderBy('student_id')
            ->get();

        return view('guru.attendance.view', compact('schedule', 'attendanceByDate', 'students', 'month'));
    }

    public function editAttendance(Request $request, $scheduleId, $date)
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()->back()->with('error', 'Akses ditolak. Anda bukan guru.');
        }

        $schedule = Schedule::with(['subject', 'classroom', 'classroomAssignment.classStudents.student.user'])
            ->where('id', $scheduleId)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$schedule) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan.');
        }

        // Get existing attendance for this date and schedule
        $existingAttendance = StudentAttendance::where('schedule_id', $scheduleId)
            ->where('attendance_date', $date)
            ->get()
            ->keyBy('student_id');

        if ($existingAttendance->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data absensi untuk tanggal tersebut.');
        }

        // Get students in this class
        $students = $schedule->classroomAssignment->classStudents()
            ->with('student.user')
            ->orderBy('student_id')
            ->get();

        return view('guru.attendance.edit', compact('schedule', 'students', 'existingAttendance', 'date'));
    }

    public function updateAttendance(Request $request, $scheduleId, $date)
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()->back()->with('error', 'Akses ditolak. Anda bukan guru.');
        }

        $schedule = Schedule::where('id', $scheduleId)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$schedule) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan.');
        }

        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:hadir,izin,sakit,alpha',
            'attendances.*.notes' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $schedule, $date) {
            foreach ($request->attendances as $data) {
                StudentAttendance::where('schedule_id', $schedule->id)
                    ->where('student_id', $data['student_id'])
                    ->where('attendance_date', $date)
                    ->update([
                        'status' => $data['status'],
                        'notes' => $data['notes'] ?? null,
                    ]);
            }
        });

        return redirect()->route('teacher.attendance.view', $scheduleId)
            ->with('success', 'Absensi berhasil diperbarui.');
    }
}

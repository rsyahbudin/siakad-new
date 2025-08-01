<?php

namespace App\Services;

use App\Models\StudentAttendance;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\ClassroomAssignment;
use App\Models\Semester;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * Get monthly attendance statistics for a student
     */
    public function getMonthlyStats($studentId, $month, $year)
    {
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $stats = StudentAttendance::where('student_id', $studentId)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_days,
                SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = "izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = "sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = "alpha" THEN 1 ELSE 0 END) as alpha
            ')
            ->first();

        return [
            'total_days' => $stats->total_days ?? 0,
            'hadir' => $stats->hadir ?? 0,
            'izin' => $stats->izin ?? 0,
            'sakit' => $stats->sakit ?? 0,
            'alpha' => $stats->alpha ?? 0,
            'percentage' => $stats->total_days > 0 ? round(($stats->hadir / $stats->total_days) * 100, 2) : 0
        ];
    }

    /**
     * Get semester attendance statistics for a student
     */
    public function getSemesterStats($studentId, $semesterId)
    {
        $stats = StudentAttendance::where('student_id', $studentId)
            ->whereHas('schedule.classroomAssignment', function ($query) use ($semesterId) {
                $semester = Semester::find($semesterId);
                if ($semester) {
                    $query->where('academic_year_id', $semester->academic_year_id);
                }
            })
            ->selectRaw('
                COUNT(*) as total_days,
                SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = "izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = "sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = "alpha" THEN 1 ELSE 0 END) as alpha
            ')
            ->first();

        return [
            'total_days' => $stats->total_days ?? 0,
            'hadir' => $stats->hadir ?? 0,
            'izin' => $stats->izin ?? 0,
            'sakit' => $stats->sakit ?? 0,
            'alpha' => $stats->alpha ?? 0,
            'percentage' => $stats->total_days > 0 ? round(($stats->hadir / $stats->total_days) * 100, 2) : 0
        ];
    }

    /**
     * Get class attendance summary for homeroom teacher
     */
    public function getClassAttendanceSummary($classroomAssignmentId, $semesterId)
    {
        $students = ClassroomAssignment::find($classroomAssignmentId)
            ->classStudents()
            ->with('student.user')
            ->get();

        $summary = [];
        foreach ($students as $classStudent) {
            $studentId = $classStudent->student_id;
            $semesterStats = $this->getSemesterStats($studentId, $semesterId);

            $summary[$studentId] = [
                'student' => $classStudent->student,
                'stats' => $semesterStats
            ];
        }

        return $summary;
    }

    /**
     * Aggregate daily attendance to semester summary for raport
     */
    public function aggregateToSemesterSummary($classroomAssignmentId, $semesterId)
    {
        $semester = Semester::find($semesterId);
        if (!$semester) {
            return false;
        }

        $students = ClassroomAssignment::find($classroomAssignmentId)
            ->classStudents()
            ->pluck('student_id');

        foreach ($students as $studentId) {
            $stats = $this->getSemesterStats($studentId, $semesterId);

            // Update or create semester attendance summary
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'classroom_assignment_id' => $classroomAssignmentId,
                    'semester_id' => $semesterId,
                ],
                [
                    'teacher_id' => ClassroomAssignment::find($classroomAssignmentId)->homeroom_teacher_id,
                    'academic_year_id' => $semester->academic_year_id,
                    'sakit' => $stats['sakit'],
                    'izin' => $stats['izin'],
                    'alpha' => $stats['alpha'],
                ]
            );
        }

        return true;
    }

    /**
     * Get attendance by subject for a student
     */
    public function getAttendanceBySubject($studentId, $semesterId)
    {
        $semester = Semester::find($semesterId);
        if (!$semester) {
            return collect();
        }

        return StudentAttendance::where('student_id', $studentId)
            ->whereHas('schedule.classroomAssignment', function ($query) use ($semester) {
                $query->where('academic_year_id', $semester->academic_year_id);
            })
            ->with(['subject', 'schedule'])
            ->selectRaw('
                subject_id,
                COUNT(*) as total_days,
                SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = "izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = "sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = "alpha" THEN 1 ELSE 0 END) as alpha
            ')
            ->groupBy('subject_id')
            ->get();
    }

    /**
     * Check if attendance exists for a specific date and schedule
     */
    public function attendanceExists($scheduleId, $date)
    {
        return StudentAttendance::where('schedule_id', $scheduleId)
            ->where('attendance_date', $date)
            ->exists();
    }

    /**
     * Get teacher's schedules for today
     */
    public function getTodaySchedules($teacherId)
    {
        $today = now();
        $dayOfWeek = $today->format('l'); // Monday, Tuesday, etc.

        return Schedule::where('teacher_id', $teacherId)
            ->where('day', $dayOfWeek)
            ->with(['subject', 'classroom'])
            ->orderBy('time_start')
            ->get();
    }
}

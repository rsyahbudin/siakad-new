<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\PPDBApplication;
use App\Models\TransferStudent;
use App\Models\Schedule;
use App\Models\Grade;
use App\Models\AcademicYear;
use App\Models\ClassStudent;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            // Get active academic year
            $activeYear = AcademicYear::where('is_active', true)->first();

            // Get statistics for admin dashboard
            $stats = [
                'total_students' => Student::where('status', 'Aktif')->count(),
                'total_teachers' => Teacher::count(),
                'total_classrooms' => Classroom::count(),
                'total_schedules' => Schedule::count(),
                'ppdb_pending' => PPDBApplication::where('status', 'pending')->count(),
                'ppdb_approved' => PPDBApplication::where('status', 'lulus')->count(),
                'transfer_pending' => TransferStudent::where('status', 'pending')->count(),
                'transfer_approved' => TransferStudent::where('status', 'approved')->count(),
                'recent_grades' => Grade::with(['student', 'subject', 'classroom'])
                    ->latest()
                    ->limit(5)
                    ->get(),
                'recent_ppdb' => PPDBApplication::latest()
                    ->limit(5)
                    ->get(),
            ];

                        // Get student statistics by grade level for active academic year
            if ($activeYear) {
                $stats['students_by_grade'] = ClassStudent::join('classrooms', 'class_student.classroom_id', '=', 'classrooms.id')
                    ->join('students', 'class_student.student_id', '=', 'students.id')
                    ->where('class_student.academic_year_id', $activeYear->id)
                    ->whereIn('students.status', ['Aktif', 'Pindahan'])
                    ->selectRaw('classrooms.grade_level, COUNT(*) as total')
                    ->groupBy('classrooms.grade_level')
                    ->orderBy('classrooms.grade_level')
                    ->get()
                    ->keyBy('grade_level');
                
                $stats['active_year'] = $activeYear;
            } else {
                $stats['students_by_grade'] = collect();
                $stats['active_year'] = null;
            }
            return view('dashboard.admin', compact('stats'));
        } elseif ($user->isTeacher()) {
            // Get teacher-specific data
            $teacher = $user->teacher;
            if (!$teacher) {
                abort(403, 'Data guru tidak ditemukan.');
            }

            $teacherStats = [
                'total_schedules' => Schedule::where('teacher_id', $teacher->id)->count(),
                'total_students' => Schedule::where('teacher_id', $teacher->id)
                    ->with('classroom.students')
                    ->get()
                    ->sum(function ($schedule) {
                        return $schedule->classroom->students->count();
                    }),
                'today_schedules' => Schedule::with(['subject', 'classroom'])
                    ->where('teacher_id', $teacher->id)
                    ->where('day', now()->isoFormat('dddd'))
                    ->orderBy('time_start')
                    ->get(),
                'recent_grades' => Grade::whereHas('subject.schedules', function ($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id);
                })
                    ->with(['student', 'subject'])
                    ->latest()
                    ->limit(5)
                    ->get(),
            ];
            return view('dashboard.guru', compact('teacherStats'));
        } elseif ($user->isStudent()) {
            $student = $user->student;
            $classroom = $student->classrooms()->latest('id')->first();
            $today = now()->isoFormat('dddd'); // e.g. 'Monday', 'Selasa', dst
            $todaySchedules = [];
            if ($classroom) {
                $todaySchedules = \App\Models\Schedule::with(['subject', 'teacher'])
                    ->where('classroom_id', $classroom->id)
                    ->where('day', $today)
                    ->orderBy('time_start')
                    ->get();
            }
            return view('dashboard.siswa', compact('todaySchedules'));
        } elseif ($user->isKepalaSekolah()) {
            return redirect()->route('kepala.dashboard');
        } elseif ($user->isWaliMurid()) {
            return redirect()->route('wali.dashboard');
        }
        abort(403, 'Akses tidak diizinkan.');
    }

    public function guru()
    {
        $user = Auth::user();

        if (!$user->isTeacher()) {
            abort(403, 'Akses hanya untuk guru.');
        }

        $teacher = $user->teacher;
        if (!$teacher) {
            abort(403, 'Data guru tidak ditemukan.');
        }

        $teacherStats = [
            'total_schedules' => Schedule::where('teacher_id', $teacher->id)->count(),
            'total_students' => Schedule::where('teacher_id', $teacher->id)
                ->with('classroom.students')
                ->get()
                ->sum(function ($schedule) {
                    return $schedule->classroom->students->count();
                }),
            'today_schedules' => Schedule::with(['subject', 'classroom'])
                ->where('teacher_id', $teacher->id)
                ->where('day', now()->isoFormat('dddd'))
                ->orderBy('time_start')
                ->get(),
            'recent_grades' => Grade::whereHas('subject.schedules', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
                ->with(['student', 'subject'])
                ->latest()
                ->limit(5)
                ->get(),
        ];

        return view('dashboard.guru', compact('teacherStats'));
    }
}

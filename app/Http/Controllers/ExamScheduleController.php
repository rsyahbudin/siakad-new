<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamSchedule;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Teacher;
use App\Models\Major;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = ExamSchedule::with(['academicYear', 'semester', 'subject', 'classroom', 'supervisor', 'major']);

        // Get active semester and academic year
        $activeSemester = Semester::where('is_active', true)->first();
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        // Filter berdasarkan role user
        if ($user->role === 'admin') {
            // Admin bisa melihat semua jadwal, tapi default hanya untuk semester aktif
            if ($request->filled('academic_year_id')) {
                $query->where('academic_year_id', $request->academic_year_id);
            } else {
                // Default to active academic year
                $query->where('academic_year_id', $activeAcademicYear->id ?? 0);
            }

            if ($request->filled('semester_id')) {
                $query->where('semester_id', $request->semester_id);
            } else {
                // Default to active semester
                $query->where('semester_id', $activeSemester->id ?? 0);
            }

            if ($request->filled('exam_type')) {
                $query->where('exam_type', $request->exam_type);
            }
            if ($request->filled('classroom_id')) {
                $query->where('classroom_id', $request->classroom_id);
            }
            if ($request->filled('subject_id')) {
                $query->where('subject_id', $request->subject_id);
            }
            if ($request->filled('major_id')) {
                $query->where('major_id', $request->major_id);
            }
            if ($request->filled('supervisor_id')) {
                $query->where('supervisor_id', $request->supervisor_id);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('subject', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    })
                        ->orWhereHas('classroom', function ($subQ) use ($search) {
                            $subQ->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('supervisor', function ($subQ) use ($search) {
                            $subQ->where('full_name', 'like', "%{$search}%");
                        });
                });
            }
        } elseif ($user->role === 'guru') {
            // Guru hanya bisa melihat jadwal ujian yang dia awasi untuk semester aktif
            $teacher = Teacher::where('user_id', $user->id)->first();
            if ($teacher) {
                $query->where('supervisor_id', $teacher->id)
                    ->where('academic_year_id', $activeAcademicYear->id ?? 0)
                    ->where('semester_id', $activeSemester->id ?? 0);
            }
        } elseif ($user->role === 'siswa') {
            // Siswa hanya bisa melihat jadwal ujian kelasnya untuk semester aktif
            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                $classroomId = $student->classStudents()->where('academic_year_id', $activeAcademicYear->id ?? 0)->first()?->classroom_id;
                if ($classroomId) {
                    $query->where('classroom_id', $classroomId)
                        ->where('academic_year_id', $activeAcademicYear->id ?? 0)
                        ->where('semester_id', $activeSemester->id ?? 0);
                }
            }
        } elseif ($user->role === 'wali_murid') {
            // Wali murid melihat jadwal ujian anaknya untuk semester aktif
            $waliMurid = \App\Models\WaliMurid::where('user_id', $user->id)->first();
            if ($waliMurid) {
                $student = Student::find($waliMurid->student_id);
                if ($student) {
                    $classroomId = $student->classStudents()->where('academic_year_id', $activeAcademicYear->id ?? 0)->first()?->classroom_id;
                    if ($classroomId) {
                        $query->where('classroom_id', $classroomId)
                            ->where('academic_year_id', $activeAcademicYear->id ?? 0)
                            ->where('semester_id', $activeSemester->id ?? 0);
                    }
                }
            }
        }

        $examSchedules = $query->orderBy('exam_date')->orderBy('start_time')->paginate(20);

        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $semesters = Semester::orderBy('name')->get();
        $classrooms = Classroom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $majors = Major::orderBy('name')->get();
        $supervisors = Teacher::orderBy('full_name')->get();

        return view('admin.exam-schedule.index', compact('examSchedules', 'academicYears', 'semesters', 'classrooms', 'subjects', 'majors', 'supervisors', 'activeSemester', 'activeAcademicYear'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get active semester and academic year
        $activeSemester = Semester::where('is_active', true)->first();
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        if (!$activeSemester || !$activeAcademicYear) {
            return redirect()->route('admin.exam-schedules.index')
                ->with('error', 'Tidak ada semester atau tahun ajaran aktif. Silakan aktifkan semester terlebih dahulu.');
        }

        // Only show active academic year and semester
        $academicYears = AcademicYear::where('id', $activeAcademicYear->id)->get();
        $semesters = Semester::where('id', $activeSemester->id)->get();
        $subjects = Subject::with('major')->orderBy('name')->get();
        $classrooms = Classroom::orderBy('name')->get();
        $teachers = Teacher::orderBy('full_name')->get();
        $majors = Major::orderBy('name')->get();

        return view('admin.exam-schedule.create', compact('academicYears', 'semesters', 'subjects', 'classrooms', 'teachers', 'majors', 'activeSemester', 'activeAcademicYear'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Get active semester and academic year
        $activeSemester = Semester::where('is_active', true)->first();
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        if (!$activeSemester || !$activeAcademicYear) {
            return redirect()->route('admin.exam-schedules.index')
                ->with('error', 'Tidak ada semester atau tahun ajaran aktif. Silakan aktifkan semester terlebih dahulu.');
        }

        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_id' => 'required|exists:semesters,id',
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'supervisor_id' => 'required|exists:teachers,id',
            'exam_type' => 'required|in:uts,uas',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_general_subject' => 'required|boolean',
            'major_id' => 'nullable|exists:majors,id',
        ]);

        // Ensure only creating for active semester and academic year
        if ($request->academic_year_id != $activeAcademicYear->id) {
            return back()->withErrors(['academic_year_id' => 'Hanya dapat membuat jadwal ujian untuk tahun ajaran aktif.']);
        }

        if ($request->semester_id != $activeSemester->id) {
            return back()->withErrors(['semester_id' => 'Hanya dapat membuat jadwal ujian untuk semester aktif.']);
        }

        // Validasi untuk memastikan hanya 1 UTS dan 1 UAS per semester
        $existingExam = ExamSchedule::where('academic_year_id', $request->academic_year_id)
            ->where('semester_id', $request->semester_id)
            ->where('subject_id', $request->subject_id)
            ->where('classroom_id', $request->classroom_id)
            ->where('exam_type', $request->exam_type)
            ->exists();

        if ($existingExam) {
            return back()->withErrors(['exam_type' => 'Jadwal ujian ' . strtoupper($request->exam_type) . ' untuk mata pelajaran ini sudah ada.']);
        }

        // Validasi untuk memastikan guru tidak mengawasi 2 ujian pada waktu yang sama
        $conflictingSchedule = ExamSchedule::where('supervisor_id', $request->supervisor_id)
            ->where('exam_date', $request->exam_date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($conflictingSchedule) {
            return back()->withErrors(['supervisor_id' => 'Guru ini sudah ditugaskan mengawasi ujian lain pada waktu yang sama.']);
        }

        ExamSchedule::create($request->all());

        return redirect()->route('admin.exam-schedules.index')->with('success', 'Jadwal ujian berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ExamSchedule $examSchedule)
    {
        $examSchedule->load(['academicYear', 'semester', 'subject', 'classroom', 'supervisor', 'major']);
        return view('admin.exam-schedule.show', compact('examSchedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExamSchedule $examSchedule)
    {
        // Get active semester and academic year
        $activeSemester = Semester::where('is_active', true)->first();
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        if (!$activeSemester || !$activeAcademicYear) {
            return redirect()->route('admin.exam-schedules.index')
                ->with('error', 'Tidak ada semester atau tahun ajaran aktif. Silakan aktifkan semester terlebih dahulu.');
        }

        // Only allow editing exams for active semester and academic year
        if ($examSchedule->academic_year_id != $activeAcademicYear->id || $examSchedule->semester_id != $activeSemester->id) {
            return redirect()->route('admin.exam-schedules.index')
                ->with('error', 'Hanya dapat mengedit jadwal ujian untuk semester dan tahun ajaran aktif.');
        }

        // Only show active academic year and semester
        $academicYears = AcademicYear::where('id', $activeAcademicYear->id)->get();
        $semesters = Semester::where('id', $activeSemester->id)->get();
        $subjects = Subject::with('major')->orderBy('name')->get();
        $classrooms = Classroom::orderBy('name')->get();
        $teachers = Teacher::orderBy('full_name')->get();
        $majors = Major::orderBy('name')->get();

        return view('admin.exam-schedule.edit', compact('examSchedule', 'academicYears', 'semesters', 'subjects', 'classrooms', 'teachers', 'majors', 'activeSemester', 'activeAcademicYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExamSchedule $examSchedule)
    {
        // Get active semester and academic year
        $activeSemester = Semester::where('is_active', true)->first();
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        if (!$activeSemester || !$activeAcademicYear) {
            return redirect()->route('admin.exam-schedules.index')
                ->with('error', 'Tidak ada semester atau tahun ajaran aktif. Silakan aktifkan semester terlebih dahulu.');
        }

        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_id' => 'required|exists:semesters,id',
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'supervisor_id' => 'required|exists:teachers,id',
            'exam_type' => 'required|in:uts,uas',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_general_subject' => 'required|boolean',
            'major_id' => 'nullable|exists:majors,id',
        ]);

        // Ensure only updating for active semester and academic year
        if ($request->academic_year_id != $activeAcademicYear->id) {
            return back()->withErrors(['academic_year_id' => 'Hanya dapat mengedit jadwal ujian untuk tahun ajaran aktif.']);
        }

        if ($request->semester_id != $activeSemester->id) {
            return back()->withErrors(['semester_id' => 'Hanya dapat mengedit jadwal ujian untuk semester aktif.']);
        }

        // Validasi untuk memastikan hanya 1 UTS dan 1 UAS per semester (kecuali untuk record yang sedang diedit)
        $existingExam = ExamSchedule::where('academic_year_id', $request->academic_year_id)
            ->where('semester_id', $request->semester_id)
            ->where('subject_id', $request->subject_id)
            ->where('classroom_id', $request->classroom_id)
            ->where('exam_type', $request->exam_type)
            ->where('id', '!=', $examSchedule->id)
            ->exists();

        if ($existingExam) {
            return back()->withErrors(['exam_type' => 'Jadwal ujian ' . strtoupper($request->exam_type) . ' untuk mata pelajaran ini sudah ada.']);
        }

        // Validasi untuk memastikan guru tidak mengawasi 2 ujian pada waktu yang sama (kecuali untuk record yang sedang diedit)
        $conflictingSchedule = ExamSchedule::where('supervisor_id', $request->supervisor_id)
            ->where('exam_date', $request->exam_date)
            ->where('id', '!=', $examSchedule->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($conflictingSchedule) {
            return back()->withErrors(['supervisor_id' => 'Guru ini sudah ditugaskan mengawasi ujian lain pada waktu yang sama.']);
        }

        $examSchedule->update($request->all());

        return redirect()->route('admin.exam-schedules.index')->with('success', 'Jadwal ujian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamSchedule $examSchedule)
    {
        // Get active semester and academic year
        $activeSemester = Semester::where('is_active', true)->first();
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        // Only allow deleting exams for active semester and academic year
        if ($examSchedule->academic_year_id != $activeAcademicYear->id || $examSchedule->semester_id != $activeSemester->id) {
            return redirect()->route('admin.exam-schedules.index')
                ->with('error', 'Hanya dapat menghapus jadwal ujian untuk semester dan tahun ajaran aktif.');
        }

        $examSchedule->delete();
        return redirect()->route('admin.exam-schedules.index')->with('success', 'Jadwal ujian berhasil dihapus.');
    }

    /**
     * Tampilkan jadwal ujian untuk siswa
     */
    public function studentSchedule()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        // Get current active academic year and semester
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        if (!$activeAcademicYear || !$activeSemester) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran atau semester aktif.');
        }

        $classroomId = $student->classStudents()->where('academic_year_id', $activeAcademicYear->id)->first()?->classroom_id;

        if (!$classroomId) {
            return redirect()->back()->with('error', 'Siswa belum terdaftar di kelas manapun.');
        }

        $examSchedules = ExamSchedule::with(['academicYear', 'semester', 'subject', 'classroom', 'supervisor', 'major'])
            ->where('classroom_id', $classroomId)
            ->where('academic_year_id', $activeAcademicYear->id)
            ->where('semester_id', $activeSemester->id)
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get()
            ->groupBy('exam_type');

        return view('siswa.exam-schedule.index', compact('examSchedules', 'activeSemester', 'activeAcademicYear'));
    }

    /**
     * Tampilkan jadwal ujian untuk guru
     */
    public function teacherSchedule()
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        // Get current active academic year and semester
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        if (!$activeAcademicYear || !$activeSemester) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran atau semester aktif.');
        }

        $examSchedules = ExamSchedule::with(['academicYear', 'semester', 'subject', 'classroom', 'supervisor', 'major'])
            ->where('supervisor_id', $teacher->id)
            ->where('academic_year_id', $activeAcademicYear->id)
            ->where('semester_id', $activeSemester->id)
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get()
            ->groupBy('exam_type');

        return view('guru.exam-schedule.index', compact('examSchedules', 'activeSemester', 'activeAcademicYear'));
    }

    /**
     * Tampilkan jadwal ujian untuk wali murid
     */
    public function parentSchedule()
    {
        $user = Auth::user();
        $waliMurid = \App\Models\WaliMurid::where('user_id', $user->id)->first();

        if (!$waliMurid) {
            return redirect()->back()->with('error', 'Data wali murid tidak ditemukan.');
        }

        $student = Student::find($waliMurid->student_id);
        if (!$student) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        // Get current active academic year and semester
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        if (!$activeAcademicYear || !$activeSemester) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran atau semester aktif.');
        }

        $classroomId = $student->classStudents()->where('academic_year_id', $activeAcademicYear->id)->first()?->classroom_id;

        if (!$classroomId) {
            return redirect()->back()->with('error', 'Siswa belum terdaftar di kelas manapun.');
        }

        $examSchedules = ExamSchedule::with(['academicYear', 'semester', 'subject', 'classroom', 'supervisor', 'major'])
            ->where('classroom_id', $classroomId)
            ->where('academic_year_id', $activeAcademicYear->id)
            ->where('semester_id', $activeSemester->id)
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get()
            ->groupBy('exam_type');

        return view('wali-murid.exam-schedule.index', compact('examSchedules', 'activeSemester', 'activeAcademicYear'));
    }
}

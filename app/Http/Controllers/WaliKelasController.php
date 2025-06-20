<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicYear;
use App\Models\ClassroomAssignment;
use App\Models\Schedule;
use App\Models\Grade;
use App\Models\SubjectSetting;
use App\Models\Raport;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\StudentPromotion;
use Illuminate\Support\Facades\DB;
use App\Models\Semester;

class WaliKelasController extends Controller
{
    public function leger(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $assignment = ClassroomAssignment::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeSemester?->academic_year_id)
            ->first();
        $kelas = $assignment?->classroom;
        if (!$assignment || !$kelas) {
            return view('guru.wali-leger-empty');
        }
        $students = $assignment->classStudents()->with('student.user')->get()->pluck('student');
        $mapels = $kelas ? Schedule::where('classroom_id', $kelas->id)
            ->with('subject')
            ->get()
            ->pluck('subject')
            ->unique('id')
            ->sortBy('name') : collect();
        $grades = Grade::where('classroom_id', $kelas?->id)
            ->where('semester_id', $activeSemester?->id)
            ->get()
            ->groupBy(['student_id', 'subject_id']);
        $subjectSettings = SubjectSetting::where('academic_year_id', $activeSemester?->academic_year_id)
            ->whereIn('subject_id', $mapels->pluck('id'))
            ->get()
            ->keyBy('subject_id');
        return view('guru.wali-leger', compact('kelas', 'students', 'mapels', 'grades', 'subjectSettings', 'activeSemester'));
    }

    public function kelas(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $assignment = ClassroomAssignment::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeSemester?->academic_year_id)
            ->first();
        $kelas = $assignment?->classroom;
        if (!$assignment || !$kelas) {
            return view('guru.wali-kelas-empty');
        }
        $students = $assignment->classStudents()->with('student.user')->paginate(20);
        return view('guru.wali-kelas', compact('kelas', 'students', 'activeSemester'));
    }

    public function absensi(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $assignment = ClassroomAssignment::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeSemester?->academic_year_id)
            ->first();
        $kelas = $assignment?->classroom;
        if (!$assignment || !$kelas) {
            return view('guru.wali-kelas-empty');
        }
        $tab = $request->get('tab', 'input');
        $q = $request->get('q');
        $studentsQuery = $assignment->classStudents()->with('student');
        if ($q) {
            $studentsQuery->whereHas('student', function ($query) use ($q) {
                $query->where('full_name', 'like', "%$q%")
                    ->orWhere('nis', 'like', "%$q%")
                    ->orWhere('nisn', 'like', "%$q%");
            });
        }
        $students = $studentsQuery->paginate(20)->appends(['tab' => $tab, 'q' => $q]);
        $studentList = $students->pluck('student');
        // Absensi harian (input hari ini)
        $today = now()->format('Y-m-d');
        $absensiHarian = Attendance::where('classroom_assignment_id', $assignment->id)
            ->where('semester_id', $activeSemester->id)
            ->where('attendance_date', $today)
            ->get()
            ->keyBy('student_id');
        // Rekap semester
        $rekapAbsensi = Attendance::whereIn('student_id', $studentList->pluck('id'))
            ->where('semester_id', $activeSemester->id)
            ->select('student_id', 'status', DB::raw('count(*) as total'))
            ->groupBy('student_id', 'status')
            ->get()
            ->groupBy('student_id');
        $semesterInt = $activeSemester->name === 'Ganjil' ? 1 : 2;
        $raports = Raport::where('classroom_id', $kelas->id)
            ->where('academic_year_id', $activeSemester->academic_year_id)
            ->where('semester', $semesterInt)
            ->get()
            ->keyBy('student_id');
        return view('guru.wali-absensi', compact('kelas', 'students', 'absensiHarian', 'rekapAbsensi', 'raports', 'activeSemester', 'tab', 'q'));
    }

    public function storeAbsensi(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $assignment = ClassroomAssignment::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeSemester?->academic_year_id)
            ->firstOrFail();
        $kelas = $assignment->classroom;
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.status' => 'required|in:Hadir,Sakit,Izin,Alpha',
            'attendances.*.notes' => 'nullable|string|max:255',
        ]);
        $attendanceDate = now()->format('Y-m-d');
        DB::transaction(function () use ($request, $teacher, $activeSemester, $attendanceDate, $assignment) {
            foreach ($request->attendances as $studentId => $data) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'classroom_assignment_id' => $assignment->id,
                        'semester_id' => $activeSemester->id,
                        'attendance_date' => $attendanceDate,
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'academic_year_id' => $activeSemester->academic_year_id,
                        'status' => $data['status'],
                        'notes' => $data['notes'],
                    ]
                );
            }
        });
        return redirect()->route('wali.absensi')->with('success', 'Absensi harian berhasil disimpan.');
    }

    public function finalisasi()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $assignment = ClassroomAssignment::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeSemester?->academic_year_id)
            ->first();
        $kelas = $assignment?->classroom;
        if (!$assignment || !$kelas) {
            return view('guru.wali-kelas-empty');
        }
        $semesterInt = $activeSemester->name === 'Ganjil' ? 1 : 2;
        $raports = Raport::where('classroom_id', $kelas->id)
            ->where('academic_year_id', $activeSemester->academic_year_id)
            ->where('semester', $semesterInt)
            ->with('student')
            ->get();
        $isAllFinalized = $raports->every(fn($raport) => $raport->is_finalized);
        return view('guru.wali-finalisasi', compact('kelas', 'raports', 'activeSemester', 'isAllFinalized'));
    }

    public function showRaport(Student $student)
    {
        $activeSemester = Semester::where('is_active', true)->first();
        if (!$activeSemester) {
            return back()->with('error', 'Tahun ajaran aktif belum ditentukan.');
        }
        $assignment = $student->classStudents()->where('academic_year_id', $activeSemester->academic_year_id)->first();
        $kelas = $assignment?->classroomAssignment?->classroom;
        $waliKelas = $assignment?->classroomAssignment?->homeroomTeacher;
        if (!$kelas) {
            return back()->with('error', 'Siswa tidak terdaftar di kelas manapun pada tahun ajaran ini.');
        }
        $waliKelasAssignment = ClassroomAssignment::where('homeroom_teacher_id', Auth::user()->teacher->id)
            ->where('academic_year_id', $activeSemester->academic_year_id)
            ->first();
        if ($assignment->classroom_assignment_id !== $waliKelasAssignment?->id) {
            return back()->with('error', 'Anda tidak memiliki hak akses untuk melihat raport siswa ini.');
        }
        $semesterInt = $activeSemester->name === 'Ganjil' ? 1 : 2;
        $raport = Raport::where('student_id', $student->id)
            ->where('classroom_id', $kelas->id)
            ->where('academic_year_id', $activeSemester->academic_year_id)
            ->where('semester', $semesterInt)
            ->first();
        $grades = Grade::with('subject')
            ->where('student_id', $student->id)
            ->where('semester_id', $activeSemester->id)
            ->get();
        $subjectSettings = SubjectSetting::where('academic_year_id', $activeSemester->academic_year_id)
            ->whereIn('subject_id', $grades->pluck('subject_id'))
            ->get()
            ->keyBy('subject_id');
        // Absensi: rekap langsung jika raport kosong atau field absensi kosong
        if (!$raport || (!$raport->attendance_sick && !$raport->attendance_permit && !$raport->attendance_absent)) {
            $attendance = Attendance::where('student_id', $student->id)
                ->where('semester_id', $activeSemester->id)
                ->selectRaw(
                    "SUM(status = 'Sakit') as sakit, SUM(status = 'Izin') as izin, SUM(status = 'Alpha') as alpha"
                )->first();
            $attendance_sick = $attendance->sakit ?? 0;
            $attendance_permit = $attendance->izin ?? 0;
            $attendance_absent = $attendance->alpha ?? 0;
        } else {
            $attendance_sick = $raport->attendance_sick;
            $attendance_permit = $raport->attendance_permit;
            $attendance_absent = $raport->attendance_absent;
        }
        return view('siswa.raport', compact('student', 'activeSemester', 'kelas', 'waliKelas', 'raport', 'grades', 'subjectSettings', 'attendance_sick', 'attendance_permit', 'attendance_absent'));
    }

    public function kenaikan()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $assignment = ClassroomAssignment::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeSemester?->academic_year_id)
            ->first();
        $kelas = $assignment?->classroom;

        if (!$kelas) {
            return view('guru.wali-kelas-empty');
        }

        $students = $kelas->students()->with('user')->orderBy('full_name')->get();

        // Ambil data nilai dan KKM untuk menghitung rekomendasi
        $grades = Grade::where('classroom_id', $kelas->id)
            ->where('semester_id', $activeSemester->id)
            ->get()->groupBy('student_id');

        $subjectSettings = SubjectSetting::where('academic_year_id', $activeSemester->academic_year_id)
            ->get()->keyBy('subject_id');

        // Ambil data promosi yang sudah ada
        $promotions = StudentPromotion::where('from_classroom_id', $kelas->id)
            ->where('promotion_year_id', $activeSemester->id)
            ->get()->keyBy('student_id');

        $promotionData = $students->map(function ($student) use ($grades, $subjectSettings, $promotions, $kelas, $activeSemester) {
            $studentGrades = $grades->get($student->id, collect());
            $failedSubjects = 0;

            foreach ($studentGrades as $grade) {
                $setting = $subjectSettings->get($grade->subject_id);
                if ($setting) {
                    $finalScore = $grade->getFinalScore(
                        $setting->task_weight ?? 0,
                        $setting->uts_weight ?? 0,
                        $setting->uas_weight ?? 0
                    );
                    if ($finalScore < ($setting->kkm ?? 75)) {
                        $failedSubjects++;
                    }
                }
            }

            // Aturan: Gagal lebih dari 3 mapel -> tidak layak naik
            $recommendation = $failedSubjects > 3 ? 'Tidak Layak Naik' : 'Layak Naik';

            // Dapatkan keputusan final yang sudah ada atau default
            $existingPromotion = $promotions->get($student->id);
            $finalDecision = $existingPromotion ? $existingPromotion->final_decision : null;

            return (object) [
                'student' => $student,
                'failed_subjects' => $failedSubjects,
                'system_recommendation' => $recommendation,
                'final_decision' => $finalDecision,
            ];
        });


        return view('guru.wali-kenaikan', compact('kelas', 'promotionData', 'activeSemester'));
    }

    public function storeKenaikan(Request $request)
    {
        $request->validate([
            'promotions' => 'required|array',
            'promotions.*.final_decision' => 'required|in:Naik Kelas,Tidak Naik Kelas',
        ]);

        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $assignment = ClassroomAssignment::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeSemester?->academic_year_id)
            ->firstOrFail();
        $kelas = $assignment->classroom;

        DB::transaction(function () use ($request, $kelas, $activeSemester) {
            foreach ($request->promotions as $studentId => $data) {
                // Rekalkulasi rekomendasi untuk keamanan
                $studentGrades = Grade::where('student_id', $studentId)
                    ->where('semester_id', $activeSemester->id)
                    ->get();
                $subjectSettings = SubjectSetting::where('academic_year_id', $activeSemester->academic_year_id)
                    ->get()->keyBy('subject_id');

                $failedSubjects = 0;
                foreach ($studentGrades as $grade) {
                    $setting = $subjectSettings->get($grade->subject_id);
                    if ($setting) {
                        $finalScore = $grade->getFinalScore(
                            $setting->task_weight ?? 0,
                            $setting->uts_weight ?? 0,
                            $setting->uas_weight ?? 0
                        );
                        if ($finalScore < ($setting->kkm ?? 75)) {
                            $failedSubjects++;
                        }
                    }
                }
                $recommendation = $failedSubjects > 3 ? 'Tidak Layak Naik' : 'Layak Naik';

                StudentPromotion::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'promotion_year_id' => $activeSemester->id,
                        'from_classroom_id' => $kelas->id,
                    ],
                    [
                        'system_recommendation' => $recommendation,
                        'final_decision' => $data['final_decision'],
                        'notes' => $data['notes'] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('wali.kenaikan')->with('success', 'Keputusan kenaikan kelas berhasil disimpan.');
    }

    public function siswaPindahan(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $assignment = ClassroomAssignment::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeSemester?->academic_year_id)
            ->first();
        $kelas = $assignment?->classroom;
        if (!$assignment || !$kelas) {
            return view('guru.wali-kelas-empty');
        }
        $students = $kelas->students()->where('status', 'Pindahan')->with('user')->orderBy('full_name')->get();
        $subjects = Schedule::where('classroom_id', $kelas->id)
            ->with('subject')
            ->get()
            ->pluck('subject')
            ->unique('id')
            ->sortBy('name');
        $grades = Grade::where('classroom_id', $kelas->id)
            ->where('semester_id', $activeSemester->id)
            ->where('source', 'konversi')
            ->get()
            ->groupBy(['student_id', 'subject_id']);
        return view('guru.wali-pindahan', compact('kelas', 'students', 'subjects', 'grades', 'activeSemester'));
    }

    public function storeKonversi(Request $request)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.subject_id' => 'required|exists:subjects,id',
            'grades.*.nilai' => 'required|numeric|min:0|max:100',
        ]);
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $assignment = ClassroomAssignment::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeSemester?->academic_year_id)
            ->firstOrFail();
        $kelas = $assignment->classroom;
        foreach ($request->grades as $data) {
            Grade::updateOrCreate(
                [
                    'student_id' => $data['student_id'],
                    'subject_id' => $data['subject_id'],
                    'classroom_id' => $kelas->id,
                    'semester_id' => $activeSemester->id,
                    'source' => 'konversi',
                ],
                [
                    'final_grade' => $data['nilai'],
                    'is_passed' => $data['nilai'] >= 75,
                ]
            );
        }
        return redirect()->route('wali.pindahan')->with('success', 'Nilai konversi berhasil disimpan.');
    }
}

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
use App\Services\AttendanceService;
use Illuminate\Support\Facades\DB;
use App\Models\Semester;

class WaliKelasController extends Controller
{
    public function leger(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $activeYear = $activeSemester?->academicYear;
        $assignment = ClassroomAssignment::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeSemester?->academic_year_id)
            ->first();
        $kelas = $assignment?->classroom;

        // Inisialisasi statistik default
        $classStatistics = [
            'total_students' => 0,
            'lulus_semua' => 0,
            'perlu_perhatian' => 0,
            'total_mapel' => 0,
            'total_completed_subjects' => 0,
            'total_passed_subjects' => 0,
            'overall_pass_rate' => 0
        ];
        $studentStatistics = [];
        $studentAverages = [];

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

        $subjectSettings = SubjectSetting::where('academic_year_id', $activeSemester->academic_year_id)
            ->whereIn('subject_id', $mapels->pluck('id'))
            ->get()
            ->keyBy('subject_id');

        // Ambil bobot semester
        $semesterWeight = \App\Models\SemesterWeight::where('academic_year_id', $activeYear->id)
            ->where('is_active', true)
            ->first();

        $ganjilSemester = \App\Models\Semester::where('academic_year_id', $activeYear->id)->where('name', 'Ganjil')->first();
        $genapSemester = \App\Models\Semester::where('academic_year_id', $activeYear->id)->where('name', 'Genap')->first();

        $gradesGanjil = \App\Models\Grade::where('classroom_id', $kelas?->id)
            ->where('semester_id', $ganjilSemester?->id)
            ->get()
            ->groupBy(['student_id', 'subject_id']);

        $gradesGenap = \App\Models\Grade::where('classroom_id', $kelas?->id)
            ->where('semester_id', $genapSemester?->id)
            ->get()
            ->groupBy(['student_id', 'subject_id']);

        // Hitung rata-rata per siswa
        foreach ($students as $student) {
            $studentId = $student->id;
            $ganjilArr = [];
            $genapArr = [];
            $yearlyArr = [];

            foreach ($mapels as $mapel) {
                $ganjil = $gradesGanjil->get($studentId)?->get($mapel->id)?->first();
                $genap = $gradesGenap->get($studentId)?->get($mapel->id)?->first();
                $nilaiGanjil = $ganjil?->final_grade;
                $nilaiGenap = $genap?->final_grade;

                if (!is_null($nilaiGanjil)) $ganjilArr[] = $nilaiGanjil;
                if (!is_null($nilaiGenap)) $genapArr[] = $nilaiGenap;

                if (!is_null($nilaiGanjil) && !is_null($nilaiGenap) && $semesterWeight) {
                    $yearly = ($nilaiGanjil * $semesterWeight->ganjil_weight + $nilaiGenap * $semesterWeight->genap_weight) / 100;
                    $yearlyArr[] = $yearly;
                }
            }

            $studentAverages[$studentId] = [
                'ganjil' => count($ganjilArr) ? round(array_sum($ganjilArr) / count($ganjilArr), 2) : null,
                'genap' => count($genapArr) ? round(array_sum($genapArr) / count($genapArr), 2) : null,
                'yearly' => count($yearlyArr) ? round(array_sum($yearlyArr) / count($yearlyArr), 2) : null,
            ];
        }

        // Hitung statistik kelas
        $classStatistics = $this->calculateWaliClassStatistics($students, $mapels, $gradesGanjil, $gradesGenap, $semesterWeight);

        // Hitung statistik per siswa
        foreach ($students as $student) {
            $studentStatistics[$student->id] = $this->calculateWaliStudentStatistics(
                $student->id,
                $mapels,
                $gradesGanjil,
                $gradesGenap,
                $semesterWeight
            );
        }

        return view('guru.wali-leger', compact(
            'kelas',
            'students',
            'mapels',
            'grades',
            'subjectSettings',
            'activeSemester',
            'gradesGanjil',
            'gradesGenap',
            'semesterWeight',
            'classStatistics',
            'studentStatistics',
            'studentAverages'
        ));
    }

    /**
     * Calculate statistics for wali kelas class
     */
    private function calculateWaliClassStatistics($students, $mapels, $gradesGanjil, $gradesGenap, $semesterWeight)
    {
        $totalStudents = $students->count();
        $lulusSemua = 0;
        $totalMapel = $mapels->count();
        $totalCompletedSubjects = 0;
        $totalPassedSubjects = 0;

        foreach ($students as $student) {
            $studentId = $student->id;
            $semuaLulus = true;
            $adaNilai = false;

            foreach ($mapels as $mapel) {
                $ganjil = $gradesGanjil->get($studentId)?->get($mapel->id)?->first();
                $genap = $gradesGenap->get($studentId)?->get($mapel->id)?->first();
                $nilaiGanjil = $ganjil?->final_grade;
                $nilaiGenap = $genap?->final_grade;

                if (!is_null($nilaiGanjil) && !is_null($nilaiGenap) && $semesterWeight) {
                    $adaNilai = true;
                    $yearly = ($nilaiGanjil * $semesterWeight->ganjil_weight + $nilaiGenap * $semesterWeight->genap_weight) / 100;
                    $kkm = $ganjil?->getKKM() ?? $genap?->getKKM();

                    if ($kkm !== null && $yearly < $kkm) {
                        $semuaLulus = false;
                    }

                    $totalCompletedSubjects++;
                    if ($yearly >= $kkm) {
                        $totalPassedSubjects++;
                    }
                }
            }

            if ($adaNilai && $semuaLulus) {
                $lulusSemua++;
            }
        }

        return [
            'total_students' => $totalStudents,
            'lulus_semua' => $lulusSemua,
            'perlu_perhatian' => $totalStudents - $lulusSemua,
            'total_mapel' => $totalMapel,
            'total_completed_subjects' => $totalCompletedSubjects,
            'total_passed_subjects' => $totalPassedSubjects,
            'overall_pass_rate' => $totalCompletedSubjects > 0 ? round(($totalPassedSubjects / $totalCompletedSubjects) * 100, 1) : 0
        ];
    }

    /**
     * Calculate statistics for a single student in wali kelas
     */
    private function calculateWaliStudentStatistics($studentId, $mapels, $gradesGanjil, $gradesGenap, $semesterWeight)
    {
        $completedSubjects = 0;
        $passedSubjects = 0;
        $failedSubjects = 0;
        $subjectDetails = [];

        foreach ($mapels as $mapel) {
            $ganjil = $gradesGanjil->get($studentId)?->get($mapel->id)?->first();
            $genap = $gradesGenap->get($studentId)?->get($mapel->id)?->first();
            $nilaiGanjil = $ganjil?->final_grade;
            $nilaiGenap = $genap?->final_grade;

            $yearly = null;
            if (!is_null($nilaiGanjil) && !is_null($nilaiGenap) && $semesterWeight) {
                $yearly = ($nilaiGanjil * $semesterWeight->ganjil_weight + $nilaiGenap * $semesterWeight->genap_weight) / 100;
            }

            // Hitung statistik
            if ($nilaiGanjil !== null || $nilaiGenap !== null) {
                $completedSubjects++;
            }

            // Simpan detail untuk ditampilkan
            $subjectDetails[] = [
                'subject' => $mapel,
                'ganjil' => $nilaiGanjil,
                'genap' => $nilaiGenap,
                'yearly' => $yearly,
                'kkm' => $ganjil?->getKKM() ?? $genap?->getKKM()
            ];

            // Tentukan status berdasarkan nilai akhir tahun
            if ($yearly !== null && $ganjil?->getKKM() !== null) {
                if ($yearly >= $ganjil->getKKM()) {
                    $passedSubjects++;
                } else {
                    $failedSubjects++;
                }
            }
        }

        return [
            'total_subjects' => $mapels->count(),
            'completed_subjects' => $completedSubjects,
            'passed_subjects' => $passedSubjects,
            'failed_subjects' => $failedSubjects,
            'completion_rate' => $mapels->count() > 0 ? round(($completedSubjects / $mapels->count()) * 100, 1) : 0,
            'pass_rate' => $completedSubjects > 0 ? round(($passedSubjects / $completedSubjects) * 100, 1) : 0,
            'subject_details' => $subjectDetails
        ];
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
        $q = $request->get('q');
        $studentsQuery = $assignment->classStudents()->with('student');
        if ($q) {
            $studentsQuery->whereHas('student', function ($query) use ($q) {
                $query->where('full_name', 'like', "%$q%")
                    ->orWhere('nis', 'like', "%$q%")
                    ->orWhere('nisn', 'like', "%$q%");
            });
        }
        $students = $studentsQuery->paginate(20)->appends(['q' => $q]);

        // Cek apakah raport kelas ini sudah difinalisasi
        $semesterInt = $activeSemester->name === 'Ganjil' ? 1 : 2;
        $isFinalized = Raport::where('classroom_id', $kelas->id)
            ->where('academic_year_id', $activeSemester->academic_year_id)
            ->where('semester', $semesterInt)
            ->where('is_finalized', true)
            ->exists();

        // Get accumulated attendance data from daily attendance system
        $attendanceService = new AttendanceService();
        $accumulatedAttendances = [];
        $lockedAttendances = [];

        foreach ($students as $classStudent) {
            $studentId = $classStudent->student->id;
            $semesterStats = $attendanceService->getSemesterStats($studentId, $activeSemester->id);

            // Check if there's locked attendance data
            $lockedAttendance = Attendance::where('student_id', $studentId)
                ->where('classroom_assignment_id', $assignment->id)
                ->where('semester_id', $activeSemester->id)
                ->where('is_locked', true)
                ->first();

            if ($lockedAttendance) {
                $lockedAttendances[$studentId] = $lockedAttendance;
                $accumulatedAttendances[$studentId] = [
                    'sakit' => $lockedAttendance->sakit,
                    'izin' => $lockedAttendance->izin,
                    'alpha' => $lockedAttendance->alpha,
                    'hadir' => $semesterStats['hadir'],
                    'total_days' => $semesterStats['total_days'],
                    'percentage' => $semesterStats['percentage'],
                    'is_locked' => true,
                    'locked_at' => $lockedAttendance->locked_at,
                    'locked_by' => $lockedAttendance->locked_by
                ];
            } else {
                $accumulatedAttendances[$studentId] = [
                    'sakit' => $semesterStats['sakit'],
                    'izin' => $semesterStats['izin'],
                    'alpha' => $semesterStats['alpha'],
                    'hadir' => $semesterStats['hadir'],
                    'total_days' => $semesterStats['total_days'],
                    'percentage' => $semesterStats['percentage'],
                    'is_locked' => false
                ];
            }
        }

        return view('guru.wali-absensi', compact('kelas', 'students', 'accumulatedAttendances', 'activeSemester', 'q', 'isFinalized'));
    }

    public function storeAbsensi(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $assignment = ClassroomAssignment::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeSemester?->academic_year_id)
            ->firstOrFail();

        // Cek apakah raport sudah difinalisasi sebelum menyimpan
        $semesterInt = $activeSemester->name === 'Ganjil' ? 1 : 2;
        $isFinalized = Raport::where('classroom_id', $assignment->classroom->id)
            ->where('academic_year_id', $activeSemester->academic_year_id)
            ->where('semester', $semesterInt)
            ->where('is_finalized', true)
            ->exists();

        if ($isFinalized) {
            return redirect()->route('wali.absensi')->with('error', 'Tidak dapat menyimpan absensi karena raport sudah difinalisasi.');
        }

        $request->validate([
            'action' => 'required|in:save,lock',
            'attendances' => 'required|array',
            'attendances.*.sakit' => 'required|integer|min:0',
            'attendances.*.izin' => 'required|integer|min:0',
            'attendances.*.alpha' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $teacher, $activeSemester, $assignment) {
            $semester = $activeSemester;
            $isLocked = $request->action === 'lock';

            // Process attendance data and save to semester summary
            foreach ($request->attendances as $studentId => $attendanceData) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'classroom_assignment_id' => $assignment->id,
                        'semester_id' => $semester->id,
                    ],
                    [
                        'teacher_id' => $assignment->homeroom_teacher_id,
                        'academic_year_id' => $semester->academic_year_id,
                        'sakit' => $attendanceData['sakit'],
                        'izin' => $attendanceData['izin'],
                        'alpha' => $attendanceData['alpha'],
                        'is_locked' => $isLocked,
                        'locked_at' => $isLocked ? now() : null,
                        'locked_by' => $isLocked ? $teacher->id : null,
                    ]
                );
            }
        });

        $message = $request->action === 'lock'
            ? 'Absensi semester berhasil disimpan dan dikunci.'
            : 'Absensi semester berhasil disimpan.';

        return redirect()->route('wali.absensi')->with('success', $message);
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

        // Cek apakah sudah ada raport yang difinalisasi
        $existingRaports = Raport::where('classroom_id', $kelas->id)
            ->where('academic_year_id', $activeSemester->academic_year_id)
            ->where('semester', $semesterInt)
            ->where('is_finalized', true)
            ->with('student')
            ->get();

        $isFinalized = $existingRaports->isNotEmpty();

        // Ambil daftar siswa untuk ditampilkan
        $students = $assignment->classStudents()->with('student')->get();

        // Jika sudah difinalisasi, gunakan data raport
        // Jika belum, gunakan data siswa dari assignment
        $displayData = $isFinalized ? $existingRaports : $students;

        return view('guru.wali-finalisasi', compact('kelas', 'displayData', 'activeSemester', 'isFinalized', 'students'));
    }

    public function storeFinalisasi(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $assignment = ClassroomAssignment::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeSemester?->academic_year_id)
            ->firstOrFail();
        $kelas = $assignment->classroom;

        $request->validate([
            'catatan' => 'nullable|array',
            'catatan.*' => 'nullable|string|max:500',
        ]);

        $semesterInt = $activeSemester->name === 'Ganjil' ? 1 : 2;
        $students = $assignment->classStudents()->with('student')->get();

        // Cek apakah sudah ada raport yang difinalisasi
        $existingRaports = Raport::where('classroom_id', $kelas->id)
            ->where('academic_year_id', $activeSemester->academic_year_id)
            ->where('semester', $semesterInt)
            ->where('is_finalized', true)
            ->exists();

        if ($existingRaports) {
            return redirect()->route('wali.finalisasi')->with('error', 'Raport sudah difinalisasi sebelumnya. Tidak dapat melakukan finalisasi ulang.');
        }

        DB::transaction(function () use ($request, $students, $kelas, $activeSemester, $semesterInt) {
            foreach ($students as $classStudent) {
                $student = $classStudent->student;

                // Rekap absensi dari tabel attendances
                $attendance = Attendance::where('student_id', $student->id)
                    ->where('semester_id', $activeSemester->id)
                    ->first();

                // Buat raport baru (CREATE, bukan UPDATE)
                Raport::create([
                    'student_id' => $student->id,
                    'classroom_id' => $kelas->id,
                    'academic_year_id' => $activeSemester->academic_year_id,
                    'semester' => $semesterInt,
                    'attendance_sick' => $attendance->sakit ?? 0,
                    'attendance_permit' => $attendance->izin ?? 0,
                    'attendance_absent' => $attendance->alpha ?? 0,
                    'homeroom_teacher_notes' => $request->catatan[$student->id] ?? null,
                    'is_finalized' => true,
                    'finalized_at' => now(),
                ]);
            }
        });

        return redirect()->route('wali.finalisasi')->with('success', 'Semua raport berhasil difinalisasi. Data tidak dapat diubah lagi.');
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

        // Ambil semua tahun ajaran yang pernah diikuti siswa (untuk filter)
        $academicYears = AcademicYear::whereHas('classroomAssignments.classStudents', function ($query) use ($student) {
            $query->where('student_id', $student->id);
        })->orderBy('year', 'desc')->get();

        // Gunakan tahun ajaran aktif sebagai selectedYear
        $selectedYear = $activeSemester->academicYear;
        $selectedSemester = $activeSemester->name === 'Ganjil' ? 1 : 2;

        $raport = Raport::where('student_id', $student->id)
            ->where('classroom_id', $kelas->id)
            ->where('academic_year_id', $activeSemester->academic_year_id)
            ->where('semester', $selectedSemester)
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
                ->first();
            $attendance_sick = $attendance->sakit ?? 0;
            $attendance_permit = $attendance->izin ?? 0;
            $attendance_absent = $attendance->alpha ?? 0;
        } else {
            $attendance_sick = $raport->attendance_sick;
            $attendance_permit = $raport->attendance_permit;
            $attendance_absent = $raport->attendance_absent;
        }
        return view('siswa.raport', compact(
            'student',
            'selectedYear',
            'selectedSemester',
            'academicYears',
            'kelas',
            'waliKelas',
            'raport',
            'grades',
            'subjectSettings',
            'attendance_sick',
            'attendance_permit',
            'attendance_absent'
        ));
    }

    public function kenaikan()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();

        // 1. Validation: Must be Genap semester
        if ($activeSemester->name !== 'Genap') {
            return view('guru.wali-kenaikan-disabled', [
                'message' => 'Fitur penilaian kenaikan dan kelulusan hanya dapat diakses pada akhir semester Genap.'
            ]);
        }

        $assignment = ClassroomAssignment::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeSemester->academic_year_id)
            ->first();
        $kelas = $assignment?->classroom;

        if (!$kelas) {
            return view('guru.wali-kelas-empty');
        }

        // 2. Validation: Genap report must be finalized for all students
        $studentCount = $assignment->classStudents()->count();
        $finalizedRaportCount = Raport::where('classroom_id', $kelas->id)
            ->where('academic_year_id', $activeSemester->academic_year_id)
            ->where('semester', 2) // Genap
            ->where('is_finalized', true)
            ->count();

        if ($studentCount !== $finalizedRaportCount) {
            return view('guru.wali-kenaikan-disabled', [
                'message' => "Proses penilaian kenaikan belum dapat dilakukan. Pastikan raport semester Genap untuk semua {$studentCount} siswa di kelas Anda telah difinalisasi.",
                'kelas' => $kelas,
                'activeSemester' => $activeSemester
            ]);
        }

        $students = $assignment->classStudents()->with('student.user')->get()->pluck('student');

        $ganjilSemester = Semester::where('academic_year_id', $activeSemester->academic_year_id)
            ->where('name', 'Ganjil')
            ->first();

        // Get semester weights for yearly grade calculation
        $semesterWeights = \App\Models\SemesterWeight::where('academic_year_id', $activeSemester->academic_year_id)
            ->where('is_active', true)
            ->first();

        if (!$semesterWeights) {
            return view('guru.wali-kenaikan-disabled', [
                'message' => 'Pengaturan bobot semester belum diatur. Silakan atur bobot semester di menu Pengaturan KKM.',
                'kelas' => $kelas,
                'activeSemester' => $activeSemester
            ]);
        }

        // Ambil data nilai dan KKM untuk menghitung rekomendasi
        $grades = Grade::whereIn('student_id', $students->pluck('id'))
            ->whereIn('semester_id', [$activeSemester->id, $ganjilSemester?->id])
            ->get()->groupBy('student_id');

        // Ambil semua mata pelajaran di kelas ini
        $subjectsInClass = Schedule::where('classroom_id', $kelas->id)
            ->with('subject')
            ->get()
            ->pluck('subject')
            ->unique('id')
            ->sortBy('name');

        // Ambil SubjectSetting untuk semua mata pelajaran
        $subjectSettings = SubjectSetting::where('academic_year_id', $activeSemester->academic_year_id)
            ->whereIn('subject_id', $subjectsInClass->pluck('id'))
            ->get()
            ->keyBy('subject_id');

        // Validasi: Pastikan semua mata pelajaran memiliki pengaturan KKM
        $subjectsWithoutSettings = $subjectsInClass->filter(function ($subject) use ($subjectSettings) {
            return !$subjectSettings->has($subject->id);
        });

        if ($subjectsWithoutSettings->count() > 0) {
            $subjectNames = $subjectsWithoutSettings->pluck('name')->implode(', ');
            return view('guru.wali-kenaikan-disabled', [
                'message' => "Pengaturan KKM belum lengkap untuk mata pelajaran: {$subjectNames}. Silakan atur KKM di menu Pengaturan KKM.",
                'kelas' => $kelas,
                'activeSemester' => $activeSemester
            ]);
        }

        // Ambil data promosi yang sudah ada
        $promotions = StudentPromotion::where('from_classroom_id', $kelas->id)
            ->where('promotion_year_id', $activeSemester->academic_year_id)
            ->get()->keyBy('student_id');

        // Ambil setting maksimal mapel gagal dari database (AppSetting)
        $maxFailedSubjects = \App\Models\AppSetting::getValue('max_failed_subjects', 0);

        $promotionData = $students->map(function ($student) use ($grades, $subjectSettings, $promotions, $kelas, $activeSemester, $ganjilSemester, $maxFailedSubjects, $semesterWeights, $subjectsInClass) {
            $studentGrades = $grades->get($student->id, collect())->groupBy('subject_id');
            $failedSubjects = 0;
            $subjectDetails = [];

            foreach ($subjectsInClass as $subject) {
                $subjectId = $subject->id;
                $ganjilSettings = $subjectSettings->get($subjectId);
                $genapSettings = $subjectSettings->get($subjectId);

                if ($ganjilSettings && $genapSettings) {
                    $gradesForSubject = $studentGrades->get($subjectId, collect());
                    $gradeGanjil = $gradesForSubject->firstWhere('semester_id', $ganjilSemester->id);
                    $gradeGenap = $gradesForSubject->firstWhere('semester_id', $activeSemester->id);

                    $ganjilGrade = null;
                    $genapGrade = null;
                    $yearlyGrade = null;
                    $kkm = null;
                    $isFailed = false;
                    $hasCompleteGrades = false;

                    if ($gradeGanjil && $gradeGenap) {
                        // Calculate semester grades using proper weights
                        $ganjilGrade = $gradeGanjil->getFinalScore(
                            $ganjilSettings->assignment_weight,
                            $ganjilSettings->uts_weight,
                            $ganjilSettings->uas_weight
                        );

                        $genapGrade = $gradeGenap->getFinalScore(
                            $genapSettings->assignment_weight,
                            $genapSettings->uts_weight,
                            $genapSettings->uas_weight
                        );

                        // Calculate yearly grade using semester weights
                        $yearlyGrade = $semesterWeights->calculateYearlyGrade($ganjilGrade, $genapGrade);
                        $kkm = $genapSettings->kkm;
                        $hasCompleteGrades = true;

                        // Check if yearly grade meets KKM
                        if ($yearlyGrade < $kkm) {
                            $failedSubjects++;
                            $isFailed = true;
                        }
                    }

                    $subjectDetails[] = [
                        'subject' => $subject,
                        'ganjil_grade' => $ganjilGrade,
                        'genap_grade' => $genapGrade,
                        'yearly_grade' => $yearlyGrade,
                        'kkm' => $kkm,
                        'is_failed' => $isFailed,
                        'has_complete_grades' => $hasCompleteGrades
                    ];
                }
            }

            $isLastGrade = str_starts_with($kelas->name, 'XII');
            $recommendation = 'Layak ' . ($isLastGrade ? 'Lulus' : 'Naik');
            if ($isLastGrade) {
                if ($failedSubjects > $maxFailedSubjects) {
                    $recommendation = 'Tidak Layak Lulus';
                }
            } else {
                if ($failedSubjects > $maxFailedSubjects) {
                    $recommendation = 'Tidak Layak Naik';
                }
            }

            // Dapatkan keputusan final yang sudah ada atau default
            $existingPromotion = $promotions->get($student->id);
            $finalDecision = $existingPromotion ? $existingPromotion->final_decision : null;

            return (object) [
                'student' => $student,
                'failed_subjects' => $failedSubjects,
                'system_recommendation' => $recommendation,
                'final_decision' => $finalDecision,
                'subject_details' => $subjectDetails,
                'max_failed_subjects' => $maxFailedSubjects,
                'is_last_grade' => $isLastGrade
            ];
        });

        // Tentukan apakah kelas ini tingkat akhir
        $isLastGrade = str_starts_with($kelas->name, 'XII');
        // Hitung statistik ringkasan keputusan
        $countNaik = $promotionData->where('final_decision', $isLastGrade ? 'Lulus' : 'Naik Kelas')->count();
        $countTidakNaik = $promotionData->where('final_decision', $isLastGrade ? 'Tidak Lulus' : 'Tidak Naik Kelas')->count();
        $countBelum = $promotionData->whereNull('final_decision')->count();

        return view('guru.wali-kenaikan', compact('kelas', 'promotionData', 'activeSemester', 'maxFailedSubjects', 'countNaik', 'countTidakNaik', 'countBelum'));
    }

    public function storeKenaikan(Request $request)
    {
        $activeSemester = Semester::where('is_active', true)->first();

        // Validasi semester harus Genap
        if ($activeSemester->name !== 'Genap') {
            return redirect()->route('wali.kenaikan')->with('error', 'Fitur penilaian kenaikan dan kelulusan hanya dapat diakses pada akhir semester Genap.');
        }

        $assignment = ClassroomAssignment::where('homeroom_teacher_id', Auth::user()->teacher->id)
            ->where('academic_year_id', $activeSemester?->academic_year_id)
            ->firstOrFail();
        $kelas = $assignment->classroom;

        $isLastGrade = str_starts_with($kelas->name, 'XII');
        $validDecisions = $isLastGrade ? ['Lulus', 'Tidak Lulus'] : ['Naik Kelas', 'Tidak Naik Kelas'];

        $request->validate([
            'promotions' => 'required|array',
            'promotions.*.final_decision' => 'required|in:' . implode(',', $validDecisions),
            'promotions.*.notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $kelas, $activeSemester) {
            foreach ($request->promotions as $studentId => $data) {
                StudentPromotion::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'promotion_year_id' => $activeSemester->academic_year_id,
                        'from_classroom_id' => $kelas->id,
                    ],
                    [
                        'final_decision' => $data['final_decision'],
                        'notes' => $data['notes'] ?? null,
                        'processed_by_user_id' => Auth::id(),
                    ]
                );
            }
        });

        return redirect()->route('wali.kenaikan')->with('success', 'Keputusan kenaikan kelas berhasil disimpan.');
    }



    /**
     * Tampilkan detail nilai siswa untuk wali kelas
     */
    public function detailNilaiSiswa($id)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $assignment = ClassroomAssignment::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeSemester?->academic_year_id)
            ->first();
        $kelas = $assignment?->classroom;
        $student = Student::with('user')->findOrFail($id);
        // Pastikan siswa memang di kelas yang diwalikan
        $classStudent = $student->classStudents()->where('academic_year_id', $activeSemester?->academic_year_id)->first();
        if (!$classStudent || $classStudent->classroom_assignment_id != $assignment?->id) {
            abort(403, 'Anda tidak berhak mengakses detail nilai siswa ini.');
        }
        $grades = Grade::with(['subject', 'semester'])
            ->where('student_id', $id)
            ->orderBy('academic_year_id')
            ->orderBy('semester_id')
            ->orderBy('subject_id')
            ->get();
        $rekap = [];
        foreach ($grades as $nilai) {
            $th = $nilai->academic_year_id;
            $sm = $nilai->semester->name;
            $mp = $nilai->subject->name;
            $rekap[$th][$sm][$mp] = [
                'final_grade' => $nilai->final_grade,
                'kkm' => $nilai->getKKM(),
                'subject_id' => $nilai->subject_id,
                'tugas' => $nilai->assignment_grade ?? null,
                'uts' => $nilai->uts_grade ?? null,
                'uas' => $nilai->uas_grade ?? null,
            ];
        }
        $tahunAjaranIds = array_keys($rekap);
        $tahunAjaranMap = \App\Models\AcademicYear::whereIn('id', $tahunAjaranIds)->pluck('year', 'id');
        return view('admin.nilai-siswa-detail', compact('student', 'rekap', 'grades', 'tahunAjaranMap', 'kelas'));
    }
}

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
        $studentList = $students->pluck('student');

        // Cek apakah raport kelas ini sudah difinalisasi
        $semesterInt = $activeSemester->name === 'Ganjil' ? 1 : 2;
        $isFinalized = Raport::where('classroom_id', $kelas->id)
            ->where('academic_year_id', $activeSemester->academic_year_id)
            ->where('semester', $semesterInt)
            ->where('is_finalized', true)
            ->exists();

        // Get semester attendance data
        $rekapAbsensi = Attendance::whereIn('student_id', $studentList->pluck('id'))
            ->where('semester_id', $activeSemester->id)
            ->where('classroom_assignment_id', $assignment->id)
            ->get()
            ->keyBy('student_id');

        return view('guru.wali-absensi', compact('kelas', 'students', 'rekapAbsensi', 'activeSemester', 'q', 'isFinalized'));
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
            'attendances' => 'required|array',
            'attendances.*.sakit' => 'required|integer|min:0',
            'attendances.*.izin' => 'required|integer|min:0',
            'attendances.*.alpha' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $teacher, $activeSemester, $assignment) {
            foreach ($request->attendances as $studentId => $data) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'classroom_assignment_id' => $assignment->id,
                        'semester_id' => $activeSemester->id,
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'academic_year_id' => $activeSemester->academic_year_id,
                        'sakit' => $data['sakit'],
                        'izin' => $data['izin'],
                        'alpha' => $data['alpha'],
                    ]
                );
            }
        });

        return redirect()->route('wali.absensi')->with('success', 'Absensi semester berhasil disimpan.');
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

        // Ambil data nilai dan KKM untuk menghitung rekomendasi
        $grades = Grade::whereIn('student_id', $students->pluck('id'))
            ->whereIn('semester_id', [$activeSemester->id, $ganjilSemester?->id])
            ->get()->groupBy('student_id');

        $subjectSettings = SubjectSetting::where('academic_year_id', $activeSemester->academic_year_id)
            ->get()->keyBy('subject_id');

        // Ambil data promosi yang sudah ada
        $promotions = StudentPromotion::where('from_classroom_id', $kelas->id)
            ->where('promotion_year_id', $activeSemester->academic_year_id) // promotion is tied to the academic year
            ->get()->keyBy('student_id');

        // Ambil setting maksimal mapel gagal dari config
        $maxFailedSubjects = config('siakad.max_failed_subjects', 0);

        $promotionData = $students->map(function ($student) use ($grades, $subjectSettings, $promotions, $kelas, $activeSemester, $ganjilSemester, $maxFailedSubjects) {
            $studentGrades = $grades->get($student->id, collect())->groupBy('subject_id');
            $failedSubjects = 0;

            $subjectsInClass = Schedule::where('classroom_id', $kelas->id)->pluck('subject_id')->unique();

            foreach ($subjectsInClass as $subjectId) {
                $setting = $subjectSettings->get($subjectId);
                if ($setting) {
                    $gradesForSubject = $studentGrades->get($subjectId, collect());
                    $gradeGanjil = $gradesForSubject->firstWhere('semester_id', $ganjilSemester->id);
                    $gradeGenap = $gradesForSubject->firstWhere('semester_id', $activeSemester->id);

                    // Calculate Ganjil average
                    $ganjilGrades = $gradesForSubject->where('semester_id', $ganjilSemester->id);
                    $totalNilaiGanjil = 0;
                    $mapelCountGanjil = 0;
                    foreach ($ganjilGrades as $grade) {
                        $settings = $subjectSettings->get($grade->subject_id);
                        if ($settings) {
                            $totalNilaiGanjil += $grade->getFinalScore($settings->assignment_weight, $settings->uts_weight, $settings->uas_weight);
                        } else {
                            // Use default weights if settings not found
                            $totalNilaiGanjil += $grade->getFinalScore(30, 30, 40);
                        }
                        $mapelCountGanjil++;
                    }
                    $avgGanjil = $mapelCountGanjil > 0 ? $totalNilaiGanjil / $mapelCountGanjil : 0;

                    // Calculate Genap average
                    $genapGrades = $gradesForSubject->where('semester_id', $activeSemester->id);
                    $totalNilaiGenap = 0;
                    $mapelCountGenap = 0;
                    foreach ($genapGrades as $grade) {
                        $settings = $subjectSettings->get($grade->subject_id);
                        if ($settings) {
                            $totalNilaiGenap += $grade->getFinalScore($settings->assignment_weight, $settings->uts_weight, $settings->uas_weight);
                        } else {
                            // Use default weights if settings not found
                            $totalNilaiGenap += $grade->getFinalScore(30, 30, 40);
                        }
                        $mapelCountGenap++;
                    }
                    $avgGenap = $mapelCountGenap > 0 ? $totalNilaiGenap / $mapelCountGenap : 0;

                    // Yearly average score
                    $yearlyScore = ($avgGanjil + $avgGenap) / 2;

                    if ($yearlyScore < ($setting->kkm ?? 75)) {
                        $failedSubjects++;
                    }
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
            ];
        });


        return view('guru.wali-kenaikan', compact('kelas', 'promotionData', 'activeSemester'));
    }

    public function storeKenaikan(Request $request)
    {
        $activeSemester = Semester::where('is_active', true)->first();
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
                // For security, re-calculate recommendation on the server
                // (Logic is already complex in kenaikan(), for now we trust the flow)
                // In a real-world scenario with higher security needs, re-calculating here is a must.

                StudentPromotion::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'promotion_year_id' => $activeSemester->academic_year_id,
                        'from_classroom_id' => $kelas->id,
                    ],
                    [
                        // We don't store the recommendation, it's informative.
                        // 'system_recommendation' => $recommendation, 
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

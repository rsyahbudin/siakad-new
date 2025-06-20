<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Grade;
use App\Models\SubjectSetting;
use App\Models\Raport;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\StudentPromotion;
use Illuminate\Support\Facades\DB;

class WaliKelasController extends Controller
{
    public function leger(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeYear = AcademicYear::where('is_active', true)->first();
        // Ambil kelas yang diampu sebagai wali
        $kelas = Classroom::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeYear?->id)
            ->first();
        if (!$kelas) {
            return view('guru.wali-leger-empty');
        }
        // Ambil semua siswa di kelas tsb
        $students = $kelas ? $kelas->students()->with('user')->orderBy('full_name')->get() : collect();
        // Ambil semua mapel yang diajarkan di kelas tsb
        $mapels = $kelas ? Schedule::where('classroom_id', $kelas->id)
            ->with('subject')
            ->get()
            ->pluck('subject')
            ->unique('id')
            ->sortBy('name') : collect();
        // Ambil semua nilai untuk kelas, mapel, tahun ajaran aktif
        $grades = Grade::where('classroom_id', $kelas?->id)
            ->where('academic_year_id', $activeYear?->id)
            ->get()
            ->groupBy(['student_id', 'subject_id']);
        // Ambil bobot per mapel
        $subjectSettings = SubjectSetting::where('academic_year_id', $activeYear?->id)
            ->whereIn('subject_id', $mapels->pluck('id'))
            ->get()
            ->keyBy('subject_id');
        return view('guru.wali-leger', compact('kelas', 'students', 'mapels', 'grades', 'subjectSettings', 'activeYear'));
    }
    public function kelas(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeYear = AcademicYear::where('is_active', true)->first();
        $kelas = Classroom::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeYear?->id)
            ->first();
        if (!$kelas) {
            return view('guru.wali-kelas-empty');
        }
        $students = $kelas->students()->with('user')->orderBy('full_name')->paginate(20);
        return view('guru.wali-kelas', compact('kelas', 'students', 'activeYear'));
    }

    public function absensi()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeYear = AcademicYear::getActive();
        $kelas = Classroom::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeYear?->id)
            ->first();

        if (!$kelas) {
            return view('guru.wali-kelas-empty');
        }

        $students = $kelas->students()->orderBy('full_name')->get();

        // Ambil rekapitulasi dari tabel attendances
        $rekapAbsensi = Attendance::whereIn('student_id', $students->pluck('id'))
            ->where('academic_year_id', $activeYear->id)
            ->select('student_id', 'status', DB::raw('count(*) as total'))
            ->groupBy('student_id', 'status')
            ->get()
            ->groupBy('student_id');

        // Ambil data yang sudah tersimpan di raport
        $raports = Raport::where('classroom_id', $kelas->id)
            ->where('academic_year_id', $activeYear->id)
            ->get()
            ->keyBy('student_id');

        return view('guru.wali-absensi', compact('kelas', 'students', 'rekapAbsensi', 'raports', 'activeYear'));
    }

    public function storeAbsensi(Request $request)
    {
        $request->validate([
            'attendance' => 'required|array',
            'attendance.*.sick' => 'required|integer|min:0',
            'attendance.*.permit' => 'required|integer|min:0',
            'attendance.*.absent' => 'required|integer|min:0',
        ]);

        $user = Auth::user();
        $teacher = $user->teacher;
        $activeYear = AcademicYear::getActive();
        $kelas = Classroom::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeYear?->id)
            ->firstOrFail();

        foreach ($request->attendance as $studentId => $data) {
            Raport::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'classroom_id' => $kelas->id,
                    'academic_year_id' => $activeYear->id,
                    'semester' => $activeYear->semester,
                ],
                [
                    'attendance_sick' => $data['sick'],
                    'attendance_permit' => $data['permit'],
                    'attendance_absent' => $data['absent'],
                ]
            );
        }

        return redirect()->route('wali.absensi')->with('success', 'Rekapitulasi absensi berhasil disimpan.');
    }

    public function finalisasi()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeYear = AcademicYear::getActive();
        $kelas = Classroom::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeYear?->id)
            ->first();

        if (!$kelas) {
            return view('guru.wali-kelas-empty');
        }

        $raports = Raport::where('classroom_id', $kelas->id)
            ->where('academic_year_id', $activeYear->id)
            ->with('student')
            ->get();

        $isAllFinalized = $raports->every(fn($raport) => $raport->is_finalized);

        return view('guru.wali-finalisasi', compact('kelas', 'raports', 'activeYear', 'isAllFinalized'));
    }

    public function showRaport(Student $student)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return back()->with('error', 'Tahun ajaran aktif belum ditentukan.');
        }

        $classroom = $student->classrooms()->where('academic_year_id', $activeYear->id)->first();
        if (!$classroom) {
            return back()->with('error', 'Siswa tidak terdaftar di kelas manapun pada tahun ajaran ini.');
        }

        // Pastikan wali kelas hanya bisa akses siswanya sendiri
        $waliKelasClassroomId = Auth::user()->teacher->homeroomClassrooms()->where('academic_year_id', $activeYear->id)->value('id');
        if ($classroom->id !== $waliKelasClassroomId) {
            return back()->with('error', 'Anda tidak memiliki hak akses untuk melihat raport siswa ini.');
        }

        $raport = Raport::where('student_id', $student->id)
            ->where('academic_year_id', $activeYear->id)
            ->first();

        $grades = Grade::with('subject')
            ->where('student_id', $student->id)
            ->where('academic_year_id', $activeYear->id)
            ->get();

        $subjectSettings = SubjectSetting::where('academic_year_id', $activeYear->id)
            ->whereIn('subject_id', $grades->pluck('subject_id'))
            ->get()
            ->keyBy('subject_id');

        return view('siswa.raport', compact('student', 'activeYear', 'classroom', 'raport', 'grades', 'subjectSettings'));
    }

    public function storeFinalisasi(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeYear = AcademicYear::getActive();
        $kelas = Classroom::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeYear?->id)
            ->firstOrFail();

        $catatan = $request->input('catatan', []);

        DB::transaction(function () use ($kelas, $activeYear, $catatan) {
            $students = $kelas->students()->get();
            $studentIds = $students->pluck('id');

            // 1. Ambil rekapitulasi absensi terbaru dari tabel attendances
            $rekapAbsensi = Attendance::whereIn('student_id', $studentIds)
                ->where('academic_year_id', $activeYear->id)
                ->select('student_id', 'status', DB::raw('count(*) as total'))
                ->groupBy('student_id', 'status')
                ->get()
                ->groupBy('student_id');

            foreach ($students as $student) {
                $rekap = $rekapAbsensi->get($student->id);
                $sakit = $rekap ? ($rekap->firstWhere('status', 'Sakit')->total ?? 0) : 0;
                $izin = $rekap ? ($rekap->firstWhere('status', 'Izin')->total ?? 0) : 0;
                $alpha = $rekap ? ($rekap->firstWhere('status', 'Alpha')->total ?? 0) : 0;

                // 2. Update atau buat data raport dengan rekap absensi final dan catatan wali
                $raport = Raport::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'classroom_id' => $kelas->id,
                        'academic_year_id' => $activeYear->id,
                        'semester' => $activeYear->semester,
                    ],
                    [
                        'attendance_sick' => $sakit,
                        'attendance_permit' => $izin,
                        'attendance_absent' => $alpha,
                        'homeroom_teacher_notes' => $catatan[$student->id] ?? null,
                    ]
                );

                // 3. Finalisasi raport
                if (!$raport->is_finalized) {
                    $raport->finalize();
                }
            }
        });

        return redirect()->route('wali.finalisasi')->with('success', 'Semua raport siswa di kelas ini telah berhasil difinalisasi.');
    }

    public function kenaikan()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeYear = AcademicYear::getActive();
        $kelas = Classroom::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeYear?->id)
            ->first();

        if (!$kelas) {
            return view('guru.wali-kelas-empty');
        }

        $students = $kelas->students()->with('user')->orderBy('full_name')->get();

        // Ambil data nilai dan KKM untuk menghitung rekomendasi
        $grades = Grade::where('classroom_id', $kelas->id)
            ->where('academic_year_id', $activeYear->id)
            ->get()->groupBy('student_id');

        $subjectSettings = SubjectSetting::where('academic_year_id', $activeYear->id)
            ->get()->keyBy('subject_id');

        // Ambil data promosi yang sudah ada
        $promotions = StudentPromotion::where('from_classroom_id', $kelas->id)
            ->where('promotion_year_id', $activeYear->id)
            ->get()->keyBy('student_id');

        $promotionData = $students->map(function ($student) use ($grades, $subjectSettings, $promotions, $kelas, $activeYear) {
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


        return view('guru.wali-kenaikan', compact('kelas', 'promotionData', 'activeYear'));
    }

    public function storeKenaikan(Request $request)
    {
        $request->validate([
            'promotions' => 'required|array',
            'promotions.*.final_decision' => 'required|in:Naik Kelas,Tidak Naik Kelas',
        ]);

        $user = Auth::user();
        $teacher = $user->teacher;
        $activeYear = AcademicYear::getActive();
        $kelas = Classroom::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeYear?->id)
            ->firstOrFail();

        DB::transaction(function () use ($request, $kelas, $activeYear) {
            foreach ($request->promotions as $studentId => $data) {
                // Rekalkulasi rekomendasi untuk keamanan
                $studentGrades = Grade::where('student_id', $studentId)
                    ->where('academic_year_id', $activeYear->id)
                    ->get();
                $subjectSettings = SubjectSetting::where('academic_year_id', $activeYear->id)
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
                        'promotion_year_id' => $activeYear->id,
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
        $activeYear = AcademicYear::where('is_active', true)->first();
        $kelas = Classroom::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeYear?->id)
            ->first();
        if (!$kelas) {
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
            ->where('academic_year_id', $activeYear->id)
            ->where('source', 'konversi')
            ->get()
            ->groupBy(['student_id', 'subject_id']);
        return view('guru.wali-pindahan', compact('kelas', 'students', 'subjects', 'grades', 'activeYear'));
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
        $activeYear = AcademicYear::where('is_active', true)->first();
        $kelas = Classroom::where('homeroom_teacher_id', $teacher->id)
            ->where('academic_year_id', $activeYear?->id)
            ->firstOrFail();
        foreach ($request->grades as $data) {
            Grade::updateOrCreate(
                [
                    'student_id' => $data['student_id'],
                    'subject_id' => $data['subject_id'],
                    'classroom_id' => $kelas->id,
                    'academic_year_id' => $activeYear->id,
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

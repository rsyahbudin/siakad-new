<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Raport;
use App\Models\Grade;
use App\Models\ClassroomAssignment;
use App\Models\Semester;
use App\Models\StudentPromotion;
use App\Models\PPDBApplication;
use App\Models\TransferStudent;
use App\Models\ExamSchedule;
use App\Models\SubjectSetting;
use Illuminate\Support\Facades\DB;
use App\Models\Subject;

class KepalaSekolahController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Get active academic year & semester
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        // Get statistics (scoped to active year where applicable)
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalClassrooms = Classroom::count();
        $totalRaports = Raport::where('is_finalized', true)
            ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->count();
        $totalActiveStudents = Student::where('status', 'Aktif')->count();
        $totalPindahanStudents = Student::where('status', 'Pindahan')->count();

        // Get recent grades
        $recentGrades = Grade::with(['student', 'subject', 'classroom'])
            ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->when($activeSemester, fn($q) => $q->where('semester_id', $activeSemester->id))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get recent raports
        $recentRaports = Raport::with(['student', 'classroom', 'semester'])
            ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get classroom statistics
        $classroomStats = Classroom::withCount('students')->get();

        return view('kepala-sekolah.dashboard', compact(
            'user',
            'activeYear',
            'activeSemester',
            'totalStudents',
            'totalTeachers',
            'totalClassrooms',
            'totalRaports',
            'totalActiveStudents',
            'totalPindahanStudents',
            'recentGrades',
            'recentRaports',
            'classroomStats'
        ));
    }

    public function laporanAkademik()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        // Get academic reports
        $classrooms = Classroom::with(['students', 'homeroomTeacher'])
            ->withCount(['students as students_count'])
            ->get();

        $subjects = Subject::with('teachers')->get();

        // Calculate statistics scoped to active year & semester
        $totalStudents = Student::count();
        $totalGrades = Grade::when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->when($activeSemester, fn($q) => $q->where('semester_id', $activeSemester->id))
            ->count();
        $averageGrade = $totalGrades > 0 ? Grade::when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->when($activeSemester, fn($q) => $q->where('semester_id', $activeSemester->id))
            ->avg('final_grade') : 0;
        $passedKKM = Grade::when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->when($activeSemester, fn($q) => $q->where('semester_id', $activeSemester->id))
            ->where('final_grade', '>=', 75)->count();
        $belowKKM = Grade::when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->when($activeSemester, fn($q) => $q->where('semester_id', $activeSemester->id))
            ->where('final_grade', '<', 75)->count();

        // Do not duplicate recent grades table from dashboard here
        $recentGrades = collect();

        $statistics = [
            'total_students' => $totalStudents,
            'total_grades' => $totalGrades,
            'average_grade' => round($averageGrade, 1),
            'passed_kkm' => $passedKKM,
            'below_kkm' => $belowKKM,
            'pass_rate' => $totalGrades > 0 ? round(($passedKKM / $totalGrades) * 100, 1) : 0
        ];

        return view('kepala-sekolah.laporan-akademik', compact(
            'activeYear',
            'activeSemester',
            'classrooms',
            'subjects',
            'statistics',
            'recentGrades'
        ));
    }

    // Laporan keuangan dihapus untuk Kepala Sekolah

    public function pengaturanSekolah()
    {
        // Get system settings and configurations
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $semesters = Semester::orderBy('name')->get();

        return view('kepala-sekolah.pengaturan-sekolah', compact(
            'activeYear',
            'activeSemester',
            'academicYears',
            'semesters'
        ));
    }

    /**
     * Kenaikan Kelas & Kelulusan - Dipindahkan dari Admin
     */
    public function kenaikanKelas()
    {
        $activeSemester = Semester::where('is_active', true)->first();

        if (!$activeSemester || $activeSemester->name !== 'Genap') {
            return view('kepala-sekolah.kenaikan-kelas.disabled', [
                'message' => 'Proses kenaikan dan kelulusan massal hanya dapat dijalankan pada akhir semester Genap.'
            ]);
        }

        $academicYear = $activeSemester->academicYear;
        $assignments = ClassroomAssignment::with('classroom', 'homeroomTeacher.user')
            ->where('academic_year_id', $academicYear->id)
            ->get();

        $promotionStatus = $assignments->map(function ($assignment) use ($academicYear) {
            $studentCount = $assignment->classStudents()->count();

            if ($studentCount === 0) {
                return (object) [
                    'assignment' => $assignment,
                    'student_count' => 0,
                    'promotion_count' => 0,
                    'is_ready' => true,
                    'status_message' => 'Kelas kosong',
                    'count_naik' => 0,
                    'count_tidak_naik' => 0,
                    'count_belum' => 0,
                    'is_last_grade' => false
                ];
            }

            $promotions = StudentPromotion::where('from_classroom_id', $assignment->classroom_id)
                ->where('promotion_year_id', $academicYear->id)
                ->get();
            $promotionCount = $promotions->count();

            // Tentukan apakah kelas ini tingkat akhir
            $isLastGrade = str_starts_with($assignment->classroom->name, 'XII');
            $countNaik = $promotions->where('final_decision', $isLastGrade ? 'Lulus' : 'Naik Kelas')->count();
            $countTidakNaik = $promotions->where('final_decision', $isLastGrade ? 'Tidak Lulus' : 'Tidak Naik Kelas')->count();
            $countBelum = $studentCount - ($countNaik + $countTidakNaik);

            $isReady = $studentCount === $promotionCount;

            return (object) [
                'assignment' => $assignment,
                'student_count' => $studentCount,
                'promotion_count' => $promotionCount,
                'is_ready' => $isReady,
                'status_message' => $isReady ? 'Siap diproses' : 'Menunggu keputusan wali kelas',
                'count_naik' => $countNaik,
                'count_tidak_naik' => $countTidakNaik,
                'count_belum' => $countBelum,
                'is_last_grade' => $isLastGrade
            ];
        });

        $allReady = $promotionStatus->every('is_ready', true);
        return view('kepala-sekolah.kenaikan-kelas.index', compact('academicYear', 'promotionStatus', 'allReady'));
    }

    /**
     * Process the mass promotion and graduation.
     */
    public function processKenaikanKelas()
    {
        DB::beginTransaction();
        try {
            $activeSemester = Semester::where('is_active', true)->firstOrFail();
            $currentYear = $activeSemester->academicYear;

            $nextYearName = (explode('/', $currentYear->year)[0] + 1) . '/' . (explode('/', $currentYear->year)[1] + 1);
            $nextYear = AcademicYear::firstOrCreate(['year' => $nextYearName]);

            Semester::firstOrCreate(['academic_year_id' => $nextYear->id, 'name' => 'Ganjil']);
            Semester::firstOrCreate(['academic_year_id' => $nextYear->id, 'name' => 'Genap']);

            $promotions = StudentPromotion::with('student', 'fromClassroom')
                ->where('promotion_year_id', $currentYear->id)->get();

            foreach ($promotions as $promotion) {
                if ($promotion->final_decision === 'Naik Kelas') {
                    // Find next grade classroom
                    $currentGrade = $promotion->fromClassroom->grade_level;
                    $nextGrade = $currentGrade + 1;

                    if ($nextGrade <= 12) {
                        $nextClassroom = Classroom::where('grade_level', $nextGrade)
                            ->where('major_id', $promotion->fromClassroom->major_id)
                            ->first();

                        if ($nextClassroom) {
                            // Move student to next grade
                            $promotion->student->classStudents()->create([
                                'classroom_id' => $nextClassroom->id,
                                'academic_year_id' => $nextYear->id
                            ]);
                        }
                    }
                } elseif ($promotion->final_decision === 'Lulus') {
                    // Mark student as graduated
                    $promotion->student->update(['status' => 'Lulus']);
                }
                // For 'Tidak Naik Kelas' or 'Tidak Lulus', student stays in same class
            }

            DB::commit();
            return redirect()->route('kepala.kenaikan-kelas')->with('success', 'Proses kenaikan kelas dan kelulusan berhasil diselesaikan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('kepala.kenaikan-kelas')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Monitoring PPDB
     */
    public function monitoringPPDB()
    {
        $applications = PPDBApplication::with(['student'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statistics = [
            'total' => PPDBApplication::count(),
            'pending' => PPDBApplication::where('status', 'Pending')->count(),
            'approved' => PPDBApplication::where('status', 'Approved')->count(),
            'rejected' => PPDBApplication::where('status', 'Rejected')->count(),
        ];

        return view('kepala-sekolah.monitoring.ppdb', compact('applications', 'statistics'));
    }

    /**
     * Monitoring Siswa Pindahan
     */
    public function monitoringSiswaPindahan()
    {
        $transfers = TransferStudent::with(['student'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statistics = [
            'total' => TransferStudent::count(),
            'pending' => TransferStudent::where('status', 'Pending')->count(),
            'approved' => TransferStudent::where('status', 'Approved')->count(),
            'rejected' => TransferStudent::where('status', 'Rejected')->count(),
        ];

        return view('kepala-sekolah.monitoring.siswa-pindahan', compact('transfers', 'statistics'));
    }

    /**
     * Monitoring Jadwal Ujian
     */
    public function monitoringJadwalUjian()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $examSchedules = ExamSchedule::with(['academicYear', 'semester', 'subject', 'classroom', 'supervisor'])
            ->where('academic_year_id', $activeYear->id ?? 0)
            ->where('semester_id', $activeSemester->id ?? 0)
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->paginate(20);

        $statistics = [
            'total' => ExamSchedule::where('academic_year_id', $activeYear->id ?? 0)
                ->where('semester_id', $activeSemester->id ?? 0)->count(),
            'uts' => ExamSchedule::where('academic_year_id', $activeYear->id ?? 0)
                ->where('semester_id', $activeSemester->id ?? 0)
                ->where('exam_type', 'uts')->count(),
            'uas' => ExamSchedule::where('academic_year_id', $activeYear->id ?? 0)
                ->where('semester_id', $activeSemester->id ?? 0)
                ->where('exam_type', 'uas')->count(),
        ];

        return view('kepala-sekolah.monitoring.jadwal-ujian', compact('examSchedules', 'statistics', 'activeYear', 'activeSemester'));
    }

    /**
     * Pengaturan KKM
     */
    public function pengaturanKKM()
    {
        $subjects = \App\Models\Subject::with('subjectSettings')->get();
        $semesterWeights = \App\Models\SemesterWeight::all();

        return view('kepala-sekolah.pengaturan-kkm', compact('subjects', 'semesterWeights'));
    }

    /**
     * Update KKM settings
     */
    public function updateKKM(Request $request)
    {
        $request->validate([
            'kkm_values' => 'required|array',
            'kkm_values.*' => 'required|numeric|min:0|max:100'
        ]);

        foreach ($request->kkm_values as $subjectId => $kkmValue) {
            \App\Models\SubjectSetting::updateOrCreate(
                ['subject_id' => $subjectId],
                ['kkm' => $kkmValue]
            );
        }

        return redirect()->route('kepala.pengaturan-kkm')->with('success', 'Pengaturan KKM berhasil diperbarui.');
    }

    /**
     * Monitoring Guru
     */
    public function monitoringGuru()
    {
        $teachers = Teacher::with(['user', 'subject', 'classroomAssignments'])
            ->withCount(['classroomAssignments', 'examSchedules'])
            ->get();

        $statistics = [
            'total' => Teacher::count(),
            'active' => Teacher::count(), // All teachers are considered active since there's no status column
            'homeroom' => Teacher::whereHas('classroomAssignments')->count(),
        ];

        return view('kepala-sekolah.monitoring.guru', compact('teachers', 'statistics'));
    }

    /**
     * Monitoring Kelas
     */
    public function monitoringKelas()
    {
        $classrooms = Classroom::with(['students', 'homeroomTeacher', 'major'])
            ->withCount('students')
            ->get();

        $totalStudents = $classrooms->sum('students_count');
        $avgStudents = $classrooms->count() > 0 ? round($totalStudents / $classrooms->count(), 1) : 0;

        $statistics = [
            'total' => Classroom::count(),
            'total_students' => $totalStudents,
            'homeroom_teachers' => Teacher::whereHas('classroomAssignments')->count(),
            'avg_students' => $avgStudents,
        ];

        return view('kepala-sekolah.monitoring.kelas', compact('classrooms', 'statistics'));
    }

    /**
     * Monitoring Nilai
     */
    public function monitoringNilai()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $recentGrades = Grade::with(['student', 'subject', 'classroom'])
            ->where('academic_year_id', $activeYear->id ?? 0)
            ->where('semester_id', $activeSemester->id ?? 0)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get subject statistics
        $subjectStats = Grade::with(['subject', 'subject.subjectSettings'])
            ->where('academic_year_id', $activeYear->id ?? 0)
            ->where('semester_id', $activeSemester->id ?? 0)
            ->get()
            ->groupBy('subject_id')
            ->map(function ($grades, $subjectId) {
                $subject = $grades->first()->subject;
                $totalCount = $grades->count();
                $passedCount = $grades->where('final_grade', '>=', $subject->subjectSettings->first()->kkm ?? 75)->count();

                return (object) [
                    'subject_name' => $subject->name,
                    'major_name' => $subject->major->name ?? 'Umum',
                    'average_grade' => $grades->avg('final_grade'),
                    'highest_grade' => $grades->max('final_grade'),
                    'lowest_grade' => $grades->min('final_grade'),
                    'total_count' => $totalCount,
                    'passed_count' => $passedCount,
                ];
            });

        $statistics = [
            'total_grades' => Grade::where('academic_year_id', $activeYear->id ?? 0)
                ->where('semester_id', $activeSemester->id ?? 0)->count(),
            'average_grade' => Grade::where('academic_year_id', $activeYear->id ?? 0)
                ->where('semester_id', $activeSemester->id ?? 0)->avg('final_grade') ?? 0,
            'passed_kkm' => Grade::where('academic_year_id', $activeYear->id ?? 0)
                ->where('semester_id', $activeSemester->id ?? 0)
                ->where('final_grade', '>=', 75)->count(),
            'below_kkm' => Grade::where('academic_year_id', $activeYear->id ?? 0)
                ->where('semester_id', $activeSemester->id ?? 0)
                ->where('final_grade', '<', 75)->count(),
        ];

        return view('kepala-sekolah.monitoring.nilai', compact('recentGrades', 'subjectStats', 'statistics', 'activeYear', 'activeSemester'));
    }
}

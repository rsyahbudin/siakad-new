<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
use App\Models\KepalaSekolah;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $recentRaports = Raport::with(['student', 'classroom', 'academicYear'])
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
        $classrooms = Classroom::with(['students'])
            ->withCount(['students as students_count'])
            ->get();

        $subjects = Subject::all();

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

        // Get KKM settings data (read-only for kepala sekolah)
        $subjects = \App\Models\Subject::with('subjectSettings')->get();
        $semesterWeights = \App\Models\SemesterWeight::first();

        return view('kepala-sekolah.pengaturan-sekolah', compact(
            'activeYear',
            'activeSemester',
            'academicYears',
            'semesters',
            'subjects',
            'semesterWeights'
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

            // Buat tahun ajaran baru
            $nextYearName = (explode('/', $currentYear->year)[0] + 1) . '/' . (explode('/', $currentYear->year)[1] + 1);
            $nextYear = AcademicYear::firstOrCreate(['year' => $nextYearName]);

            // Buat semester untuk tahun ajaran baru
            Semester::firstOrCreate(['academic_year_id' => $nextYear->id, 'name' => 'Ganjil']);
            Semester::firstOrCreate(['academic_year_id' => $nextYear->id, 'name' => 'Genap']);

            // Ambil semua keputusan kenaikan kelas
            $promotions = StudentPromotion::with('student', 'fromClassroom')
                ->where('promotion_year_id', $currentYear->id)->get();

            $processedCount = 0;
            $graduatedCount = 0;
            $promotedCount = 0;
            $retainedCount = 0;

            // Pastikan siswa yang lulus tidak memiliki assignment kelas di tahun ajaran baru
            $graduatedStudents = $promotions->where('final_decision', 'Lulus')->pluck('student_id');
            if ($graduatedStudents->count() > 0) {
                \App\Models\ClassStudent::whereIn('student_id', $graduatedStudents)
                    ->where('academic_year_id', $nextYear->id)
                    ->delete();
            }



            foreach ($promotions as $promotion) {
                if ($promotion->final_decision === 'Naik Kelas') {
                    // Cari kelas tingkat berikutnya
                    $currentGrade = $promotion->fromClassroom->grade_level;
                    $nextGrade = $currentGrade + 1;

                    if ($nextGrade <= 12) {
                        $nextClassroom = Classroom::where('grade_level', $nextGrade)
                            ->where('major_id', $promotion->fromClassroom->major_id)
                            ->first();

                        if ($nextClassroom) {
                            // Cari atau buat classroom assignment untuk kelas berikutnya
                            $nextAssignment = ClassroomAssignment::firstOrCreate([
                                'classroom_id' => $nextClassroom->id,
                                'academic_year_id' => $nextYear->id
                            ]);

                            // Pindahkan siswa ke kelas tingkat berikutnya
                            $promotion->student->classStudents()->create([
                                'classroom_id' => $nextClassroom->id,
                                'classroom_assignment_id' => $nextAssignment->id,
                                'academic_year_id' => $nextYear->id
                            ]);
                            $promotedCount++;
                        } else {
                            // Jika tidak ada kelas tingkat berikutnya, tetap di kelas yang sama
                            $currentAssignment = ClassroomAssignment::firstOrCreate([
                                'classroom_id' => $promotion->fromClassroom->id,
                                'academic_year_id' => $nextYear->id
                            ]);

                            $promotion->student->classStudents()->create([
                                'classroom_id' => $promotion->fromClassroom->id,
                                'classroom_assignment_id' => $currentAssignment->id,
                                'academic_year_id' => $nextYear->id
                            ]);
                            $retainedCount++;
                        }
                    } else {
                        // Jika sudah kelas XII, tetap di kelas yang sama
                        $currentAssignment = ClassroomAssignment::firstOrCreate([
                            'classroom_id' => $promotion->fromClassroom->id,
                            'academic_year_id' => $nextYear->id
                        ]);

                        $promotion->student->classStudents()->create([
                            'classroom_id' => $promotion->fromClassroom->id,
                            'classroom_assignment_id' => $currentAssignment->id,
                            'academic_year_id' => $nextYear->id
                        ]);
                        $retainedCount++;
                    }
                } elseif ($promotion->final_decision === 'Lulus') {
                    // Tandai siswa sebagai lulus
                    $promotion->student->update(['status' => 'Lulus']);

                    // Pastikan siswa yang lulus TIDAK dimasukkan ke kelas manapun
                    // Siswa lulus tidak akan memiliki assignment kelas di tahun ajaran baru
                    $graduatedCount++;
                } elseif ($promotion->final_decision === 'Tidak Naik Kelas' || $promotion->final_decision === 'Tidak Lulus') {
                    // Siswa tetap di kelas yang sama
                    $currentAssignment = ClassroomAssignment::firstOrCreate([
                        'classroom_id' => $promotion->fromClassroom->id,
                        'academic_year_id' => $nextYear->id
                    ]);

                    $promotion->student->classStudents()->create([
                        'classroom_id' => $promotion->fromClassroom->id,
                        'classroom_assignment_id' => $currentAssignment->id,
                        'academic_year_id' => $nextYear->id
                    ]);
                    $retainedCount++;
                }

                $processedCount++;
            }

            // Aktifkan tahun ajaran baru (otomatis menonaktifkan yang lain)
            $nextYear->setAsActive();

            DB::commit();

            $message = "Proses kenaikan kelas berhasil diselesaikan. ";
            $message .= "Total diproses: {$processedCount} siswa. ";
            $message .= "Naik kelas: {$promotedCount} siswa. ";
            $message .= "Lulus: {$graduatedCount} siswa. ";
            $message .= "Tinggal kelas: {$retainedCount} siswa.";

            return redirect()->route('kepala.kenaikan-kelas')->with('success', $message);
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
        $applications = PPDBApplication::orderBy('created_at', 'desc')
            ->paginate(20);

        $statistics = [
            'total' => PPDBApplication::count(),
            'pending' => PPDBApplication::where('status', 'pending')->count(),
            'approved' => PPDBApplication::where('status', 'lulus')->count(),
            'rejected' => PPDBApplication::where('status', 'ditolak')->count(),
        ];

        return view('kepala-sekolah.monitoring.ppdb', compact('applications', 'statistics'));
    }

    /**
     * Monitoring Siswa Pindahan
     */
    public function monitoringSiswaPindahan()
    {
        $transfers = TransferStudent::orderBy('created_at', 'desc')
            ->paginate(20);

        $statistics = [
            'total' => TransferStudent::count(),
            'pending' => TransferStudent::where('status', 'pending')->count(),
            'approved' => TransferStudent::where('status', 'approved')->count(),
            'rejected' => TransferStudent::where('status', 'rejected')->count(),
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
        $activeYear = AcademicYear::where('is_active', true)->first();

        $classrooms = Classroom::with(['students', 'major'])
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

        return view('kepala-sekolah.monitoring.kelas', compact('classrooms', 'statistics', 'activeYear'));
    }

    public function laporanPersemester(Request $request)
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $selectedYearId = $request->get('academic_year_id');
        $selectedSemester = $request->get('semester', 'Ganjil');

        $selectedYear = null;
        if ($selectedYearId) {
            $selectedYear = AcademicYear::find($selectedYearId);
        } else {
            $selectedYear = AcademicYear::where('is_active', true)->first();
        }

        if (!$selectedYear) {
            $academicSettings = [
                'subject_settings' => collect(),
                'semester_weights' => null,
                'max_failed_subjects' => 2,
            ];
            return view('kepala-sekolah.laporan-persemester', compact('academicYears', 'selectedYear', 'selectedSemester', 'academicSettings'));
        }

        $semesterNumber = $selectedSemester === 'Ganjil' ? 1 : 2;

        // Data Pengaturan Akademik
        $academicSettings = $this->getAcademicSettings($selectedYear);

        // Data Siswa
        $studentData = $this->getStudentData($selectedYear, $selectedSemester);

        // Data Guru & Rombel
        $teacherData = $this->getTeacherData($selectedYear, $selectedSemester);

        // Data Absensi
        $attendanceData = $this->getAttendanceData($selectedYear, $selectedSemester);

        // Data Nilai Rapor
        $gradeData = $this->getGradeData($selectedYear, $selectedSemester);

        // Data Ekstrakurikuler
        $extracurricularData = $this->getExtracurricularData($selectedYear, $selectedSemester);

        // Data Kenaikan Kelas (hanya semester genap)
        $promotionData = null;
        if ($selectedSemester === 'Genap') {
            $promotionData = $this->getPromotionData($selectedYear);
        }

        return view('kepala-sekolah.laporan-persemester', compact(
            'academicYears',
            'selectedYear',
            'selectedSemester',
            'academicSettings',
            'studentData',
            'teacherData',
            'attendanceData',
            'gradeData',
            'extracurricularData',
            'promotionData'
        ));
    }

    public function downloadLaporanPersemester(Request $request)
    {
        $academicYearId = $request->get('academic_year_id');
        $semester = $request->get('semester', 'Ganjil');
        $format = $request->get('format', 'pdf');

        $academicYear = AcademicYear::findOrFail($academicYearId);
        $semesterNumber = $semester === 'Ganjil' ? 1 : 2;

        // Get school data from app_settings
        $schoolData = [
            'name' => \App\Models\AppSetting::getValue('school_name', 'SMA Negeri 300'),
            'npsn' => \App\Models\AppSetting::getValue('school_npsn', '1231512'),
            'address' => \App\Models\AppSetting::getValue('school_address', 'Jl. Raya Bogor'),
            'phone' => \App\Models\AppSetting::getValue('school_phone', '081239402132'),
            'email' => \App\Models\AppSetting::getValue('school_email', 'sma300@gmail.com'),
            'website' => \App\Models\AppSetting::getValue('school_website', 'https://sman300.sch.id'),
        ];

        // Get kepala sekolah data
        $kepalaSekolah = \App\Models\KepalaSekolah::first();

        // Get academic settings data
        $academicSettings = $this->getAcademicSettings($academicYear);

        // Get all data
        $studentData = $this->getStudentData($academicYear, $semester);
        $teacherData = $this->getTeacherData($academicYear, $semester);
        $attendanceData = $this->getAttendanceData($academicYear, $semester);
        $gradeData = $this->getGradeData($academicYear, $semester);
        $extracurricularData = $this->getExtracurricularData($academicYear, $semester);
        $promotionData = null;
        if ($semester === 'Genap') {
            $promotionData = $this->getPromotionData($academicYear);
        }

        $data = [
            'academicYear' => $academicYear,
            'semester' => $semester,
            'schoolData' => $schoolData,
            'kepalaSekolah' => $kepalaSekolah,
            'academicSettings' => $academicSettings,
            'studentData' => $studentData,
            'teacherData' => $teacherData,
            'attendanceData' => $attendanceData,
            'gradeData' => $gradeData,
            'extracurricularData' => $extracurricularData,
            'promotionData' => $promotionData,
        ];

        if ($format === 'excel') {
            return $this->downloadExcel($data);
        } else {
            return $this->downloadPDF($data);
        }
    }

    private function getStudentData($academicYear, $semester)
    {
        // Jumlah siswa per kelas/tingkat
        $studentsPerClass = \App\Models\Classroom::with(['classroomAssignments.classStudents.student', 'major'])
            ->whereHas('classroomAssignments', function ($query) use ($academicYear) {
                $query->where('academic_year_id', $academicYear->id);
            })
            ->get()
            ->map(function ($classroom) {
                $studentCount = 0;
                $maleCount = 0;
                $femaleCount = 0;

                foreach ($classroom->classroomAssignments as $assignment) {
                    foreach ($assignment->classStudents as $classStudent) {
                        $studentCount++;
                        if ($classStudent->student->gender === 'L') {
                            $maleCount++;
                        } else {
                            $femaleCount++;
                        }
                    }
                }

                return [
                    'classroom' => $classroom->name,
                    'grade_level' => $classroom->grade_level,
                    'major_name' => $classroom->major ? $classroom->major->name : 'Umum',
                    'student_count' => $studentCount,
                    'male_count' => $maleCount,
                    'female_count' => $femaleCount,
                ];
            });

        // Kelompokkan berdasarkan tingkatan dan jurusan
        $studentsPerGradeAndMajor = $studentsPerClass->groupBy(function ($class) {
            return $class['grade_level'] . '_' . ($class['major_name'] ?? 'Umum');
        })->map(function ($classes, $key) {
            $parts = explode('_', $key);
            $gradeLevel = $parts[0];
            $majorName = $parts[1] ?? 'Umum';

            $totalStudents = $classes->sum('student_count');
            $totalMale = $classes->sum('male_count');
            $totalFemale = $classes->sum('female_count');
            $classCount = $classes->count();

            return [
                'grade_level' => $gradeLevel,
                'grade_name' => $this->getGradeName($gradeLevel),
                'major_name' => $majorName,
                'student_count' => $totalStudents,
                'male_count' => $totalMale,
                'female_count' => $totalFemale,
                'class_count' => $classCount,
            ];
        })->values();

        // Kelompokkan berdasarkan tingkatan saja (untuk summary)
        $studentsPerGrade = $studentsPerClass->groupBy('grade_level')->map(function ($classes, $gradeLevel) {
            $totalStudents = $classes->sum('student_count');
            $totalMale = $classes->sum('male_count');
            $totalFemale = $classes->sum('female_count');
            $classCount = $classes->count();

            return [
                'grade_level' => $gradeLevel,
                'grade_name' => $this->getGradeName($gradeLevel),
                'student_count' => $totalStudents,
                'male_count' => $totalMale,
                'female_count' => $totalFemale,
                'class_count' => $classCount,
            ];
        })->values();

        // Total siswa
        $totalStudents = $studentsPerClass->sum('student_count');
        $totalMale = $studentsPerClass->sum('male_count');
        $totalFemale = $studentsPerClass->sum('female_count');

        // Siswa pindahan (tetap sebagai kategori terpisah)
        $transferStudents = \App\Models\Student::where('status', 'Pindahan')
            ->whereHas('classroomAssignments', function ($query) use ($academicYear) {
                $query->where('classroom_assignments.academic_year_id', $academicYear->id);
            })
            ->count();

        // Mutasi masuk - siswa baru dan pindahan yang masuk di tahun ajaran ini
        // (berdasarkan created_at atau enrollment date di tahun ajaran ini)
        $mutationsIn = \App\Models\ClassStudent::whereHas('classroomAssignment', function ($query) use ($academicYear) {
            $query->where('academic_year_id', $academicYear->id);
        })
            ->whereHas('student', function ($query) {
                $query->whereIn('status', ['Aktif', 'Pindahan']);
            })
            ->distinct('student_id')
            ->count();

        // Mutasi keluar - siswa yang keluar di tahun ajaran ini
        $mutationsOut = \App\Models\Student::where('status', 'Keluar')
            ->whereYear('updated_at', $academicYear->start_year) // Asumsi tahun keluar berdasarkan updated_at
            ->count();

        return [
            'students_per_grade' => $studentsPerGrade,
            'students_per_grade_major' => $studentsPerGradeAndMajor,
            'total_students' => $totalStudents,
            'total_male' => $totalMale,
            'total_female' => $totalFemale,
            'transfer_students' => $transferStudents,
            'mutations_in' => $mutationsIn,
            'mutations_out' => $mutationsOut,
        ];
    }

    private function getAcademicSettings($academicYear)
    {
        // Get subject settings (KKM and weights)
        $subjectSettings = \App\Models\SubjectSetting::with('subject')
            ->where('academic_year_id', $academicYear->id)
            ->where('is_active', true)
            ->get()
            ->map(function ($setting) {
                return [
                    'subject_name' => $setting->subject->name,
                    'subject_code' => $setting->subject->code,
                    'kkm' => $setting->kkm,
                    'assignment_weight' => $setting->assignment_weight,
                    'uts_weight' => $setting->uts_weight,
                    'uas_weight' => $setting->uas_weight,
                ];
            });

        // Get semester weights
        $semesterWeights = \App\Models\SemesterWeight::where('academic_year_id', $academicYear->id)
            ->where('is_active', true)
            ->first();

        // Get max failed subjects setting
        $maxFailedSubjects = \App\Models\AppSetting::getValue('max_failed_subjects', 2);

        return [
            'subject_settings' => $subjectSettings,
            'semester_weights' => $semesterWeights ? [
                'ganjil_weight' => $semesterWeights->ganjil_weight,
                'genap_weight' => $semesterWeights->genap_weight,
            ] : null,
            'max_failed_subjects' => $maxFailedSubjects,
        ];
    }

    private function getGradeName($gradeLevel)
    {
        switch ($gradeLevel) {
            case 10:
                return 'X';
            case 11:
                return 'XI';
            case 12:
                return 'XII';
            default:
                return 'Kelas ' . $gradeLevel;
        }
    }

    private function getTeacherData($academicYear, $semester)
    {
        // Jumlah guru
        $teacherCount = \App\Models\Teacher::count();

        // Mapel yang diajar
        $subjectsTaught = \App\Models\Subject::whereHas('schedules.teacher')
            ->distinct()
            ->count();

        // Rombongan belajar
        $classroomCount = \App\Models\Classroom::whereHas('classroomAssignments', function ($query) use ($academicYear) {
            $query->where('classroom_assignments.academic_year_id', $academicYear->id);
        })->count();

        return [
            'teacher_count' => $teacherCount,
            'subjects_taught' => $subjectsTaught,
            'classroom_count' => $classroomCount,
        ];
    }

    private function getAttendanceData($academicYear, $semester)
    {
        // Dapatkan semester yang sesuai
        $semesterModel = \App\Models\Semester::where('academic_year_id', $academicYear->id)
            ->where('name', $semester)
            ->first();

        if (!$semesterModel) {
            return [
                'overall_attendance_percentage' => 0,
                'attendance_per_class' => []
            ];
        }

        $semesterNumber = $semester === 'Ganjil' ? 1 : 2;

        // Rekap absensi siswa per kelas
        $attendancePerClass = \App\Models\Classroom::with(['classroomAssignments.classStudents.student.attendances'])
            ->whereHas('classroomAssignments', function ($query) use ($academicYear) {
                $query->where('classroom_assignments.academic_year_id', $academicYear->id);
            })
            ->get()
            ->map(function ($classroom) use ($semesterNumber) {
                $totalSick = 0;
                $totalPermit = 0;
                $totalAbsent = 0;
                $totalStudents = 0;

                foreach ($classroom->classroomAssignments as $assignment) {
                    foreach ($assignment->classStudents as $classStudent) {
                        $attendance = $classStudent->student->attendances()
                            ->where('semester_id', $semesterNumber)
                            ->first();

                        if ($attendance) {
                            $totalSick += $attendance->sakit;
                            $totalPermit += $attendance->izin;
                            $totalAbsent += $attendance->alpha;
                        }
                        $totalStudents++;
                    }
                }

                $totalAttendance = $totalSick + $totalPermit + $totalAbsent;
                $attendancePercentage = $totalStudents > 0 ? (($totalStudents - $totalAbsent) / $totalStudents) * 100 : 0;

                return [
                    'classroom' => $classroom->name,
                    'total_students' => $totalStudents,
                    'sick' => $totalSick,
                    'permit' => $totalPermit,
                    'absent' => $totalAbsent,
                    'attendance_percentage' => round($attendancePercentage, 2),
                ];
            });

        return [
            'attendance_per_class' => $attendancePerClass,
            'overall_attendance_percentage' => $attendancePerClass->avg('attendance_percentage'),
        ];
    }

    private function getGradeData($academicYear, $semester)
    {
        // Dapatkan semester yang sesuai
        $semesterModel = \App\Models\Semester::where('academic_year_id', $academicYear->id)
            ->where('name', $semester)
            ->first();

        if (!$semesterModel) {
            return [
                'passed_students' => 0,
                'failed_students' => 0,
                'total_grades' => 0,
                'total_subjects' => 0,
                'total_students_with_grades' => 0,
                'average_grade' => 0,
                'grades_by_class' => [],
                'grades_by_grade_level' => []
            ];
        }

        // Nilai akhir semester per siswa
        $grades = \App\Models\Grade::with(['student', 'subject', 'classroom'])
            ->where('grades.academic_year_id', $academicYear->id)
            ->where('grades.semester_id', $semesterModel->id)
            ->get();

        // Jumlah siswa tuntas/tidak tuntas KKM
        $subjectSettings = \App\Models\SubjectSetting::where('subject_settings.academic_year_id', $academicYear->id)
            ->where('subject_settings.is_active', true)
            ->get()
            ->keyBy('subject_id');

        $passedStudents = 0;
        $failedStudents = 0;

        // Kelompokkan nilai per kelas
        $gradesByClass = $grades->groupBy('classroom_id')
            ->map(function ($classGrades, $classroomId) use ($subjectSettings) {
                $classroom = $classGrades->first()->classroom;
                $averageGrade = $classGrades->avg('final_grade');
                $totalStudents = $classGrades->unique('student_id')->count();
                $passedCount = 0;
                $failedCount = 0;

                foreach ($classGrades as $grade) {
                    $kkm = $subjectSettings->get($grade->subject_id)?->kkm ?? 75;
                    if ($grade->final_grade >= $kkm) {
                        $passedCount++;
                    } else {
                        $failedCount++;
                    }
                }

                return [
                    'classroom_name' => $classroom->name,
                    'grade_level' => $classroom->grade_level,
                    'major_name' => $classroom->major ? $classroom->major->name : 'Umum',
                    'average_grade' => round($averageGrade, 2),
                    'total_students' => $totalStudents,
                    'passed_count' => $passedCount,
                    'failed_count' => $failedCount,
                    'pass_percentage' => $totalStudents > 0 ? round(($passedCount / $totalStudents) * 100, 1) : 0,
                ];
            })
            ->values();

        // Kelompokkan nilai per tingkatan
        $gradesByGradeLevel = $grades->groupBy(function ($grade) {
            return $grade->classroom->grade_level;
        })
            ->map(function ($gradeLevelGrades, $gradeLevel) use ($subjectSettings) {
                $averageGrade = $gradeLevelGrades->avg('final_grade');
                $totalStudents = $gradeLevelGrades->unique('student_id')->count();
                $passedCount = 0;
                $failedCount = 0;

                foreach ($gradeLevelGrades as $grade) {
                    $kkm = $subjectSettings->get($grade->subject_id)?->kkm ?? 75;
                    if ($grade->final_grade >= $kkm) {
                        $passedCount++;
                    } else {
                        $failedCount++;
                    }
                }

                return [
                    'grade_level' => $gradeLevel,
                    'grade_name' => $this->getGradeName($gradeLevel),
                    'average_grade' => round($averageGrade, 2),
                    'total_students' => $totalStudents,
                    'passed_count' => $passedCount,
                    'failed_count' => $failedCount,
                    'pass_percentage' => $totalStudents > 0 ? round(($passedCount / $totalStudents) * 100, 1) : 0,
                ];
            })
            ->values();

        // Hitung total tuntas/tidak tuntas
        foreach ($grades as $grade) {
            $kkm = $subjectSettings->get($grade->subject_id)?->kkm ?? 75;
            if ($grade->final_grade >= $kkm) {
                $passedStudents++;
            } else {
                $failedStudents++;
            }
        }

        return [
            'grades_by_class' => $gradesByClass,
            'grades_by_grade_level' => $gradesByGradeLevel,
            'passed_students' => $passedStudents,
            'failed_students' => $failedStudents,
            'total_grades' => count($grades),
            'total_subjects' => $grades->unique('subject_id')->count(),
            'total_students_with_grades' => $grades->unique('student_id')->count(),
            'average_grade' => $grades->count() > 0 ? round($grades->avg('final_grade'), 2) : 0,
        ];
    }

    private function getExtracurricularData($academicYear, $semester)
    {
        // Data keikutsertaan siswa
        $extracurriculars = \App\Models\Extracurricular::with(['students' => function ($query) use ($academicYear) {
            $query->wherePivot('student_extracurriculars.academic_year_id', $academicYear->id);
        }])
            ->get()
            ->map(function ($extracurricular) {
                return [
                    'name' => $extracurricular->name,
                    'category' => $extracurricular->category,
                    'student_count' => $extracurricular->students->count(),
                    'students' => $extracurricular->students->map(function ($student) {
                        return [
                            'name' => $student->full_name,
                            'grade' => $student->pivot->grade ?? '-',
                            'status' => $student->pivot->status ?? 'Aktif',
                        ];
                    }),
                ];
            });

        return [
            'extracurriculars' => $extracurriculars,
            'total_participants' => $extracurriculars->sum('student_count'),
        ];
    }

    private function getPromotionData($academicYear)
    {
        // Dapatkan semester Genap
        $semesterGenap = \App\Models\Semester::where('academic_year_id', $academicYear->id)
            ->where('name', 'Genap')
            ->first();

        if (!$semesterGenap) {
            return [
                'promoted_students' => 0,
                'retained_students' => 0,
                'graduated_students' => 0
            ];
        }

        // Kenaikan Kelas (kelas X & XI)
        $promotions = \App\Models\Raport::with(['student', 'classroom'])
            ->where('raports.academic_year_id', $academicYear->id)
            ->where('raports.semester', 2) // Semester Genap
            ->whereIn('raports.classroom_id', function ($query) {
                $query->select('id')
                    ->from('classrooms')
                    ->whereIn('grade_level', ['X', 'XI']);
            })
            ->get();

        $promotedStudents = $promotions->where('promotion_status', 'RECOMMENDED')->count();
        $retainedStudents = $promotions->where('promotion_status', 'NOT_RECOMMENDED')->count();

        // Kelulusan (kelas XII)
        $graduations = \App\Models\Raport::with(['student', 'classroom'])
            ->where('raports.academic_year_id', $academicYear->id)
            ->where('raports.semester', 2) // Semester Genap
            ->whereIn('raports.classroom_id', function ($query) {
                $query->select('id')
                    ->from('classrooms')
                    ->where('grade_level', 'XII');
            })
            ->get();

        $graduatedStudents = $graduations->where('promotion_status', 'RECOMMENDED')->count();

        return [
            'promoted_students' => $promotedStudents,
            'retained_students' => $retainedStudents,
            'graduated_students' => $graduatedStudents,
        ];
    }

    private function downloadPDF($data)
    {
        $pdf = Pdf::loadView('kepala-sekolah.laporan-persemester-pdf', $data);

        // Sanitize filename by removing invalid characters
        $cleanSemester = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $data['semester']);
        $cleanYear = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $data['academicYear']->year);
        $filename = 'laporan_persemester_' . $cleanSemester . '_' . $cleanYear . '.pdf';

        return $pdf->download($filename);
    }

    private function downloadExcel($data)
    {
        // Implementation for Excel download will be added later
        return response()->json(['message' => 'Excel download feature will be implemented soon']);
    }


    /**
     * Pengaturan Akun (Profil dan Password) - Kepala Sekolah
     */
    public function accountSettings()
    {
        $user = Auth::user();
        $profile = $user->kepalaSekolah; // may be null if not set
        return view('kepala-sekolah.pengaturan-akun', compact('user', 'profile'));
    }

    public function updateAccount(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nip' => 'required|string|size:18|unique:kepala_sekolahs,nip,' . ($user->kepalaSekolah->id ?? 'null'),
            'full_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'last_education' => 'nullable|string|max:100',
            'degree' => 'nullable|string|max:100',
            'major' => 'nullable|string|max:150',
            'university' => 'nullable|string|max:150',
            'graduation_year' => 'nullable|integer|min:1950|max:2100',
            'birth_place' => 'nullable|string|max:150',
            'birth_date' => 'nullable|date',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        // sync to kepala sekolah profile if exists
        $profile = $user->kepalaSekolah;
        if (!$profile) {
            $profile = new KepalaSekolah(['user_id' => $user->id]);
        }

        $profile->nip = $validated['nip'];
        $profile->full_name = $validated['full_name'] ?? $profile->full_name;
        $profile->phone_number = $validated['phone_number'] ?? $profile->phone_number;
        $profile->address = $validated['address'] ?? $profile->address;
        $profile->position = 'Kepala Sekolah';
        $profile->last_education = $validated['last_education'] ?? $profile->last_education;
        $profile->degree = $validated['degree'] ?? $profile->degree;
        $profile->major = $validated['major'] ?? $profile->major;
        $profile->university = $validated['university'] ?? $profile->university;
        $profile->graduation_year = $validated['graduation_year'] ?? $profile->graduation_year;
        $profile->birth_place = $validated['birth_place'] ?? $profile->birth_place;
        $profile->birth_date = $validated['birth_date'] ?? $profile->birth_date;
        $profile->save();

        return back()->with('success', 'Profil akun berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->with('error', 'Password saat ini tidak sesuai.');
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}

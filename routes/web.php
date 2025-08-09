<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassAssignmentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\PenugasanGuruController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\SubjectSettingController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GuruJadwalController;
use App\Http\Controllers\GuruNilaiController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\SiswaRaportController;
use App\Http\Controllers\GuruAbsensiController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\Admin\NilaiSiswaController;
use App\Http\Controllers\KepalaSekolahController;
use App\Http\Controllers\WaliMuridController;
use App\Http\Controllers\PPDBApplicationController;
use App\Http\Controllers\TransferStudentController;
use App\Http\Controllers\ExamScheduleController;
use App\Http\Controllers\Admin\KepalaSekolahAccountController;
use App\Http\Controllers\ExtracurricularController;
use App\Http\Controllers\StudentExtracurricularController;
use App\Http\Controllers\GuruExtracurricularGradeController;

Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});

// PPDB Public Routes (No Authentication Required)
Route::prefix('ppdb')->name('ppdb.')->middleware('check.system.enabled:ppdb')->group(function () {
    Route::get('/register', [PPDBApplicationController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [PPDBApplicationController::class, 'register'])->name('register.store');
    Route::get('/success', [PPDBApplicationController::class, 'showSuccess'])->name('success');
    Route::get('/status-check', [PPDBApplicationController::class, 'showStatusCheck'])->name('status-check');
    Route::post('/status-check', [PPDBApplicationController::class, 'checkStatus'])->name('status-check.post');
    Route::get('/status', [PPDBApplicationController::class, 'checkStatus'])->name('status');
});

// Transfer Student Public Routes (No Authentication Required)
Route::prefix('transfer')->name('transfer.')->middleware('check.system.enabled:transfer')->group(function () {
    Route::get('/register', [TransferStudentController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [TransferStudentController::class, 'register'])->name('register.store');
    Route::get('/success', [TransferStudentController::class, 'showSuccess'])->name('success');
    Route::get('/status-check', [TransferStudentController::class, 'showStatusCheck'])->name('status-check');
    Route::post('/status-check', [TransferStudentController::class, 'checkStatus'])->name('status-check.post');
    Route::get('/status', [TransferStudentController::class, 'checkStatus'])->name('status');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Resource index routes for admin menu (dummy controller, just for view)
Route::middleware('auth')->group(function () {
    Route::resource('tahun-ajaran', AcademicYearController::class)->parameters(['tahun-ajaran' => 'tahun_ajaran']);
    Route::post('tahun-ajaran/{tahun_ajaran}/set-active', [AcademicYearController::class, 'setActive'])->name('tahun-ajaran.set-active');
    Route::resource('jurusan', MajorController::class)->parameters(['jurusan' => 'jurusan']);
    Route::resource('mapel', SubjectController::class)->parameters(['mapel' => 'mapel']);
    Route::resource('guru', TeacherController::class)->parameters(['guru' => 'guru']);

    // Student routes with role-based access
    Route::get('/siswa', [StudentController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/export', [StudentController::class, 'export'])->name('siswa.export');

    // Admin-only student routes
    Route::middleware('check.role:admin')->group(function () {
        Route::get('/siswa/create', [StudentController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [StudentController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}/edit', [StudentController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}', [StudentController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [StudentController::class, 'destroy'])->name('siswa.destroy');

        // Extracurricular management routes
        Route::resource('extracurricular', ExtracurricularController::class);
        Route::post('extracurricular/{extracurricular}/add-student', [ExtracurricularController::class, 'addStudent'])->name('extracurricular.add-student');
        Route::delete('extracurricular/{extracurricular}/remove-student/{student}', [ExtracurricularController::class, 'removeStudent'])->name('extracurricular.remove-student');
        Route::put('extracurricular/{extracurricular}/update-student/{student}', [ExtracurricularController::class, 'updateStudent'])->name('extracurricular.update-student');
        Route::put('extracurricular/{extracurricular}/update-student-status', [ExtracurricularController::class, 'updateStudentStatus'])->name('extracurricular.update-student-status');
    });

    // Admin-only routes
    Route::middleware('check.role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('nilai-siswa', NilaiSiswaController::class)->only(['show']);

        // Admin Siswa Pindahan routes
        Route::get('siswa-pindahan', [\App\Http\Controllers\AdminController::class, 'siswaPindahan'])->name('siswa-pindahan');
        Route::post('siswa-pindahan/store-konversi', [\App\Http\Controllers\AdminController::class, 'storeKonversi'])->name('store-konversi');
        Route::get('siswa-pindahan/daftar', [\App\Http\Controllers\AdminController::class, 'daftarSiswaPindahan'])->name('daftar-siswa-pindahan');
        Route::get('siswa-pindahan/{id}/detail', [\App\Http\Controllers\AdminController::class, 'detailSiswaPindahan'])->name('detail-siswa-pindahan');

        // PPDB Admin Routes
        Route::get('ppdb', [PPDBApplicationController::class, 'adminIndex'])->name('ppdb.index');
        Route::get('ppdb/batch-test-score', [PPDBApplicationController::class, 'showBatchTestScore'])->name('ppdb.batch-test-score');
        Route::post('ppdb/batch-test-score', [PPDBApplicationController::class, 'updateBatchTestScore'])->name('ppdb.update-batch-test-score');
        Route::get('ppdb/{application}', [PPDBApplicationController::class, 'adminShow'])->name('ppdb.show');
        Route::put('ppdb/{application}', [PPDBApplicationController::class, 'adminUpdate'])->name('ppdb.update');
        Route::patch('ppdb/{application}/test-score', [PPDBApplicationController::class, 'updateTestScore'])->name('ppdb.update-test-score');
        Route::get('ppdb/{application}/download/{documentType}', [PPDBApplicationController::class, 'downloadDocument'])->name('ppdb.download');

        // Transfer Student Admin Routes
        Route::get('transfer', [TransferStudentController::class, 'adminIndex'])->name('transfer.index');
        Route::get('transfer/{transferStudent}', [TransferStudentController::class, 'adminShow'])->name('transfer.show');
        Route::put('transfer/{transferStudent}', [TransferStudentController::class, 'adminUpdate'])->name('transfer.update');
        Route::get('transfer/{transferStudent}/download/{documentType}', [TransferStudentController::class, 'downloadDocument'])->name('transfer.download');
        Route::get('transfer/{transferStudent}/grade-conversion', [TransferStudentController::class, 'showGradeConversion'])->name('transfer.grade-conversion');
        Route::post('transfer/{transferStudent}/grade-conversion', [TransferStudentController::class, 'saveGradeConversion'])->name('transfer.save-grade-conversion');

        // Exam Schedule Routes (Admin only)
        Route::resource('exam-schedules', ExamScheduleController::class);

        // System Settings Routes
        Route::get('system-settings', [\App\Http\Controllers\SystemSettingController::class, 'index'])->name('system-settings.index');
        Route::post('system-settings', [\App\Http\Controllers\SystemSettingController::class, 'updateSchoolInfo'])->name('system-settings.index');
        Route::post('system-settings/toggle-ppdb', [\App\Http\Controllers\SystemSettingController::class, 'togglePPDB'])->name('system-settings.toggle-ppdb');
        Route::post('system-settings/toggle-transfer-student', [\App\Http\Controllers\SystemSettingController::class, 'toggleTransferStudent'])->name('system-settings.toggle-transfer-student');

        // Kepala Sekolah Account Management (single instance)
        Route::get('kepala-sekolah', [KepalaSekolahAccountController::class, 'index'])->name('kepsek.index');
        Route::get('kepala-sekolah/create', [KepalaSekolahAccountController::class, 'create'])->name('kepsek.create');
        Route::post('kepala-sekolah', [KepalaSekolahAccountController::class, 'store'])->name('kepsek.store');
        Route::get('kepala-sekolah/{user}/edit', [KepalaSekolahAccountController::class, 'edit'])->name('kepsek.edit');
        Route::put('kepala-sekolah/{user}', [KepalaSekolahAccountController::class, 'update'])->name('kepsek.update');
        Route::delete('kepala-sekolah/{user}', [KepalaSekolahAccountController::class, 'destroy'])->name('kepsek.destroy');
    });

    Route::get('/siswa/{siswa}', [StudentController::class, 'show'])->name('siswa.show');
    Route::get('/siswa/{siswa}/detail', [StudentController::class, 'detail'])->name('siswa.detail');
    Route::resource('kelas', ClassroomController::class)->parameters(['kelas' => 'kelas']);
    // Guru
    Route::get('/jadwal-guru', [GuruJadwalController::class, 'index'])->name('jadwal.guru');
    Route::get('/input-nilai', [GuruNilaiController::class, 'index'])->name('nilai.input');
    Route::post('/input-nilai', [GuruNilaiController::class, 'store'])->name('nilai.input.store');
    // Route::get('/input-nilai/import', [GuruNilaiController::class, 'showImportForm'])->name('nilai.import.show');
    // Route::post('/input-nilai/import', [GuruNilaiController::class, 'import'])->name('nilai.import.store');
    // Route::get('/input-nilai/template', [GuruNilaiController::class, 'downloadTemplate'])->name('nilai.import.template');
    // Route::get('guru/nilai/import/form', [GuruNilaiController::class, 'handleImport'])->name('nilai.import.handle');
    // Route::post('guru/nilai/import/store', [GuruNilaiController::class, 'storeImport'])->name('nilai.import.store');
    // Route::get('guru/nilai/import/template', [GuruNilaiController::class, 'downloadTemplate'])->name('nilai.import.template');
    // Route::get('/input-nilai/import', [GuruNilaiController::class, 'showImportForm'])->name('nilai.import.show');
    // Route::post('/input-nilai/import', [GuruNilaiController::class, 'import'])->name('nilai.import.store');
    // Route::get('/input-nilai/template', [GuruNilaiController::class, 'downloadTemplate'])->name('nilai.import.template');
    Route::get('guru/nilai/import', [GuruNilaiController::class, 'showImportForm'])->name('nilai.import.show');
    Route::post('guru/nilai/import', [GuruNilaiController::class, 'import'])->name('nilai.import.store');
    Route::get('guru/nilai/import/template', [GuruNilaiController::class, 'downloadTemplate'])->name('nilai.import.template');

    // Teacher Exam Schedule Routes
    Route::get('/jadwal-ujian-guru', [ExamScheduleController::class, 'teacherSchedule'])->name('guru.exam-schedule');

    // Teacher Attendance Routes
    Route::middleware('check.role:teacher')->prefix('teacher/attendance')->name('teacher.attendance.')->group(function () {
        Route::get('/', [TeacherAttendanceController::class, 'index'])->name('index');
        Route::get('/take/{schedule}', [TeacherAttendanceController::class, 'takeAttendance'])->name('take');
        Route::post('/store/{schedule}', [TeacherAttendanceController::class, 'storeAttendance'])->name('store');
        Route::get('/view/{schedule}', [TeacherAttendanceController::class, 'viewAttendance'])->name('view');
        Route::get('/edit/{schedule}/{date}', [TeacherAttendanceController::class, 'editAttendance'])->name('edit');
        Route::put('/update/{schedule}/{date}', [TeacherAttendanceController::class, 'updateAttendance'])->name('update');
    });

    // Teacher Extracurricular Grade Routes
    Route::middleware(['check.role:teacher'])->prefix('teacher/extracurricular-grade')->name('teacher.extracurricular-grade.')->group(function () {
        Route::get('/', [GuruExtracurricularGradeController::class, 'index'])->name('index');
        Route::middleware(['check.extracurricular.supervisor'])->group(function () {
            Route::get('/{extracurricular}', [GuruExtracurricularGradeController::class, 'show'])->name('show');
            Route::post('/{extracurricular}', [GuruExtracurricularGradeController::class, 'store'])->name('store');
            Route::get('/{extracurricular}/template', [GuruExtracurricularGradeController::class, 'downloadTemplate'])->name('template');
            Route::post('/{extracurricular}/import', [GuruExtracurricularGradeController::class, 'import'])->name('import');
        });
    });


    // Siswa
    Route::get('/profil-siswa', [\App\Http\Controllers\StudentController::class, 'profilSiswa'])->name('profil.siswa');
    Route::post('/profil-siswa', [\App\Http\Controllers\StudentController::class, 'updateProfilSiswa'])->name('profil.siswa.update');
    Route::post('/profil-siswa/password', [\App\Http\Controllers\StudentController::class, 'changePasswordSiswa'])->name('profil.siswa.password');
    Route::get('/jadwal-siswa', [\App\Http\Controllers\StudentController::class, 'jadwalMingguanSiswa'])->name('jadwal.siswa');
    Route::get('/nilai-siswa', [\App\Http\Controllers\StudentController::class, 'nilaiAkademikSiswa'])->name('nilai.siswa');
    Route::get('/raport-siswa', [SiswaRaportController::class, 'index'])->name('siswa.raport');
    Route::get('/raport-siswa/semua', [SiswaRaportController::class, 'allRaports'])->name('siswa.all-raports');
    Route::get('/jadwal-ujian-siswa', [ExamScheduleController::class, 'studentSchedule'])->name('siswa.exam-schedule');

    // Student extracurricular routes
    Route::get('/ekskul-siswa', [StudentExtracurricularController::class, 'index'])->name('siswa.extracurricular.index');
    Route::get('/ekskul-siswa/{extracurricular}', [StudentExtracurricularController::class, 'show'])->name('siswa.extracurricular.show');
    Route::post('/ekskul-siswa/{extracurricular}/enroll', [StudentExtracurricularController::class, 'enroll'])->name('siswa.extracurricular.enroll');
    Route::post('/ekskul-siswa/{extracurricular}/leave', [StudentExtracurricularController::class, 'leave'])->name('siswa.extracurricular.leave');
    // Admin
    Route::prefix('jadwal-admin')->name('jadwal.admin.')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::get('/create', [ScheduleController::class, 'create'])->name('create');
        Route::post('/', [ScheduleController::class, 'store'])->name('store');
        Route::get('/{jadwal}/edit', [ScheduleController::class, 'edit'])->name('edit');
        Route::put('/{jadwal}', [ScheduleController::class, 'update'])->name('update');
        Route::delete('/{jadwal}', [ScheduleController::class, 'destroy'])->name('destroy');
    });
    Route::get('/penugasan-guru', [PenugasanGuruController::class, 'index'])->name('penugasan.guru');
    Route::get('/pembagian-kelas', [ClassAssignmentController::class, 'index'])->name('pembagian.kelas');
    Route::post('/pembagian-kelas', [ClassAssignmentController::class, 'store'])->name('pembagian.kelas.store');
    Route::post('/pembagian-kelas/auto-place', [ClassAssignmentController::class, 'autoPlaceStudents'])->name('pembagian.kelas.auto-place');
    Route::get('/pengaturan-kkm', [SubjectSettingController::class, 'index'])->middleware('check.role:admin')->name('pengaturan.kkm');
    Route::post('/pengaturan-kkm', [SubjectSettingController::class, 'update'])->middleware('check.role:admin')->name('pengaturan.kkm.update');
    Route::post('/pengaturan-kkm/update-failed-subjects', [\App\Http\Controllers\SubjectSettingController::class, 'updateFailedSubjects'])->middleware('check.role:admin')->name('pengaturan.kkm.update-failed-subjects');
    Route::post('/pengaturan-kkm/update-semester-weights', [\App\Http\Controllers\SubjectSettingController::class, 'updateSemesterWeights'])->middleware('check.role:admin')->name('pengaturan.kkm.update-semester-weights');
    Route::get('/manajemen-pengguna', [UserController::class, 'index'])->name('manajemen.pengguna');
    // Guru Wali Kelas
    Route::view('/wali/dashboard', 'guru.wali-dashboard')->name('wali.guru.dashboard');
    Route::get('/wali/kelas', [\App\Http\Controllers\WaliKelasController::class, 'kelas'])->name('wali.kelas');
    Route::get('/wali/leger', [\App\Http\Controllers\WaliKelasController::class, 'leger'])->name('wali.leger');
    Route::get('/wali/finalisasi', [WaliKelasController::class, 'finalisasi'])->name('wali.finalisasi');
    Route::post('/wali/finalisasi', [WaliKelasController::class, 'storeFinalisasi'])->name('wali.finalisasi.store');
    Route::get('/wali/kenaikan', [WaliKelasController::class, 'kenaikan'])->name('wali.kenaikan');
    Route::post('/wali/kenaikan', [WaliKelasController::class, 'storeKenaikan'])->name('wali.kenaikan.store');
    // Route::get('/wali/pindahan', [\App\Http\Controllers\WaliKelasController::class, 'siswaPindahan'])->name('wali.pindahan');
    // Route::post('/wali/pindahan', [\App\Http\Controllers\WaliKelasController::class, 'storeKonversi'])->name('wali.pindahan.store');
    Route::get('/nilai-admin', [GradeController::class, 'index'])->name('nilai.admin');
    Route::post('/admin/user/{id}/reset-password', [UserController::class, 'resetPassword'])->name('admin.user.reset-password')->middleware('auth');
    Route::post('semesters/{semester}/set-active', [\App\Http\Controllers\SemesterController::class, 'setActive'])->name('semesters.set-active');

    // Kepala Sekolah Routes
    Route::middleware('check.role:kepala_sekolah')->prefix('kepala-sekolah')->name('kepala.')->group(function () {
        Route::get('/dashboard', [KepalaSekolahController::class, 'dashboard'])->name('dashboard');
        Route::get('/laporan-akademik', [KepalaSekolahController::class, 'laporanAkademik'])->name('laporan.akademik');
        // Laporan Keuangan dihilangkan untuk Kepala Sekolah
        Route::get('/pengaturan-sekolah', [KepalaSekolahController::class, 'pengaturanSekolah'])->name('pengaturan.sekolah');

        // Kenaikan Kelas & Kelulusan (Dipindahkan dari Admin)
        Route::get('/kenaikan-kelas', [KepalaSekolahController::class, 'kenaikanKelas'])->name('kenaikan-kelas');
        Route::post('/kenaikan-kelas/process', [KepalaSekolahController::class, 'processKenaikanKelas'])->name('kenaikan-kelas.process');

        // Monitoring PPDB
        Route::get('/monitoring/ppdb', [KepalaSekolahController::class, 'monitoringPPDB'])->name('monitoring.ppdb');

        // Monitoring Siswa Pindahan
        Route::get('/monitoring/siswa-pindahan', [KepalaSekolahController::class, 'monitoringSiswaPindahan'])->name('monitoring.siswa-pindahan');

        // Monitoring Jadwal Ujian
        Route::get('/monitoring/jadwal-ujian', [KepalaSekolahController::class, 'monitoringJadwalUjian'])->name('monitoring.jadwal-ujian');

        // Pengaturan KKM - dipindahkan ke Admin, tidak tersedia di kepala sekolah

        // Monitoring Guru
        Route::get('/monitoring/guru', [KepalaSekolahController::class, 'monitoringGuru'])->name('monitoring.guru');

        // Monitoring Kelas
        Route::get('/monitoring/kelas', [KepalaSekolahController::class, 'monitoringKelas'])->name('monitoring.kelas');

        // Monitoring Nilai
        Route::get('/monitoring/nilai', [KepalaSekolahController::class, 'monitoringNilai'])->name('monitoring.nilai');

        // Pengaturan Akun (Profil + Password) - 1 menu
        Route::get('/pengaturan-akun', [KepalaSekolahController::class, 'accountSettings'])->name('pengaturan.akun');
        Route::post('/pengaturan-akun', [KepalaSekolahController::class, 'updateAccount'])->name('pengaturan.akun.update');
        Route::post('/pengaturan-akun/password', [KepalaSekolahController::class, 'updatePassword'])->name('pengaturan.akun.password');
    });

    // Wali Murid Routes
    Route::middleware('check.role:wali_murid')->prefix('wali-murid')->name('wali.')->group(function () {
        Route::get('/dashboard', [WaliMuridController::class, 'dashboard'])->name('dashboard');
        Route::get('/nilai-anak', [WaliMuridController::class, 'nilaiAnak'])->name('nilai.anak');
        Route::get('/raport-anak', [WaliMuridController::class, 'raportAnak'])->name('raport.anak');
        Route::get('/jadwal-anak', [WaliMuridController::class, 'jadwalAnak'])->name('jadwal.anak');
        Route::get('/absensi-anak', [WaliMuridController::class, 'absensiAnak'])->name('absensi.anak');
        Route::get('/jadwal-ujian-anak', [ExamScheduleController::class, 'parentSchedule'])->name('exam-schedule');
    });
});

Route::prefix('pengaturan-mapel')->name('pengaturan.mapel.')->group(function () {
    Route::get('/', [SubjectSettingController::class, 'index'])->name('index');
    Route::get('/create', [SubjectSettingController::class, 'create'])->name('create');
    Route::post('/', [SubjectSettingController::class, 'store'])->name('store');
    Route::get('/{setting}/edit', [SubjectSettingController::class, 'edit'])->name('edit');
    Route::put('/{setting}', [SubjectSettingController::class, 'update'])->name('update');
    Route::delete('/{setting}', [SubjectSettingController::class, 'destroy'])->name('destroy');
});

// Wali Kelas Routes
Route::middleware('is.homeroom.teacher')->prefix('wali-kelas')->name('wali.')->group(function () {
    Route::get('/kelas', [WaliKelasController::class, 'kelas'])->name('kelas');
    Route::get('/leger', [WaliKelasController::class, 'leger'])->name('leger');
    Route::get('/raport/{student}', [WaliKelasController::class, 'showRaport'])->name('raport.show');
    Route::get('/absensi', [WaliKelasController::class, 'absensi'])->name('absensi');
    Route::post('/absensi', [WaliKelasController::class, 'storeAbsensi'])->name('absensi.store');
    Route::get('/catatan', [WaliKelasController::class, 'catatan'])->name('catatan');
    Route::post('/catatan', [WaliKelasController::class, 'storeCatatan'])->name('catatan.store');
    Route::get('/finalisasi', [WaliKelasController::class, 'finalisasi'])->name('finalisasi');
    Route::post('/finalisasi', [WaliKelasController::class, 'storeFinalisasi'])->name('finalisasi.store');
    Route::get('/detail-nilai/{id}', [WaliKelasController::class, 'detailNilaiSiswa'])->name('detail-nilai');
});

Route::get('/home', function () {
    return redirect('/dashboard');
})->name('home');

require __DIR__ . '/auth.php';
require __DIR__ . '/settings.php';

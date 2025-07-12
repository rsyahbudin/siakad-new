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
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\Admin\NilaiSiswaController;

Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
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
    });

    // Admin-only Promotion routes
    Route::middleware('check.role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('promotions', [PromotionController::class, 'index'])->name('promotions.index');
        Route::post('promotions/process', [PromotionController::class, 'process'])->name('promotions.process');
        Route::resource('nilai-siswa', NilaiSiswaController::class)->only(['show']);
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


    // Siswa
    Route::get('/profil-siswa', [\App\Http\Controllers\StudentController::class, 'profilSiswa'])->name('profil.siswa');
    Route::post('/profil-siswa', [\App\Http\Controllers\StudentController::class, 'updateProfilSiswa'])->name('profil.siswa.update');
    Route::post('/profil-siswa/password', [\App\Http\Controllers\StudentController::class, 'changePasswordSiswa'])->name('profil.siswa.password');
    Route::get('/jadwal-siswa', [\App\Http\Controllers\StudentController::class, 'jadwalMingguanSiswa'])->name('jadwal.siswa');
    Route::get('/nilai-siswa', [\App\Http\Controllers\StudentController::class, 'nilaiAkademikSiswa'])->name('nilai.siswa');
    Route::get('/raport-siswa', [SiswaRaportController::class, 'index'])->name('siswa.raport');
    Route::get('/raport-siswa/semua', [SiswaRaportController::class, 'allRaports'])->name('siswa.all-raports');
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
    Route::get('/pengaturan-kkm', [SubjectSettingController::class, 'index'])->name('pengaturan.kkm');
    Route::post('/pengaturan-kkm', [SubjectSettingController::class, 'update'])->name('pengaturan.kkm.update');
    Route::post('/pengaturan-kkm/update-failed-subjects', [\App\Http\Controllers\SubjectSettingController::class, 'updateFailedSubjects'])->name('pengaturan.kkm.update-failed-subjects');
    Route::post('/pengaturan-kkm/update-semester-weights', [\App\Http\Controllers\SubjectSettingController::class, 'updateSemesterWeights'])->name('pengaturan.kkm.update-semester-weights');
    Route::get('/manajemen-pengguna', [UserController::class, 'index'])->name('manajemen.pengguna');
    // Guru Wali Kelas
    Route::view('/wali/dashboard', 'guru.wali-dashboard')->name('wali.dashboard');
    Route::get('/wali/kelas', [\App\Http\Controllers\WaliKelasController::class, 'kelas'])->name('wali.kelas');
    Route::get('/wali/leger', [\App\Http\Controllers\WaliKelasController::class, 'leger'])->name('wali.leger');
    Route::get('/wali/finalisasi', [WaliKelasController::class, 'finalisasi'])->name('wali.finalisasi');
    Route::post('/wali/finalisasi', [WaliKelasController::class, 'storeFinalisasi'])->name('wali.finalisasi.store');
    Route::get('/wali/kenaikan', [WaliKelasController::class, 'kenaikan'])->name('wali.kenaikan');
    Route::post('/wali/kenaikan', [WaliKelasController::class, 'storeKenaikan'])->name('wali.kenaikan.store');
    Route::get('/wali/pindahan', [\App\Http\Controllers\WaliKelasController::class, 'siswaPindahan'])->name('wali.pindahan');
    Route::post('/wali/pindahan', [\App\Http\Controllers\WaliKelasController::class, 'storeKonversi'])->name('wali.pindahan.store');
    Route::get('/nilai-admin', [GradeController::class, 'index'])->name('nilai.admin');
    Route::post('/admin/user/{id}/reset-password', [UserController::class, 'resetPassword'])->name('admin.user.reset-password')->middleware('auth');
    Route::post('semesters/{semester}/set-active', [\App\Http\Controllers\SemesterController::class, 'setActive'])->name('semesters.set-active');
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

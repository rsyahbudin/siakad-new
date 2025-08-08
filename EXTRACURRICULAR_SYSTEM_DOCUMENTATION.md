# Sistem Ekstrakurikuler (Ekskul)

## Overview

Sistem ekstrakurikuler telah ditambahkan ke SIAKAD untuk mengelola kegiatan ekstrakurikuler siswa. Sistem ini memungkinkan admin untuk mengelola ekskul, siswa untuk mendaftar dan melihat ekskul mereka, serta tracking kehadiran dan prestasi siswa dalam ekskul.

## Fitur Utama

### 1. Manajemen Ekskul (Admin)

- **CRUD Ekskul**: Create, Read, Update, Delete ekskul
- **Pembina Ekskul**: Menetapkan guru sebagai pembina ekskul
- **Kapasitas**: Mengatur maksimal peserta ekskul
- **Jadwal**: Mengatur hari, waktu, dan lokasi ekskul
- **Kategori**: Mengelompokkan ekskul berdasarkan kategori

### 2. Pendaftaran Siswa (Siswa)

- **Lihat Ekskul Tersedia**: Melihat daftar ekskul yang bisa diikuti
- **Daftar Ekskul**: Mendaftar ke ekskul yang diinginkan
- **Keluar Ekskul**: Keluar dari ekskul yang sudah diikuti
- **Status Keanggotaan**: Melihat status keanggotaan dalam ekskul

### 3. Tracking Keanggotaan

- **Status**: Aktif, Tidak Aktif, Lulus
- **Posisi**: Anggota, Ketua, Wakil Ketua, Sekretaris, Bendahara
- **Prestasi**: Mencatat prestasi yang diraih
- **Catatan**: Catatan khusus untuk setiap siswa
- **Tanggal**: Tanggal bergabung dan keluar

## Struktur Database

### 1. Tabel `extracurriculars`

```sql
CREATE TABLE extracurriculars (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    category VARCHAR(255) DEFAULT 'Umum',
    day VARCHAR(255) NULL,
    time_start TIME NULL,
    time_end TIME NULL,
    location VARCHAR(255) NULL,
    teacher_id BIGINT UNSIGNED NULL,
    max_participants INT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
);
```

### 2. Tabel `student_extracurriculars` (Pivot)

```sql
CREATE TABLE student_extracurriculars (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    extracurricular_id BIGINT UNSIGNED NOT NULL,
    academic_year_id BIGINT UNSIGNED NOT NULL,
    status ENUM('Aktif', 'Tidak Aktif', 'Lulus') DEFAULT 'Aktif',
    position ENUM('Anggota', 'Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara') DEFAULT 'Anggota',
    achievements TEXT NULL,
    notes TEXT NULL,
    join_date DATE NULL,
    leave_date DATE NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (extracurricular_id) REFERENCES extracurriculars(id) ON DELETE CASCADE,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
    UNIQUE KEY student_extracurricular_year_unique (student_id, extracurricular_id, academic_year_id)
);
```

## Model dan Relasi

### 1. Model Extracurricular

```php
class Extracurricular extends Model
{
    protected $fillable = [
        'name', 'description', 'category', 'day', 'time_start',
        'time_end', 'location', 'teacher_id', 'max_participants', 'is_active'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_extracurriculars')
                    ->withPivot(['status', 'position', 'achievements', 'notes', 'join_date', 'leave_date', 'academic_year_id'])
                    ->withTimestamps();
    }

    public function getActiveStudentsCount()
    {
        return $this->students()->wherePivot('status', 'Aktif')->count();
    }

    public function isFull()
    {
        if (!$this->max_participants) return false;
        return $this->getActiveStudentsCount() >= $this->max_participants;
    }
}
```

### 2. Model Student (Update)

```php
public function extracurriculars()
{
    return $this->belongsToMany(Extracurricular::class, 'student_extracurriculars')
                ->withPivot(['status', 'position', 'achievements', 'notes', 'join_date', 'leave_date', 'academic_year_id'])
                ->withTimestamps();
}

public function getActiveExtracurriculars($academicYearId = null)
{
    $query = $this->extracurriculars()->wherePivot('status', 'Aktif');
    if ($academicYearId) {
        $query->wherePivot('academic_year_id', $academicYearId);
    }
    return $query->get();
}
```

### 3. Model Teacher (Update)

```php
public function extracurriculars()
{
    return $this->hasMany(Extracurricular::class);
}
```

## Controller

### 1. ExtracurricularController (Admin)

- **index()**: Menampilkan daftar semua ekskul
- **create()**: Form tambah ekskul baru
- **store()**: Menyimpan ekskul baru
- **show()**: Detail ekskul dengan daftar siswa
- **edit()**: Form edit ekskul
- **update()**: Update data ekskul
- **destroy()**: Hapus ekskul
- **addStudent()**: Tambah siswa ke ekskul
- **removeStudent()**: Keluarkan siswa dari ekskul
- **updateStudentStatus()**: Update status siswa dalam ekskul

### 2. StudentExtracurricularController (Siswa)

- **index()**: Menampilkan ekskul tersedia dan ekskul yang diikuti
- **show()**: Detail ekskul untuk siswa
- **enroll()**: Daftar ke ekskul
- **leave()**: Keluar dari ekskul

## Routes

### 1. Admin Routes

```php
Route::middleware('check.role:admin')->group(function () {
    Route::resource('extracurricular', ExtracurricularController::class);
    Route::post('extracurricular/{extracurricular}/add-student', [ExtracurricularController::class, 'addStudent']);
    Route::post('extracurricular/{extracurricular}/remove-student', [ExtracurricularController::class, 'removeStudent']);
    Route::put('extracurricular/{extracurricular}/update-student-status', [ExtracurricularController::class, 'updateStudentStatus']);
});
```

### 2. Student Routes

```php
Route::get('/ekskul-siswa', [StudentExtracurricularController::class, 'index'])->name('siswa.extracurricular.index');
Route::get('/ekskul-siswa/{extracurricular}', [StudentExtracurricularController::class, 'show'])->name('siswa.extracurricular.show');
Route::post('/ekskul-siswa/{extracurricular}/enroll', [StudentExtracurricularController::class, 'enroll'])->name('siswa.extracurricular.enroll');
Route::post('/ekskul-siswa/{extracurricular}/leave', [StudentExtracurricularController::class, 'leave'])->name('siswa.extracurricular.leave');
```

## Data Seeder

### ExtracurricularSeeder

Membuat data ekskul contoh:

- **Pramuka**: Umum, Sabtu 07:00-10:00, Lapangan Sekolah
- **PMR**: Kesehatan, Jumat 15:00-17:00, Ruang PMR
- **Rohis**: Keagamaan, Selasa 15:30-17:30, Masjid Sekolah
- **English Club**: Bahasa, Rabu 15:00-16:30, Ruang Bahasa
- **Basket**: Olahraga, Senin 16:00-18:00, Lapangan Basket
- **Seni Tari**: Seni, Kamis 15:00-17:00, Aula Sekolah
- **Komputer Club**: Teknologi, Jumat 14:00-16:00, Lab Komputer
- **Jurnalistik**: Akademik, Selasa 15:00-16:30, Ruang Jurnalistik

## Validasi

### 1. Extracurricular Validation

```php
$request->validate([
    'name' => 'required|string|max:255',
    'description' => 'nullable|string',
    'category' => 'required|string|max:255',
    'day' => 'nullable|string|max:255',
    'time_start' => 'nullable|date_format:H:i',
    'time_end' => 'nullable|date_format:H:i|after:time_start',
    'location' => 'nullable|string|max:255',
    'teacher_id' => 'nullable|exists:teachers,id',
    'max_participants' => 'nullable|integer|min:1',
    'is_active' => 'boolean',
]);
```

### 2. Student Enrollment Validation

```php
$request->validate([
    'student_id' => 'required|exists:students,id',
    'academic_year_id' => 'required|exists:academic_years,id',
    'position' => 'required|in:Anggota,Ketua,Wakil Ketua,Sekretaris,Bendahara',
    'notes' => 'nullable|string',
]);
```

## Business Logic

### 1. Kapasitas Ekskul

- **Tidak Terbatas**: Jika `max_participants` null
- **Terbatas**: Jika `max_participants` diisi, cek jumlah siswa aktif
- **Penuh**: Jika jumlah siswa aktif >= max_participants

### 2. Keanggotaan Siswa

- **Satu Tahun Ajaran**: Siswa hanya bisa terdaftar sekali per tahun ajaran per ekskul
- **Status Tracking**: Aktif, Tidak Aktif, Lulus
- **Posisi**: Anggota, Ketua, Wakil Ketua, Sekretaris, Bendahara

### 3. Prestasi dan Catatan

- **Prestasi**: Mencatat prestasi yang diraih siswa
- **Catatan**: Catatan khusus untuk setiap siswa
- **Tanggal**: Tanggal bergabung dan keluar

## UI/UX Features

### 1. Admin Interface

- **Daftar Ekskul**: Tabel dengan informasi lengkap
- **Form Tambah/Edit**: Form yang user-friendly
- **Detail Ekskul**: Informasi lengkap ekskul dan daftar siswa
- **Manajemen Siswa**: Tambah, hapus, update status siswa

### 2. Student Interface

- **Ekskul Tersedia**: Daftar ekskul yang bisa diikuti
- **Ekskul Saya**: Ekskul yang sedang diikuti
- **Detail Ekskul**: Informasi lengkap ekskul
- **Aksi**: Daftar dan keluar dari ekskul

## Security Features

### 1. Role-Based Access

- **Admin**: Full access untuk manajemen ekskul
- **Student**: Hanya bisa melihat dan mendaftar ekskul
- **Teacher**: Bisa melihat ekskul yang dibina

### 2. Data Validation

- **Input Validation**: Validasi semua input user
- **Business Rules**: Validasi aturan bisnis (kapasitas, duplikasi)
- **SQL Injection Protection**: Menggunakan Eloquent ORM

### 3. Authorization

- **Middleware**: Check role untuk setiap route
- **Ownership**: Siswa hanya bisa akses data mereka sendiri

## Performance Optimization

### 1. Database Optimization

- **Indexes**: Index pada foreign keys dan unique constraints
- **Eager Loading**: Load relasi yang diperlukan
- **Query Optimization**: Optimasi query untuk performa

### 2. Caching

- **Route Caching**: Cache routes untuk performa
- **Config Caching**: Cache konfigurasi
- **View Caching**: Cache view jika diperlukan

## Testing Strategy

### 1. Unit Testing

- **Model Testing**: Test model methods dan relasi
- **Controller Testing**: Test controller methods
- **Validation Testing**: Test validasi input

### 2. Feature Testing

- **Admin Flow**: Test alur admin dari CRUD ekskul
- **Student Flow**: Test alur siswa mendaftar dan keluar ekskul
- **Business Logic**: Test aturan bisnis (kapasitas, duplikasi)

### 3. Integration Testing

- **Database Integration**: Test integrasi dengan database
- **Role Integration**: Test integrasi dengan sistem role
- **Academic Year Integration**: Test integrasi dengan tahun ajaran

## Maintenance

### 1. Regular Tasks

- **Data Cleanup**: Bersihkan data ekskul yang tidak aktif
- **Performance Monitoring**: Monitor performa query
- **User Feedback**: Kumpulkan feedback dari pengguna

### 2. Updates

- **Feature Updates**: Update fitur sesuai kebutuhan
- **Security Updates**: Update keamanan secara berkala
- **Database Updates**: Update struktur database jika diperlukan

## Future Enhancements

### 1. Advanced Features

- **Attendance Tracking**: Tracking kehadiran siswa dalam ekskul
- **Achievement System**: Sistem prestasi dan sertifikat
- **Event Management**: Manajemen event ekskul
- **Photo Gallery**: Galeri foto kegiatan ekskul

### 2. Integration

- **Raport Integration**: Integrasi ekskul ke raport siswa
- **Notification System**: Notifikasi untuk kegiatan ekskul
- **Calendar Integration**: Integrasi dengan kalender sekolah
- **Mobile App**: Aplikasi mobile untuk akses ekskul

### 3. Analytics

- **Participation Analytics**: Analisis partisipasi siswa
- **Performance Analytics**: Analisis performa ekskul
- **Trend Analysis**: Analisis tren ekskul yang populer

## Deployment Checklist

### 1. Database

- [ ] Run migrations
- [ ] Run seeders
- [ ] Test database connections
- [ ] Verify foreign key constraints

### 2. Application

- [ ] Update routes
- [ ] Test controllers
- [ ] Verify middleware
- [ ] Test role-based access

### 3. Testing

- [ ] Test admin functionality
- [ ] Test student functionality
- [ ] Test business logic
- [ ] Test edge cases

## Rollback Plan

Jika diperlukan rollback:

1. **Database**: Rollback migrations
2. **Code**: Restore controller dan model ke versi sebelumnya
3. **Routes**: Remove extracurricular routes
4. **Testing**: Test semua fitur yang terkait

## Conclusion

Sistem ekstrakurikuler telah berhasil diimplementasikan dengan fitur lengkap:

- **Manajemen Ekskul**: CRUD lengkap untuk admin
- **Pendaftaran Siswa**: Interface yang user-friendly untuk siswa
- **Tracking Keanggotaan**: Sistem tracking yang komprehensif
- **Security**: Keamanan yang baik dengan role-based access
- **Performance**: Optimasi performa database dan aplikasi
- **Maintainability**: Kode yang mudah di-maintain dan dikembangkan

Sistem ini memberikan solusi lengkap untuk manajemen ekstrakurikuler di sekolah dengan fitur yang sesuai kebutuhan administrasi dan siswa.

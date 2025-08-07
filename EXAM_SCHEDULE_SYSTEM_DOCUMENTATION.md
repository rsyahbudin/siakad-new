# Sistem Jadwal Ujian SMA

## Deskripsi

Sistem jadwal ujian SMA adalah fitur yang memungkinkan pengelolaan jadwal ujian untuk siswa SMA dengan ketentuan khusus sesuai dengan struktur pendidikan SMA.

## Fitur Utama

### 1. Manajemen Jadwal Ujian (Admin)

- **CRUD Jadwal Ujian**: Admin dapat membuat, membaca, memperbarui, dan menghapus jadwal ujian
- **Filter Jadwal**: Filter berdasarkan tahun ajaran, semester, jenis ujian, dan kelas
- **Validasi Jadwal**: Mencegah duplikasi jadwal dan konflik pengawas
- **Pengaturan Pengawas**: Setiap ruang ujian memiliki 1 pengawas/guru

### 2. Tampilan Jadwal Ujian (Siswa)

- **Jadwal Pribadi**: Siswa hanya dapat melihat jadwal ujian kelasnya
- **Pemisahan UTS/UAS**: Jadwal dipisahkan berdasarkan jenis ujian
- **Informasi Lengkap**: Menampilkan mata pelajaran, tanggal, waktu, ruangan, dan pengawas

### 3. Tampilan Jadwal Pengawasan (Guru)

- **Jadwal Pengawasan**: Guru hanya dapat melihat jadwal ujian yang dia awasi
- **Informasi Ruangan**: Detail ruangan dan kelas yang diawasi
- **Panduan Pengawasan**: Informasi penting untuk pengawas ujian

### 4. Tampilan Jadwal Anak (Wali Murid)

- **Jadwal Anak**: Wali murid dapat melihat jadwal ujian anaknya
- **Informasi Orang Tua**: Panduan untuk mendukung anak saat ujian

## Ketentuan Sistem

### 1. Jenis Mata Pelajaran

- **Mata Pelajaran Umum**: Diikuti oleh semua siswa dari semua jurusan
    - Bahasa Indonesia
    - Matematika Wajib
    - Bahasa Inggris
    - PPKn
- **Mata Pelajaran Jurusan**: Diikuti oleh siswa sesuai jurusannya
    - IPA: Fisika, Kimia, Biologi, Matematika Peminatan
    - IPS: Ekonomi, Geografi, Sosiologi, Sejarah

### 2. Struktur Ujian

- **UTS (Ujian Tengah Semester)**: 1 kali per semester
- **UAS (Ujian Akhir Semester)**: 1 kali per semester
- **Waktu Serentak**: Semua siswa kelas X, XI, dan XII mengikuti ujian pada waktu yang sama
- **Ruangan Terpisah**: Setiap kelas memiliki ruangan sendiri

### 3. Pengawasan

- **1 Pengawas per Ruangan**: Setiap ruang ujian diawasi oleh 1 guru
- **Validasi Konflik**: Sistem mencegah guru mengawasi 2 ujian pada waktu yang sama
- **Panduan Pengawasan**: Informasi khusus untuk pengawas ujian

## Struktur Database

### Tabel: exam_schedules

```sql
CREATE TABLE exam_schedules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    academic_year_id BIGINT UNSIGNED NOT NULL,
    semester_id BIGINT UNSIGNED NOT NULL,
    subject_id BIGINT UNSIGNED NOT NULL,
    classroom_id BIGINT UNSIGNED NOT NULL,
    supervisor_id BIGINT UNSIGNED NOT NULL,
    exam_type ENUM('uts', 'uas') NOT NULL,
    exam_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    is_general_subject BOOLEAN DEFAULT FALSE,
    major_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
    FOREIGN KEY (semester_id) REFERENCES semesters(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE CASCADE,
    FOREIGN KEY (supervisor_id) REFERENCES teachers(id) ON DELETE CASCADE,
    FOREIGN KEY (major_id) REFERENCES majors(id) ON DELETE SET NULL
);
```

## Model dan Controller

### Model: ExamSchedule

- **Relasi**: AcademicYear, Semester, Subject, Classroom, Teacher, Major
- **Scope**: UTS, UAS, GeneralSubjects, MajorSubjects, ByMajor, ByClassroom, ActiveSemester
- **Validasi**: Mencegah duplikasi jadwal dan konflik pengawas

### Controller: ExamScheduleController

- **Admin Routes**: CRUD lengkap untuk jadwal ujian
- **Student Routes**: Tampilan jadwal ujian siswa
- **Teacher Routes**: Tampilan jadwal pengawasan guru
- **Parent Routes**: Tampilan jadwal ujian anak

## Routes

### Admin Routes

```php
Route::resource('exam-schedules', ExamScheduleController::class);
```

### Student Routes

```php
Route::get('/jadwal-ujian-siswa', [ExamScheduleController::class, 'studentSchedule'])
    ->name('siswa.exam-schedule');
```

### Teacher Routes

```php
Route::get('/jadwal-ujian-guru', [ExamScheduleController::class, 'teacherSchedule'])
    ->name('guru.exam-schedule');
```

### Parent Routes

```php
Route::get('/jadwal-ujian-anak', [ExamScheduleController::class, 'parentSchedule'])
    ->name('exam-schedule');
```

## Views

### Admin Views

- `resources/views/admin/exam-schedule/index.blade.php` - Daftar jadwal ujian
- `resources/views/admin/exam-schedule/create.blade.php` - Form tambah jadwal
- `resources/views/admin/exam-schedule/edit.blade.php` - Form edit jadwal
- `resources/views/admin/exam-schedule/show.blade.php` - Detail jadwal

### Student Views

- `resources/views/siswa/exam-schedule/index.blade.php` - Jadwal ujian siswa

### Teacher Views

- `resources/views/guru/exam-schedule/index.blade.php` - Jadwal pengawasan guru

### Parent Views

- `resources/views/wali-murid/exam-schedule/index.blade.php` - Jadwal ujian anak

## Validasi

### 1. Validasi Duplikasi Jadwal

- Mencegah jadwal UTS/UAS yang sama untuk mata pelajaran, kelas, dan semester yang sama
- Validasi dilakukan saat create dan update

### 2. Validasi Konflik Pengawas

- Mencegah guru mengawasi 2 ujian pada waktu yang sama
- Validasi berdasarkan tanggal dan waktu ujian

### 3. Validasi Data

- Semua field required kecuali major_id (opsional untuk mapel umum)
- Format waktu dan tanggal yang valid
- Relasi foreign key yang valid

## Seeder

### ExamScheduleSeeder

- Membuat data contoh jadwal ujian
- Membagi jadwal berdasarkan mapel umum dan jurusan
- Mengatur waktu ujian yang bervariasi
- Menghindari konflik jadwal

## Keamanan

### 1. Role-Based Access

- **Admin**: Akses penuh untuk CRUD jadwal ujian
- **Siswa**: Hanya dapat melihat jadwal kelasnya
- **Guru**: Hanya dapat melihat jadwal yang dia awasi
- **Wali Murid**: Hanya dapat melihat jadwal anaknya

### 2. Validasi Input

- Sanitasi input untuk mencegah XSS
- Validasi format data
- Pengecekan relasi foreign key

## Penggunaan

### 1. Admin

1. Login sebagai admin
2. Akses menu "Jadwal Ujian"
3. Gunakan fitur CRUD untuk mengelola jadwal
4. Filter jadwal sesuai kebutuhan

### 2. Siswa

1. Login sebagai siswa
2. Akses menu "Jadwal Ujian"
3. Lihat jadwal UTS dan UAS kelasnya

### 3. Guru

1. Login sebagai guru
2. Akses menu "Jadwal Pengawasan"
3. Lihat jadwal ujian yang dia awasi

### 4. Wali Murid

1. Login sebagai wali murid
2. Akses menu "Jadwal Ujian Anak"
3. Lihat jadwal ujian anaknya

## Maintenance

### 1. Backup Data

- Backup tabel exam_schedules secara berkala
- Backup relasi dengan tabel lain

### 2. Monitoring

- Monitor performa query jadwal ujian
- Periksa log error secara berkala

### 3. Update

- Update jadwal sesuai kalender akademik
- Hapus jadwal yang sudah lewat

## Troubleshooting

### 1. Error Duplikasi Jadwal

- Periksa constraint unique pada database
- Pastikan tidak ada jadwal yang sama

### 2. Error Konflik Pengawas

- Periksa jadwal guru yang bersangkutan
- Atur ulang pengawas jika diperlukan

### 3. Error Relasi

- Periksa data di tabel terkait
- Pastikan foreign key valid

## Kesimpulan

Sistem jadwal ujian SMA telah berhasil diimplementasikan dengan fitur lengkap sesuai ketentuan yang diminta. Sistem ini mendukung:

1. ✅ CRUD jadwal ujian untuk admin
2. ✅ Tampilan jadwal untuk siswa, guru, dan wali murid
3. ✅ Validasi untuk mencegah duplikasi dan konflik
4. ✅ Pemisahan mapel umum dan jurusan
5. ✅ Pengaturan pengawas per ruangan
6. ✅ Pembatasan akses berdasarkan role
7. ✅ Dokumentasi lengkap

Sistem siap digunakan untuk mengelola jadwal ujian SMA dengan efektif dan aman.

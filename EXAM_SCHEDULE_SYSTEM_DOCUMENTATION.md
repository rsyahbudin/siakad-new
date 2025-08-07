# Exam Schedule System Documentation

## Overview

Sistem jadwal ujian SMA yang memungkinkan admin untuk mengelola jadwal ujian UTS dan UAS untuk semua siswa. Sistem ini mendukung mata pelajaran umum dan mata pelajaran jurusan spesifik.

## Fitur Utama

### 1. Manajemen Jadwal Ujian

- **Bulk Creation**: Membuat jadwal ujian untuk semua kelas berdasarkan angkatan dan jurusan sekaligus
- **Role-based Access**: Akses berbeda untuk admin, guru, siswa, dan wali murid
- **Active Semester Restriction**: Hanya semester aktif yang dapat dimanipulasi
- **Major-specific Subjects**: Validasi mata pelajaran sesuai jurusan

### 2. Filter dan Pencarian

- **Advanced Filtering**: Filter berdasarkan tahun ajaran, semester, jenis ujian, kelas, mata pelajaran, jurusan, dan pengawas
- **Search Functionality**: Pencarian berdasarkan nama mata pelajaran, kelas, atau pengawas
- **Active Filter Display**: Menampilkan filter yang sedang aktif dengan badge berwarna
- **Quick Access**: Tombol "Tampilkan Semester Aktif" untuk filter cepat

### 3. Validasi dan Keamanan

- **Duplicate Prevention**: Mencegah pembuatan jadwal ganda
- **Supervisor Conflict Check**: Memastikan pengawas tidak bentrok jadwal
- **Major Validation**: Memastikan mata pelajaran jurusan hanya untuk jurusan yang sesuai
- **Active Semester Enforcement**: Hanya semester aktif yang dapat dimodifikasi

## Database Schema

### ExamSchedule Model

```php
protected $fillable = [
    'academic_year_id',
    'semester_id',
    'subject_id',
    'classroom_id',
    'supervisor_id',
    'exam_type', // 'uts' or 'uas'
    'exam_date',
    'start_time',
    'end_time',
    'is_general_subject',
    'major_id'
];
```

### Relationships

- `belongsTo(AcademicYear::class)`
- `belongsTo(Semester::class)`
- `belongsTo(Subject::class)`
- `belongsTo(Classroom::class)`
- `belongsTo(Teacher::class, 'supervisor_id')`
- `belongsTo(Major::class)`

## API Endpoints

### Admin Routes

- `GET /admin/exam-schedules` - Daftar jadwal ujian dengan filter
- `GET /admin/exam-schedules/create` - Form pembuatan jadwal ujian
- `POST /admin/exam-schedules` - Simpan jadwal ujian (bulk creation)
- `GET /admin/exam-schedules/{id}` - Detail jadwal ujian
- `GET /admin/exam-schedules/{id}/edit` - Form edit jadwal ujian
- `PUT /admin/exam-schedules/{id}` - Update jadwal ujian
- `DELETE /admin/exam-schedules/{id}` - Hapus jadwal ujian

### User-specific Routes

- `GET /siswa/exam-schedules` - Jadwal ujian untuk siswa
- `GET /guru/exam-schedules` - Jadwal ujian untuk guru
- `GET /wali-murid/exam-schedules` - Jadwal ujian untuk wali murid

## Fitur Baru (Update Terbaru)

### 1. Perbaikan Filter Semester

- **Masalah**: Filter semester menampilkan duplikasi
- **Solusi**: Hanya menampilkan semester aktif di dropdown filter
- **Implementasi**: `Semester::where('is_active', true)->orderBy('name')->get()`

### 2. Perbaikan Database Column Issue

- **Masalah**: Error `Unknown column 'grade' in 'field list'` karena tabel `classrooms` menggunakan kolom `grade_level` bukan `grade`
- **Solusi**:
    - Menggunakan `grade_level` (integer) untuk query database
    - Menambahkan konversi `grade_level` ke format string (X, XI, XII)
    - Menambahkan accessor `getGradeAttribute()` di model Classroom
- **Implementasi**:

    ```php
    // Konversi grade_level ke string
    $grades = Classroom::distinct()->pluck('grade_level')->sort()->values()->map(function($gradeLevel) {
        return $gradeLevel == 10 ? 'X' : ($gradeLevel == 11 ? 'XI' : 'XII');
    });

    // Konversi string ke grade_level untuk query
    $gradeLevel = $request->grade === 'X' ? 10 : ($request->grade === 'XI' ? 11 : 12);
    $classrooms = Classroom::where('grade_level', $gradeLevel);
    ```

### 3. Bulk Creation System

- **Fitur**: Membuat jadwal ujian untuk semua kelas berdasarkan angkatan dan jurusan
- **Input**: Grade (X, XI, XII) dan Major (IPA, IPS, atau Umum)
- **Output**: Otomatis membuat jadwal untuk semua kelas yang sesuai
- **Validasi**:
    - Memastikan kelas tersedia untuk kombinasi angkatan dan jurusan
    - Mencegah duplikasi jadwal
    - Validasi konflik pengawas

### 4. Major-specific Subject Validation

- **Fitur**: Memastikan mata pelajaran jurusan hanya untuk jurusan yang sesuai
- **Contoh**: Mata pelajaran Biologi hanya untuk jurusan IPA
- **Implementasi**: Validasi di controller dan JavaScript di form

### 5. Enhanced Filter System

- **Search**: Pencarian berdasarkan mata pelajaran, kelas, atau pengawas
- **Multiple Filters**: 8 filter berbeda untuk pencarian yang tepat
- **Active Filter Display**: Menampilkan filter yang sedang aktif
- **Quick Access**: Tombol untuk filter semester aktif

### 6. Role-Specific Schedule Display

- **Student View**: Menampilkan jadwal ujian sesuai kelas siswa
- **Teacher View**: Menampilkan jadwal pengawasan ujian yang dia awasi
- **Parent View**: Menampilkan jadwal ujian anak mereka
- **Active Semester Filter**: Semua tampilan hanya menampilkan jadwal semester aktif

### 7. Pagination System

- **Consistent Pagination**: Semua tampilan jadwal ujian menggunakan pagination (10 item per halaman)
- **Improved Performance**: Mengurangi waktu loading untuk data yang besar
- **Better UX**: Tampilan yang lebih rapi dan mudah dinavigasi
- **Responsive Design**: Pagination yang responsif untuk semua ukuran layar

#### Pagination Implementation

**Controller Changes**:

```php
// Before: Using get() and groupBy()
$examSchedules = ExamSchedule::with(['academicYear', 'semester', 'subject', 'classroom', 'supervisor', 'major'])
    ->where('classroom_id', $classroomId)
    ->where('academic_year_id', $activeAcademicYear->id)
    ->where('semester_id', $activeSemester->id)
    ->orderBy('exam_date')
    ->orderBy('start_time')
    ->get()
    ->groupBy('exam_type');

// After: Using paginate()
$examSchedules = ExamSchedule::with(['academicYear', 'semester', 'subject', 'classroom', 'supervisor', 'major'])
    ->where('classroom_id', $classroomId)
    ->where('academic_year_id', $activeAcademicYear->id)
    ->where('semester_id', $activeSemester->id)
    ->orderBy('exam_date')
    ->orderBy('start_time')
    ->paginate(10);
```

**View Changes**:

```php
// Statistics using pagination
{{ $examSchedules->total() }} // Total records
{{ $totalUts }} // Total UTS across all pages
{{ $totalUas }} // Total UAS across all pages

// Pagination links
{{ $examSchedules->links() }}

// Loop through paginated collection
@foreach($examSchedules as $schedule)
    // Display schedule data
@endforeach
```

#### Implementation Details

**Student Schedule (`studentSchedule()`)**:

```php
// Get student's classroom for active academic year
$classroomId = $student->classStudents()
    ->where('academic_year_id', $activeAcademicYear->id)
    ->first()?->classroom_id;

// Get exam schedules for student's classroom
$examSchedules = ExamSchedule::with(['academicYear', 'semester', 'subject', 'classroom', 'supervisor', 'major'])
    ->where('classroom_id', $classroomId)
    ->where('academic_year_id', $activeAcademicYear->id)
    ->where('semester_id', $activeSemester->id)
    ->orderBy('exam_date')
    ->orderBy('start_time')
    ->get()
    ->groupBy('exam_type');
```

**Teacher Schedule (`teacherSchedule()`)**:

```php
// Get exam schedules where teacher is supervisor
$examSchedules = ExamSchedule::with(['academicYear', 'semester', 'subject', 'classroom', 'supervisor', 'major'])
    ->where('supervisor_id', $teacher->id)
    ->where('academic_year_id', $activeAcademicYear->id)
    ->where('semester_id', $activeSemester->id)
    ->orderBy('exam_date')
    ->orderBy('start_time')
    ->get()
    ->groupBy('exam_type');
```

**Parent Schedule (`parentSchedule()`)**:

```php
// Get child's classroom for active academic year
$classroomId = $student->classStudents()
    ->where('academic_year_id', $activeAcademicYear->id)
    ->first()?->classroom_id;

// Get exam schedules for child's classroom
$examSchedules = ExamSchedule::with(['academicYear', 'semester', 'subject', 'classroom', 'supervisor', 'major'])
    ->where('classroom_id', $classroomId)
    ->where('academic_year_id', $activeAcademicYear->id)
    ->where('semester_id', $activeSemester->id)
    ->orderBy('exam_date')
    ->orderBy('start_time')
    ->get()
    ->groupBy('exam_type');
```

## Form Pembuatan Jadwal Ujian

### Input Fields

1. **Tahun Ajaran**: Otomatis terisi semester aktif
2. **Semester**: Otomatis terisi semester aktif
3. **Angkatan**: X, XI, atau XII
4. **Jurusan**: IPA, IPS, atau Umum (untuk mata pelajaran umum)
5. **Mata Pelajaran**: Otomatis filter berdasarkan jurusan yang dipilih
6. **Jenis Ujian**: UTS atau UAS
7. **Pengawas**: Daftar guru yang tersedia
8. **Tanggal Ujian**: Tanggal pelaksanaan
9. **Waktu Mulai & Selesai**: Durasi ujian
10. **Jenis Mata Pelajaran**: Umum atau Jurusan (otomatis)

### JavaScript Features

- **Dynamic Subject Filtering**: Mata pelajaran otomatis filter berdasarkan jurusan
- **Auto Major Detection**: Jenis mata pelajaran otomatis berdasarkan jurusan
- **Real-time Validation**: Validasi form secara real-time

## Role-based Access Control

### Admin

- **Full Access**: CRUD semua jadwal ujian
- **Bulk Creation**: Membuat jadwal untuk semua kelas sekaligus
- **Advanced Filtering**: Akses ke semua filter dan pencarian
- **Active Semester Management**: Hanya dapat memodifikasi semester aktif

### Guru

- **View Only**: Hanya dapat melihat jadwal yang dia awasi
- **Active Semester**: Hanya jadwal semester aktif
- **Filtered View**: Tidak dapat mengubah filter

### Siswa

- **Class-specific**: Hanya jadwal kelasnya sendiri
- **Active Semester**: Hanya jadwal semester aktif
- **Read-only**: Tidak dapat memodifikasi

### Wali Murid

- **Child's Schedule**: Hanya jadwal anaknya
- **Active Semester**: Hanya jadwal semester aktif
- **Read-only**: Tidak dapat memodifikasi

## Validasi dan Error Handling

### Duplicate Prevention

```php
$existingSchedules = ExamSchedule::where([
    'academic_year_id' => $request->academic_year_id,
    'semester_id' => $request->semester_id,
    'subject_id' => $request->subject_id,
    'exam_type' => $request->exam_type,
    'exam_date' => $request->exam_date,
])->whereIn('classroom_id', $classrooms->pluck('id'))->get();
```

### Supervisor Conflict Check

```php
$supervisorConflict = ExamSchedule::where([
    'academic_year_id' => $request->academic_year_id,
    'semester_id' => $request->semester_id,
    'supervisor_id' => $request->supervisor_id,
    'exam_date' => $request->exam_date,
])->where(function($query) use ($request) {
    // Time overlap validation
})->exists();
```

### Major-specific Subject Validation

```php
if ($subject->major_id && $request->major_id != $subject->major_id) {
    return back()->withErrors(['major_id' => "Mata pelajaran {$subject->name} hanya dapat dijadwalkan untuk jurusan {$subject->major->name}."]);
}
```

## Total Statistics Fix

### Problem

Statistik UTS dan UAS di tampilan siswa, guru, dan wali murid hanya menampilkan jumlah di halaman saat ini, bukan total keseluruhan.

### Solution

Menghitung total UTS dan UAS secara terpisah di controller dan mengirimkannya ke view.

### Implementation

#### Controller Changes

```php
// Menambahkan perhitungan total di setiap method (studentSchedule, teacherSchedule, parentSchedule)
$totalUts = ExamSchedule::where('classroom_id', $classroomId)
    ->where('academic_year_id', $activeAcademicYear->id)
    ->where('semester_id', $activeSemester->id)
    ->where('exam_type', 'uts')
    ->count();

$totalUas = ExamSchedule::where('classroom_id', $classroomId)
    ->where('academic_year_id', $activeAcademicYear->id)
    ->where('semester_id', $activeSemester->id)
    ->where('exam_type', 'uas')
    ->count();

// Mengirim variabel ke view
return view('siswa.exam-schedule.index', compact('examSchedules', 'activeSemester', 'activeAcademicYear', 'totalUts', 'totalUas'));
```

#### View Changes

```php
// Sebelum (hanya menghitung di halaman saat ini)
<p class="text-2xl font-bold text-gray-900">{{ $examSchedules->where('exam_type', 'uts')->count() }}</p>

// Sesudah (menampilkan total keseluruhan)
<p class="text-2xl font-bold text-gray-900">{{ $totalUts }}</p>
```

### Benefits

- **Accurate Statistics**: Statistik menampilkan total yang benar
- **Consistent Display**: Semua tampilan menggunakan logika yang sama
- **Better UX**: Pengguna melihat informasi yang akurat

## UI/UX Improvements

### Modern Filter Design

- **Responsive Grid**: Layout yang responsif untuk semua ukuran layar
- **Color-coded Badges**: Filter aktif ditampilkan dengan badge berwarna
- **Quick Actions**: Tombol untuk aksi cepat
- **Search Integration**: Pencarian terintegrasi dengan filter

### Enhanced Form

- **Dynamic Filtering**: Mata pelajaran otomatis filter berdasarkan jurusan
- **Auto-completion**: Field otomatis terisi berdasarkan semester aktif
- **Real-time Validation**: Validasi form secara real-time
- **User-friendly Messages**: Pesan error dan sukses yang informatif

## Performance Optimizations

### Database Queries

- **Eager Loading**: Menggunakan `with()` untuk mengurangi N+1 queries
- **Indexed Filters**: Filter berdasarkan kolom yang terindeks
- **Pagination**: Pagination untuk daftar jadwal yang besar

### Caching Strategy

- **Active Semester Caching**: Cache semester aktif untuk mengurangi query
- **Filter Options Caching**: Cache opsi filter yang sering digunakan

## Security Considerations

### Input Validation

- **Server-side Validation**: Validasi lengkap di controller
- **Client-side Validation**: Validasi tambahan di JavaScript
- **SQL Injection Prevention**: Menggunakan Eloquent ORM

### Access Control

- **Role-based Middleware**: Middleware untuk kontrol akses
- **Active Semester Enforcement**: Hanya semester aktif yang dapat dimodifikasi
- **Data Isolation**: Setiap role hanya dapat melihat data yang relevan

## Future Enhancements

### Planned Features

1. **Export Functionality**: Export jadwal ke PDF/Excel
2. **Notification System**: Notifikasi untuk jadwal ujian
3. **Calendar View**: Tampilan kalender untuk jadwal
4. **Bulk Import**: Import jadwal dari file Excel
5. **Advanced Reporting**: Laporan detail jadwal ujian

### Technical Improvements

1. **API Endpoints**: RESTful API untuk integrasi
2. **Real-time Updates**: WebSocket untuk update real-time
3. **Mobile Optimization**: Optimasi untuk perangkat mobile
4. **Advanced Search**: Full-text search dengan Elasticsearch

## Troubleshooting

### Common Issues

1. **Filter Not Working**: Pastikan semester aktif sudah diset
2. **Duplicate Schedules**: Periksa validasi duplikasi
3. **Supervisor Conflicts**: Periksa jadwal pengawas
4. **Major Validation**: Pastikan mata pelajaran sesuai jurusan

### Debug Commands

```bash
# Check active semester
php artisan tinker --execute="echo App\Models\Semester::where('is_active', true)->first();"

# Check exam schedules
php artisan tinker --execute="echo App\Models\ExamSchedule::count();"

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## Conclusion

Sistem jadwal ujian ini telah diperbarui dengan fitur-fitur baru yang meningkatkan efisiensi dan user experience. Sistem sekarang mendukung pembuatan jadwal secara bulk, validasi yang lebih ketat, dan interface yang lebih user-friendly.

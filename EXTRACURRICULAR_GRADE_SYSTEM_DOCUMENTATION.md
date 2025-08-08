# Sistem Penilaian Ekstrakurikuler

## Overview

Sistem penilaian ekstrakurikuler memungkinkan guru pembimbing untuk memberikan nilai kepada siswa yang mengikuti ekstrakurikuler yang mereka bimbing. Nilai menggunakan skala sederhana: "Sangat Baik", "Baik", "Cukup", "Kurang".

## Fitur Utama

### 1. Database Schema

- **Migration**: `2025_08_08_182414_add_grade_to_student_extracurriculars_table.php`
- **Field**: `grade` (ENUM: 'Sangat Baik', 'Baik', 'Cukup', 'Kurang')
- **Lokasi**: Tabel `student_extracurriculars` (pivot table)
- **Fields yang tersedia**: `grade`, `position`, `achievements`, `notes`, `status`, `join_date`, `leave_date`

### 2. Model Updates

- **Extracurricular.php**: Menambahkan `grade` ke `withPivot()` pada relationship `students()`
- **Student.php**: Sudah memiliki relationship `extracurriculars()` dengan pivot data

### 3. Controller

- **GuruExtracurricularGradeController.php**: Controller utama untuk guru

    - `index()`: Menampilkan daftar ekstrakurikuler yang dibimbing (hanya yang dibimbing oleh guru tersebut)
    - `show()`: Menampilkan form input nilai, posisi, prestasi, dan catatan untuk ekstrakurikuler tertentu
    - `store()`: Menyimpan nilai, posisi, prestasi, dan catatan yang diinput
    - `downloadTemplate()`: Download template Excel dengan semua field
    - `import()`: Import data dari file Excel

- **StudentExtracurricularController.php**: Controller untuk siswa
    - `enroll()`: Mendaftar ke ekstrakurikuler (dengan validasi 1 ekskul per tahun)
    - `leave()`: Keluar dari ekstrakurikuler (dengan validasi wajib 1 ekskul)

### 4. Middleware

- **CheckExtracurricularSupervisor.php**: Middleware untuk memvalidasi pembina ekstrakurikuler
    - Memastikan user adalah guru
    - Memastikan guru adalah pembina ekstrakurikuler yang diakses
    - Memberikan pesan error yang jelas jika tidak memiliki akses

### 5. Routes

```php
// Teacher Extracurricular Grade Routes
Route::middleware('check.role:teacher')->prefix('teacher/extracurricular-grade')->name('teacher.extracurricular-grade.')->group(function () {
    Route::get('/', [GuruExtracurricularGradeController::class, 'index'])->name('index');
    Route::get('/{extracurricular}', [GuruExtracurricularGradeController::class, 'show'])->name('show');
    Route::post('/{extracurricular}', [GuruExtracurricularGradeController::class, 'store'])->name('store');
    Route::get('/{extracurricular}/template', [GuruExtracurricularGradeController::class, 'downloadTemplate'])->name('template');
    Route::post('/{extracurricular}/import', [GuruExtracurricularGradeController::class, 'import'])->name('import');
});
```

### 6. Views

- **`resources/views/guru/extracurricular-grade/index.blade.php`**: Dashboard guru untuk melihat ekstrakurikuler yang dibimbing
- **`resources/views/guru/extracurricular-grade/show.blade.php`**: Form input nilai dengan fitur manual dan import Excel

### 7. Sidebar Integration

- **Dashboard Layout**: Menambahkan menu "Nilai Ekstrakurikuler" di section "Mengajar" untuk role teacher
- **Route**: `teacher.extracurricular-grade.index`

### 8. Raport Integration

- **Format Tabel**: Ekstrakurikuler ditampilkan dalam format tabel seperti nilai akademik
- **Kolom Tabel**: No, Nama Ekstrakurikuler, Posisi, Nilai, Prestasi, Catatan
- **Color Coding untuk Nilai**:
    - Sangat Baik: Hijau
    - Baik: Biru
    - Cukup: Kuning
    - Kurang: Merah
- **Layout**: Tabel terpisah dari kehadiran dan catatan wali kelas untuk tampilan yang lebih rapi

## Alur Kerja

### 1. Guru Mengakses Sistem

1. Login sebagai guru
2. Klik menu "Nilai Ekstrakurikuler" di sidebar
3. Melihat daftar ekstrakurikuler yang dibimbing

### 2. Input Data Manual

1. Klik "Input Nilai" pada ekstrakurikuler tertentu
2. Melihat daftar siswa aktif
3. Input nilai, posisi, prestasi, dan catatan untuk setiap siswa
4. Klik "Simpan Nilai"

### 3. Import Data Excel

1. Klik "Download Template" untuk mendapatkan template Excel
2. Isi template dengan nilai, posisi, prestasi, dan catatan siswa
3. Klik "Import Excel" dan pilih file
4. Sistem akan memvalidasi dan mengimpor data

### 4. Tampilan di Raport

1. Siswa dapat melihat nilai, posisi, prestasi, dan catatan ekstrakurikuler di raport
2. Nilai ditampilkan dengan warna sesuai grade
3. Jika belum dinilai, akan muncul "Belum dinilai"
4. Data lengkap dari pembina ekstrakurikuler ditampilkan

## Validasi dan Keamanan

### 1. Role-based Access

- Hanya guru yang dapat mengakses fitur ini
- Guru hanya dapat mengakses ekstrakurikuler yang mereka bimbing
- Middleware `CheckExtracurricularSupervisor` memastikan hanya pembina ekstrakurikuler yang dapat mengakses

### 2. Data Validation

- Nilai harus salah satu dari: "Sangat Baik", "Baik", "Cukup", "Kurang"
- Posisi harus salah satu dari: "Anggota", "Ketua", "Wakil Ketua", "Sekretaris", "Bendahara"
- File Excel harus format yang benar
- Siswa harus aktif di ekstrakurikuler tersebut
- Siswa wajib mengikuti minimal 1 ekstrakurikuler per tahun ajaran

### 3. Error Handling

- Pesan error yang informatif
- Validasi file Excel dengan detail error per baris
- Fallback untuk data yang tidak valid

## Panduan Penilaian

### Kriteria Penilaian

- **Sangat Baik**: Siswa sangat aktif, berprestasi tinggi, dan menjadi teladan
- **Baik**: Siswa aktif mengikuti kegiatan dan menunjukkan kemajuan
- **Cukup**: Siswa mengikuti kegiatan dengan baik namun masih perlu peningkatan
- **Kurang**: Siswa kurang aktif atau belum menunjukkan kemajuan yang signifikan

## File Excel Template

### Format Template

- **Kolom A**: No (nomor urut)
- **Kolom B**: NIS (nomor induk siswa)
- **Kolom C**: Nama Siswa
- **Kolom D**: Posisi (Anggota/Ketua/Wakil Ketua/Sekretaris/Bendahara)
- **Kolom E**: Nilai Ekstrakurikuler (Sangat Baik/Baik/Cukup/Kurang)
- **Kolom F**: Prestasi (opsional)
- **Kolom G**: Catatan (opsional)

### Validasi Excel

- NIS harus valid dan terdaftar
- Siswa harus aktif di ekstrakurikuler tersebut
- Posisi harus sesuai dengan pilihan yang tersedia
- Nilai harus sesuai dengan skala yang ditentukan

## Testing

### Manual Testing

1. Login sebagai guru yang membimbing ekstrakurikuler
2. Akses menu "Nilai Ekstrakurikuler"
3. Test input nilai manual
4. Test download dan import template Excel
5. Verifikasi nilai muncul di raport siswa

### Database Testing

1. Verifikasi field `grade` terisi dengan benar
2. Test dengan nilai null/kosong
3. Test dengan semua nilai yang valid

## Maintenance

### Backup

- Backup data nilai ekstrakurikuler secara berkala
- Backup template Excel yang digunakan

### Monitoring

- Monitor penggunaan fitur ini
- Track error yang terjadi saat import
- Monitor performa query database

## Future Enhancements

### Fitur yang Bisa Ditambahkan

1. **Histori Penilaian**: Menyimpan riwayat perubahan nilai
2. **Notifikasi**: Notifikasi ke siswa saat nilai diupdate
3. **Laporan**: Laporan statistik nilai ekstrakurikuler
4. **Bulk Actions**: Aksi massal untuk nilai
5. **Export**: Export nilai ke berbagai format

### Performance Optimization

1. **Caching**: Cache data ekstrakurikuler yang sering diakses
2. **Indexing**: Optimasi index database untuk query yang sering digunakan
3. **Pagination**: Pagination untuk daftar siswa yang banyak

## Deployment

### Migration

```bash
php artisan migrate
```

### Verification

1. Cek route list: `php artisan route:list | findstr extracurricular`
2. Test akses menu di dashboard
3. Test input nilai manual dan import Excel
4. Verifikasi tampilan di raport

### Rollback

Jika perlu rollback:

```bash
php artisan migrate:rollback --step=1
```

## Troubleshooting

### Common Issues

1. **Menu tidak muncul**: Pastikan user memiliki role teacher
2. **Tidak bisa input nilai**: Pastikan guru adalah pembimbing ekstrakurikuler tersebut
3. **Import gagal**: Cek format Excel dan validasi data
4. **Nilai tidak muncul di raport**: Pastikan relationship dan pivot data benar
5. **Akses ditolak**: Pastikan guru adalah pembina ekstrakurikuler yang diakses
6. **Tidak ada ekstrakurikuler ditampilkan**: Pastikan guru ditugaskan sebagai pembina ekstrakurikuler

### Debug Steps

1. Cek log Laravel untuk error detail
2. Verifikasi data di database
3. Test dengan data minimal
4. Cek permission dan role user

## Conclusion

Sistem penilaian ekstrakurikuler telah berhasil diimplementasikan dengan fitur lengkap:

- ✅ Database schema dengan field grade
- ✅ Controller dengan CRUD dan import/export
- ✅ Middleware untuk validasi pembina ekstrakurikuler
- ✅ Views untuk input nilai manual dan Excel
- ✅ Integration dengan sidebar dan raport
- ✅ Validasi dan error handling yang ketat
- ✅ Documentation lengkap

Sistem ini memungkinkan guru untuk memberikan penilaian yang terstruktur dan dapat dilihat oleh siswa melalui raport mereka.

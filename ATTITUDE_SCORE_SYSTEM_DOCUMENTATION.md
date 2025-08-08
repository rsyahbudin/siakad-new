# Sistem Penilaian Sikap

## Overview

Sistem penilaian sikap telah ditambahkan ke aplikasi SIAKAD untuk memungkinkan guru mata pelajaran memberikan penilaian sikap kepada setiap siswa. Nilai sikap mencakup tiga kategori: Baik, Cukup, dan Kurang Baik.

## Fitur yang Ditambahkan

### 1. Database Migration

- Menambahkan kolom `attitude_grade` ke tabel `grades`
- Tipe data: ENUM dengan nilai 'Baik', 'Cukup', 'Kurang Baik'
- Kolom dapat NULL untuk nilai yang belum diinput

### 2. Model Updates

- Mengupdate model `Grade` untuk menyertakan `attitude_grade` dalam fillable attributes
- Menambahkan relationship dan method yang diperlukan

### 3. Input Nilai oleh Guru

- **Halaman Input Nilai**: Menambahkan dropdown untuk nilai sikap di form input nilai
- **Import Excel**: Menambahkan kolom nilai sikap di template Excel dan proses import
- **Validasi**: Memastikan nilai sikap hanya dapat berisi 'Baik', 'Cukup', atau 'Kurang Baik'

### 4. Tampilan Nilai Sikap

#### A. Halaman Input Nilai Guru

- Menambahkan kolom "Nilai Sikap" di tabel input nilai
- Dropdown dengan opsi: Baik, Cukup, Kurang Baik
- Warna yang berbeda untuk setiap nilai:
    - Baik: Hijau
    - Cukup: Kuning
    - Kurang Baik: Merah

#### B. Halaman Raport Siswa

- Menambahkan kolom nilai sikap di raport akademik
- Tampilan dengan badge berwarna sesuai nilai

#### C. Halaman Detail Nilai (Admin/Wali Kelas)

- Menampilkan nilai sikap di halaman detail nilai siswa
- Konsisten dengan tampilan di halaman lain

#### D. Halaman Leger Wali Kelas

- Menambahkan kolom nilai sikap di tabel leger
- Menampilkan nilai sikap untuk setiap mata pelajaran

#### E. Halaman Nilai Siswa

- Menambahkan kolom nilai sikap di halaman nilai siswa
- Tampilan yang konsisten dengan halaman lain

#### F. Halaman Nilai Anak (Wali Murid)

- Menambahkan kolom nilai sikap di halaman nilai anak
- Memungkinkan wali murid melihat nilai sikap anaknya

#### G. Halaman Monitoring Nilai (Kepala Sekolah)

- Menambahkan kolom nilai sikap di monitoring nilai
- Memungkinkan kepala sekolah memantau nilai sikap siswa

### 5. Import/Export Excel

- **Template Excel**: Menambahkan kolom "Nilai Sikap" di template import
- **Format**: Kolom F berisi nilai sikap (Baik/Cukup/Kurang Baik)
- **Validasi**: Memastikan nilai yang diimport sesuai dengan enum yang diizinkan

## Alur Kerja

### 1. Input Nilai oleh Guru

1. Guru mengakses halaman input nilai
2. Memilih kelas dan mata pelajaran
3. Mengisi nilai tugas, UTS, UAS, dan nilai sikap
4. Menyimpan nilai ke database

### 2. Import Nilai dari Excel

1. Guru mengunduh template Excel
2. Mengisi nilai termasuk kolom nilai sikap
3. Upload file Excel
4. Sistem memvalidasi dan menyimpan nilai sikap

### 3. Tampilan Nilai

1. Nilai sikap ditampilkan di berbagai halaman
2. Menggunakan badge berwarna untuk membedakan nilai
3. Konsisten di semua tampilan

## Struktur Database

### Tabel `grades`

```sql
ALTER TABLE grades ADD COLUMN attitude_grade ENUM('Baik', 'Cukup', 'Kurang Baik') NULL AFTER uas_grade;
```

### Model Grade

```php
protected $fillable = [
    'student_id',
    'subject_id',
    'classroom_id',
    'classroom_assignment_id',
    'academic_year_id',
    'semester_id',
    'assignment_grade',
    'uts_grade',
    'uas_grade',
    'attitude_grade', // Kolom baru
    'final_grade',
    'is_passed',
    'source',
];
```

## Validasi

### 1. Input Validation

- Nilai sikap harus salah satu dari: 'Baik', 'Cukup', 'Kurang Baik'
- Dapat dikosongkan (NULL) jika belum diinput

### 2. Import Validation

- Memvalidasi nilai sikap saat import Excel
- Menampilkan error jika nilai tidak sesuai

## Tampilan UI

### 1. Badge Warna

- **Baik**: `bg-green-100 text-green-800`
- **Cukup**: `bg-yellow-100 text-yellow-800`
- **Kurang Baik**: `bg-red-100 text-red-800`

### 2. Dropdown Input

```html
<select name="nilai[{{ $siswa->id }}][sikap]">
    <option value="">- Pilih -</option>
    <option value="Baik">Baik</option>
    <option value="Cukup">Cukup</option>
    <option value="Kurang Baik">Kurang Baik</option>
</select>
```

## Controller Updates

### 1. GuruNilaiController

- Menambahkan handling untuk `attitude_grade` di method `store()`
- Mengupdate method `import()` untuk menangani nilai sikap
- Mengupdate method `downloadTemplate()` untuk menambahkan kolom nilai sikap

### 2. WaliKelasController

- Mengupdate method `calculateWaliStudentStatistics()` untuk menyertakan nilai sikap
- Mengupdate method `detailNilaiSiswa()` untuk menampilkan nilai sikap

### 3. Admin Controllers

- Mengupdate controller untuk menyertakan nilai sikap di tampilan detail nilai

## Routes

Tidak ada perubahan pada routes karena menggunakan route yang sudah ada.

## Testing

### 1. Manual Testing

- [ ] Input nilai sikap melalui form
- [ ] Import nilai sikap melalui Excel
- [ ] Tampilan nilai sikap di berbagai halaman
- [ ] Validasi input nilai sikap

### 2. Edge Cases

- [ ] Nilai sikap kosong
- [ ] Import dengan nilai sikap yang tidak valid
- [ ] Tampilan di berbagai role (Guru, Wali Kelas, Admin, Siswa, Wali Murid, Kepala Sekolah)

## Keamanan

### 1. Authorization

- Hanya guru mata pelajaran yang dapat input nilai sikap untuk mata pelajarannya
- Wali kelas dapat melihat nilai sikap siswa di kelasnya
- Admin dapat melihat semua nilai sikap

### 2. Validation

- Validasi enum untuk memastikan nilai yang valid
- Sanitasi input untuk mencegah XSS

## Performance

### 1. Database

- Index pada kolom `attitude_grade` untuk query yang efisien
- Tidak ada perubahan signifikan pada performa karena hanya menambah satu kolom

### 2. UI

- Lazy loading untuk tampilan nilai sikap
- Caching untuk data yang sering diakses

## Maintenance

### 1. Backup

- Backup database sebelum deployment
- Backup template Excel yang sudah diupdate

### 2. Monitoring

- Monitor penggunaan fitur nilai sikap
- Track error rate pada input nilai sikap

## Future Enhancements

### 1. Analytics

- Dashboard untuk analisis nilai sikap
- Report trend nilai sikap per kelas/mata pelajaran

### 2. Advanced Features

- Multiple nilai sikap per mata pelajaran
- Rubrik penilaian sikap yang lebih detail
- Integration dengan sistem absensi untuk penilaian sikap otomatis

## Deployment Checklist

- [ ] Jalankan migration untuk menambah kolom `attitude_grade`
- [ ] Update semua view yang menampilkan nilai
- [ ] Test fitur input nilai sikap
- [ ] Test import/export Excel dengan nilai sikap
- [ ] Verifikasi tampilan di semua role
- [ ] Update dokumentasi user manual
- [ ] Training untuk guru tentang fitur baru

## Rollback Plan

Jika terjadi masalah, dapat melakukan rollback dengan:

1. Drop kolom `attitude_grade` dari tabel `grades`
2. Revert semua perubahan pada view dan controller
3. Restore backup database jika diperlukan

# Sistem Penempatan Kelas Otomatis - Dokumentasi

## 📋 **Overview**

Sistem penempatan kelas otomatis telah diimplementasikan untuk siswa baru (PPDB) dan siswa pindahan. Sistem ini akan secara otomatis menempatkan siswa ke kelas yang sesuai berdasarkan jurusan dan kelas yang diminati, dengan mempertimbangkan kapasitas kelas yang sebenarnya dari database.

## 🎯 **Fitur Utama**

### **1. Penempatan Kelas PPDB**

- ✅ **Otomatis** saat status berubah menjadi "Lulus"
- ✅ **Berdasarkan jurusan** yang diminati (IPA/IPS)
- ✅ **Kelas X** untuk semua siswa PPDB
- ✅ **Auto-create kelas** jika belum ada
- ✅ **Kapasitas dinamis** sesuai dengan kapasitas kelas di database

### **2. Penempatan Kelas Siswa Pindahan**

- ✅ **Otomatis** saat status berubah menjadi "Approved"
- ✅ **Berdasarkan kelas dan jurusan** tujuan
- ✅ **Kelas X, XI, XII** sesuai target
- ✅ **Auto-create kelas** jika belum ada
- ✅ **Kapasitas dinamis** sesuai dengan kapasitas kelas di database

### **3. Service ClassPlacementService**

```php
// Penempatan siswa PPDB
$success = ClassPlacementService::placePPDBStudent($student, $desiredMajor);

// Penempatan siswa pindahan
$success = ClassPlacementService::placeTransferStudent($student, $targetGrade, $targetMajor);

// Info penempatan kelas
$info = ClassPlacementService::getClassPlacementInfo($grade, $major);

// Kelas yang tersedia
$availableClasses = ClassPlacementService::getAvailableClasses($grade, $major);

// Cek kapasitas kelas
$hasCapacity = ClassPlacementService::hasAvailableCapacity($classroomId);
```

## 🔧 **Implementasi**

### **1. Alur Penempatan PPDB**

```
Siswa PPDB Diterima → Jurusan yang Diminati → Cek Kapasitas Kelas → Penempatan Kelas
```

**Contoh:**

- Siswa A mendaftar dengan jurusan **IPA** → ditempatkan di kelas **X IPA 1** (kapasitas: 32 siswa)
- Siswa B mendaftar dengan jurusan **IPS** → ditempatkan di kelas **X IPS 1** (kapasitas: 36 siswa)

### **2. Alur Penempatan Siswa Pindahan**

```
Siswa Pindahan Diterima → Kelas & Jurusan Tujuan → Cek Kapasitas Kelas → Penempatan Kelas
```

**Contoh:**

- Siswa C pindah dari kelas **XI IPA** → ditempatkan di kelas **XI IPA 1** (kapasitas: 30 siswa)
- Siswa D pindah dari kelas **XII IPS** → ditempatkan di kelas **XII IPS 1** (kapasitas: 35 siswa)

### **3. Logika Penempatan**

#### **Mencari Kelas yang Tersedia:**

1. ✅ Cari kelas dengan grade dan jurusan yang sesuai
2. ✅ Pilih kelas dengan urutan terendah (X IPA 1, X IPA 2, dst)
3. ✅ **Cek kapasitas kelas sesuai database** (bukan hardcoded)
4. ✅ Jika kelas penuh, buat kelas baru

#### **Auto-Create Kelas:**

1. ✅ Jika tidak ada kelas yang sesuai
2. ✅ Buat kelas baru dengan nama otomatis
3. ✅ Format: "Grade Jurusan Nomor" (X IPA 1, XI IPS 2, dst)
4. ✅ **Kapasitas mengikuti kelas yang sudah ada** atau default 36

## 📊 **Contoh Penempatan dengan Kapasitas Dinamis**

### **PPDB - Tahun 2025**

**Jurusan IPA (Kapasitas: 32 siswa per kelas):**

- Siswa #1-32 → **X IPA 1** (32 siswa)
- Siswa #33-64 → **X IPA 2** (32 siswa)
- Siswa #65-96 → **X IPA 3** (32 siswa)

**Jurusan IPS (Kapasitas: 36 siswa per kelas):**

- Siswa #1-36 → **X IPS 1** (36 siswa)
- Siswa #37-72 → **X IPS 2** (36 siswa)

### **Siswa Pindahan - Tahun 2025**

**Kelas XI IPA (Kapasitas: 30 siswa per kelas):**

- Siswa #1-30 → **XI IPA 1** (30 siswa)
- Siswa #31-60 → **XI IPA 2** (30 siswa)

**Kelas XII IPS (Kapasitas: 35 siswa per kelas):**

- Siswa #1-35 → **XII IPS 1** (35 siswa)

## 🔒 **Validasi & Keamanan**

### **1. Kapasitas Kelas**

- ✅ **Kapasitas dinamis** sesuai database
- ✅ **Fallback ke 36** jika kapasitas tidak diatur
- ✅ **Auto-create kelas baru** jika penuh
- ✅ **Cek kapasitas sebelum penempatan**

### **2. Tahun Ajaran**

- ✅ Hanya tahun ajaran aktif
- ✅ Kelas sesuai tahun ajaran
- ✅ Validasi tahun ajaran sebelum penempatan

### **3. Jurusan**

- ✅ Validasi jurusan yang diminati
- ✅ Cek keberadaan jurusan di database
- ✅ Error handling jika jurusan tidak ditemukan

### **4. Database Transaction**

- ✅ Menggunakan DB transaction
- ✅ Rollback jika terjadi error
- ✅ Log semua aktivitas penempatan dengan detail kapasitas

## 🚀 **Cara Penggunaan**

### **Untuk Admin:**

1. **PPDB:**

    - Lihat detail pendaftar PPDB
    - Set status menjadi "Lulus"
    - Sistem otomatis:
        - ✅ Generate NIS
        - ✅ Buat akun siswa dan wali murid
        - ✅ **Tempatkan di kelas sesuai jurusan dan kapasitas**
    - Lihat informasi penempatan di halaman detail

2. **Siswa Pindahan:**
    - Lihat detail siswa pindahan
    - Set status menjadi "Approved"
    - Sistem otomatis:
        - ✅ Generate NIS
        - ✅ Buat akun siswa dan wali murid
        - ✅ Konversi nilai
        - ✅ **Tempatkan di kelas sesuai target dan kapasitas**
    - Lihat informasi penempatan di halaman detail

### **Untuk Developer:**

1. **Penempatan Manual:**

    ```php
    // PPDB
    $success = ClassPlacementService::placePPDBStudent($student, 'IPA');

    // Transfer
    $success = ClassPlacementService::placeTransferStudent($student, 'XI', 'IPS');
    ```

2. **Info Penempatan:**

    ```php
    $info = ClassPlacementService::getClassPlacementInfo('X', 'IPA');
    // Returns: [['class_name' => 'X IPA 1', 'student_count' => 25, 'capacity' => 32, ...]]
    ```

3. **Cek Kelas Tersedia:**

    ```php
    $availableClasses = ClassPlacementService::getAvailableClasses('X', 'IPA');
    // Returns: [['id' => 1, 'name' => 'X IPA 1', 'available_slots' => 7, ...]]
    ```

4. **Cek Kapasitas:**
    ```php
    $hasCapacity = ClassPlacementService::hasAvailableCapacity($classroomId);
    // Returns: true/false
    ```

## 📝 **Controller Updates**

### **PPDBApplicationController**

```php
// Place student in appropriate class based on desired major
$placementSuccess = ClassPlacementService::placePPDBStudent($student, $application->desired_major);

if ($placementSuccess) {
    Log::info("Student {$student->full_name} successfully placed in class");
} else {
    Log::warning("Failed to place student {$student->full_name} in class");
}
```

### **TransferStudentController**

```php
// Place student in appropriate class based on target grade and major
$placementSuccess = ClassPlacementService::placeTransferStudent($student, $transferStudent->target_grade, $transferStudent->target_major);

if ($placementSuccess) {
    Log::info("Transfer student {$student->full_name} successfully placed in class");
} else {
    Log::warning("Failed to place transfer student {$student->full_name} in class");
}
```

## 🎨 **Interface Admin**

### **Halaman Detail PPDB:**

- ✅ **Status "Lulus"**: Menampilkan informasi penempatan kelas
- ✅ **Jurusan**: IPA/IPS yang diminati
- ✅ **Kelas Target**: X IPA 1, X IPS 1, dst
- ✅ **Informasi**: Penempatan otomatis sesuai jurusan dan kapasitas

### **Halaman Detail Siswa Pindahan:**

- ✅ **Status "Approved"**: Menampilkan informasi penempatan kelas
- ✅ **Kelas & Jurusan**: XI IPA, XII IPS, dst
- ✅ **Kelas Target**: XI IPA 1, XII IPS 1, dst
- ✅ **Informasi**: Penempatan otomatis sesuai target dan kapasitas

## ⚠️ **Penting**

1. **Kapasitas Kelas:**

    - **Kapasitas dinamis** sesuai database
    - **Fallback ke 36** jika kapasitas tidak diatur
    - **Auto-create kelas baru** jika penuh
    - **Sistem akan mencari kelas dengan slot tersedia**

2. **Tahun Ajaran:**

    - Hanya tahun ajaran aktif yang digunakan
    - Kelas sesuai tahun ajaran
    - Validasi tahun ajaran sebelum penempatan

3. **Jurusan:**

    - Harus sesuai dengan jurusan yang ada di database
    - Error handling jika jurusan tidak ditemukan
    - Validasi sebelum penempatan

4. **Auto-Create Kelas:**
    - Sistem akan membuat kelas baru jika diperlukan
    - Nama kelas otomatis: "Grade Jurusan Nomor"
    - **Kapasitas mengikuti kelas yang sudah ada** atau default 36

## 🔧 **Perbaikan Kapasitas**

### **1. Sebelum Perbaikan:**

```php
// Hardcoded capacity
if ($studentCount >= 36) {
    // Create new class
}
```

### **2. Setelah Perbaikan:**

```php
// Dynamic capacity from database
$classCapacity = $classroom->capacity ?? 36;
if ($studentCount >= $classCapacity) {
    // Create new class
}
```

### **3. Logging yang Lebih Detail:**

```php
Log::info("Student {$student->full_name} placed in class {$classroom->name} (Capacity: {$classCapacity}, Current: " . ($studentCount + 1) . ")");
```

## ✅ **Status: READY TO USE!**

Sistem penempatan kelas otomatis sudah berfungsi dengan sempurna dan siap digunakan untuk:

- ✅ PPDB (Penerimaan Peserta Didik Baru)
- ✅ Siswa Pindahan
- ✅ Penempatan otomatis sesuai jurusan/kelas
- ✅ **Kapasitas dinamis sesuai database**
- ✅ Auto-create kelas jika diperlukan
- ✅ Validasi kapasitas dan tahun ajaran
- ✅ Database transaction untuk keamanan data
- ✅ Logging untuk monitoring dengan detail kapasitas

## 🔄 **Integrasi dengan Menu Pembagian Kelas**

**Tidak perlu menggunakan menu pembagian kelas manual** karena:

1. ✅ **Otomatis**: Penempatan dilakukan otomatis saat approval
2. ✅ **Akurat**: Berdasarkan data pendaftaran yang valid
3. ✅ **Konsisten**: Mengikuti aturan yang sama untuk semua siswa
4. ✅ **Efisien**: Tidak perlu input manual oleh admin
5. ✅ **Kapasitas Dinamis**: Menggunakan kapasitas kelas yang sebenarnya

**Menu pembagian kelas tetap tersedia untuk:**

- ✅ **Penyesuaian manual** jika diperlukan
- ✅ **Pemindahan siswa** antar kelas
- ✅ **Monitoring** jumlah siswa per kelas
- ✅ **Administrasi** kelas secara umum

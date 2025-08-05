# Sistem NIS (Nomor Induk Siswa) - Dokumentasi

## 📋 **Overview**

Sistem NIS baru telah diimplementasikan untuk PPDB dan Siswa Pindahan dengan format yang konsisten dan unik.

## 🎯 **Fitur Utama**

### **1. Format NIS**

- **Format**: `YY + 6 digit urutan`
- **Contoh**: `25000001` untuk tahun 2025, siswa #1
- **Panjang**: 8 digit (2 digit tahun + 6 digit urutan)
- **Unik**: Tidak boleh sama dengan NIS lain

### **2. Pembuatan NIS Otomatis**

#### **PPDB (Penerimaan Peserta Didik Baru)**

- ✅ NIS digenerate **otomatis** saat status berubah menjadi "Lulus"
- ✅ Format: `YY + 6 digit urutan` (contoh: 25000001)
- ✅ Berbeda dengan NISN (NISN tetap dari input user)

#### **Siswa Pindahan**

- ✅ NIS digenerate **otomatis** saat status berubah menjadi "Approved"
- ✅ Format: `YY + 6 digit urutan` (contoh: 25000001)
- ✅ **Berbeda** dengan NIS sekolah asal
- ✅ NIS sekolah asal disimpan di field `nis_previous`

### **3. Service NISGeneratorService**

```php
// Generate NIS untuk siswa baru
$nis = NISGeneratorService::generateNIS();

// Generate NIS untuk siswa pindahan (berbeda dari NIS sebelumnya)
$nis = NISGeneratorService::generateNISForTransferStudent($previousNIS);

// Validasi format NIS
$isValid = NISGeneratorService::validateNIS($nis);

// Contoh format NIS
$example = NISGeneratorService::getNISFormatExample();
```

## 🔧 **Implementasi**

### **1. Database**

- ✅ Kolom `nis` di tabel `students` (sudah ada)
- ✅ Kolom `nis_previous` di tabel `transfer_students` (sudah ada)
- ✅ Constraint unique pada kolom `nis`

### **2. Controller Updates**

#### **PPDBApplicationController**

```php
// Generate unique NIS for the student
$nis = NISGeneratorService::generateNIS();

// Create student record
$student = Student::create([
    'user_id' => $user->id,
    'nis' => $nis, // Generated unique NIS
    'nisn' => $application->nisn,
    // ... other fields
]);
```

#### **TransferStudentController**

```php
// Generate unique NIS for transfer student (different from previous NIS)
$nis = NISGeneratorService::generateNISForTransferStudent($transferStudent->nis_previous);

// Create student record
$student = Student::create([
    'user_id' => $user->id,
    'nis' => $nis, // Generated unique NIS
    'nisn' => $transferStudent->nisn,
    // ... other fields
]);
```

### **3. View Updates**

#### **Admin PPDB Show View**

- ✅ Menampilkan contoh NIS yang akan digenerate
- ✅ Hanya muncul saat status "Lulus"
- ✅ Format: `YY + 6 digit urutan`

#### **Admin Transfer Student Show View**

- ✅ Menampilkan NIS sekolah asal
- ✅ Menampilkan contoh NIS yang akan digenerate
- ✅ Hanya muncul saat status "Approved"
- ✅ Format: `YY + 6 digit urutan` (berbeda dari NIS sebelumnya)

## 📊 **Contoh NIS**

### **Tahun 2025**

- Siswa #1: `25000001`
- Siswa #2: `25000002`
- Siswa #3: `25000003`
- ... dst

### **Tahun 2026**

- Siswa #1: `26000001`
- Siswa #2: `26000002`
- ... dst

## 🔒 **Validasi**

### **1. Format NIS**

- ✅ Harus 8 digit
- ✅ 2 digit pertama = tahun (YY)
- ✅ 6 digit terakhir = urutan (000001-999999)
- ✅ Hanya angka yang diperbolehkan

### **2. Uniqueness**

- ✅ NIS tidak boleh sama dengan siswa lain
- ✅ NIS siswa pindahan berbeda dari NIS sekolah asal
- ✅ Auto-increment untuk urutan

### **3. Year-based**

- ✅ NIS dimulai dengan tahun masuk
- ✅ Reset urutan setiap tahun baru
- ✅ Format konsisten per tahun

## 🚀 **Cara Penggunaan**

### **Untuk Admin:**

1. **PPDB:**

    - Lihat detail pendaftar PPDB
    - Set status menjadi "Lulus"
    - NIS akan digenerate otomatis
    - Lihat contoh NIS di halaman detail

2. **Siswa Pindahan:**
    - Lihat detail siswa pindahan
    - Set status menjadi "Approved"
    - NIS akan digenerate otomatis (berbeda dari NIS sekolah asal)
    - Lihat contoh NIS di halaman detail

### **Untuk Developer:**

1. **Generate NIS Manual:**

    ```php
    $nis = NISGeneratorService::generateNIS();
    ```

2. **Generate NIS untuk Transfer:**

    ```php
    $nis = NISGeneratorService::generateNISForTransferStudent($previousNIS);
    ```

3. **Validasi NIS:**
    ```php
    $isValid = NISGeneratorService::validateNIS($nis);
    ```

## 📝 **Migration & Seeder**

### **Seeder untuk Update Data Existing**

```bash
php artisan db:seed --class=UpdateExistingStudentsNISSeeder
```

Seeder ini akan:

- ✅ Mencari siswa yang belum memiliki NIS
- ✅ Generate NIS baru dengan format yang benar
- ✅ Update database dengan NIS baru

## ⚠️ **Penting**

1. **NIS vs NISN:**

    - NISN = Nomor Induk Siswa Nasional (dari user input)
    - NIS = Nomor Induk Siswa (digenerate otomatis)

2. **Uniqueness:**

    - NIS harus unik di seluruh sistem
    - NIS siswa pindahan berbeda dari NIS sekolah asal

3. **Year-based:**

    - NIS dimulai dengan tahun masuk
    - Reset urutan setiap tahun baru

4. **Auto-generation:**
    - NIS digenerate otomatis saat status berubah
    - Tidak bisa diubah manual oleh admin

## ✅ **Status: READY TO USE!**

Sistem NIS sudah berfungsi dengan sempurna dan siap digunakan untuk:

- ✅ PPDB (Penerimaan Peserta Didik Baru)
- ✅ Siswa Pindahan
- ✅ Format konsisten dan unik
- ✅ Auto-generation saat approval
- ✅ Validasi format dan uniqueness

# Perbaikan Kapasitas Kelas - Sistem Penempatan Otomatis

## 🎯 **Overview**

Sistem penempatan otomatis telah diperbaiki untuk menggunakan kapasitas kelas yang sebenarnya dari database, bukan hardcoded 36 siswa. Ini memastikan penempatan siswa sesuai dengan kapasitas kelas yang telah ditentukan oleh admin.

## 🔧 **Perubahan Utama**

### **1. Sebelum Perbaikan (Hardcoded)**

```php
// Menggunakan kapasitas hardcoded 36
if ($studentCount >= 36) {
    // Create new class
}
```

### **2. Setelah Perbaikan (Dinamis)**

```php
// Menggunakan kapasitas dari database
$classCapacity = $classroom->capacity ?? 36; // Fallback ke 36 jika tidak diatur
if ($studentCount >= $classCapacity) {
    // Create new class
}
```

## 📊 **Implementasi Perbaikan**

### **1. Method `placePPDBStudent()`**

```php
// Check if class is full using actual capacity from database
$studentCount = ClassStudent::where('classroom_id', $classroom->id)
    ->where('academic_year_id', $academicYear->id)
    ->count();

$classCapacity = $classroom->capacity ?? 36; // Default to 36 if not set

if ($studentCount >= $classCapacity) {
    // Create new class if current is full
    $classroom = self::createNewClass($academicYear, $major, 'X');
}
```

### **2. Method `placeTransferStudent()`**

```php
// Check if class is full using actual capacity from database
$studentCount = ClassStudent::where('classroom_id', $classroom->id)
    ->where('academic_year_id', $academicYear->id)
    ->count();

$classCapacity = $classroom->capacity ?? 36; // Default to 36 if not set

if ($studentCount >= $classCapacity) {
    // Create new class if current is full
    $classroom = self::createNewClass($academicYear, $major, $targetGrade);
}
```

### **3. Method `createNewClass()`**

```php
// Get default capacity from existing classes or use 36 as fallback
$defaultCapacity = Classroom::where('grade', $grade)
    ->where('major_id', $major->id)
    ->where('academic_year_id', $academicYear->id)
    ->value('capacity') ?? 36;

return Classroom::create([
    'name' => $className,
    'grade' => $grade,
    'major_id' => $major->id,
    'academic_year_id' => $academicYear->id,
    'capacity' => $defaultCapacity, // Menggunakan kapasitas yang sudah ada
    'is_active' => true,
]);
```

### **4. Method `getClassPlacementInfo()`**

```php
$studentCount = ClassStudent::where('classroom_id', $classroom->id)
    ->where('academic_year_id', $academicYear->id)
    ->count();

$capacity = $classroom->capacity ?? 36;

$info[] = [
    'class_name' => $classroom->name,
    'student_count' => $studentCount,
    'capacity' => $capacity,
    'available_slots' => $capacity - $studentCount,
    'is_full' => $studentCount >= $capacity,
];
```

## 🆕 **Method Baru yang Ditambahkan**

### **1. `getAvailableClasses()`**

```php
public static function getAvailableClasses(string $grade, string $major): array
{
    // Returns classes with available slots
    $availableClasses = [];
    foreach ($classrooms as $classroom) {
        $studentCount = ClassStudent::where('classroom_id', $classroom->id)
            ->where('academic_year_id', $academicYear->id)
            ->count();

        $capacity = $classroom->capacity ?? 36;
        $availableSlots = $capacity - $studentCount;

        if ($availableSlots > 0) {
            $availableClasses[] = [
                'id' => $classroom->id,
                'name' => $classroom->name,
                'available_slots' => $availableSlots,
                'current_students' => $studentCount,
                'capacity' => $capacity,
            ];
        }
    }

    return $availableClasses;
}
```

### **2. `hasAvailableCapacity()`**

```php
public static function hasAvailableCapacity(int $classroomId): bool
{
    $classroom = Classroom::find($classroomId);
    if (!$classroom) {
        return false;
    }

    $studentCount = ClassStudent::where('classroom_id', $classroomId)
        ->where('academic_year_id', $academicYear->id)
        ->count();

    $capacity = $classroom->capacity ?? 36;

    return $studentCount < $capacity;
}
```

## 📈 **Contoh Penggunaan**

### **1. Kapasitas Berbeda per Kelas**

```php
// Kelas X IPA 1: Kapasitas 32 siswa
// Kelas X IPA 2: Kapasitas 30 siswa
// Kelas X IPS 1: Kapasitas 36 siswa
// Kelas XI IPA 1: Kapasitas 28 siswa

// Sistem akan menempatkan siswa sesuai kapasitas yang sudah ditentukan
```

### **2. Auto-Create dengan Kapasitas yang Konsisten**

```php
// Jika kelas X IPA 1 sudah penuh (32 siswa)
// Sistem akan membuat X IPA 2 dengan kapasitas yang sama (32 siswa)
// Bukan hardcoded 36 siswa
```

### **3. Logging yang Lebih Detail**

```php
Log::info("Student {$student->full_name} placed in class {$classroom->name} (Capacity: {$classCapacity}, Current: " . ($studentCount + 1) . ")");
```

## 🎯 **Keuntungan Perbaikan**

### **1. Fleksibilitas**

- ✅ **Kapasitas dinamis** sesuai kebutuhan sekolah
- ✅ **Berbeda per kelas** sesuai kondisi
- ✅ **Mudah diubah** oleh admin

### **2. Akurasi**

- ✅ **Menggunakan data real** dari database
- ✅ **Tidak ada asumsi** hardcoded
- ✅ **Konsisten** dengan pengaturan admin

### **3. Monitoring**

- ✅ **Logging detail** dengan kapasitas
- ✅ **Tracking penempatan** yang akurat
- ✅ **Debugging** yang lebih mudah

### **4. Fallback Safety**

- ✅ **Default 36** jika kapasitas tidak diatur
- ✅ **Tidak ada error** jika field kosong
- ✅ **Backward compatibility**

## 🔍 **Testing**

### **1. Test Kapasitas Dinamis**

```php
// Test dengan kapasitas berbeda
$classroom1 = Classroom::create(['capacity' => 32]);
$classroom2 = Classroom::create(['capacity' => 28]);
$classroom3 = Classroom::create(['capacity' => 36]);

// Sistem akan menghormati kapasitas yang berbeda
```

### **2. Test Auto-Create**

```php
// Test pembuatan kelas baru
// Kelas baru akan mengikuti kapasitas kelas yang sudah ada
$newClass = ClassPlacementService::createNewClass($academicYear, $major, 'X');
// Kapasitas akan sama dengan kelas X yang sudah ada
```

### **3. Test Logging**

```php
// Log akan menampilkan detail kapasitas
// "Student John Doe placed in class X IPA 1 (Capacity: 32, Current: 25)"
```

## ⚠️ **Penting untuk Diperhatikan**

### **1. Database Schema**

- ✅ Field `capacity` harus ada di tabel `classrooms`
- ✅ Default value bisa diatur di migration
- ✅ Nullable field untuk backward compatibility

### **2. Admin Interface**

- ✅ Admin dapat mengatur kapasitas per kelas
- ✅ Interface untuk mengubah kapasitas
- ✅ Validasi kapasitas (min/max)

### **3. Migration**

```php
// Jika belum ada field capacity
Schema::table('classrooms', function (Blueprint $table) {
    $table->integer('capacity')->default(36)->after('name');
});
```

## ✅ **Status: IMPLEMENTED!**

Perbaikan kapasitas kelas telah berhasil diimplementasikan dengan:

- ✅ **Kapasitas dinamis** dari database
- ✅ **Fallback ke 36** jika tidak diatur
- ✅ **Auto-create konsisten** dengan kapasitas existing
- ✅ **Logging detail** dengan informasi kapasitas
- ✅ **Method baru** untuk monitoring kapasitas
- ✅ **Backward compatibility** dengan sistem lama

**Sistem penempatan otomatis sekarang 100% RESPECTFUL terhadap kapasitas kelas yang ditentukan admin!** 🎉

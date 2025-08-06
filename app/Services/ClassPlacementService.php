<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Classroom;
use App\Models\ClassroomAssignment;
use App\Models\ClassStudent;
use App\Models\AcademicYear;
use App\Models\Major;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClassPlacementService
{
    /**
     * Place PPDB student in appropriate class based on desired major
     */
    public static function placePPDBStudent(Student $student, string $desiredMajor): bool
    {
        try {
            DB::beginTransaction();

            // Get current academic year
            $academicYear = AcademicYear::where('is_active', true)->first();
            if (!$academicYear) {
                throw new \Exception('Tidak ada tahun ajaran aktif.');
            }

            // Get major
            $major = Major::where('name', $desiredMajor)->first();
            if (!$major) {
                throw new \Exception('Jurusan tidak ditemukan: ' . $desiredMajor);
            }

            // Find available class for Grade X and desired major
            $classroom = Classroom::where('grade', 'X')
                ->where('major_id', $major->id)
                ->where('academic_year_id', $academicYear->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->first();

            if (!$classroom) {
                // Create new class if none exists
                $classroom = self::createNewClass($academicYear, $major, 'X');
            }

            // Check if class is full using actual capacity from database
            $studentCount = ClassStudent::where('classroom_id', $classroom->id)
                ->where('academic_year_id', $academicYear->id)
                ->count();

            $classCapacity = $classroom->capacity ?? 36; // Default to 36 if not set

            if ($studentCount >= $classCapacity) {
                // Create new class if current is full
                $classroom = self::createNewClass($academicYear, $major, 'X');
            }

            // Assign student to class
            ClassStudent::create([
                'student_id' => $student->id,
                'classroom_id' => $classroom->id,
                'academic_year_id' => $academicYear->id,
            ]);

            Log::info("Student {$student->full_name} placed in class {$classroom->name} (Capacity: {$classCapacity}, Current: " . ($studentCount + 1) . ")");

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to place PPDB student: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Place transfer student in appropriate class based on target grade and major
     */
    public static function placeTransferStudent(Student $student, string $targetGrade, string $targetMajor): bool
    {
        try {
            DB::beginTransaction();

            // Get current academic year
            $academicYear = AcademicYear::where('is_active', true)->first();
            if (!$academicYear) {
                throw new \Exception('Tidak ada tahun ajaran aktif.');
            }

            // Get major
            $major = Major::where('name', $targetMajor)->first();
            if (!$major) {
                throw new \Exception('Jurusan tidak ditemukan: ' . $targetMajor);
            }

            // Find available class for target grade and major
            $classroom = Classroom::where('grade', $targetGrade)
                ->where('major_id', $major->id)
                ->where('academic_year_id', $academicYear->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->first();

            if (!$classroom) {
                // Create new class if none exists
                $classroom = self::createNewClass($academicYear, $major, $targetGrade);
            }

            // Check if class is full using actual capacity from database
            $studentCount = ClassStudent::where('classroom_id', $classroom->id)
                ->where('academic_year_id', $academicYear->id)
                ->count();

            $classCapacity = $classroom->capacity ?? 36; // Default to 36 if not set

            if ($studentCount >= $classCapacity) {
                // Create new class if current is full
                $classroom = self::createNewClass($academicYear, $major, $targetGrade);
            }

            // Assign student to class
            ClassStudent::create([
                'student_id' => $student->id,
                'classroom_id' => $classroom->id,
                'academic_year_id' => $academicYear->id,
            ]);

            Log::info("Transfer student {$student->full_name} placed in class {$classroom->name} (Capacity: {$classCapacity}, Current: " . ($studentCount + 1) . ")");

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to place transfer student: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create new class when needed
     */
    private static function createNewClass(AcademicYear $academicYear, Major $major, string $grade): Classroom
    {
        // Find the highest class number for this grade and major
        $highestClass = Classroom::where('grade', $grade)
            ->where('major_id', $major->id)
            ->where('academic_year_id', $academicYear->id)
            ->orderBy('name', 'desc')
            ->first();

        $classNumber = 1;
        if ($highestClass) {
            // Extract number from class name (e.g., "X IPA 1" -> 1)
            preg_match('/\d+$/', $highestClass->name, $matches);
            if ($matches) {
                $classNumber = (int) $matches[0] + 1;
            }
        }

        // Create class name (e.g., "X IPA 1", "XI IPS 2")
        $className = "{$grade} {$major->name} {$classNumber}";

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
            'capacity' => $defaultCapacity,
            'is_active' => true,
        ]);
    }

    /**
     * Get class placement info for display
     */
    public static function getClassPlacementInfo(string $grade, string $major): array
    {
        $academicYear = AcademicYear::where('is_active', true)->first();
        if (!$academicYear) {
            return ['error' => 'Tidak ada tahun ajaran aktif.'];
        }

        $majorModel = Major::where('name', $major)->first();
        if (!$majorModel) {
            return ['error' => 'Jurusan tidak ditemukan.'];
        }

        $classrooms = Classroom::where('grade', $grade)
            ->where('major_id', $majorModel->id)
            ->where('academic_year_id', $academicYear->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $info = [];
        foreach ($classrooms as $classroom) {
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
        }

        return $info;
    }

    /**
     * Get available classes for a specific grade and major
     */
    public static function getAvailableClasses(string $grade, string $major): array
    {
        $academicYear = AcademicYear::where('is_active', true)->first();
        if (!$academicYear) {
            return ['error' => 'Tidak ada tahun ajaran aktif.'];
        }

        $majorModel = Major::where('name', $major)->first();
        if (!$majorModel) {
            return ['error' => 'Jurusan tidak ditemukan.'];
        }

        $classrooms = Classroom::where('grade', $grade)
            ->where('major_id', $majorModel->id)
            ->where('academic_year_id', $academicYear->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

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

    /**
     * Check if a class has available capacity
     */
    public static function hasAvailableCapacity(int $classroomId): bool
    {
        $academicYear = AcademicYear::where('is_active', true)->first();
        if (!$academicYear) {
            return false;
        }

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
}

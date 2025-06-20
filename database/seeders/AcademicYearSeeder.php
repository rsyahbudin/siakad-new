<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;
use Carbon\Carbon;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Current Academic Year
        $currentYear = Carbon::now()->year;
        $nextYear = $currentYear + 1;

        // Create Academic Year 2024/2025
        $academicYear = AcademicYear::create([
            'year' => "{$currentYear}/{$nextYear}",
            'semester' => 1, // Ganjil
            'is_active' => true,
            'start_date' => Carbon::create($currentYear, 7, 1), // July 1st
            'end_date' => Carbon::create($currentYear, 12, 31), // December 31st
        ]);

        // Create Semester 2
        AcademicYear::create([
            'year' => "{$currentYear}/{$nextYear}",
            'semester' => 2, // Genap
            'is_active' => false,
            'start_date' => Carbon::create($nextYear, 1, 1), // January 1st
            'end_date' => Carbon::create($nextYear, 6, 30), // June 30th
        ]);

        // Create next year's semesters (inactive)
        AcademicYear::create([
            'year' => "{$nextYear}/" . ($nextYear + 1),
            'semester' => 1,
            'is_active' => false,
            'start_date' => Carbon::create($nextYear, 7, 1),
            'end_date' => Carbon::create($nextYear, 12, 31),
        ]);

        AcademicYear::create([
            'year' => "{$nextYear}/" . ($nextYear + 1),
            'semester' => 2,
            'is_active' => false,
            'start_date' => Carbon::create($nextYear + 1, 1, 1),
            'end_date' => Carbon::create($nextYear + 1, 6, 30),
        ]);
    }
}

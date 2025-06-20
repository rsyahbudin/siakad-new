<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;
use App\Models\Semester;
use Carbon\Carbon;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tahun ajaran
        $years = [
            [
                'year' => '2023/2024',
                'start_date' => '2023-07-01',
                'end_date' => '2024-06-30',
            ],
            [
                'year' => '2024/2025',
                'start_date' => '2024-07-01',
                'end_date' => '2025-06-30',
            ],
        ];

        foreach ($years as $i => $data) {
            $isActiveYear = $i === (count($years) - 1); // Hanya tahun ajaran terakhir yang aktif
            $academicYear = AcademicYear::create([
                'year' => $data['year'],
                'is_active' => $isActiveYear,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ]);

            // Semester Ganjil
            Semester::create([
                'academic_year_id' => $academicYear->id,
                'name' => 'Ganjil',
                'is_active' => $isActiveYear, // Semester Ganjil tahun ajaran terakhir aktif
                'start_date' => $data['start_date'],
                'end_date' => date('Y-m-d', strtotime($data['start_date'] . ' +5 months')), // Jan 1
            ]);
            // Semester Genap
            Semester::create([
                'academic_year_id' => $academicYear->id,
                'name' => 'Genap',
                'is_active' => false,
                'start_date' => date('Y-m-d', strtotime($data['start_date'] . ' +6 months')),
                'end_date' => $data['end_date'],
            ]);
        }
    }
}

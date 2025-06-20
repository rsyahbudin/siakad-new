<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;
use App\Models\AcademicYear;
use App\Models\Teacher;
use App\Models\Major;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get majors
        $ipa = Major::where('short_name', 'IPA')->first();
        $ips = Major::where('short_name', 'IPS')->first();

        // Sample classrooms
        $classrooms = [
            // Grade X (10)
            [
                'name' => 'X-1',
                'grade_level' => 10,
                'capacity' => 32,
                'major_id' => null,
            ],
            [
                'name' => 'X-2',
                'grade_level' => 10,
                'capacity' => 32,
                'major_id' => null,
            ],
            // Grade XI (11)
            [
                'name' => 'XI IPA-1',
                'grade_level' => 11,
                'capacity' => 32,
                'major_id' => $ipa->id ?? null,
            ],
            [
                'name' => 'XI IPS-1',
                'grade_level' => 11,
                'capacity' => 32,
                'major_id' => $ips->id ?? null,
            ],
            // Grade XII (12)
            [
                'name' => 'XII IPA-1',
                'grade_level' => 12,
                'capacity' => 32,
                'major_id' => $ipa->id ?? null,
            ],
            [
                'name' => 'XII IPS-1',
                'grade_level' => 12,
                'capacity' => 32,
                'major_id' => $ips->id ?? null,
            ],
        ];

        // Create classrooms
        foreach ($classrooms as $classroom) {
            Classroom::create($classroom);
        }
    }
}

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
        // Get current academic year
        $academicYear = AcademicYear::where('is_active', true)->first();

        // Get teachers for homeroom assignments
        $teachers = Teacher::all();

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
                'academic_year_id' => $academicYear->id,
                'homeroom_teacher_id' => $teachers[0]->id ?? null,
            ],
            [
                'name' => 'X-2',
                'grade_level' => 10,
                'capacity' => 32,
                'academic_year_id' => $academicYear->id,
                'homeroom_teacher_id' => $teachers[1]->id ?? null,
            ],

            // Grade XI (11)
            [
                'name' => 'XI IPA-1',
                'grade_level' => 11,
                'capacity' => 32,
                'academic_year_id' => $academicYear->id,
                'major_id' => $ipa->id,
                'homeroom_teacher_id' => $teachers[0]->id ?? null,
            ],
            [
                'name' => 'XI IPS-1',
                'grade_level' => 11,
                'capacity' => 32,
                'academic_year_id' => $academicYear->id,
                'major_id' => $ips->id,
                'homeroom_teacher_id' => $teachers[1]->id ?? null,
            ],

            // Grade XII (12)
            [
                'name' => 'XII IPA-1',
                'grade_level' => 12,
                'capacity' => 32,
                'academic_year_id' => $academicYear->id,
                'major_id' => $ipa->id,
                'homeroom_teacher_id' => $teachers[0]->id ?? null,
            ],
            [
                'name' => 'XII IPS-1',
                'grade_level' => 12,
                'capacity' => 32,
                'academic_year_id' => $academicYear->id,
                'major_id' => $ips->id,
                'homeroom_teacher_id' => $teachers[1]->id ?? null,
            ],
        ];

        // Create classrooms
        foreach ($classrooms as $classroom) {
            Classroom::create($classroom);
        }
    }
}

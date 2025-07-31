<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AcademicYearSeeder::class,
            MajorSeeder::class,
            SubjectSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            ClassroomSeeder::class,
            SubjectSettingSeeder::class,
            ClassroomAssignmentSeeder::class,
            KepalaSekolahSeeder::class,
            WaliMuridSeeder::class,
        ]);
    }
}

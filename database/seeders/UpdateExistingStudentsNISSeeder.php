<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Services\NISGeneratorService;

class UpdateExistingStudentsNISSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::whereNull('nis')->orWhere('nis', '')->get();

        foreach ($students as $student) {
            try {
                $nis = NISGeneratorService::generateNIS();
                $student->update(['nis' => $nis]);
                $this->command->info("Updated student {$student->full_name} with NIS: {$nis}");
            } catch (\Exception $e) {
                $this->command->error("Failed to update student {$student->full_name}: " . $e->getMessage());
            }
        }

        $this->command->info("Updated " . $students->count() . " students with new NIS format.");
    }
}

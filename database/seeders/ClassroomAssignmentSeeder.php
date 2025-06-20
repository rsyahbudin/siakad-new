<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;
use App\Models\AcademicYear;
use App\Models\Teacher;
use App\Models\ClassroomAssignment;

class ClassroomAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $years = AcademicYear::all();
        $classrooms = Classroom::all();
        $teachers = Teacher::all();
        $teacherCount = $teachers->count();
        foreach ($years as $year) {
            foreach ($classrooms as $i => $classroom) {
                ClassroomAssignment::create([
                    'classroom_id' => $classroom->id,
                    'academic_year_id' => $year->id,
                    'homeroom_teacher_id' => $teacherCount > 0 ? $teachers[$i % $teacherCount]->id : null,
                ]);
            }
        }
    }
}

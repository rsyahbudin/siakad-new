<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExamSchedule;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Teacher;
use App\Models\Major;
use Carbon\Carbon;

class ExamScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data yang diperlukan
        $academicYear = AcademicYear::where('is_active', true)->first();
        if (!$academicYear) {
            $academicYear = AcademicYear::first();
        }

        $semester = Semester::where('is_active', true)->first();
        if (!$semester) {
            $semester = Semester::first();
        }

        $subjects = Subject::all();
        $classrooms = Classroom::all();
        $teachers = Teacher::all();
        $majors = Major::all();

        if (!$academicYear || !$semester || $subjects->isEmpty() || $classrooms->isEmpty() || $teachers->isEmpty()) {
            $this->command->error('Data yang diperlukan tidak ditemukan. Pastikan seeder lain sudah dijalankan.');
            return;
        }

        // Hapus data lama
        ExamSchedule::truncate();

        // Mata pelajaran umum
        $generalSubjects = $subjects->where('major_id', null);

        // Mata pelajaran jurusan
        $majorSubjects = $subjects->where('major_id', '!=', null);

        $examDates = [
            Carbon::now()->addDays(5),
            Carbon::now()->addDays(7),
            Carbon::now()->addDays(10),
            Carbon::now()->addDays(12),
            Carbon::now()->addDays(15),
            Carbon::now()->addDays(17),
            Carbon::now()->addDays(20),
            Carbon::now()->addDays(22),
        ];

        $examTimes = [
            ['start' => '08:00:00', 'end' => '10:00:00'],
            ['start' => '10:30:00', 'end' => '12:30:00'],
            ['start' => '13:00:00', 'end' => '15:00:00'],
            ['start' => '15:30:00', 'end' => '17:30:00'],
        ];

        // Buat jadwal UTS untuk mapel umum
        $dateIndex = 0;
        foreach ($classrooms as $classroom) {
            foreach ($generalSubjects as $subject) {
                $teacher = $teachers->random();
                $timeSlot = $examTimes[$dateIndex % count($examTimes)];

                ExamSchedule::create([
                    'academic_year_id' => $academicYear->id,
                    'semester_id' => $semester->id,
                    'subject_id' => $subject->id,
                    'classroom_id' => $classroom->id,
                    'supervisor_id' => $teacher->id,
                    'exam_type' => 'uts',
                    'exam_date' => $examDates[$dateIndex % count($examDates)],
                    'start_time' => $timeSlot['start'],
                    'end_time' => $timeSlot['end'],
                    'is_general_subject' => true,
                    'major_id' => null,
                ]);
                $dateIndex++;
            }
        }

        // Buat jadwal UTS untuk mapel jurusan
        foreach ($majors as $major) {
            $majorClassrooms = $classrooms->where('major_id', $major->id);
            $majorSubjectList = $majorSubjects->where('major_id', $major->id);

            foreach ($majorClassrooms as $classroom) {
                foreach ($majorSubjectList as $subject) {
                    $teacher = $teachers->random();
                    $timeSlot = $examTimes[$dateIndex % count($examTimes)];

                    ExamSchedule::create([
                        'academic_year_id' => $academicYear->id,
                        'semester_id' => $semester->id,
                        'subject_id' => $subject->id,
                        'classroom_id' => $classroom->id,
                        'supervisor_id' => $teacher->id,
                        'exam_type' => 'uts',
                        'exam_date' => $examDates[$dateIndex % count($examDates)],
                        'start_time' => $timeSlot['start'],
                        'end_time' => $timeSlot['end'],
                        'is_general_subject' => false,
                        'major_id' => $major->id,
                    ]);
                    $dateIndex++;
                }
            }
        }

        // Buat jadwal UAS untuk mapel umum
        $uasDates = [
            Carbon::now()->addDays(35),
            Carbon::now()->addDays(37),
            Carbon::now()->addDays(40),
            Carbon::now()->addDays(42),
            Carbon::now()->addDays(45),
            Carbon::now()->addDays(47),
            Carbon::now()->addDays(50),
            Carbon::now()->addDays(52),
        ];

        $dateIndex = 0;
        foreach ($classrooms as $classroom) {
            foreach ($generalSubjects as $subject) {
                $teacher = $teachers->random();
                $timeSlot = $examTimes[$dateIndex % count($examTimes)];

                ExamSchedule::create([
                    'academic_year_id' => $academicYear->id,
                    'semester_id' => $semester->id,
                    'subject_id' => $subject->id,
                    'classroom_id' => $classroom->id,
                    'supervisor_id' => $teacher->id,
                    'exam_type' => 'uas',
                    'exam_date' => $uasDates[$dateIndex % count($uasDates)],
                    'start_time' => $timeSlot['start'],
                    'end_time' => $timeSlot['end'],
                    'is_general_subject' => true,
                    'major_id' => null,
                ]);
                $dateIndex++;
            }
        }

        // Buat jadwal UAS untuk mapel jurusan
        foreach ($majors as $major) {
            $majorClassrooms = $classrooms->where('major_id', $major->id);
            $majorSubjectList = $majorSubjects->where('major_id', $major->id);

            foreach ($majorClassrooms as $classroom) {
                foreach ($majorSubjectList as $subject) {
                    $teacher = $teachers->random();
                    $timeSlot = $examTimes[$dateIndex % count($examTimes)];

                    ExamSchedule::create([
                        'academic_year_id' => $academicYear->id,
                        'semester_id' => $semester->id,
                        'subject_id' => $subject->id,
                        'classroom_id' => $classroom->id,
                        'supervisor_id' => $teacher->id,
                        'exam_type' => 'uas',
                        'exam_date' => $uasDates[$dateIndex % count($uasDates)],
                        'start_time' => $timeSlot['start'],
                        'end_time' => $timeSlot['end'],
                        'is_general_subject' => false,
                        'major_id' => $major->id,
                    ]);
                    $dateIndex++;
                }
            }
        }

        $this->command->info('Exam Schedule seeder berhasil dijalankan!');
    }
}

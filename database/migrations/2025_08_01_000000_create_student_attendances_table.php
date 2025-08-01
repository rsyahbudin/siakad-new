<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Classroom;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Schedule::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Teacher::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Subject::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Classroom::class)->constrained()->onDelete('cascade');

            // Attendance date and time
            $table->date('attendance_date');
            $table->time('attendance_time');

            // Attendance status: hadir, izin, sakit, alpha
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');

            // Optional notes
            $table->text('notes')->nullable();

            $table->timestamps();

            // Unique constraint to prevent duplicate attendance for same student, schedule, and date
            $table->unique(
                ['student_id', 'schedule_id', 'attendance_date'],
                'unique_student_schedule_date'
            );

            // Indexes for better performance
            $table->index(['attendance_date', 'schedule_id']);
            $table->index(['student_id', 'attendance_date']);
            $table->index(['teacher_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_attendances');
    }
};

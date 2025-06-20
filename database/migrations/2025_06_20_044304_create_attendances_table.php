<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\AcademicYear;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Schedule::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Teacher::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(AcademicYear::class)->constrained()->onDelete('cascade');
            $table->date('attendance_date');
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpha']);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicate entries for the same student, on the same schedule, on the same day
            $table->unique(['student_id', 'schedule_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

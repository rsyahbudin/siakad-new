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
            $table->foreignIdFor(Schedule::class)->nullable()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Teacher::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(AcademicYear::class)->constrained()->onDelete('cascade');
            $table->date('attendance_date');
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpha']);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('classroom_assignment_id');
            $table->foreign('classroom_assignment_id')->references('id')->on('classroom_assignments')->onDelete('cascade');
            $table->unsignedBigInteger('semester_id');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
            $table->timestamps();

            // Unique constraint to prevent duplicate entries for the same student, in the same class, on the same day and semester
            $table->unique(
                ['student_id', 'classroom_assignment_id', 'semester_id', 'attendance_date'],
                'uniq_attendance_sksd'
            );
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

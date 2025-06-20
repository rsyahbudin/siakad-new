<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Subject;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Subject::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Classroom::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(AcademicYear::class)->constrained()->onDelete('cascade');

            // Component grades (stored as decimal for more precise calculations)
            $table->decimal('assignment_grade', 5, 2)->nullable()->comment('Nilai Tugas');
            $table->decimal('uts_grade', 5, 2)->nullable()->comment('Nilai UTS');
            $table->decimal('uas_grade', 5, 2)->nullable()->comment('Nilai UAS');

            // Final calculated grade
            $table->decimal('final_grade', 5, 2)->nullable()->comment('Nilai Akhir');
            $table->boolean('is_passed')->nullable()->comment('Status Ketuntasan');

            // Grade source tracking
            $table->enum('source', ['input_guru', 'konversi'])->default('input_guru');

            // Unique constraint to prevent duplicate grades
            $table->unique(['student_id', 'subject_id', 'academic_year_id'], 'unique_student_subject_year');

            // Indexes for common queries
            $table->index(['student_id', 'academic_year_id']);
            $table->index(['classroom_id', 'subject_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};

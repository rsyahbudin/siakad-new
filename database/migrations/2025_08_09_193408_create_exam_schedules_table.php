<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Teacher;
use App\Models\Major;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AcademicYear::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Semester::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Subject::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Classroom::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Teacher::class, 'supervisor_id')->constrained('teachers')->onDelete('cascade');
            $table->enum('exam_type', ['uts', 'uas']);
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_general_subject')->default(false);
            $table->foreignIdFor(Major::class)->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            // Add unique constraint to prevent duplicate exam schedules
            $table->unique(['academic_year_id', 'semester_id', 'subject_id', 'classroom_id', 'exam_type'], 'unique_exam_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_schedules');
    }
};

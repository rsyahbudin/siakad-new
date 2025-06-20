<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('raports', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Classroom::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(AcademicYear::class)->constrained()->onDelete('cascade');

            // Academic period
            $table->tinyInteger('semester')->comment('1: Ganjil, 2: Genap');

            // Attendance records
            $table->unsignedTinyInteger('attendance_sick')->default(0)->comment('Jumlah Sakit');
            $table->unsignedTinyInteger('attendance_permit')->default(0)->comment('Jumlah Izin');
            $table->unsignedTinyInteger('attendance_absent')->default(0)->comment('Jumlah Alpha');

            // Teacher notes and comments
            $table->text('homeroom_teacher_notes')->nullable()->comment('Catatan Wali Kelas');

            // Finalization status
            $table->boolean('is_finalized')->default(false);
            $table->timestamp('finalized_at')->nullable();

            // Promotion status (for semester 2)
            $table->enum('promotion_status', ['RECOMMENDED', 'NOT_RECOMMENDED', 'NOT_APPLICABLE'])
                ->default('NOT_APPLICABLE')
                ->comment('Status Kenaikan Kelas');
            $table->text('promotion_notes')->nullable()->comment('Catatan Kenaikan Kelas');

            // Unique constraint
            $table->unique(['student_id', 'academic_year_id', 'semester'], 'unique_student_semester_report');

            // Common query indexes
            $table->index(['classroom_id', 'academic_year_id']);
            $table->index('is_finalized');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raports');
    }
};

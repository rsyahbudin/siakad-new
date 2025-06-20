<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\AcademicYear;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('class_student', function (Blueprint $table) {
            $table->id();

            // Relasi ke kelas
            $table->foreignIdFor(Classroom::class)->constrained()->onDelete('cascade');

            // Relasi ke siswa
            $table->foreignIdFor(Student::class)->constrained()->onDelete('cascade');

            // Relasi ke tahun ajaran
            $table->foreignIdFor(AcademicYear::class)->constrained()->onDelete('cascade');

            // Mencegah satu siswa yang sama didaftarkan ke kelas yang sama lebih dari sekali.
            $table->unique(['classroom_id', 'student_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_student');
    }
};

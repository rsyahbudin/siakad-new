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
            
            $table->unsignedTinyInteger('assignment_grade')->nullable(); // Nilai Tugas
            $table->unsignedTinyInteger('uts_grade')->nullable(); // Nilai UTS
            $table->unsignedTinyInteger('uas_grade')->nullable(); // Nilai UAS
            
            $table->enum('source', ['input_guru', 'konversi'])->default('input_guru');
            
            // Kunci unik agar seorang siswa tidak punya dua baris nilai untuk mapel yg sama di semester yg sama
            $table->unique(['student_id', 'subject_id', 'academic_year_id']);
            
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AcademicYear;
use App\Models\Major;
use App\Models\Teacher;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "X-1", "XI IPA 2"
            $table->integer('grade_level'); // 10, 11, 12
            $table->integer('capacity')->nullable();
            // Relasi ke tahun ajaran
            $table->foreignIdFor(AcademicYear::class)->constrained()->onDelete('cascade');
            // Relasi ke jurusan (nullable karena kelas X mungkin belum punya jurusan)
            $table->foreignIdFor(Major::class)->nullable()->constrained()->onDelete('set null');
            // Relasi ke guru sebagai wali kelas (homeroom teacher)
            $table->foreignIdFor(Teacher::class, 'homeroom_teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};

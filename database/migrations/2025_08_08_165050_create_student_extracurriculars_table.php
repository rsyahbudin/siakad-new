<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_extracurriculars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('extracurricular_id');
            $table->unsignedBigInteger('academic_year_id');
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Lulus'])->default('Aktif');
            $table->enum('position', ['Anggota', 'Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara'])->default('Anggota');
            $table->text('achievements')->nullable(); // Prestasi yang diraih
            $table->text('notes')->nullable(); // Catatan khusus
            $table->date('join_date')->nullable(); // Tanggal bergabung
            $table->date('leave_date')->nullable(); // Tanggal keluar
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('extracurricular_id')->references('id')->on('extracurriculars')->onDelete('cascade');
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');

            // Unique constraint untuk mencegah duplikasi
            $table->unique(['student_id', 'extracurricular_id', 'academic_year_id'], 'student_extracurricular_year_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_extracurriculars');
    }
};

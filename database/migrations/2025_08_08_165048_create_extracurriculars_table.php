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
        Schema::create('extracurriculars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->default('Umum'); // Umum, Olahraga, Seni, Akademik, dll
            $table->string('day')->nullable(); // Hari pelaksanaan
            $table->time('time_start')->nullable(); // Waktu mulai
            $table->time('time_end')->nullable(); // Waktu selesai
            $table->string('location')->nullable(); // Tempat pelaksanaan
            $table->unsignedBigInteger('teacher_id')->nullable(); // Pembina ekskul
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
            $table->integer('max_participants')->nullable(); // Maksimal peserta
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extracurriculars');
    }
};

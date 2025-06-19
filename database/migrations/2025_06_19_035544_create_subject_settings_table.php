<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AcademicYear;
use App\Models\Subject;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subject_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Subject::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(AcademicYear::class)->constrained()->onDelete('cascade');
            
            $table->unsignedTinyInteger('kkm'); // Nilai 0-255, cukup untuk KKM 0-100
            $table->unsignedTinyInteger('assignment_weight'); // Bobot Tugas dalam %
            $table->unsignedTinyInteger('uts_weight'); // Bobot UTS dalam %
            $table->unsignedTinyInteger('uas_weight'); // Bobot UAS dalam %
            
            // Kunci unik untuk memastikan satu mapel hanya punya satu set aturan per tahun ajaran
            $table->unique(['subject_id', 'academic_year_id']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_settings');
    }
};

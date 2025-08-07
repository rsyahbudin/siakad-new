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
        Schema::table('exam_schedules', function (Blueprint $table) {
            // Hapus constraint unique jika ada
            $table->dropUnique('unique_exam_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_schedules', function (Blueprint $table) {
            // Kembalikan constraint unique
            $table->unique(['academic_year_id', 'semester_id', 'subject_id', 'classroom_id', 'exam_type'], 'unique_exam_schedule');
        });
    }
};

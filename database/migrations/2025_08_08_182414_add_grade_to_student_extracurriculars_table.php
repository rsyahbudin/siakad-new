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
        Schema::table('student_extracurriculars', function (Blueprint $table) {
            $table->enum('grade', ['Sangat Baik', 'Baik', 'Cukup', 'Kurang'])->nullable()->after('notes')->comment('Nilai Ekstrakurikuler');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_extracurriculars', function (Blueprint $table) {
            $table->dropColumn('grade');
        });
    }
};

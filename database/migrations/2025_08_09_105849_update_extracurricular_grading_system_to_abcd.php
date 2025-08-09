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
            // Drop columns that are no longer needed
            $table->dropColumn(['position', 'achievements']);

            // Update grade column to use A-D system
            $table->dropColumn('grade');
            $table->enum('grade', ['A', 'B', 'C', 'D'])->nullable()->after('notes')->comment('Nilai Ekstrakurikuler A-D');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_extracurriculars', function (Blueprint $table) {
            // Restore the old columns
            $table->string('position')->default('Anggota')->after('status');
            $table->text('achievements')->nullable()->after('position');

            // Restore old grade system
            $table->dropColumn('grade');
            $table->enum('grade', ['Sangat Baik', 'Baik', 'Cukup', 'Kurang'])->nullable()->after('notes')->comment('Nilai Ekstrakurikuler');
        });
    }
};

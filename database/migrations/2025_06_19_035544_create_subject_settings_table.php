<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\AcademicYear;
use App\Models\Subject;
use App\Models\Semester;

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
            $table->foreignIdFor(Semester::class)->constrained()->onDelete('cascade');

            // KKM and grade weights
            $table->decimal('kkm', 5, 2)->comment('Kriteria Ketuntasan Minimal');
            $table->decimal('assignment_weight', 5, 2)->comment('Bobot Nilai Tugas (%)');
            $table->decimal('uts_weight', 5, 2)->comment('Bobot Nilai UTS (%)');
            $table->decimal('uas_weight', 5, 2)->comment('Bobot Nilai UAS (%)');

            // Additional settings
            $table->boolean('allow_remedial')->default(true)->comment('Izinkan Remedial');
            $table->decimal('remedial_max_grade', 5, 2)->nullable()->comment('Nilai Maksimal Remedial');
            $table->boolean('is_active')->default(true)->comment('Status Aktif');

            // Unique constraint - now includes semester
            $table->unique(['subject_id', 'academic_year_id', 'semester_id'], 'unique_subject_year_semester_settings');

            // Common query index
            $table->index(['academic_year_id', 'semester_id', 'is_active']);

            $table->timestamps();
        });

        // Add check constraint using raw SQL
        DB::statement('ALTER TABLE subject_settings ADD CONSTRAINT check_weights_sum_100 
            CHECK (assignment_weight + uts_weight + uas_weight = 100.00)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove check constraint first (if your DB requires explicit removal)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE subject_settings DROP CONSTRAINT IF EXISTS check_weights_sum_100');
        }

        Schema::dropIfExists('subject_settings');
    }
};

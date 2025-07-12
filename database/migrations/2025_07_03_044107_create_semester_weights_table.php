<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\AcademicYear;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('semester_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AcademicYear::class)->constrained()->onDelete('cascade');

            // Bobot untuk perhitungan nilai akhir tahun
            $table->decimal('ganjil_weight', 5, 2)->default(50.00)->comment('Bobot Semester Ganjil (%)');
            $table->decimal('genap_weight', 5, 2)->default(50.00)->comment('Bobot Semester Genap (%)');

            // Status aktif
            $table->boolean('is_active')->default(true)->comment('Status Aktif');

            // Timestamps
            $table->timestamps();

            // Unique constraint - satu konfigurasi per tahun ajaran
            $table->unique('academic_year_id', 'unique_academic_year_weight');

            // Index
            $table->index(['academic_year_id', 'is_active']);
        });

        // Add check constraint untuk memastikan total bobot = 100%
        DB::statement('ALTER TABLE semester_weights ADD CONSTRAINT check_semester_weights_sum_100 
            CHECK (ganjil_weight + genap_weight = 100.00)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove check constraint first
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE semester_weights DROP CONSTRAINT IF EXISTS check_semester_weights_sum_100');
        }

        Schema::dropIfExists('semester_weights');
    }
};

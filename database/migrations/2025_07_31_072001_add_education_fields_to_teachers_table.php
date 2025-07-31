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
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('degree')->nullable()->after('address'); // Gelar (S1, S2, S3, dll)
            $table->string('major')->nullable()->after('degree'); // Program Studi/Jurusan
            $table->string('university')->nullable()->after('major'); // Universitas/Institut/Sekolah Tinggi
            $table->year('graduation_year')->nullable()->after('university'); // Tahun Lulus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['degree', 'major', 'university', 'graduation_year']);
        });
    }
};

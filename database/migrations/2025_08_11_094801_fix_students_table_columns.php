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
        Schema::table('students', function (Blueprint $table) {
            // Perbaiki kolom yang mungkin bermasalah dengan panjang data
            $table->string('full_name', 100)->change();
            $table->string('birth_place', 50)->change();
            $table->string('religion', 20)->change();
            $table->string('phone_number', 20)->nullable()->change();
            $table->text('address')->nullable()->change(); // Ubah ke text untuk alamat yang panjang
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('full_name')->change();
            $table->string('birth_place')->change();
            $table->string('religion')->change();
            $table->string('phone_number')->nullable()->change();
            $table->string('address')->nullable()->change();
        });
    }
};

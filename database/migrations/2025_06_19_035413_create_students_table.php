<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel 'users' untuk login.
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');

            $table->string('nis')->unique();
            $table->string('nisn')->unique();
            $table->string('full_name');
            $table->enum('gender', ['L', 'P']);
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('religion');
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('parent_phone')->nullable();

            // Kolom ini penting untuk fitur "Siswa Pindahan" kita.
            $table->enum('status', ['Aktif', 'Pindahan', 'Lulus', 'Keluar'])->default('Aktif');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

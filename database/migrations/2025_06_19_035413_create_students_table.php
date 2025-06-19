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
            
            $table->string('nisn')->unique();
            $table->string('full_name');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->date('birth_date');
            
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

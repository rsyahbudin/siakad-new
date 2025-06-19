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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel 'users' untuk login.
            // onDelete('cascade') berarti jika akun login seorang guru dihapus,
            // data profil guru ini juga akan ikut terhapus.
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            
            $table->string('nip')->unique();
            $table->string('full_name');
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};

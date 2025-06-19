<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;  
use App\Models\Major;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            
            // Relasi ke tabel 'majors'
            // Nullable karena mapel umum tidak memiliki jurusan spesifik.
            // onDelete('set null') berarti jika sebuah jurusan dihapus,
            // kolom ini akan menjadi NULL, bukan menghapus mata pelajarannya.
            $table->foreignIdFor(Major::class)->nullable()->constrained()->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};

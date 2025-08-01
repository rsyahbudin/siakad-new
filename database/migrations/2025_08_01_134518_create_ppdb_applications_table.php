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
        Schema::create('ppdb_applications', function (Blueprint $table) {
            $table->id();

            // Data Calon Siswa
            $table->string('full_name');
            $table->string('nisn')->unique();
            $table->string('birth_place');
            $table->date('birth_date');
            $table->enum('gender', ['L', 'P']);
            $table->string('religion');
            $table->string('phone_number');
            $table->text('address');

            // Data Orang Tua
            $table->string('parent_name');
            $table->string('parent_phone');
            $table->string('parent_occupation')->nullable();
            $table->text('parent_address')->nullable();

            // Jalur Pendaftaran
            $table->enum('entry_path', ['tes', 'prestasi', 'afirmasi']);
            $table->enum('desired_major', ['IPA', 'IPS']);

            // Dokumen Upload
            $table->string('raport_file')->nullable(); // Rapor semester 1-5
            $table->string('photo_file')->nullable(); // Pas foto 3x4
            $table->string('family_card_file')->nullable(); // Fotokopi kartu keluarga
            $table->string('achievement_certificate_file')->nullable(); // Piagam prestasi (jalur prestasi)
            $table->string('financial_document_file')->nullable(); // Surat keterangan tidak mampu/KIP/PKH (jalur afirmasi)

            // Nilai dan Status
            $table->decimal('test_score', 5, 2)->nullable(); // Nilai tes (jalur tes)
            $table->decimal('average_raport_score', 5, 2)->nullable(); // Rata-rata nilai rapor
            $table->enum('status', ['pending', 'lulus', 'ditolak'])->default('pending');
            $table->text('notes')->nullable(); // Catatan admin

            // Tracking
            $table->string('application_number')->unique(); // Nomor pendaftaran otomatis
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_applications');
    }
};

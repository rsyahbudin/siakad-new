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
        Schema::create('transfer_students', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();

            // Student Information
            $table->string('full_name');
            $table->string('nisn', 10)->unique();
            $table->string('nis_previous')->nullable(); // NIS dari sekolah sebelumnya
            $table->string('birth_place');
            $table->date('birth_date');
            $table->enum('gender', ['L', 'P']);
            $table->string('religion');
            $table->string('phone_number', 20);
            $table->string('email')->unique();
            $table->text('address');

            // Parent Information
            $table->string('parent_name');
            $table->string('parent_phone', 20);
            $table->string('parent_email')->unique();
            $table->string('parent_occupation')->nullable();
            $table->text('parent_address')->nullable();

            // Previous School Information
            $table->string('previous_school_name');
            $table->text('previous_school_address');
            $table->string('previous_school_npsn')->nullable();
            $table->enum('previous_grade', ['X', 'XI', 'XII']); // Kelas asal
            $table->enum('previous_major', ['IPA', 'IPS', 'Bahasa', 'Lainnya']); // Jurusan asal
            $table->string('previous_academic_year'); // Tahun ajaran terakhir
            $table->text('transfer_reason'); // Alasan pindah

            // Desired Information
            $table->enum('desired_grade', ['X', 'XI', 'XII']); // Kelas yang diinginkan
            $table->enum('desired_major', ['IPA', 'IPS']); // Jurusan yang diinginkan

            // Document Files
            $table->string('raport_file'); // Rapor dari sekolah asal
            $table->string('photo_file');
            $table->string('family_card_file');
            $table->string('transfer_certificate_file'); // Surat pindah dari sekolah asal
            $table->string('birth_certificate_file');
            $table->string('health_certificate_file')->nullable();

            // Grade Conversion (will be filled by admin)
            $table->json('original_grades')->nullable(); // Nilai asli dari sekolah asal
            $table->json('converted_grades')->nullable(); // Nilai hasil konversi admin
            $table->text('conversion_notes')->nullable(); // Catatan konversi
            $table->enum('grade_scale', ['0-100', '0-4', 'A-F', 'Predikat'])->default('0-100'); // Skala nilai sekolah asal

            // Status and Processing
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('desired_grade');
            $table->index('desired_major');
            $table->index('previous_grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_students');
    }
};

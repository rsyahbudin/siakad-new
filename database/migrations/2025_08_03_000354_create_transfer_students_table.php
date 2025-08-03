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
            $table->string('application_number')->unique();

            // Student Information
            $table->string('full_name');
            $table->string('nisn', 10)->unique();
            $table->string('nis_previous')->nullable(); // NIS dari sekolah asal
            $table->string('birth_place');
            $table->date('birth_date');
            $table->enum('gender', ['L', 'P']);
            $table->string('religion');
            $table->string('phone_number', 20);
            $table->string('email');
            $table->text('address');

            // Parent Information
            $table->string('parent_name');
            $table->string('parent_phone', 20);
            $table->string('parent_email');
            $table->string('parent_occupation')->nullable();
            $table->text('parent_address')->nullable();

            // Previous School Information
            $table->string('previous_school_name');
            $table->text('previous_school_address');
            $table->string('previous_school_npsn')->nullable();
            $table->enum('previous_grade', ['10', '11', '12']); // Kelas terakhir di sekolah asal
            $table->enum('previous_major', ['IPA', 'IPS', 'Bahasa', 'Lainnya']);
            $table->string('transfer_reason'); // Alasan pindah

            // Target Information
            $table->enum('target_grade', ['10', '11', '12']); // Kelas yang dituju
            $table->enum('target_major', ['IPA', 'IPS']); // Jurusan yang dituju

            // Documents
            $table->string('raport_file'); // Rapor dari sekolah asal
            $table->string('photo_file');
            $table->string('family_card_file');
            $table->string('transfer_letter_file'); // Surat pindah dari sekolah asal
            $table->string('birth_certificate_file');
            $table->string('previous_certificate_file')->nullable(); // Ijazah jika ada

            // Grade Conversion
            $table->json('original_grades')->nullable(); // Nilai asli dari sekolah asal
            $table->json('converted_grades')->nullable(); // Nilai setelah konversi
            $table->boolean('grades_converted')->default(false);
            $table->text('conversion_notes')->nullable();

            // Status and Processing
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('processed_at')->nullable();

            $table->timestamps();
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

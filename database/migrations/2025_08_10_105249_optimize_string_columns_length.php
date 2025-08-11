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
        // Optimize users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 100)->change(); // Nama maksimal 100 karakter
            $table->string('email', 100)->change(); // Email maksimal 100 karakter
            $table->string('role', 20)->change(); // Role maksimal 20 karakter
            $table->string('password', 255)->change(); // Password tetap 255 untuk hash
        });

        // Optimize students table
        Schema::table('students', function (Blueprint $table) {
            $table->string('nis', 20)->change(); // NIS maksimal 20 karakter
            $table->string('nisn', 10)->change(); // NISN maksimal 10 karakter
            $table->string('full_name', 100)->change(); // Nama lengkap maksimal 100 karakter
            $table->string('birth_place', 50)->change(); // Tempat lahir maksimal 50 karakter
            $table->string('religion', 20)->change(); // Agama maksimal 20 karakter
            $table->string('phone_number', 20)->change(); // Nomor telepon maksimal 20 karakter
            $table->string('address', 200)->change(); // Alamat maksimal 200 karakter
            $table->string('parent_name', 100)->change(); // Nama orang tua maksimal 100 karakter
            $table->string('parent_phone', 20)->change(); // Telepon orang tua maksimal 20 karakter
        });

        // Optimize teachers table
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('nip', 20)->change(); // NIP maksimal 20 karakter
            $table->string('full_name', 100)->change(); // Nama lengkap maksimal 100 karakter
            $table->string('phone_number', 20)->change(); // Nomor telepon maksimal 20 karakter
        });

        // Optimize subjects table
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('name', 50)->change(); // Nama mata pelajaran maksimal 50 karakter
            $table->string('code', 10)->change(); // Kode mata pelajaran maksimal 10 karakter
        });

        // Optimize majors table
        Schema::table('majors', function (Blueprint $table) {
            $table->string('name', 50)->change(); // Nama jurusan maksimal 50 karakter
            $table->string('short_name', 10)->change(); // Singkatan jurusan maksimal 10 karakter
        });

        // Optimize classrooms table
        Schema::table('classrooms', function (Blueprint $table) {
            $table->string('name', 20)->change(); // Nama kelas maksimal 20 karakter
        });

        // Optimize academic_years table
        Schema::table('academic_years', function (Blueprint $table) {
            $table->string('year', 9)->change(); // Tahun ajaran sudah optimal (2024/2025)
        });

        // Optimize app_settings table
        Schema::table('app_settings', function (Blueprint $table) {
            $table->string('key', 50)->change(); // Key setting maksimal 50 karakter
            $table->string('value', 500)->change(); // Value setting maksimal 500 karakter
            $table->string('description', 200)->change(); // Deskripsi maksimal 200 karakter
        });

        // Optimize wali_murids table
        Schema::table('wali_murids', function (Blueprint $table) {
            $table->string('full_name', 100)->change(); // Nama lengkap maksimal 100 karakter
            $table->string('phone_number', 20)->change(); // Nomor telepon maksimal 20 karakter
            $table->string('relationship', 30)->change(); // Hubungan maksimal 30 karakter
        });

        // Optimize kepala_sekolahs table
        Schema::table('kepala_sekolahs', function (Blueprint $table) {
            $table->string('nip', 20)->change(); // NIP maksimal 20 karakter
            $table->string('full_name', 100)->change(); // Nama lengkap maksimal 100 karakter
            $table->string('phone_number', 20)->change(); // Nomor telepon maksimal 20 karakter
            $table->string('position', 30)->change(); // Jabatan maksimal 30 karakter
        });

        // Optimize ppdb_applications table
        Schema::table('ppdb_applications', function (Blueprint $table) {
            $table->string('full_name', 100)->change(); // Nama lengkap maksimal 100 karakter
            $table->string('nisn', 10)->change(); // NISN maksimal 10 karakter
            $table->string('birth_place', 50)->change(); // Tempat lahir maksimal 50 karakter
            $table->string('religion', 20)->change(); // Agama maksimal 20 karakter
            $table->string('phone_number', 20)->change(); // Nomor telepon maksimal 20 karakter
            $table->string('parent_name', 100)->change(); // Nama orang tua maksimal 100 karakter
            $table->string('parent_phone', 20)->change(); // Telepon orang tua maksimal 20 karakter
            $table->string('parent_occupation', 50)->change(); // Pekerjaan orang tua maksimal 50 karakter
            $table->string('raport_file', 255)->change(); // File tetap 255 untuk path
            $table->string('photo_file', 255)->change(); // File tetap 255 untuk path
            $table->string('family_card_file', 255)->change(); // File tetap 255 untuk path
            $table->string('achievement_certificate_file', 255)->change(); // File tetap 255 untuk path
            $table->string('financial_document_file', 255)->change(); // File tetap 255 untuk path
            $table->string('application_number', 20)->change(); // Nomor pendaftaran maksimal 20 karakter
        });

        // Optimize transfer_students table
        Schema::table('transfer_students', function (Blueprint $table) {
            $table->string('registration_number', 20)->change(); // Nomor registrasi maksimal 20 karakter
            $table->string('full_name', 100)->change(); // Nama lengkap maksimal 100 karakter
            $table->string('nisn', 10)->change(); // NISN maksimal 10 karakter
            $table->string('nis_previous', 20)->change(); // NIS sebelumnya maksimal 20 karakter
            $table->string('birth_place', 50)->change(); // Tempat lahir maksimal 50 karakter
            $table->string('religion', 20)->change(); // Agama maksimal 20 karakter
            $table->string('phone_number', 20)->change(); // Nomor telepon maksimal 20 karakter
            $table->string('email', 100)->change(); // Email maksimal 100 karakter
            $table->string('parent_name', 100)->change(); // Nama orang tua maksimal 100 karakter
            $table->string('parent_phone', 20)->change(); // Telepon orang tua maksimal 20 karakter
            $table->string('parent_email', 100)->change(); // Email orang tua maksimal 100 karakter
            $table->string('parent_occupation', 50)->change(); // Pekerjaan orang tua maksimal 50 karakter
            $table->string('previous_school_name', 100)->change(); // Nama sekolah sebelumnya maksimal 100 karakter
            $table->string('previous_school_npsn', 10)->change(); // NPSN sekolah sebelumnya maksimal 10 karakter
            $table->string('previous_academic_year', 9)->change(); // Tahun ajaran sebelumnya maksimal 9 karakter
            $table->string('raport_file', 255)->change(); // File tetap 255 untuk path
            $table->string('photo_file', 255)->change(); // File tetap 255 untuk path
            $table->string('family_card_file', 255)->change(); // File tetap 255 untuk path
            $table->string('transfer_certificate_file', 255)->change(); // File tetap 255 untuk path
            $table->string('birth_certificate_file', 255)->change(); // File tetap 255 untuk path
            $table->string('health_certificate_file', 255)->change(); // File tetap 255 untuk path
        });

        // Optimize extracurriculars table
        Schema::table('extracurriculars', function (Blueprint $table) {
            $table->string('name', 50)->change(); // Nama ekstrakurikuler maksimal 50 karakter
            $table->string('category', 30)->change(); // Kategori maksimal 30 karakter
            $table->string('day', 10)->change(); // Hari maksimal 10 karakter
            $table->string('location', 100)->change(); // Lokasi maksimal 100 karakter
        });

        // Optimize system_settings table
        Schema::table('system_settings', function (Blueprint $table) {
            $table->string('key', 50)->change(); // Key setting maksimal 50 karakter
            $table->string('description', 200)->change(); // Deskripsi maksimal 200 karakter
        });

        // Optimize password_reset_tokens table
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 100)->change(); // Email maksimal 100 karakter
            $table->string('token', 255)->change(); // Token tetap 255 untuk hash
        });

        // Optimize sessions table
        Schema::table('sessions', function (Blueprint $table) {
            $table->string('id', 255)->change(); // Session ID tetap 255
            $table->string('ip_address', 45)->change(); // IP address sudah optimal
        });

        // Optimize cache table
        Schema::table('cache', function (Blueprint $table) {
            $table->string('key', 255)->change(); // Cache key tetap 255
        });

        // Optimize jobs table
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('queue', 255)->change(); // Queue name tetap 255
        });

        // Optimize job_batches table
        Schema::table('job_batches', function (Blueprint $table) {
            $table->string('id', 255)->change(); // Job batch ID tetap 255
            $table->string('name', 255)->change(); // Job batch name tetap 255
        });

        // Optimize failed_jobs table
        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->string('uuid', 255)->change(); // UUID tetap 255
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('email')->change();
            $table->string('role')->change();
            $table->string('password')->change();
        });

        // Revert students table
        Schema::table('students', function (Blueprint $table) {
            $table->string('nis')->change();
            $table->string('nisn')->change();
            $table->string('full_name')->change();
            $table->string('birth_place')->change();
            $table->string('religion')->change();
            $table->string('phone_number')->change();
            $table->string('address')->change();
            $table->string('parent_name')->change();
            $table->string('parent_phone')->change();
        });

        // Revert teachers table
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('nip')->change();
            $table->string('full_name')->change();
            $table->string('phone_number')->change();
        });

        // Revert subjects table
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('code')->change();
        });

        // Revert majors table
        Schema::table('majors', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('short_name')->change();
        });

        // Revert classrooms table
        Schema::table('classrooms', function (Blueprint $table) {
            $table->string('name')->change();
        });

        // Revert academic_years table
        Schema::table('academic_years', function (Blueprint $table) {
            $table->string('year')->change();
        });

        // Revert app_settings table
        Schema::table('app_settings', function (Blueprint $table) {
            $table->string('key')->change();
            $table->string('value')->change();
            $table->string('description')->change();
        });

        // Revert wali_murids table
        Schema::table('wali_murids', function (Blueprint $table) {
            $table->string('full_name')->change();
            $table->string('phone_number')->change();
            $table->string('relationship')->change();
        });

        // Revert kepala_sekolahs table
        Schema::table('kepala_sekolahs', function (Blueprint $table) {
            $table->string('nip')->change();
            $table->string('full_name')->change();
            $table->string('phone_number')->change();
            $table->string('position')->change();
        });

        // Revert ppdb_applications table
        Schema::table('ppdb_applications', function (Blueprint $table) {
            $table->string('full_name')->change();
            $table->string('nisn')->change();
            $table->string('birth_place')->change();
            $table->string('religion')->change();
            $table->string('phone_number')->change();
            $table->string('parent_name')->change();
            $table->string('parent_phone')->change();
            $table->string('parent_occupation')->change();
            $table->string('raport_file')->change();
            $table->string('photo_file')->change();
            $table->string('family_card_file')->change();
            $table->string('achievement_certificate_file')->change();
            $table->string('financial_document_file')->change();
            $table->string('application_number')->change();
        });

        // Revert transfer_students table
        Schema::table('transfer_students', function (Blueprint $table) {
            $table->string('registration_number')->change();
            $table->string('full_name')->change();
            $table->string('nisn')->change();
            $table->string('nis_previous')->change();
            $table->string('birth_place')->change();
            $table->string('religion')->change();
            $table->string('phone_number')->change();
            $table->string('email')->change();
            $table->string('parent_name')->change();
            $table->string('parent_phone')->change();
            $table->string('parent_email')->change();
            $table->string('parent_occupation')->change();
            $table->string('previous_school_name')->change();
            $table->string('previous_school_npsn')->change();
            $table->string('previous_academic_year')->change();
            $table->string('raport_file')->change();
            $table->string('photo_file')->change();
            $table->string('family_card_file')->change();
            $table->string('transfer_certificate_file')->change();
            $table->string('birth_certificate_file')->change();
            $table->string('health_certificate_file')->change();
        });

        // Revert extracurriculars table
        Schema::table('extracurriculars', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('category')->change();
            $table->string('day')->change();
            $table->string('location')->change();
        });

        // Revert system_settings table
        Schema::table('system_settings', function (Blueprint $table) {
            $table->string('key')->change();
            $table->string('description')->change();
        });

        // Revert password_reset_tokens table
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->change();
            $table->string('token')->change();
        });

        // Revert sessions table
        Schema::table('sessions', function (Blueprint $table) {
            $table->string('id')->change();
            $table->string('ip_address')->change();
        });

        // Revert cache table
        Schema::table('cache', function (Blueprint $table) {
            $table->string('key')->change();
        });

        // Revert jobs table
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('queue')->change();
        });

        // Revert job_batches table
        Schema::table('job_batches', function (Blueprint $table) {
            $table->string('id')->change();
            $table->string('name')->change();
        });

        // Revert failed_jobs table
        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->string('uuid')->change();
        });
    }
};

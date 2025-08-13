<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update major_interest untuk siswa transfer yang sudah ada
        // Ambil data dari transfer_students dan update ke students
        $transferStudents = DB::table('transfer_students')
            ->where('status', 'approved')
            ->get();

        foreach ($transferStudents as $transferStudent) {
            DB::table('students')
                ->where('nisn', $transferStudent->nisn)
                ->where('status', 'Pindahan')
                ->update([
                    'major_interest' => $transferStudent->desired_major
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback karena ini adalah data fix
    }
};

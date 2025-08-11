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
        // Step 1: Migrate existing parent data from students table to wali_murids table
        $studentsWithParents = DB::table('students')
            ->whereNotNull('parent_name')
            ->where('parent_name', '!=', '')
            ->get();

        foreach ($studentsWithParents as $student) {
            // Check if wali_murid already exists for this student
            $existingWaliMurid = DB::table('wali_murids')
                ->where('student_id', $student->id)
                ->first();

            if (!$existingWaliMurid) {
                // Create new wali_murid record
                DB::table('wali_murids')->insert([
                    'user_id' => $student->user_id, // Use student's user_id as parent user_id
                    'full_name' => $student->parent_name,
                    'phone_number' => $student->parent_phone,
                    'address' => $student->address, // Use student's address as parent address
                    'relationship' => 'Orang Tua',
                    'student_id' => $student->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                // Update existing wali_murid record with parent data
                DB::table('wali_murids')
                    ->where('student_id', $student->id)
                    ->update([
                        'full_name' => $student->parent_name,
                        'phone_number' => $student->parent_phone,
                        'updated_at' => now(),
                    ]);
            }
        }

        // Step 2: Remove parent columns from students table
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['parent_name', 'parent_phone']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Add back parent columns to students table
        Schema::table('students', function (Blueprint $table) {
            $table->string('parent_name', 100)->nullable()->after('address');
            $table->string('parent_phone', 20)->nullable()->after('parent_name');
        });

        // Step 2: Migrate data back from wali_murids to students table
        $waliMurids = DB::table('wali_murids')->get();

        foreach ($waliMurids as $waliMurid) {
            DB::table('students')
                ->where('id', $waliMurid->student_id)
                ->update([
                    'parent_name' => $waliMurid->full_name,
                    'parent_phone' => $waliMurid->phone_number,
                ]);
        }
    }
};

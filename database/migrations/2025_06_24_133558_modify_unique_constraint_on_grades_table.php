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
        Schema::table('grades', function (Blueprint $table) {
            // Drop the old unique constraint
            $table->dropUnique('unique_student_subject_year');

            // Add the new, more specific unique constraint
            $table->unique(['student_id', 'subject_id', 'semester_id'], 'unique_student_subject_semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Drop the new unique constraint
            $table->dropUnique('unique_student_subject_semester');

            // Re-add the old unique constraint
            $table->unique(['student_id', 'subject_id', 'academic_year_id'], 'unique_student_subject_year');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_student', function (Blueprint $table) {
            $table->unsignedBigInteger('classroom_assignment_id')->nullable()->after('classroom_id');
            $table->foreign('classroom_assignment_id')->references('id')->on('classroom_assignments')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('class_student', function (Blueprint $table) {
            $table->dropForeign(['classroom_assignment_id']);
            $table->dropColumn('classroom_assignment_id');
        });
    }
};

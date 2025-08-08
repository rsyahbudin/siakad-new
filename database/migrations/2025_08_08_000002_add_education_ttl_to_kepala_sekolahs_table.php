<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kepala_sekolahs', function (Blueprint $table) {
            $table->string('degree')->nullable()->after('last_education');
            $table->string('major')->nullable()->after('degree');
            $table->string('university')->nullable()->after('major');
            $table->unsignedSmallInteger('graduation_year')->nullable()->after('university');
            $table->string('birth_place')->nullable()->after('graduation_year');
            $table->date('birth_date')->nullable()->after('birth_place');
        });
    }

    public function down(): void
    {
        Schema::table('kepala_sekolahs', function (Blueprint $table) {
            $table->dropColumn(['degree', 'major', 'university', 'graduation_year', 'birth_place', 'birth_date']);
        });
    }
};

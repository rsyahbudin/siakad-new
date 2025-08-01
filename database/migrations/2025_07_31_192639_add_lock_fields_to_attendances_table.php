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
        Schema::table('attendances', function (Blueprint $table) {
            $table->boolean('is_locked')->default(false)->after('alpha');
            $table->timestamp('locked_at')->nullable()->after('is_locked');
            $table->unsignedBigInteger('locked_by')->nullable()->after('locked_at');
            $table->foreign('locked_by')->references('id')->on('teachers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['locked_by']);
            $table->dropColumn(['is_locked', 'locked_at', 'locked_by']);
        });
    }
};

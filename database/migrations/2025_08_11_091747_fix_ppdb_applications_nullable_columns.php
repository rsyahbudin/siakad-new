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
        Schema::table('ppdb_applications', function (Blueprint $table) {
            // Fix nullable columns that should be nullable
            $table->string('achievement_certificate_file')->nullable()->change();
            $table->string('financial_document_file')->nullable()->change();
            $table->string('raport_file')->nullable()->change();
            $table->string('photo_file')->nullable()->change();
            $table->string('family_card_file')->nullable()->change();
            $table->decimal('test_score', 5, 2)->nullable()->change();
            $table->decimal('average_raport_score', 5, 2)->nullable()->change();
            $table->text('notes')->nullable()->change();
            $table->string('parent_occupation')->nullable()->change();
            $table->text('parent_address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_applications', function (Blueprint $table) {
            // Revert changes if needed
            $table->string('achievement_certificate_file')->nullable(false)->change();
            $table->string('financial_document_file')->nullable(false)->change();
            $table->string('raport_file')->nullable(false)->change();
            $table->string('photo_file')->nullable(false)->change();
            $table->string('family_card_file')->nullable(false)->change();
            $table->decimal('test_score', 5, 2)->nullable(false)->change();
            $table->decimal('average_raport_score', 5, 2)->nullable(false)->change();
            $table->text('notes')->nullable(false)->change();
            $table->string('parent_occupation')->nullable(false)->change();
            $table->text('parent_address')->nullable(false)->change();
        });
    }
};

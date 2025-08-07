<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Teacher;
use App\Models\Major;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exam_schedules', function (Blueprint $table) {
            // Tambahkan kolom supervisor_id jika belum ada
            if (!Schema::hasColumn('exam_schedules', 'supervisor_id')) {
                $table->foreignIdFor(Teacher::class, 'supervisor_id')->constrained()->onDelete('cascade');
            }

            // Tambahkan kolom is_general_subject jika belum ada
            if (!Schema::hasColumn('exam_schedules', 'is_general_subject')) {
                $table->boolean('is_general_subject')->default(false);
            }

            // Tambahkan kolom major_id jika belum ada
            if (!Schema::hasColumn('exam_schedules', 'major_id')) {
                $table->foreignIdFor(Major::class)->nullable()->constrained()->onDelete('set null');
            }

            // Hapus kolom yang tidak diperlukan
            if (Schema::hasColumn('exam_schedules', 'room_name')) {
                $table->dropColumn('room_name');
            }

            if (Schema::hasColumn('exam_schedules', 'target_grade')) {
                $table->dropColumn('target_grade');
            }

            if (Schema::hasColumn('exam_schedules', 'target_major')) {
                $table->dropColumn('target_major');
            }

            if (Schema::hasColumn('exam_schedules', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_schedules', function (Blueprint $table) {
            // Hapus kolom yang ditambahkan
            if (Schema::hasColumn('exam_schedules', 'supervisor_id')) {
                $table->dropForeign(['supervisor_id']);
                $table->dropColumn('supervisor_id');
            }

            if (Schema::hasColumn('exam_schedules', 'is_general_subject')) {
                $table->dropColumn('is_general_subject');
            }

            if (Schema::hasColumn('exam_schedules', 'major_id')) {
                $table->dropForeign(['major_id']);
                $table->dropColumn('major_id');
            }

            // Kembalikan kolom yang dihapus
            $table->string('room_name')->nullable();
            $table->string('target_grade')->nullable();
            $table->string('target_major')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }
};

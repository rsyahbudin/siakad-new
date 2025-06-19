<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(AcademicYear::class, 'promotion_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->foreignIdFor(Classroom::class, 'from_classroom_id')->constrained('classrooms')->onDelete('cascade');
            
            $table->enum('system_recommendation', ['Layak Naik', 'Tidak Layak Naik']);
            $table->enum('final_decision', ['Naik Kelas', 'Tidak Naik Kelas'])->nullable();
            
            $table->text('notes')->nullable();
            $table->foreignIdFor(User::class, 'processed_by_user_id')->nullable()->constrained('users')->onDelete('set null');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_promotions');
    }
};

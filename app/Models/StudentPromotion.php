<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\User;
use App\Models\Student;

class StudentPromotion extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'promotion_year_id',
        'from_classroom_id',
        'system_recommendation',
        'final_decision',
        'notes',
        'processed_by_user_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        // Ingat kita menggunakan nama custom di migrasi
        return $this->belongsTo(AcademicYear::class, 'promotion_year_id');
    }

    public function fromClassroom()
    {
        // Nama custom juga di sini
        return $this->belongsTo(Classroom::class, 'from_classroom_id');
    }

    public function processedBy()
    {
        // Dan di sini
        return $this->belongsTo(User::class, 'processed_by_user_id');
    }
}

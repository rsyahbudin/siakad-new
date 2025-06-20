<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'academic_year_id',
        'homeroom_teacher_id',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function homeroomTeacher()
    {
        return $this->belongsTo(Teacher::class, 'homeroom_teacher_id');
    }

    public function classStudents()
    {
        return $this->hasMany(ClassStudent::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassStudent extends Model
{
    protected $table = 'class_student';
    protected $fillable = [
        'classroom_id',
        'classroom_assignment_id',
        'academic_year_id',
        'student_id',
    ];

    public function classroomAssignment()
    {
        return $this->belongsTo(\App\Models\ClassroomAssignment::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    protected static function booted()
    {
        static::creating(function ($classStudent) {
            if (empty($classStudent->classroom_id) && !empty($classStudent->classroom_assignment_id)) {
                $assignment = \App\Models\ClassroomAssignment::find($classStudent->classroom_assignment_id);
                if ($assignment) {
                    $classStudent->classroom_id = $assignment->classroom_id;
                }
            }
        });
    }
}

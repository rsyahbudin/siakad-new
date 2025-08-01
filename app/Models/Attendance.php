<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'academic_year_id',
        'classroom_assignment_id',
        'semester_id',
        'sakit',
        'izin',
        'alpha',
        'is_locked',
        'locked_at',
        'locked_by',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'locked_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function classroomAssignment()
    {
        return $this->belongsTo(ClassroomAssignment::class);
    }

    public function lockedByTeacher()
    {
        return $this->belongsTo(Teacher::class, 'locked_by');
    }
}

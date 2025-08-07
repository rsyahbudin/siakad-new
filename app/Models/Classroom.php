<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\Major;
use App\Models\AcademicYear;
use App\Models\ClassroomAssignment;
use App\Models\ClassStudent;

class Classroom extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'grade_level',
        'capacity',
        'major_id',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function students()
    {
        // Relasi many-to-many ke siswa
        return $this->belongsToMany(Student::class, 'class_student');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function classroomAssignments()
    {
        return $this->hasMany(ClassroomAssignment::class);
    }

    /**
     * Get the grade string from grade_level
     */
    public function getGradeAttribute()
    {
        return $this->grade_level == 10 ? 'X' : ($this->grade_level == 11 ? 'XI' : 'XII');
    }

    /**
     * Get the grade string from grade_level
     */
    public function getGradeString()
    {
        return $this->grade;
    }

    public function classStudents()
    {
        return $this->hasMany(ClassStudent::class);
    }
}

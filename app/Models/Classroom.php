<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\Major;
use App\Models\AcademicYear;

class Classroom extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'academic_year_id',
        'major_id',
        'homeroom_teacher_id',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function homeroomTeacher()
    {
        // Relasi ke wali kelas
        return $this->belongsTo(Teacher::class, 'homeroom_teacher_id');
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
}

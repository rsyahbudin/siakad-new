<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\ClassroomAssignment;

class Teacher extends Model
{

    protected $fillable = [
        'user_id',
        'nip',
        'full_name',
        'phone_number',
        'address',
        'subject_id',
        'degree',
        'major',
        'university',
        'graduation_year',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function homeroomClassrooms()
    {
        // Relasi untuk kelas di mana guru ini adalah wali kelas melalui classroom assignments
        return $this->hasManyThrough(
            Classroom::class,
            ClassroomAssignment::class,
            'homeroom_teacher_id', // Foreign key di classroom_assignments
            'id', // Foreign key di classrooms
            'id', // Local key di teachers
            'classroom_id' // Local key di classroom_assignments
        );
    }

    public function schedules()
    {
        // Relasi untuk semua jadwal mengajar guru ini
        return $this->hasMany(Schedule::class);
    }

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function classroomAssignments()
    {
        return $this->hasMany(ClassroomAssignment::class, 'homeroom_teacher_id');
    }

    public function extracurriculars()
    {
        return $this->hasMany(Extracurricular::class);
    }

    public function examSchedules()
    {
        return $this->hasMany(\App\Models\ExamSchedule::class, 'supervisor_id');
    }
}

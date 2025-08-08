<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Raport;
use App\Models\User;
use App\Models\Attendance;
use App\Models\ClassStudent;
use App\Models\ClassroomAssignment;

class Student extends Model
{

    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'full_name',
        'gender',
        'birth_place',
        'birth_date',
        'religion',
        'address',
        'parent_name',
        'parent_phone',
        'phone_number',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date', // Otomatis jadi objek Carbon
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classrooms()
    {
        // Relasi many-to-many ke kelas
        return $this->belongsToMany(Classroom::class, 'class_student');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function raports()
    {
        return $this->hasMany(Raport::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function classStudents()
    {
        return $this->hasMany(ClassStudent::class);
    }

    public function classroomAssignments()
    {
        return $this->hasManyThrough(ClassroomAssignment::class, ClassStudent::class, 'student_id', 'id', 'id', 'classroom_assignment_id');
    }

    public function waliMurids()
    {
        return $this->hasMany(WaliMurid::class);
    }

    public function extracurriculars()
    {
        return $this->belongsToMany(Extracurricular::class, 'student_extracurriculars')
            ->withPivot(['status', 'position', 'achievements', 'notes', 'grade', 'join_date', 'leave_date', 'academic_year_id'])
            ->withTimestamps();
    }

    public function getActiveExtracurriculars($academicYearId = null)
    {
        $query = $this->extracurriculars()->wherePivot('status', 'Aktif');

        if ($academicYearId) {
            $query->wherePivot('academic_year_id', $academicYearId);
        }

        return $query->get();
    }
}

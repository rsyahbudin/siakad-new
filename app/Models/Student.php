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
use App\Models\TransferStudent;

class Student extends Model
{

    protected $fillable = [
        'user_id',
        'ppdb_application_id',
        'nis',
        'nisn',
        'full_name',
        'gender',
        'birth_place',
        'birth_date',
        'religion',
        'address',
        'phone_number',
        'status',
        'major_interest',
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
            ->withPivot(['status', 'notes', 'grade', 'join_date', 'leave_date', 'academic_year_id'])
            ->withTimestamps();
    }

    public function ppdbApplication()
    {
        return $this->belongsTo(PPDBApplication::class, 'ppdb_application_id');
    }

    public function transferStudent()
    {
        return $this->hasOne(TransferStudent::class, 'nisn', 'nisn');
    }

    public function getTransferData()
    {
        if ($this->status === 'Pindahan') {
            return TransferStudent::where('nisn', $this->nisn)->first();
        }
        return null;
    }

    public function getMajorInterest()
    {
        // Prioritas: PPDB Application > major_interest field
        if ($this->ppdbApplication && $this->ppdbApplication->desired_major) {
            return $this->ppdbApplication->desired_major;
        }

        if ($this->major_interest) {
            return $this->major_interest;
        }

        return null;
    }

    public function getMajorInterestSource()
    {
        if ($this->ppdbApplication && $this->ppdbApplication->desired_major) {
            return 'PPDB';
        }

        if ($this->major_interest) {
            return 'Transfer';
        }

        return null;
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

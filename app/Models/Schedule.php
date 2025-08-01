<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Attendance;

class Schedule extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'classroom_id',
        'classroom_assignment_id',
        'subject_id',
        'teacher_id',
        'day',
        'time_start',
        'time_end',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function studentAttendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    public function classroomAssignment()
    {
        return $this->belongsTo(\App\Models\ClassroomAssignment::class);
    }

    protected static function booted()
    {
        static::creating(function ($schedule) {
            if (empty($schedule->classroom_id) && !empty($schedule->classroom_assignment_id)) {
                $assignment = \App\Models\ClassroomAssignment::find($schedule->classroom_assignment_id);
                if ($assignment) {
                    $schedule->classroom_id = $assignment->classroom_id;
                }
            }
        });
    }
}

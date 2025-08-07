<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Teacher;
use App\Models\AcademicYear;
use App\Models\Semester;

class ExamSchedule extends Model
{
    protected $fillable = [
        'academic_year_id',
        'semester_id',
        'subject_id',
        'classroom_id',
        'supervisor_id',
        'exam_type', // 'uts' atau 'uas'
        'exam_date',
        'start_time',
        'end_time',
        'is_general_subject', // true untuk mapel umum, false untuk mapel jurusan
        'major_id', // nullable, hanya untuk mapel jurusan
    ];

    protected $casts = [
        'exam_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_general_subject' => 'boolean',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Teacher::class, 'supervisor_id');
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    // Scope untuk mendapatkan jadwal UTS
    public function scopeUts($query)
    {
        return $query->where('exam_type', 'uts');
    }

    // Scope untuk mendapatkan jadwal UAS
    public function scopeUas($query)
    {
        return $query->where('exam_type', 'uas');
    }

    // Scope untuk mendapatkan jadwal mapel umum
    public function scopeGeneralSubjects($query)
    {
        return $query->where('is_general_subject', true);
    }

    // Scope untuk mendapatkan jadwal mapel jurusan
    public function scopeMajorSubjects($query)
    {
        return $query->where('is_general_subject', false);
    }

    // Scope untuk mendapatkan jadwal berdasarkan jurusan
    public function scopeByMajor($query, $majorId)
    {
        return $query->where(function ($q) use ($majorId) {
            $q->where('is_general_subject', true)
                ->orWhere('major_id', $majorId);
        });
    }

    // Scope untuk mendapatkan jadwal berdasarkan kelas
    public function scopeByClassroom($query, $classroomId)
    {
        return $query->where('classroom_id', $classroomId);
    }

    // Scope untuk mendapatkan jadwal berdasarkan semester aktif
    public function scopeActiveSemester($query)
    {
        return $query->whereHas('semester', function ($q) {
            $q->where('is_active', true);
        });
    }
}

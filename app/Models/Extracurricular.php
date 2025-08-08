<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\AcademicYear;

class Extracurricular extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'day',
        'time_start',
        'time_end',
        'location',
        'teacher_id',
        'max_participants',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'time_start' => 'datetime',
        'time_end' => 'datetime',
        'max_participants' => 'integer',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_extracurriculars')
            ->withPivot(['status', 'position', 'achievements', 'notes', 'grade', 'join_date', 'leave_date', 'academic_year_id'])
            ->withTimestamps();
    }

    public function academicYears()
    {
        return $this->belongsToMany(AcademicYear::class, 'student_extracurriculars')
            ->withPivot(['student_id', 'status', 'position', 'achievements', 'notes', 'join_date', 'leave_date'])
            ->withTimestamps();
    }

    public function getActiveStudentsCount()
    {
        return $this->students()->wherePivot('status', 'Aktif')->count();
    }

    public function getAvailableSlots()
    {
        if (!$this->max_participants) {
            return null; // Tidak terbatas
        }
        return max(0, $this->max_participants - $this->getActiveStudentsCount());
    }

    public function isFull()
    {
        if (!$this->max_participants) {
            return false; // Tidak pernah penuh jika tidak ada batasan
        }
        return $this->getActiveStudentsCount() >= $this->max_participants;
    }

    public function getScheduleText()
    {
        if (!$this->day) {
            return 'Jadwal belum ditentukan';
        }

        $schedule = $this->day;
        if ($this->time_start && $this->time_end) {
            $schedule .= ' ' . $this->time_start->format('H:i') . ' - ' . $this->time_end->format('H:i');
        }

        if ($this->location) {
            $schedule .= ' di ' . $this->location;
        }

        return $schedule;
    }
}

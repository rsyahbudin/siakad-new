<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Grade;

class Raport extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'classroom_id',
        'academic_year_id',
        'semester',
        'homeroom_teacher_notes',
        'attendance_sick',
        'attendance_permit',
        'attendance_absent',
        'is_finalized',
        'finalized_at',
        'promotion_status',
        'promotion_notes'
    ];

    protected $casts = [
        'is_finalized' => 'boolean',
        'finalized_at' => 'datetime',
        'attendance_sick' => 'integer',
        'attendance_permit' => 'integer',
        'attendance_absent' => 'integer'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function grades()
    {
        return Grade::where([
            'student_id' => $this->student_id,
            'classroom_id' => $this->classroom_id,
            'academic_year_id' => $this->academic_year_id
        ])->get();
    }

    public static function getMaxFailedSubjects()
    {
        return config('siakad.max_failed_subjects', 2);
    }

    /**
     * Calculate promotion recommendation based on grades
     */
    public function calculatePromotionRecommendation(): string
    {
        if ($this->semester !== 2) {
            return 'NOT_APPLICABLE'; // Only calculate for second semester
        }

        $grades = $this->grades();
        $failedSubjects = $grades->filter(function ($grade) {
            return !$grade->is_passed;
        })->count();

        // Logic: More than max_failed_subjects = not recommended for promotion
        $maxFailed = self::getMaxFailedSubjects();
        if ($failedSubjects > $maxFailed) {
            return 'NOT_RECOMMENDED';
        }

        return 'RECOMMENDED';
    }

    /**
     * Finalize the report card
     */
    public function finalize(): void
    {
        if (!$this->is_finalized) {
            $this->is_finalized = true;
            $this->finalized_at = now();
            $this->promotion_status = $this->calculatePromotionRecommendation();
            $this->save();
        }
    }

    /**
     * Check if report can be modified
     */
    public function canBeModified(): bool
    {
        return !$this->is_finalized;
    }

    /**
     * Get the status label
     */
    public function getStatusLabel(): string
    {
        if (!$this->is_finalized) {
            return 'Draft';
        }
        return 'Final';
    }

    /**
     * Get promotion status label
     */
    public function getPromotionStatusLabel(): string
    {
        return match ($this->promotion_status) {
            'RECOMMENDED' => 'Layak Naik',
            'NOT_RECOMMENDED' => 'Tidak Layak Naik',
            default => 'Belum Ditentukan'
        };
    }
}

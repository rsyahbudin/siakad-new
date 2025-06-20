<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\SubjectSetting;

class Grade extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'subject_id',
        'classroom_id',
        'academic_year_id',
        'assignment_grade',
        'uts_grade',
        'uas_grade',
        'final_grade',
        'is_passed',
        'source',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'assignment_grade' => 'float',
        'uts_grade' => 'float',
        'uas_grade' => 'float',
        'final_grade' => 'float',
        'is_passed' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Calculate final grade based on weights from subject settings
     */
    public function calculateFinalGrade(): float
    {
        $settings = SubjectSetting::where('subject_id', $this->subject_id)
            ->where('academic_year_id', $this->academic_year_id)
            ->first();

        if (!$settings) {
            return 0;
        }

        $final = ($this->assignment_grade * $settings->assignment_weight / 100) +
            ($this->uts_grade * $settings->uts_weight / 100) +
            ($this->uas_grade * $settings->uas_weight / 100);

        $this->final_grade = round($final, 2);
        $this->is_passed = $final >= $settings->kkm;
        $this->save();

        return $this->final_grade;
    }

    /**
     * Check if the grade meets KKM requirements
     */
    public function isPassingGrade(): bool
    {
        return $this->is_passed;
    }

    /**
     * Get the grade status (Passed/Failed)
     */
    public function getStatus(): string
    {
        return $this->is_passed ? 'Tuntas' : 'Tidak Tuntas';
    }

    /**
     * Check if this is a conversion grade (for transfer students)
     */
    public function isConversionGrade(): bool
    {
        return $this->source === 'conversion';
    }

    /**
     * Calculate and return the final score without saving it.
     * This is useful for previews or recommendations.
     *
     * @param float $assignmentWeight
     * @param float $utsWeight
     * @param float $uasWeight
     * @return float
     */
    public function getFinalScore(float $assignmentWeight, float $utsWeight, float $uasWeight): float
    {
        $final = ($this->assignment_grade * $assignmentWeight / 100) +
            ($this->uts_grade * $utsWeight / 100) +
            ($this->uas_grade * $uasWeight / 100);

        return round($final, 2);
    }
}

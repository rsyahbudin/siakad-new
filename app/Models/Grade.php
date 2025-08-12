<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\SubjectSetting;
use App\Models\SemesterWeight;

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
        'classroom_assignment_id',
        'academic_year_id',
        'semester_id',
        'assignment_grade',
        'uts_grade',
        'uas_grade',
        'attitude_grade',
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

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function scheduleTeacher()
    {
        return $this->hasOne(Schedule::class, 'subject_id', 'subject_id')
            ->where('classroom_id', $this->classroom_id)
            ->where('classroom_assignment_id', function ($query) {
                $query->select('id')
                    ->from('classroom_assignments')
                    ->where('classroom_id', $this->classroom_id)
                    ->where('academic_year_id', $this->academic_year_id)
                    ->first();
            })
            ->with('teacher');
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

    /**
     * Calculate yearly grade for a subject using semester weights
     *
     * @param int $studentId
     * @param int $subjectId
     * @param int $academicYearId
     * @return float|null
     */
    public static function calculateYearlyGradeForStudentSubject($studentId, $subjectId, $academicYearId)
    {
        $semesterWeights = \App\Models\SemesterWeight::where('academic_year_id', $academicYearId)
            ->where('is_active', true)
            ->first();
        if (!$semesterWeights) {
            return null;
        }
        $ganjilGrade = self::where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->where('academic_year_id', $academicYearId)
            ->whereHas('semester', function ($query) {
                $query->where('name', 'Ganjil');
            })
            ->first();
        $genapGrade = self::where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->where('academic_year_id', $academicYearId)
            ->whereHas('semester', function ($query) {
                $query->where('name', 'Genap');
            })
            ->first();
        if (!$ganjilGrade || !$genapGrade) {
            return null;
        }
        $yearlyGrade = $semesterWeights->calculateYearlyGrade(
            $ganjilGrade->final_grade ?? 0,
            $genapGrade->final_grade ?? 0
        );
        return $yearlyGrade;
    }

    /**
     * Get KKM for this grade's subject and semester
     */
    public function getKKM(): ?int
    {
        $setting = SubjectSetting::where('subject_id', $this->subject_id)
            ->where('academic_year_id', $this->academic_year_id)
            ->first();
        return $setting?->kkm;
    }

    protected static function booted()
    {
        static::creating(function ($grade) {
            if (empty($grade->classroom_id) && !empty($grade->classroom_assignment_id)) {
                $assignment = \App\Models\ClassroomAssignment::find($grade->classroom_assignment_id);
                if ($assignment) {
                    $grade->classroom_id = $assignment->classroom_id;
                }
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AcademicYear;
use App\Models\Subject;

class SubjectSetting extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subject_id',
        'academic_year_id',
        'kkm',
        'assignment_weight',
        'uts_weight',
        'uas_weight',
        'allow_remedial',
        'remedial_max_grade',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'kkm' => 'integer',
        'assignment_weight' => 'integer',
        'uts_weight' => 'integer',
        'uas_weight' => 'integer',
        'allow_remedial' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get settings for active semester
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

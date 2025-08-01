<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom;
use App\Models\SubjectSetting;
use App\Models\Grade;
use App\Models\Raport;
use App\Models\StudentPromotion;
use App\Models\Attendance;
use App\Models\Semester;
use App\Models\ClassroomAssignment;

class AcademicYear extends Model
{
    protected $fillable = [
        'year',
        'is_active',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function subjectSettings()
    {
        return $this->hasMany(SubjectSetting::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function raports()
    {
        return $this->hasMany(Raport::class);
    }

    public function studentPromotions()
    {
        return $this->hasMany(StudentPromotion::class, 'promotion_year_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    public function classroomAssignments()
    {
        return $this->hasMany(ClassroomAssignment::class);
    }

    /**
     * Set this academic year as active and deactivate others
     */
    public function setAsActive(): void
    {
        DB::transaction(function () {
            // Deactivate all other academic years
            self::where('id', '!=', $this->id)->update(['is_active' => false]);

            // Activate this one
            $this->is_active = true;
            $this->save();
        });
    }

    /**
     * Get the currently active academic year
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Check if this academic year is the active one
     */
    public function isActiveYear(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if grades can be input for this academic year
     */
    public function canInputGrades(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        return $now->between($this->start_date, $this->end_date);
    }
}

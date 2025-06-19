<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Classroom;
use App\Models\SubjectSetting;
use App\Models\Grade;
use App\Models\Raport;
use App\Models\StudentPromotion;

class AcademicYear extends Model
{

    protected $fillable = ['year', 'semester', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean', // Otomatis jadi true/false
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
}

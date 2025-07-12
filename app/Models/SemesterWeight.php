<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AcademicYear;

class SemesterWeight extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'ganjil_weight',
        'genap_weight',
        'is_active',
    ];

    protected $casts = [
        'ganjil_weight' => 'decimal:2',
        'genap_weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get active semester weights for academic year
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get semester weights for specific academic year
     */
    public function scopeForAcademicYear($query, $academicYearId)
    {
        return $query->where('academic_year_id', $academicYearId);
    }

    /**
     * Calculate yearly grade using semester weights
     */
    public function calculateYearlyGrade(float $ganjilGrade, float $genapGrade): float
    {
        $yearlyGrade = ($ganjilGrade * $this->ganjil_weight / 100) +
            ($genapGrade * $this->genap_weight / 100);

        return round($yearlyGrade, 2);
    }
}

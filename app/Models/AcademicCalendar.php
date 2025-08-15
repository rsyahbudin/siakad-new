<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AcademicCalendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'type',
        'priority',
        'is_all_day',
        'is_active',
        'academic_year_id',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_all_day' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByAcademicYear($query, $academicYearId)
    {
        return $query->where('academic_year_id', $academicYearId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate])
                ->orWhere(function ($subQ) use ($startDate, $endDate) {
                    $subQ->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                });
        });
    }

    // Accessors
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date->format('d/m/Y');
    }

    public function getFormattedEndDateAttribute()
    {
        return $this->end_date ? $this->end_date->format('d/m/Y') : null;
    }

    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time ? $this->start_time->format('H:i') : null;
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time ? $this->end_time->format('H:i') : null;
    }

    public function getDurationAttribute()
    {
        if ($this->is_all_day) {
            $start = Carbon::parse($this->start_date);
            $end = $this->end_date ? Carbon::parse($this->end_date) : $start;
            $days = $start->diffInDays($end) + 1;
            return $days . ' hari';
        } else {
            if ($this->start_time && $this->end_time) {
                $start = Carbon::parse($this->start_time);
                $end = Carbon::parse($this->end_time);
                $hours = $start->diffInHours($end);
                $minutes = $start->diffInMinutes($end) % 60;
                return $hours . ' jam ' . $minutes . ' menit';
            }
            return 'Tidak ditentukan';
        }
    }

    public function getTypeLabelAttribute()
    {
        $types = [
            'academic' => 'Akademik',
            'holiday' => 'Libur',
            'exam' => 'Ujian',
            'meeting' => 'Rapat',
            'other' => 'Lainnya',
        ];
        return $types[$this->type] ?? $this->type;
    }

    public function getPriorityLabelAttribute()
    {
        $priorities = [
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
        ];
        return $priorities[$this->priority] ?? $this->priority;
    }

    public function getPriorityColorAttribute()
    {
        $colors = [
            'low' => 'text-green-600 bg-green-100',
            'medium' => 'text-yellow-600 bg-yellow-100',
            'high' => 'text-red-600 bg-red-100',
        ];
        return $colors[$this->priority] ?? 'text-gray-600 bg-gray-100';
    }
}

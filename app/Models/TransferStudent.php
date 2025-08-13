<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TransferStudent extends Model
{
    protected $fillable = [
        'registration_number',
        'full_name',
        'nisn',
        'nis_previous',
        'birth_place',
        'birth_date',
        'gender',
        'religion',
        'phone_number',
        'email',
        'address',
        'parent_name',
        'parent_phone',
        'parent_email',
        'parent_occupation',
        'parent_address',
        'previous_school_name',
        'previous_school_address',
        'previous_school_npsn',
        'previous_grade',
        'previous_major',
        'previous_academic_year',
        'transfer_reason',
        'desired_grade',
        'desired_major',
        'raport_file',
        'photo_file',
        'family_card_file',
        'transfer_certificate_file',
        'birth_certificate_file',
        'health_certificate_file',
        'original_grades',
        'converted_grades',
        'conversion_notes',
        'grade_scale', // Skala nilai sekolah asal (0-100, 0-4, A-F, dll)
        'status',
        'notes',
        'processed_by',
        'submitted_at',
        'processed_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'original_grades' => 'array',
        'converted_grades' => 'array',
        'grades_converted' => 'boolean',
        'processed_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    // Constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    const GRADE_10 = '10';
    const GRADE_11 = '11';
    const GRADE_12 = '12';

    const MAJOR_IPA = 'IPA';
    const MAJOR_IPS = 'IPS';
    const MAJOR_BAHASA = 'Bahasa';
    const MAJOR_LAINNYA = 'Lainnya';

    // Grade Scale Constants
    const SCALE_0_100 = '0-100';
    const SCALE_0_4 = '0-4';
    const SCALE_A_F = 'A-F';
    const SCALE_PREDIKAT = 'Predikat';

    const STATUS = [
        self::STATUS_PENDING => 'Menunggu',
        self::STATUS_APPROVED => 'Disetujui',
        self::STATUS_REJECTED => 'Ditolak',
    ];

    const GRADES = [
        self::GRADE_10 => 'Kelas 10',
        self::GRADE_11 => 'Kelas 11',
        self::GRADE_12 => 'Kelas 12',
    ];

    const MAJORS = [
        self::MAJOR_IPA => 'IPA',
        self::MAJOR_IPS => 'IPS',
    ];

    const PREVIOUS_MAJORS = [
        self::MAJOR_IPA => 'IPA',
        self::MAJOR_IPS => 'IPS',
        self::MAJOR_BAHASA => 'Bahasa',
        self::MAJOR_LAINNYA => 'Lainnya',
    ];

    const GRADE_SCALES = [
        self::SCALE_0_100 => 'Skala 0-100',
        self::SCALE_0_4 => 'Skala 0-4',
        self::SCALE_A_F => 'Skala A-F',
        self::SCALE_PREDIKAT => 'Predikat (Sangat Baik/Baik/Cukup/Kurang)',
    ];

    /**
     * Boot method to auto-generate registration number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transferStudent) {
            if (empty($transferStudent->registration_number)) {
                $transferStudent->registration_number = self::generateRegistrationNumber();
            }
        });
    }

    /**
     * Generate unique registration number
     */
    public static function generateRegistrationNumber()
    {
        $year = date('Y');
        $month = date('m');

        do {
            $number = 'TS' . $year . $month . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('registration_number', $number)->exists());

        return $number;
    }

    /**
     * Relationships
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Accessor methods
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUS[$this->status] ?? $this->status;
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_APPROVED => 'bg-green-100 text-green-800',
            self::STATUS_REJECTED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPreviousGradeLabelAttribute()
    {
        return self::GRADES[$this->previous_grade] ?? $this->previous_grade;
    }

    public function getDesiredGradeLabelAttribute()
    {
        return self::GRADES[$this->desired_grade] ?? $this->desired_grade;
    }

    public function getPreviousMajorLabelAttribute()
    {
        return self::PREVIOUS_MAJORS[$this->previous_major] ?? $this->previous_major;
    }

    public function getDesiredMajorLabelAttribute()
    {
        return self::MAJORS[$this->desired_major] ?? $this->desired_major;
    }

    public function getGradeScaleLabelAttribute()
    {
        return self::GRADE_SCALES[$this->grade_scale] ?? $this->grade_scale;
    }

    /**
     * Helper methods
     */
    public function hasAllRequiredDocuments()
    {
        $requiredFields = [
            'raport_file',
            'photo_file',
            'family_card_file',
            'transfer_certificate_file',
            'birth_certificate_file',
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }

        return true;
    }

    public function getRequiredDocuments()
    {
        return [
            'raport' => 'Rapor Sekolah Asal',
            'photo' => 'Pas Foto 3x4',
            'family_card' => 'Fotokopi Kartu Keluarga',
            'transfer_certificate' => 'Surat Pindah Sekolah',
            'birth_certificate' => 'Akta Kelahiran',
            'health_certificate' => 'Surat Keterangan Sehat (Opsional)',
        ];
    }

    public function hasGradeConversion()
    {
        return !empty($this->converted_grades);
    }

    public function isEligibleForApproval()
    {
        return $this->hasAllRequiredDocuments() && $this->hasGradeConversion();
    }

    /**
     * Convert grade from original scale to 0-100 scale
     */
    public function convertGradeTo100($originalGrade, $scale = null)
    {
        $scale = $scale ?? $this->grade_scale ?? self::SCALE_0_100;

        // Handle empty or null values
        if (empty($originalGrade)) {
            return 0;
        }

        switch ($scale) {
            case self::SCALE_0_100:
                return (float) $originalGrade;

            case self::SCALE_0_4:
                // Convert 0-4 to 0-100
                $grade = (float) $originalGrade;
                if ($grade < 0 || $grade > 4) {
                    return 0; // Invalid grade
                }
                return round($grade * 25, 2);

            case self::SCALE_A_F:
                // Convert A-F to 0-100 (consistent with dropdown display)
                $gradeMap = [
                    'A' => 90,
                    'A-' => 85,
                    'A+' => 95,
                    'B' => 80,
                    'B-' => 75,
                    'B+' => 85,
                    'C' => 60,
                    'C-' => 55,
                    'C+' => 65,
                    'D' => 60,
                    'D-' => 55,
                    'D+' => 65,
                    'E' => 50,
                    'F' => 0
                ];
                $grade = strtoupper(trim($originalGrade));
                return $gradeMap[$grade] ?? 0;

            case self::SCALE_PREDIKAT:
                // Convert Predikat to 0-100
                $predikatMap = [
                    'Sangat Baik' => 90,
                    'Baik' => 80,
                    'Cukup' => 70,
                    'Kurang' => 60,
                    'Sangat Kurang' => 50
                ];
                $predikat = trim($originalGrade);
                return $predikatMap[$predikat] ?? 0;

            default:
                return (float) $originalGrade;
        }
    }

    /**
     * Auto convert all grades from original scale to 0-100
     */
    public function autoConvertGrades()
    {
        if (empty($this->original_grades) || empty($this->grade_scale)) {
            return false;
        }

        $convertedGrades = [];
        foreach ($this->original_grades as $subject => $grade) {
            $convertedGrades[$subject] = $this->convertGradeTo100($grade);
        }

        $this->update([
            'converted_grades' => $convertedGrades,
            'conversion_notes' => 'Konversi otomatis dari skala ' . self::GRADE_SCALES[$this->grade_scale] . ' ke skala 0-100'
        ]);

        return true;
    }

    /**
     * Get average grade from converted grades
     */
    public function getAverageGrade()
    {
        if (empty($this->converted_grades)) {
            return 0;
        }

        $total = array_sum($this->converted_grades);
        $count = count($this->converted_grades);

        return $count > 0 ? round($total / $count, 2) : 0;
    }

    /**
     * Check if student meets minimum grade requirement
     */
    public function meetsMinimumGradeRequirement($minimumAverage = 70)
    {
        $average = $this->getAverageGrade();
        return $average >= $minimumAverage;
    }

    /**
     * Query scopes
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByTargetGrade($query, $grade)
    {
        return $query->where('target_grade', $grade);
    }

    public function scopeByTargetMajor($query, $major)
    {
        return $query->where('target_major', $major);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }
}

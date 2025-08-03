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
        return $this->hasAllRequiredDocuments();
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

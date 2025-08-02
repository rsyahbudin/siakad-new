<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PPDBApplication extends Model
{
    use HasFactory;

    protected $table = 'ppdb_applications';

    protected $fillable = [
        'full_name',
        'nisn',
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
        'entry_path',
        'desired_major',
        'raport_file',
        'photo_file',
        'family_card_file',
        'achievement_certificate_file',
        'financial_document_file',
        'test_score',
        'average_raport_score',
        'status',
        'notes',
        'application_number',
        'submitted_at',
        'processed_at',
        'processed_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'submitted_at' => 'datetime',
        'processed_at' => 'datetime',
        'test_score' => 'decimal:2',
        'average_raport_score' => 'decimal:2',
    ];

    // Entry path constants
    public const ENTRY_PATH_TES = 'tes';
    public const ENTRY_PATH_PRESTASI = 'prestasi';
    public const ENTRY_PATH_AFIRMASI = 'afirmasi';

    // Desired major constants
    public const MAJOR_IPA = 'IPA';
    public const MAJOR_IPS = 'IPS';

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_LULUS = 'lulus';
    public const STATUS_DITOLAK = 'ditolak';

    /**
     * Boot the model and generate application number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($application) {
            if (empty($application->application_number)) {
                $application->application_number = self::generateApplicationNumber();
            }
        });
    }

    /**
     * Generate unique application number
     */
    public static function generateApplicationNumber()
    {
        $year = date('Y');
        $prefix = 'PPDB';

        do {
            $random = strtoupper(Str::random(4));
            $number = "{$prefix}{$year}{$random}";
        } while (self::where('application_number', $number)->exists());

        return $number;
    }

    /**
     * Get the admin who processed this application
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get entry path label
     */
    public function getEntryPathLabelAttribute()
    {
        return [
            self::ENTRY_PATH_TES => 'Jalur Tes',
            self::ENTRY_PATH_PRESTASI => 'Jalur Prestasi',
            self::ENTRY_PATH_AFIRMASI => 'Jalur Afirmasi',
        ][$this->entry_path] ?? $this->entry_path;
    }

    /**
     * Get desired major label
     */
    public function getDesiredMajorLabelAttribute()
    {
        return [
            self::MAJOR_IPA => 'IPA',
            self::MAJOR_IPS => 'IPS',
        ][$this->desired_major] ?? $this->desired_major;
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_LULUS => 'Lulus',
            self::STATUS_DITOLAK => 'Ditolak',
        ][$this->status] ?? $this->status;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return [
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_LULUS => 'bg-green-100 text-green-800',
            self::STATUS_DITOLAK => 'bg-red-100 text-red-800',
        ][$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Check if application is eligible for approval based on entry path
     */
    public function isEligibleForApproval()
    {
        switch ($this->entry_path) {
            case self::ENTRY_PATH_TES:
                return $this->test_score >= 70;

            case self::ENTRY_PATH_PRESTASI:
                return $this->average_raport_score >= 85;

            case self::ENTRY_PATH_AFIRMASI:
                return true; // Only document completeness check

            default:
                return false;
        }
    }

    /**
     * Get required documents for this entry path
     */
    public function getRequiredDocuments()
    {
        $baseDocuments = [
            'raport_file' => 'Rapor Semester 1-5',
            'photo_file' => 'Pas Foto 3x4',
            'family_card_file' => 'Fotokopi Kartu Keluarga',
        ];

        switch ($this->entry_path) {
            case self::ENTRY_PATH_TES:
                return $baseDocuments;

            case self::ENTRY_PATH_PRESTASI:
                return array_merge($baseDocuments, [
                    'achievement_certificate_file' => 'Piagam Prestasi (Min. Tingkat Kabupaten)',
                ]);

            case self::ENTRY_PATH_AFIRMASI:
                return array_merge($baseDocuments, [
                    'financial_document_file' => 'Surat Keterangan Tidak Mampu/KIP/PKH',
                ]);

            default:
                return $baseDocuments;
        }
    }

    /**
     * Check if all required documents are uploaded
     */
    public function hasAllRequiredDocuments()
    {
        $requiredDocuments = array_keys($this->getRequiredDocuments());

        foreach ($requiredDocuments as $document) {
            if (empty($this->$document)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Scope for filtering by entry path
     */
    public function scopeByEntryPath($query, $entryPath)
    {
        return $query->where('entry_path', $entryPath);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by desired major
     */
    public function scopeByDesiredMajor($query, $major)
    {
        return $query->where('desired_major', $major);
    }
}

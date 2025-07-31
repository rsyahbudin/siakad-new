<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\KepalaSekolah;
use App\Models\WaliMurid;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Role constants
    public const ROLE_ADMIN = 'admin';
    public const ROLE_TEACHER = 'teacher';
    public const ROLE_STUDENT = 'student';
    public const ROLE_KEPALA_SEKOLAH = 'kepala_sekolah';
    public const ROLE_WALI_MURID = 'wali_murid';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the teacher record associated with the user.
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * Get the student record associated with the user.
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get the kepala sekolah record associated with the user.
     */
    public function kepalaSekolah()
    {
        return $this->hasOne(KepalaSekolah::class);
    }

    /**
     * Get the wali murid record associated with the user.
     */
    public function waliMurid()
    {
        return $this->hasOne(WaliMurid::class);
    }

    /**
     * Check if the user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if the user is a teacher
     */
    public function isTeacher(): bool
    {
        return $this->role === self::ROLE_TEACHER;
    }

    /**
     * Check if the user is a student
     */
    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    /**
     * Check if the user is a kepala sekolah
     */
    public function isKepalaSekolah(): bool
    {
        return $this->role === self::ROLE_KEPALA_SEKOLAH;
    }

    /**
     * Check if the user is a wali murid
     */
    public function isWaliMurid(): bool
    {
        return $this->role === self::ROLE_WALI_MURID;
    }

    /**
     * Check if the user is a homeroom teacher for the active academic year.
     */
    public function isHomeroomTeacher(): bool
    {
        if (!$this->isTeacher() || !$this->teacher) {
            return false;
        }

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return false;
        }

        return \App\Models\ClassroomAssignment::where('homeroom_teacher_id', $this->teacher->id)
            ->where('academic_year_id', $activeYear->id)
            ->exists();
    }
}

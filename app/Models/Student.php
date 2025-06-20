<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Raport;
use App\Models\User;
use App\Models\Attendance;

class Student extends Model
{

    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'full_name',
        'gender',
        'birth_place',
        'birth_date',
        'religion',
        'address',
        'parent_name',
        'parent_phone',
        'phone_number',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date', // Otomatis jadi objek Carbon
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classrooms()
    {
        // Relasi many-to-many ke kelas
        return $this->belongsToMany(Classroom::class, 'class_student');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function raports()
    {
        return $this->hasMany(Raport::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}

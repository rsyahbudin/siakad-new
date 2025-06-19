<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Schedule;

class Teacher extends Model
{

    protected $fillable = [
        'user_id',
        'nip',
        'full_name',
        'phone_number',
        'address',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function homeroomClassrooms()
    {
        // Relasi untuk kelas di mana guru ini adalah wali kelas
        return $this->hasMany(Classroom::class, 'homeroom_teacher_id');
    }

    public function schedules()
    {
        // Relasi untuk semua jadwal mengajar guru ini
        return $this->hasMany(Schedule::class);
    }
}

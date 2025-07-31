<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Student;

class WaliMurid extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'phone_number',
        'address',
        'relationship',
        'student_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}

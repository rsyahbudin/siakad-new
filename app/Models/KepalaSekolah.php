<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class KepalaSekolah extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'full_name',
        'phone_number',
        'address',
        'position',
        'last_education',
        'degree',
        'major',
        'university',
        'graduation_year',
        'birth_place',
        'birth_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

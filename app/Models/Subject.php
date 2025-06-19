<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Major;
use App\Models\Schedule;
use App\Models\Grade;

class Subject extends Model
{

    protected $fillable = ['name', 'code', 'major_id'];

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}

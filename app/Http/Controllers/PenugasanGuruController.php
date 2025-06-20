<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Teacher;
use Illuminate\Http\Request;

class PenugasanGuruController extends Controller
{
    public function index()
    {
        // Ambil semua penugasan jadwal, join guru, mapel, kelas
        $assignments = Schedule::with(['teacher', 'subject', 'classroom'])
            ->orderBy('teacher_id')
            ->orderBy('day')
            ->orderBy('time_start')
            ->get();
        return view('admin.penugasan-guru', compact('assignments'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return view('dashboard.admin');
        } elseif ($user->isTeacher()) {
            return view('dashboard.guru');
        } elseif ($user->isStudent()) {
            $student = $user->student;
            $classroom = $student->classrooms()->latest('id')->first();
            $today = now()->isoFormat('dddd'); // e.g. 'Monday', 'Selasa', dst
            $todaySchedules = [];
            if ($classroom) {
                $todaySchedules = \App\Models\Schedule::with(['subject', 'teacher'])
                    ->where('classroom_id', $classroom->id)
                    ->where('day', $today)
                    ->orderBy('time_start')
                    ->get();
            }
            return view('dashboard.siswa', compact('todaySchedules'));
        }
        abort(403, 'Akses tidak diizinkan.');
    }
}

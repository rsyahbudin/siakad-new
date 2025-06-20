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
            return view('dashboard.siswa');
        }
        abort(403, 'Akses tidak diizinkan.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Reset password user ke default (password123)
     */
    public function resetPassword($id)
    {
        $user = User::find($id);
        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }
        $user->password = Hash::make('password123');
        $user->save();
        return back()->with('success', 'Password berhasil direset ke password123.');
    }

    /**
     * Tampilkan tabel semua user untuk manajemen pengguna
     */
    public function index()
    {
        $users = \App\Models\User::orderBy('name')->get();
        return view('admin.manajemen-pengguna', compact('users'));
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat Admin Pertama
        User::create([
            'name' => 'Admin Siakad',
            'email' => 'admin@siakad.test', // Anda bisa ganti email ini
            'password' => Hash::make('password'), // Ganti dengan password yang aman
            'role' => 'admin', // Ini adalah peran penting
        ]);

        // Anda juga bisa membuat contoh guru dan siswa di sini jika perlu
        // User::create([... 'role' => 'teacher']);
        // User::create([... 'role' => 'student']);
    }
}

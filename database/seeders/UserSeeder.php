<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@siakad.test',
            'password' => Hash::make('admin123'),
            'role' => User::ROLE_ADMIN,
        ]);

        // Create Sample Teachers
        $teachers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@siakad.test',
                'password' => Hash::make('teacher123'),
                'role' => User::ROLE_TEACHER,
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti@siakad.test',
                'password' => Hash::make('teacher123'),
                'role' => User::ROLE_TEACHER,
            ],
        ];

        foreach ($teachers as $teacher) {
            User::create($teacher);
        }

        // Create Sample Students
        // $students = [
        //     [
        //         'name' => 'Ahmad Rizki',
        //         'email' => 'ahmad@siakad.test',
        //         'password' => Hash::make('student123'),
        //         'role' => User::ROLE_STUDENT,
        //     ],
        //     [
        //         'name' => 'Dewi Putri',
        //         'email' => 'dewi@siakad.test',
        //         'password' => Hash::make('student123'),
        //         'role' => User::ROLE_STUDENT,
        //     ],
        // ];

        // foreach ($students as $student) {
        //     User::create($student);
        // }

        // Create Sample Kepala Sekolah
        $kepalaSekolah = [
            'name' => 'Dr. Ahmad Supriyadi',
            'email' => 'kepala@siakad.test',
            'password' => Hash::make('kepala123'),
            'role' => User::ROLE_KEPALA_SEKOLAH,
        ];

        User::create($kepalaSekolah);

        // Create Sample Wali Murid
        // $waliMurid = [
        //     'name' => 'Siti Aminah',
        //     'email' => 'wali@siakad.test',
        //     'password' => Hash::make('wali123'),
        //     'role' => User::ROLE_WALI_MURID,
        // ];

        // User::create($waliMurid);
    }
}

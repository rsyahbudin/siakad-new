<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get student users
        $studentUsers = User::where('role', User::ROLE_STUDENT)->get();

        // Sample student data
        $studentData = [
            [
                'nis' => '2024001',
                'nisn' => '0012345671',
                'full_name' => 'Ahmad Rizki Saputra',
                'gender' => 'L',
                'birth_place' => 'Jakarta',
                'birth_date' => '2008-05-15',
                'religion' => 'Islam',
                'phone_number' => '081234567892',
                'address' => 'Jl. Pelajar No. 1, Jakarta',
                'parent_name' => 'Bambang Saputra',
                'parent_phone' => '081234567893',
            ],
            [
                'nis' => '2024002',
                'nisn' => '0012345672',
                'full_name' => 'Dewi Putri Lestari',
                'gender' => 'P',
                'birth_place' => 'Bandung',
                'birth_date' => '2008-08-20',
                'religion' => 'Islam',
                'phone_number' => '081234567894',
                'address' => 'Jl. Siswa No. 2, Jakarta',
                'parent_name' => 'Slamet Lestari',
                'parent_phone' => '081234567895',
            ],
        ];

        // Create student profiles
        foreach ($studentUsers as $index => $user) {
            if (isset($studentData[$index])) {
                $data = $studentData[$index];
                $data['user_id'] = $user->id;

                Student::create($data);
            }
        }
    }
}

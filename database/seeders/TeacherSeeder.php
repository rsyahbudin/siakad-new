<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\User;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get teacher users
        $teacherUsers = User::where('role', User::ROLE_TEACHER)->get();

        // Sample teacher data
        $teacherData = [
            [
                'nip' => '196001011985031001',
                'full_name' => 'Budi Santoso, S.Pd.',
                'phone_number' => '081234567890',
                'address' => 'Jl. Pendidikan No. 1, Jakarta',
                'degree' => 'S1',
                'major' => 'Pendidikan Bahasa Indonesia',
                'university' => 'Universitas Indonesia',
                'graduation_year' => 2010,
            ],
            [
                'nip' => '196501011990032002',
                'full_name' => 'Siti Rahayu, M.Pd.',
                'phone_number' => '081234567891',
                'address' => 'Jl. Guru No. 2, Jakarta',
                'degree' => 'S2',
                'major' => 'Pendidikan Matematika',
                'university' => 'Universitas Negeri Jakarta',
                'graduation_year' => 2015,
            ],
        ];

        // Create teacher profiles
        foreach ($teacherUsers as $index => $user) {
            if (isset($teacherData[$index])) {
                $data = $teacherData[$index];
                $data['user_id'] = $user->id;

                Teacher::create($data);
            }
        }
    }
}

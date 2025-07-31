<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WaliMurid;
use App\Models\User;
use App\Models\Student;

class WaliMuridSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the wali murid user
        $user = User::where('role', User::ROLE_WALI_MURID)->first();

        // Find a sample student
        $student = Student::first();

        if ($user && $student) {
            WaliMurid::create([
                'user_id' => $user->id,
                'full_name' => 'Siti Aminah',
                'phone_number' => '081234567891',
                'address' => 'Jl. Keluarga No. 456, Jakarta',
                'relationship' => 'Ibu',
                'student_id' => $student->id,
            ]);
        }
    }
}

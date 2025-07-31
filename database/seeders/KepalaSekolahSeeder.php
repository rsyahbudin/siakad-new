<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KepalaSekolah;
use App\Models\User;

class KepalaSekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the kepala sekolah user
        $user = User::where('role', User::ROLE_KEPALA_SEKOLAH)->first();

        if ($user) {
            KepalaSekolah::create([
                'user_id' => $user->id,
                'nip' => '198501012010012001',
                'full_name' => 'Dr. Ahmad Supriyadi, M.Pd',
                'phone_number' => '081234567890',
                'address' => 'Jl. Pendidikan No. 123, Jakarta',
                'position' => 'Kepala Sekolah',
            ]);
        }
    }
}

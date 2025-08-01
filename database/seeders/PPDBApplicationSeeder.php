<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PPDBApplication;

class PPDBApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $applications = [
            [
                'full_name' => 'Ahmad Rizki Pratama',
                'nisn' => '1234567890',
                'birth_place' => 'Jakarta',
                'birth_date' => '2008-03-15',
                'gender' => 'L',
                'religion' => 'Islam',
                'phone_number' => '081234567890',
                'email' => 'ahmad.rizki@example.com',
                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'parent_name' => 'Budi Santoso',
                'parent_phone' => '081234567891',
                'parent_occupation' => 'Wiraswasta',
                'parent_address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'entry_path' => 'tes',
                'desired_major' => 'IPA',
                'test_score' => 85.5,
                'status' => 'lulus',
                'submitted_at' => now()->subDays(5),
                'processed_at' => now()->subDays(3),
            ],
            [
                'full_name' => 'Dewi Putri Sari',
                'nisn' => '1234567891',
                'birth_place' => 'Bandung',
                'birth_date' => '2008-07-22',
                'gender' => 'P',
                'religion' => 'Islam',
                'phone_number' => '081234567892',
                'email' => 'dewi.putri@example.com',
                'address' => 'Jl. Asia Afrika No. 45, Bandung',
                'parent_name' => 'Siti Rahayu',
                'parent_phone' => '081234567893',
                'parent_occupation' => 'PNS',
                'parent_address' => 'Jl. Asia Afrika No. 45, Bandung',
                'entry_path' => 'prestasi',
                'desired_major' => 'IPA',
                'average_raport_score' => 88.5,
                'status' => 'lulus',
                'submitted_at' => now()->subDays(4),
                'processed_at' => now()->subDays(2),
            ],
            [
                'full_name' => 'Muhammad Fadli',
                'nisn' => '1234567892',
                'birth_place' => 'Surabaya',
                'birth_date' => '2008-11-10',
                'gender' => 'L',
                'religion' => 'Islam',
                'phone_number' => '081234567894',
                'email' => 'muhammad.fadli@example.com',
                'address' => 'Jl. Tunjungan No. 67, Surabaya',
                'parent_name' => 'Ahmad Supriyadi',
                'parent_phone' => '081234567895',
                'parent_occupation' => 'Pedagang',
                'parent_address' => 'Jl. Tunjungan No. 67, Surabaya',
                'entry_path' => 'afirmasi',
                'desired_major' => 'IPS',
                'status' => 'pending',
                'submitted_at' => now()->subDays(3),
            ],
            [
                'full_name' => 'Nina Safitri',
                'nisn' => '1234567893',
                'birth_place' => 'Semarang',
                'birth_date' => '2008-05-18',
                'gender' => 'P',
                'religion' => 'Islam',
                'phone_number' => '081234567896',
                'email' => 'nina.safitri@example.com',
                'address' => 'Jl. Pandanaran No. 89, Semarang',
                'parent_name' => 'Bambang Sutrisno',
                'parent_phone' => '081234567897',
                'parent_occupation' => 'Buruh',
                'parent_address' => 'Jl. Pandanaran No. 89, Semarang',
                'entry_path' => 'tes',
                'desired_major' => 'IPS',
                'test_score' => 65.0,
                'status' => 'ditolak',
                'submitted_at' => now()->subDays(2),
                'processed_at' => now()->subDays(1),
                'notes' => 'Nilai tes tidak memenuhi syarat minimal 70',
            ],
            [
                'full_name' => 'Rizki Ramadhan',
                'nisn' => '1234567894',
                'birth_place' => 'Yogyakarta',
                'birth_date' => '2008-09-30',
                'gender' => 'L',
                'religion' => 'Islam',
                'phone_number' => '081234567898',
                'email' => 'rizki.ramadhan@example.com',
                'address' => 'Jl. Malioboro No. 12, Yogyakarta',
                'parent_name' => 'Dedi Kurniawan',
                'parent_phone' => '081234567899',
                'parent_occupation' => 'Tukang Ojek',
                'parent_address' => 'Jl. Malioboro No. 12, Yogyakarta',
                'entry_path' => 'prestasi',
                'desired_major' => 'IPA',
                'average_raport_score' => 82.0,
                'status' => 'ditolak',
                'submitted_at' => now()->subDays(1),
                'processed_at' => now(),
                'notes' => 'Rata-rata nilai rapor tidak memenuhi syarat minimal 85',
            ],
        ];

        foreach ($applications as $application) {
            PPDBApplication::create($application);
        }
    }
}

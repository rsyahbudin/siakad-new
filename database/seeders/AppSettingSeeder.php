<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppSetting;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // School Information
        $schoolSettings = [
            [
                'key' => 'school_name',
                'value' => 'SMA Negeri 1 Contoh',
                'description' => 'Nama Sekolah'
            ],
            [
                'key' => 'school_npsn',
                'value' => '12345678',
                'description' => 'Nomor Pokok Sekolah Nasional (NPSN)'
            ],
            [
                'key' => 'school_address',
                'value' => 'Jl. Contoh No. 123, Kota Contoh, Provinsi Contoh',
                'description' => 'Alamat Sekolah'
            ],
            [
                'key' => 'school_phone',
                'value' => '(021) 1234567',
                'description' => 'Nomor Telepon Sekolah'
            ],
            [
                'key' => 'school_email',
                'value' => 'info@sman1contoh.sch.id',
                'description' => 'Email Sekolah'
            ],
            [
                'key' => 'school_website',
                'value' => 'https://www.sman1contoh.sch.id',
                'description' => 'Website Sekolah'
            ],
        ];

        // System Settings
        $systemSettings = [
            [
                'key' => 'system_ppdb_enabled',
                'value' => 'true',
                'description' => 'Status Aktifasi Sistem PPDB'
            ],
            [
                'key' => 'system_transfer_enabled',
                'value' => 'true',
                'description' => 'Status Aktifasi Sistem Siswa Pindahan'
            ],
            [
                'key' => 'max_failed_subjects',
                'value' => '3',
                'description' => 'Batas maksimal mapel gagal agar naik/lulus'
            ],
        ];

        // Combine all settings
        $allSettings = array_merge($schoolSettings, $systemSettings);

        // Insert or update settings
        foreach ($allSettings as $setting) {
            AppSetting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'description' => $setting['description']
                ]
            );
        }
    }
}

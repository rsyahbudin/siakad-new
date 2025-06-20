<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Major;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $majors = [
            [
                'name' => 'Ilmu Pengetahuan Alam',
                'short_name' => 'IPA',
            ],
            [
                'name' => 'Ilmu Pengetahuan Sosial',
                'short_name' => 'IPS',
            ],
        ];

        foreach ($majors as $major) {
            Major::create($major);
        }
    }
}

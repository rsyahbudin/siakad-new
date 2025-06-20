<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Major;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get majors
        $ipa = Major::where('short_name', 'IPA')->first();
        $ips = Major::where('short_name', 'IPS')->first();

        // Common subjects (for all majors)
        $commonSubjects = [
            [
                'name' => 'Bahasa Indonesia',
                'code' => 'BIN',
            ],
            [
                'name' => 'Bahasa Inggris',
                'code' => 'BIG',
            ],
            [
                'name' => 'Matematika',
                'code' => 'MTK',
            ],
            [
                'name' => 'Pendidikan Agama',
                'code' => 'PAI',
            ],
            [
                'name' => 'Pendidikan Kewarganegaraan',
                'code' => 'PKN',
            ],
        ];

        // IPA specific subjects
        $ipaSubjects = [
            [
                'name' => 'Fisika',
                'code' => 'FIS',
                'major_id' => $ipa->id,
            ],
            [
                'name' => 'Kimia',
                'code' => 'KIM',
                'major_id' => $ipa->id,
            ],
            [
                'name' => 'Biologi',
                'code' => 'BIO',
                'major_id' => $ipa->id,
            ],
        ];

        // IPS specific subjects
        $ipsSubjects = [
            [
                'name' => 'Ekonomi',
                'code' => 'EKO',
                'major_id' => $ips->id,
            ],
            [
                'name' => 'Sejarah',
                'code' => 'SEJ',
                'major_id' => $ips->id,
            ],
            [
                'name' => 'Geografi',
                'code' => 'GEO',
                'major_id' => $ips->id,
            ],
            [
                'name' => 'Sosiologi',
                'code' => 'SOS',
                'major_id' => $ips->id,
            ],
        ];

        // Create all subjects
        foreach ($commonSubjects as $subject) {
            Subject::create($subject);
        }

        foreach ($ipaSubjects as $subject) {
            Subject::create($subject);
        }

        foreach ($ipsSubjects as $subject) {
            Subject::create($subject);
        }
    }
}

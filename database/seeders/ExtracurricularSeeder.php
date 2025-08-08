<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Extracurricular;
use App\Models\Teacher;

class ExtracurricularSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some teachers for extracurricular supervisors
        $teachers = Teacher::take(5)->get();

        $extracurriculars = [
            [
                'name' => 'Pramuka',
                'description' => 'Kegiatan kepramukaan untuk melatih kepemimpinan dan kedisiplinan siswa.',
                'category' => 'Umum',
                'day' => 'Sabtu',
                'time_start' => '07:00:00',
                'time_end' => '10:00:00',
                'location' => 'Lapangan Sekolah',
                'teacher_id' => $teachers->first()?->id,
                'max_participants' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'PMR (Palang Merah Remaja)',
                'description' => 'Organisasi remaja yang bergerak di bidang kesehatan dan kemanusiaan.',
                'category' => 'Kesehatan',
                'day' => 'Jumat',
                'time_start' => '15:00:00',
                'time_end' => '17:00:00',
                'location' => 'Ruang PMR',
                'teacher_id' => $teachers->skip(1)->first()?->id,
                'max_participants' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Rohis (Rohani Islam)',
                'description' => 'Kegiatan keagamaan Islam untuk memperdalam pengetahuan agama.',
                'category' => 'Keagamaan',
                'day' => 'Selasa',
                'time_start' => '15:30:00',
                'time_end' => '17:30:00',
                'location' => 'Masjid Sekolah',
                'teacher_id' => $teachers->skip(2)->first()?->id,
                'max_participants' => 40,
                'is_active' => true,
            ],
            [
                'name' => 'English Club',
                'description' => 'Klub bahasa Inggris untuk meningkatkan kemampuan berbahasa Inggris.',
                'category' => 'Bahasa',
                'day' => 'Rabu',
                'time_start' => '15:00:00',
                'time_end' => '16:30:00',
                'location' => 'Ruang Bahasa',
                'teacher_id' => $teachers->skip(3)->first()?->id,
                'max_participants' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Basket',
                'description' => 'Tim basket sekolah untuk mengembangkan bakat olahraga.',
                'category' => 'Olahraga',
                'day' => 'Senin',
                'time_start' => '16:00:00',
                'time_end' => '18:00:00',
                'location' => 'Lapangan Basket',
                'teacher_id' => $teachers->skip(4)->first()?->id,
                'max_participants' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Seni Tari',
                'description' => 'Kegiatan seni tari untuk melestarikan budaya Indonesia.',
                'category' => 'Seni',
                'day' => 'Kamis',
                'time_start' => '15:00:00',
                'time_end' => '17:00:00',
                'location' => 'Aula Sekolah',
                'teacher_id' => $teachers->first()?->id,
                'max_participants' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Komputer Club',
                'description' => 'Klub komputer untuk mengembangkan kemampuan teknologi informasi.',
                'category' => 'Teknologi',
                'day' => 'Jumat',
                'time_start' => '14:00:00',
                'time_end' => '16:00:00',
                'location' => 'Lab Komputer',
                'teacher_id' => $teachers->skip(1)->first()?->id,
                'max_participants' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Jurnalistik',
                'description' => 'Kegiatan jurnalistik untuk mengembangkan kemampuan menulis dan media.',
                'category' => 'Akademik',
                'day' => 'Selasa',
                'time_start' => '15:00:00',
                'time_end' => '16:30:00',
                'location' => 'Ruang Jurnalistik',
                'teacher_id' => $teachers->skip(2)->first()?->id,
                'max_participants' => 25,
                'is_active' => true,
            ],
        ];

        foreach ($extracurriculars as $extracurricular) {
            Extracurricular::create($extracurricular);
        }
    }
}

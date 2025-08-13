<?php

namespace Database\Seeders;

use App\Models\TransferStudent;
use Illuminate\Database\Seeder;

class TransferStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transferStudents = [
            [
                'full_name' => 'Ahmad Rizki Pratama',
                'nisn' => '2345678904',
                'nis_previous' => 'SMA001234',
                'birth_place' => 'Jakarta',
                'birth_date' => '2007-03-15',
                'gender' => 'L',
                'religion' => 'Islam',
                'phone_number' => '081234567890',
                'email' => 'ahmad.rizki.new@email.com',
                'address' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'parent_name' => 'Budi Pratama',
                'parent_phone' => '081234567891',
                'parent_email' => 'budi.pratama.new@email.com',
                'parent_occupation' => 'Wiraswasta',
                'parent_address' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'previous_school_name' => 'SMA Negeri 1 Jakarta',
                'previous_school_address' => 'Jl. Sudirman No. 45, Jakarta',
                'previous_school_npsn' => '20104001',
                'previous_grade' => 'XI',
                'previous_major' => 'IPA',
                'previous_academic_year' => '2023/2024',
                'transfer_reason' => 'Pindah domisili karena pekerjaan orang tua',
                'desired_grade' => 'XI',
                'desired_major' => 'IPA',
                'grade_scale' => '0-100',
                'raport_file' => 'transfer/raport/sample_raport_1.pdf',
                'photo_file' => 'transfer/photo/sample_photo_1.jpg',
                'family_card_file' => 'transfer/family_card/sample_kk_1.pdf',
                'transfer_certificate_file' => 'transfer/certificate/sample_cert_1.pdf',
                'birth_certificate_file' => 'transfer/birth_certificate/sample_birth_1.pdf',
                'health_certificate_file' => null,
                'original_grades' => [
                    'Matematika' => 85,
                    'Fisika' => 82,
                    'Kimia' => 88,
                    'Biologi' => 90,
                    'Bahasa Indonesia' => 87,
                    'Bahasa Inggris' => 85
                ],
                'converted_grades' => [
                    'Matematika' => 85,
                    'Fisika' => 82,
                    'Kimia' => 88,
                    'Biologi' => 90,
                    'Bahasa Indonesia' => 87,
                    'Bahasa Inggris' => 85
                ],
                'conversion_notes' => 'Nilai sudah sesuai dengan kurikulum sekolah',
                'status' => 'approved',
                'notes' => 'Siswa memenuhi syarat dan diterima',
                'submitted_at' => '2025-08-03 13:01:42',
                'processed_at' => '2025-08-11 13:01:42',
            ],
            [
                'full_name' => 'Siti Nurhaliza',
                'nisn' => '2345678905',
                'nis_previous' => 'SMA005678',
                'birth_place' => 'Bandung',
                'birth_date' => '2006-08-22',
                'gender' => 'P',
                'religion' => 'Islam',
                'phone_number' => '081234567892',
                'email' => 'siti.nurhaliza.new@email.com',
                'address' => 'Jl. Asia Afrika No. 67, Bandung',
                'parent_name' => 'Ahmad Hidayat',
                'parent_phone' => '081234567893',
                'parent_email' => 'ahmad.hidayat.new@email.com',
                'parent_occupation' => 'PNS',
                'parent_address' => 'Jl. Asia Afrika No. 67, Bandung',
                'previous_school_name' => 'SMA Negeri 2 Bandung',
                'previous_school_address' => 'Jl. Cihampelas No. 89, Bandung',
                'previous_school_npsn' => '20204002',
                'previous_grade' => 'X',
                'previous_major' => 'IPS',
                'previous_academic_year' => '2023/2024',
                'transfer_reason' => 'Mengikuti orang tua yang pindah tugas',
                'desired_grade' => 'XI',
                'desired_major' => 'IPS',
                'grade_scale' => 'A-F',
                'raport_file' => 'transfer/raport/sample_raport_2.pdf',
                'photo_file' => 'transfer/photo/sample_photo_2.jpg',
                'family_card_file' => 'transfer/family_card/sample_kk_2.pdf',
                'transfer_certificate_file' => 'transfer/certificate/sample_cert_2.pdf',
                'birth_certificate_file' => 'transfer/birth_certificate/sample_birth_2.pdf',
                'health_certificate_file' => null,
                'original_grades' => [
                    'Matematika' => 'B',
                    'Sejarah' => 'A',
                    'Geografi' => 'B+',
                    'Ekonomi' => 'A-',
                    'Sosiologi' => 'B',
                    'Bahasa Indonesia' => 'A',
                    'Bahasa Inggris' => 'B+'
                ],
                'converted_grades' => null,
                'conversion_notes' => null,
                'status' => 'pending',
                'notes' => null,
                'submitted_at' => '2025-08-05 14:30:15',
                'processed_at' => null,
            ],
            [
                'full_name' => 'Dedi Setiawan',
                'nisn' => '2345678906',
                'nis_previous' => 'SMA009012',
                'birth_place' => 'Surabaya',
                'birth_date' => '2005-12-10',
                'gender' => 'L',
                'religion' => 'Islam',
                'phone_number' => '081234567894',
                'email' => 'dedi.setiawan.new@email.com',
                'address' => 'Jl. Tunjungan No. 45, Surabaya',
                'parent_name' => 'Sukarno',
                'parent_phone' => '081234567895',
                'parent_email' => 'sukarno.new@email.com',
                'parent_occupation' => 'Pengusaha',
                'parent_address' => 'Jl. Tunjungan No. 45, Surabaya',
                'previous_school_name' => 'SMA Negeri 3 Surabaya',
                'previous_school_address' => 'Jl. Pemuda No. 23, Surabaya',
                'previous_school_npsn' => '20504003',
                'previous_grade' => 'XII',
                'previous_major' => 'IPA',
                'previous_academic_year' => '2023/2024',
                'transfer_reason' => 'Mencari lingkungan belajar yang lebih kondusif',
                'desired_grade' => 'XII',
                'desired_major' => 'IPA',
                'grade_scale' => 'Predikat',
                'raport_file' => 'transfer/raport/sample_raport_3.pdf',
                'photo_file' => 'transfer/photo/sample_photo_3.jpg',
                'family_card_file' => 'transfer/family_card/sample_kk_3.pdf',
                'transfer_certificate_file' => 'transfer/certificate/sample_cert_3.pdf',
                'birth_certificate_file' => 'transfer/birth_certificate/sample_birth_3.pdf',
                'health_certificate_file' => null,
                'original_grades' => [
                    'Matematika' => 'Sangat Baik',
                    'Fisika' => 'Baik',
                    'Kimia' => 'Sangat Baik',
                    'Biologi' => 'Baik',
                    'Bahasa Indonesia' => 'Sangat Baik',
                    'Bahasa Inggris' => 'Baik'
                ],
                'converted_grades' => [
                    'Matematika' => 90,
                    'Fisika' => 80,
                    'Kimia' => 90,
                    'Biologi' => 80,
                    'Bahasa Indonesia' => 90,
                    'Bahasa Inggris' => 80
                ],
                'conversion_notes' => 'Konversi dari predikat ke skala 0-100 sesuai standar sekolah',
                'status' => 'pending',
                'notes' => 'Menunggu review final',
                'submitted_at' => '2025-08-07 09:15:30',
                'processed_at' => null,
            ],
        ];

        foreach ($transferStudents as $data) {
            TransferStudent::create($data);
        }
    }
}

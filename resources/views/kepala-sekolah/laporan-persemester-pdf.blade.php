<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Persemester</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #000;
        }

        .header .logo-section {
            margin-bottom: 15px;
        }

        .header .govt-logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 10px;
            background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMjgiIHN0cm9rZT0iIzAwMCIgc3Ryb2tlLXdpZHRoPSIyIi8+CjxwYXRoIGQ9Ik0zMCAxNUwzNiAyNEgyNEwzMCAxNVoiIGZpbGw9IiMwMDAiLz4KPHN0aGQgZD0iTTMwIDQ1TDI0IDM2SDM2TDMwIDQ1WiIgZmlsbD0iIzAwMCIvPgo8L3N2Zz4K') center/contain no-repeat;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #000;
        }

        .header .subtitle {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0 15px 0;
            text-transform: uppercase;
            color: #000;
        }

        .header .school-info {
            font-size: 13px;
            margin: 15px 0;
            line-height: 1.5;
            color: #000;
        }

        .header .school-info strong {
            font-size: 14px;
            color: #000;
        }

        .header .report-info {
            margin-top: 20px;
            font-size: 12px;
            max-width: 400px;
            margin: 20px auto 0 auto;
        }

        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 12px;
            padding: 6px 10px;
            background-color: #f0f0f0;
            border: 1px solid #000;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            text-align: center;
        }

        .subsection-title {
            font-size: 11px;
            font-weight: bold;
            margin: 15px 0 8px 0;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 1px solid #000;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.2px;
        }

        td:first-child,
        th:first-child {
            text-align: left;
        }

        /* Statistik menggunakan tabel, tidak perlu grid lagi */

        .summary-box {
            border: 1px solid #000;
            padding: 8px;
            margin: 15px 0;
            background-color: #f8f8f8;
        }

        .summary-title {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 6px;
            text-transform: uppercase;
            text-align: center;
        }

        .page-break {
            page-break-before: always;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #000;
            border-top: 1px solid #000;
            padding-top: 20px;
        }

        .signature-section {
            margin-top: 40px;
            text-align: right;
        }

        .signature-box {
            text-align: center;
            display: inline-block;
            margin-left: auto;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            height: 60px;
            margin-bottom: 10px;
        }

        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }

        @media print {
            body {
                margin: 0;
                padding: 15px;
                font-size: 10px;
            }

            .page-break {
                page-break-before: always;
            }

            .section {
                break-inside: avoid;
            }

            table {
                font-size: 9px;
            }

            .summary-box {
                font-size: 9px;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo-section">
            <div class="govt-logo"></div>
        </div>
        <h1>LAPORAN PERSEMESTER</h1>
        <div class="subtitle">SEKOLAH MENENGAH ATAS</div>
        <div class="school-info">
            <strong>NAMA SEKOLAH: {{ $schoolData['name'] }}</strong><br>
            NPSN: {{ $schoolData['npsn'] }}<br>
            Alamat: {{ $schoolData['address'] }}<br>
            Telepon: {{ $schoolData['phone'] }} | Email: {{ $schoolData['email'] }}
        </div>
        <div class="report-info">
            <div style="text-align: center; line-height: 1.6;">
                <div><strong>Tahun Ajaran:</strong> {{ $academicYear->year }}</div>
                <div><strong>Semester:</strong> {{ $semester }}</div>
                <div><strong>Tanggal Laporan:</strong> {{ now()->format('d F Y') }}</div>
            </div>
        </div>
    </div>

    <!-- 1. Data Siswa -->
    <div class="section">
        <div class="section-title">I. REKAPITULASI DATA SISWA</div>

        <div class="subsection-title">A. Ringkasan Data Siswa</div>
        <table style="width: 100%; margin-bottom: 15px;">
            <tbody>
                <tr>
                    <td style="width: 50%; padding: 6px; background-color: #f8f8f8;"><strong>Total Siswa</strong></td>
                    <td style="width: 16.67%; text-align: center; font-weight: bold;">{{ $studentData['total_students'] }}</td>
                    <td style="width: 16.67%; padding: 6px; background-color: #f8f8f8;"><strong>Siswa Pindahan</strong></td>
                    <td style="width: 16.67%; text-align: center; font-weight: bold;">{{ $studentData['transfer_students'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px; background-color: #f8f8f8;"><strong>Siswa Laki-laki</strong></td>
                    <td style="text-align: center; font-weight: bold;">{{ $studentData['total_male'] }}</td>
                    <td style="padding: 6px; background-color: #f8f8f8;"><strong>Mutasi Masuk</strong></td>
                    <td style="text-align: center; font-weight: bold;">{{ $studentData['mutations_in'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px; background-color: #f8f8f8;"><strong>Siswa Perempuan</strong></td>
                    <td style="text-align: center; font-weight: bold;">{{ $studentData['total_female'] }}</td>
                    <td style="padding: 6px; background-color: #f8f8f8;"><strong>Mutasi Keluar</strong></td>
                    <td style="text-align: center; font-weight: bold;">{{ $studentData['mutations_out'] }}</td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">B. Distribusi Siswa per Tingkat dan Jurusan</div>
        <table>
            <thead>
                <tr>
                    <th>Tingkat</th>
                    <th>Program Keahlian</th>
                    <th>Jumlah Kelas</th>
                    <th>Total Siswa</th>
                    <th>Laki-laki</th>
                    <th>Perempuan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($studentData['students_per_grade_major'] as $gradeMajor)
                <tr>
                    <td>{{ $gradeMajor['grade_name'] }}</td>
                    <td>{{ $gradeMajor['major_name'] }}</td>
                    <td>{{ $gradeMajor['class_count'] }}</td>
                    <td>{{ $gradeMajor['student_count'] }}</td>
                    <td>{{ $gradeMajor['male_count'] }}</td>
                    <td>{{ $gradeMajor['female_count'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- 2. Data Guru & Kelas -->
    <div class="section">
        <div class="section-title">II. DATA SUMBER DAYA PENDIDIK DAN KELAS</div>

        <table style="width: 100%; margin-bottom: 15px;">
            <tbody>
                <tr>
                    <td style="width: 40%; padding: 6px; background-color: #f8f8f8;"><strong>Jumlah Pendidik</strong></td>
                    <td style="width: 20%; text-align: center; font-weight: bold;">{{ $teacherData['teacher_count'] }} Orang</td>
                    <td style="width: 40%; padding: 6px; background-color: #f8f8f8;"><strong>Rasio Guru : Siswa</strong></td>
                    <td style="width: 20%; text-align: center; font-weight: bold;">1 : {{ number_format($studentData['total_students'] / $teacherData['teacher_count'], 1) }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px; background-color: #f8f8f8;"><strong>Mata Pelajaran</strong></td>
                    <td style="text-align: center; font-weight: bold;">{{ $teacherData['subjects_taught'] }} Mapel</td>
                    <td style="padding: 6px; background-color: #f8f8f8;"><strong>Kelas</strong></td>
                    <td style="text-align: center; font-weight: bold;">{{ $teacherData['classroom_count'] }} Kelas</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 3. Data Absensi -->
    <div class="section">
        <div class="section-title">III. REKAPITULASI KEHADIRAN SISWA</div>

        <div class="summary-box">
            <div class="summary-title">Persentase Kehadiran Keseluruhan</div>
            <p style="text-align: center; font-size: 14px; font-weight: bold; margin: 0; color: #000;">
                {{ number_format($attendanceData['overall_attendance_percentage'], 1) }}%
            </p>
        </div>

        <div class="subsection-title">Rincian Kehadiran per Kelas</div>
        <table>
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>Jumlah Siswa</th>
                    <th>Sakit</th>
                    <th>Izin</th>
                    <th>Tanpa Keterangan</th>
                    <th>Persentase Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendanceData['attendance_per_class'] as $attendance)
                <tr>
                    <td>{{ $attendance['classroom'] }}</td>
                    <td>{{ $attendance['total_students'] }}</td>
                    <td>{{ $attendance['sick'] }}</td>
                    <td>{{ $attendance['permit'] }}</td>
                    <td>{{ $attendance['absent'] }}</td>
                    <td><strong>{{ $attendance['attendance_percentage'] }}%</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- 4. Data Pengaturan Akademik -->
    <div class="section">
        <div class="section-title">IV. PENGATURAN AKADEMIK</div>

        @if($academicSettings['semester_weights'])
        <div class="subsection-title">Bobot Semester untuk Nilai Akhir Tahun</div>
        <table style="width: 100%; margin-bottom: 15px;">
            <tbody>
                <tr>
                    <td style="width: 50%; padding: 6px; background-color: #f8f8f8;"><strong>Bobot Semester Ganjil</strong></td>
                    <td style="width: 50%; text-align: center; font-weight: bold;">{{ $academicSettings['semester_weights']['ganjil_weight'] }}%</td>
                </tr>
                <tr>
                    <td style="padding: 6px; background-color: #f8f8f8;"><strong>Bobot Semester Genap</strong></td>
                    <td style="text-align: center; font-weight: bold;">{{ $academicSettings['semester_weights']['genap_weight'] }}%</td>
                </tr>
            </tbody>
        </table>
        @endif

        <div class="subsection-title">Batas Maksimal Mata Pelajaran Gagal</div>
        <table style="width: 100%; margin-bottom: 15px;">
            <tbody>
                <tr>
                    <td style="width: 50%; padding: 6px; background-color: #f8f8f8;"><strong>Batas Maksimal Mapel Gagal</strong></td>
                    <td style="width: 50%; text-align: center; font-weight: bold;">{{ $academicSettings['max_failed_subjects'] }} Mata Pelajaran</td>
                </tr>
            </tbody>
        </table>

        @if(count($academicSettings['subject_settings']) > 0)
        <div class="subsection-title">KKM dan Bobot Mata Pelajaran</div>
        <table>
            <thead>
                <tr>
                    <th>Mata Pelajaran</th>
                    <th>KKM</th>
                    <th>Bobot Tugas (%)</th>
                    <th>Bobot UTS (%)</th>
                    <th>Bobot UAS (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($academicSettings['subject_settings'] as $setting)
                <tr>
                    <td>{{ $setting['subject_name'] }}</td>
                    <td><strong>{{ $setting['kkm'] }}</strong></td>
                    <td>{{ $setting['assignment_weight'] }}%</td>
                    <td>{{ $setting['uts_weight'] }}%</td>
                    <td>{{ $setting['uas_weight'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <!-- 5. Data Nilai Rapor -->
    <div class="section">
        <div class="section-title">V. EVALUASI HASIL BELAJAR SISWA</div>

        <table style="width: 100%; margin-bottom: 15px;">
            <tbody>
                <tr>
                    <td style="width: 30%; padding: 6px; background-color: #f8f8f8;"><strong>Mata Pelajaran</strong></td>
                    <td style="width: 20%; text-align: center; font-weight: bold;">{{ $gradeData['total_subjects'] }} Mapel</td>
                    <td style="width: 30%; padding: 6px; background-color: #f8f8f8;"><strong>Rata-rata Nilai</strong></td>
                    <td style="width: 20%; text-align: center; font-weight: bold;">{{ $gradeData['average_grade'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px; background-color: #f8f8f8;"><strong>Jumlah Kelas</strong></td>
                    <td style="text-align: center; font-weight: bold;">{{ count($gradeData['grades_by_class']) }} Kelas</td>
                    <td style="padding: 6px; background-color: #f8f8f8;"><strong>Jumlah Siswa</strong></td>
                    <td style="text-align: center; font-weight: bold;">{{ $gradeData['total_students_with_grades'] }} Siswa</td>
                </tr>
            </tbody>
        </table>

        @if(count($gradeData['grades_by_class']) > 0)
        <div class="subsection-title">Rata-rata Nilai per Kelas</div>
        <table>
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>Tingkat</th>
                    <th>Jurusan</th>
                    <th>Rata-rata Nilai</th>
                    <th>Jumlah Siswa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gradeData['grades_by_class'] as $grade)
                <tr>
                    <td>{{ $grade['classroom_name'] }}</td>
                    <td>{{ $grade['grade_level'] }}</td>
                    <td>{{ $grade['major_name'] }}</td>
                    <td><strong>{{ $grade['average_grade'] }}</strong></td>
                    <td>{{ $grade['total_students'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if(count($gradeData['grades_by_grade_level']) > 0)
        <div class="subsection-title">Rata-rata Nilai per Tingkatan</div>
        <table>
            <thead>
                <tr>
                    <th>Tingkatan</th>
                    <th>Rata-rata Nilai</th>
                    <th>Jumlah Siswa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gradeData['grades_by_grade_level'] as $grade)
                <tr>
                    <td>{{ $grade['grade_name'] }}</td>
                    <td><strong>{{ $grade['average_grade'] }}</strong></td>
                    <td>{{ $grade['total_students'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        @else
        <div class="no-data">Belum ada data penilaian untuk periode ini</div>
        @endif
    </div>

    <!-- 6. Data Ekstrakurikuler -->
    <div class="section">
        <div class="section-title">VI. KEGIATAN EKSTRAKURIKULER</div>

        <div class="summary-box">
            <div class="summary-title">Total Partisipasi Ekstrakurikuler</div>
            <p style="text-align: center; font-size: 14px; font-weight: bold; margin: 0; color: #000;">
                {{ $extracurricularData['total_participants'] }} Siswa
            </p>
        </div>

        @if(count($extracurricularData['extracurriculars']) > 0)
        <div class="subsection-title">Rincian Kegiatan Ekstrakurikuler</div>
        <table>
            <thead>
                <tr>
                    <th>Nama Ekstrakurikuler</th>
                    <th>Kategori</th>
                    <th>Jumlah Peserta</th>
                </tr>
            </thead>
            <tbody>
                @foreach($extracurricularData['extracurriculars'] as $extracurricular)
                <tr>
                    <td>{{ $extracurricular['name'] }}</td>
                    <td>{{ $extracurricular['category'] }}</td>
                    <td><strong>{{ $extracurricular['student_count'] }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">Belum ada kegiatan ekstrakurikuler yang terdaftar untuk periode ini</div>
        @endif
    </div>

    <!-- 7. Data Kenaikan Kelas (hanya semester genap) -->
    @if($semester == 'Genap' && $promotionData)
    <div class="section">
        <div class="section-title">VII. KENAIKAN KELAS DAN KELULUSAN</div>

        <table style="width: 100%; margin-bottom: 15px;">
            <tbody>
                <tr>
                    <td style="width: 40%; padding: 6px; background-color: #f8f8f8;"><strong>Siswa Naik Kelas (X & XI)</strong></td>
                    <td style="width: 20%; text-align: center; font-weight: bold; color: green;">{{ $promotionData['promoted_students'] }} Siswa</td>
                    <td style="width: 40%; padding: 6px; background-color: #f8f8f8;"><strong>Persentase Kenaikan</strong></td>
                    <td style="width: 20%; text-align: center; font-weight: bold; color: green;">
                        @php
                        $totalPromotion = $promotionData['promoted_students'] + $promotionData['retained_students'];
                        @endphp
                        @if($totalPromotion > 0)
                        {{ number_format(($promotionData['promoted_students'] / $totalPromotion) * 100, 1) }}%
                        @else
                        0%
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 6px; background-color: #f8f8f8;"><strong>Siswa Tinggal Kelas</strong></td>
                    <td style="text-align: center; font-weight: bold; color: red;">{{ $promotionData['retained_students'] }} Siswa</td>
                    <td style="padding: 6px; background-color: #f8f8f8;"><strong>Siswa Lulus (Kelas XII)</strong></td>
                    <td style="text-align: center; font-weight: bold; color: blue;">{{ $promotionData['graduated_students'] }} Siswa</td>
                </tr>
            </tbody>
        </table>

        <div class="summary-box">
            <div class="summary-title">Tingkat Kelulusan Kelas XII</div>
            <p style="text-align: center; font-size: 14px; font-weight: bold; margin: 0; color: #000;">
                @if($promotionData['graduated_students'] > 0)
                100% ({{ $promotionData['graduated_students'] }} dari {{ $promotionData['graduated_students'] }} siswa)
                @else
                Belum ada data kelulusan
                @endif
            </p>
        </div>
    </div>
    @endif

    <div class="signature-section">
        <div class="signature-box">
            <p>{{ now()->format('d F Y') }}</p>
            <p>Kepala Sekolah</p>
            <div class="signature-line"></div>
            @if($kepalaSekolah)
            <p><strong>{{ $kepalaSekolah->full_name }}</strong><br>
                NIP. {{ $kepalaSekolah->nip }}</p>
            @else
            <p><strong>Kepala Sekolah</strong><br>
                NIP. -</p>
            @endif
        </div>
    </div>

</body>

</html>
@extends('layouts.dashboard')
@section('title', 'Raport Anak')

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .printable-area,
        .printable-area * {
            visibility: visible;
        }

        .printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .no-print {
            display: none;
        }

        aside,
        header,
        .no-print {
            display: none !important;
        }

        main.flex-1 {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Print specific styles */
        .printable-area {
            font-size: 12px;
            line-height: 1.3;
        }

        .print-header {
            font-size: 14px;
            font-weight: bold;
        }

        .print-table th,
        .print-table td {
            padding: 6px 8px !important;
            font-size: 11px;
        }
    }

    /* Enhanced table styling */
    .formal-table {
        border-collapse: collapse;
        border: 2px solid #000;
    }

    .formal-table th {
        background-color: #f8f9fa;
        border: 1px solid #000;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
        padding: 8px;
        font-size: 13px;
    }

    .formal-table td {
        border: 1px solid #000;
        padding: 6px 8px;
        vertical-align: middle;
        font-size: 12px;
    }

    .info-table td {
        border: none;
        padding: 2px 0;
        font-size: 13px;
    }

    .signature-section {
        border-top: 2px solid #000;
        margin-top: 30px;
        padding-top: 20px;
    }
</style>

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-green-600 via-green-700 to-emerald-800 rounded-xl shadow-lg p-6 mb-8 no-print">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">📊 Raport Digital Anak</h1>
            <p class="text-green-100">Lihat dan kelola laporan hasil belajar anak Anda dengan mudah</p>
        </div>
        <div class="flex items-center gap-3">
            @if($raport && $raport->is_finalized)
            <button onclick="window.print()"
                class="bg-green-500 hover:bg-green-600 text-white px-5 py-2.5 rounded-lg transition-all duration-300 flex items-center gap-2 shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak Raport
            </button>
            @else
            <button class="bg-gray-300 text-gray-500 px-5 py-2.5 rounded-lg flex items-center gap-2 cursor-not-allowed opacity-60"
                title="Raport belum final, tidak bisa dicetak" disabled>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Raport Terkunci
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-8 no-print overflow-hidden">
    <!-- Header Filter -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Filter Raport</h3>
                <p class="text-sm text-gray-600">Pilih tahun ajaran dan semester yang ingin ditampilkan</p>
            </div>
        </div>
    </div>

    <!-- Form Filter -->
    <div class="p-6">
        <form method="GET" action="{{ route('wali.raport.show') }}" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
            <!-- Tahun Ajaran -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Tahun Ajaran
                </label>
                <select name="academic_year_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-green-400">
                    @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ $selectedYear->id == $year->id ? 'selected' : '' }}>
                        📅 {{ $year->year }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Semester -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Semester
                </label>
                <select name="semester"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-green-400">
                    <option value="1" {{ $selectedSemester == 1 ? 'selected' : '' }}>🌱 Semester Ganjil</option>
                    <option value="2" {{ $selectedSemester == 2 ? 'selected' : '' }}>🌸 Semester Genap</option>
                </select>
            </div>

            <!-- Button -->
            <div>
                <button type="submit"
                    class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Tampilkan Raport
                </button>
            </div>
        </form>

        <!-- Info Current Selection -->
        <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-200">
            <div class="flex items-center gap-2 text-sm text-green-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <strong>Sedang menampilkan:</strong>
                Tahun Ajaran <span class="font-semibold">{{ $selectedYear->year }}</span> -
                Semester <span class="font-semibold">{{ $selectedSemester == 1 ? 'Ganjil' : 'Genap' }}</span>
            </div>
        </div>
    </div>
</div>

<div class="printable-area bg-white print-table" style="max-width: 210mm; margin: 0 auto; padding: 20mm;">
    <!-- Header Raport -->
    <div class="text-center mb-8" style="border-bottom: 3px double #000; padding-bottom: 15px;">
        <div class="flex items-center justify-center gap-6 mb-3">

            <div class="text-center">
                <h1 style="font-size: 18px; font-weight: bold; margin: 0; text-transform: uppercase; letter-spacing: 1px;">LAPORAN HASIL BELAJAR SISWA</h1>
                <h2 style="font-size: 16px; font-weight: bold; margin: 5px 0; text-transform: uppercase;">{{ $school['name'] }}</h2>
                <p style="font-size: 12px; margin: 2px 0;">NPSN: {{ $school['npsn'] }}</p>
                <p style="font-size: 11px; margin: 0; color: #666;">{{ $school['address'] }}</p>
            </div>
        </div>
        <div style="margin-top: 15px; padding: 8px; background: #f8f9fa; border: 1px solid #ccc; display: inline-block;">
            <strong>TAHUN PELAJARAN {{ $selectedYear->year }} • SEMESTER {{ $selectedSemester == 1 ? 'GANJIL' : 'GENAP' }}</strong>
        </div>
    </div>

    <!-- Status Finalisasi -->
    <div class="mb-6 no-print">
        <div style="text-align: center; padding: 8px; border: 1px solid #ccc; background: {{ $raport && $raport->is_finalized ? '#d4edda' : '#fff3cd' }};">
            @if($raport && $raport->is_finalized)
            <strong style="color: #155724;">STATUS: RAPORT FINAL</strong>
            <div style="font-size: 11px; color: #155724;">Raport telah diselesaikan dan tidak dapat diubah</div>
            @else
            <strong style="color: #856404;">STATUS: DRAFT</strong>
            <div style="font-size: 11px; color: #856404;">Raport masih dalam proses penyusunan</div>
            @endif
        </div>
    </div>

    <!-- Data Siswa -->
    <table class="info-table w-full mb-6" style="border: 2px solid #000;">
        <tr>
            <td colspan="4" style="background: #f8f9fa; border-bottom: 1px solid #000; padding: 8px; font-weight: bold; text-center; text-transform: uppercase;">
                IDENTITAS SISWA
            </td>
        </tr>
        <tr>
            <td style="width: 120px; font-weight: bold; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 6px 10px;">Nama Siswa</td>
            <td style="width: 250px; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 6px 10px;">: {{ strtoupper($student->full_name) }}</td>
            <td style="width: 100px; font-weight: bold; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 6px 10px;">Kelas</td>
            <td style="border-bottom: 1px solid #000; padding: 6px 10px;">: {{ $kelas->name }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 6px 10px;">NIS / NISN</td>
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 6px 10px;">: {{ $student->nis }} / {{ $student->nisn }}</td>
            <td style="font-weight: bold; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 6px 10px;">Wali Kelas</td>
            <td style="border-bottom: 1px solid #000; padding: 6px 10px;">: {{ strtoupper($waliKelas->full_name ?? 'BELUM DITENTUKAN') }}</td>
        </tr>
    </table>

    <!-- Tabel Nilai Akademik -->
    <div class="mb-6">
        <h3 style="font-size: 14px; font-weight: bold; margin-bottom: 8px; text-transform: uppercase; background: #f8f9fa; padding: 6px 10px; border: 1px solid #000;">
            A. CAPAIAN KOMPETENSI SIKAP, PENGETAHUAN, DAN KETERAMPILAN
        </h3>
        <table class="formal-table w-full">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 40px; vertical-align: middle;">NO</th>
                    <th rowspan="2" style="width: 200px; vertical-align: middle;">MATA PELAJARAN</th>
                    <th rowspan="2" style="width: 50px; vertical-align: middle;">KKM</th>
                    <th colspan="2" style="text-align: center;">NILAI</th>
                    <th rowspan="2" style="width: 80px; vertical-align: middle;">KETERANGAN</th>
                </tr>
                <tr>
                    <th style="width: 60px;">ANGKA</th>
                    <th style="width: 80px;">SIKAP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grades as $index => $grade)
                @php
                $setting = $subjectSettings->get($grade->subject_id);
                $nilaiAkhir = null;
                $keterangan = '-';

                if ($setting) {
                if (!is_null($grade->final_grade)) {
                $nilaiAkhir = $grade->final_grade;
                } else {
                $nilaiAkhir = ($grade->assignment_grade * $setting->assignment_weight +
                $grade->uts_grade * $setting->uts_weight +
                $grade->uas_grade * $setting->uas_weight) / 100;
                }

                // Menentukan keterangan berdasarkan KKM
                $keterangan = $nilaiAkhir >= $setting->kkm ? 'TUNTAS' : 'BELUM TUNTAS';
                }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="text-align: left; font-weight: 500;">{{ $grade->subject->name }}</td>
                    <td class="text-center">{{ $setting->kkm ?? '-' }}</td>
                    <td class="text-center" style="font-weight: bold;">{{ $nilaiAkhir !== null ? number_format($nilaiAkhir, 0) : '-' }}</td>
                    <td class="text-center">
                        @if($grade->attitude_grade)
                        <span style="font-weight: bold; font-size: 12px;">
                            {{ strtoupper($grade->attitude_grade) }}
                        </span>
                        @else
                        <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td class="text-center" style="font-weight: bold; font-size: 12px;">
                        {{ $keterangan }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; color: #999; font-style: italic;">
                        Data nilai akademik belum tersedia
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Tabel Ekstrakurikuler -->
    <div class="mb-6">
        <h3 style="font-size: 14px; font-weight: bold; margin-bottom: 8px; text-transform: uppercase; background: #f8f9fa; padding: 6px 10px; border: 1px solid #000;">
            B. EKSTRAKURIKULER
        </h3>
        @php
        $myExtracurriculars = $student->getActiveExtracurriculars($selectedYear->id);
        @endphp
        <table class="formal-table w-full">
            <thead>
                <tr>
                    <th style="width: 40px;">NO</th>
                    <th style="width: 250px;">KEGIATAN EKSTRAKURIKULER</th>
                    <th style="width: 80px;">NILAI</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myExtracurriculars as $index => $extracurricular)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="text-align: left; font-weight: 500;">{{ strtoupper($extracurricular->name) }}</td>
                    <td class="text-center">
                        @if($extracurricular->pivot->grade)
                        <span style="font-weight: bold;">{{ $extracurricular->pivot->grade }}</span>
                        @else
                        <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($extracurricular->pivot->grade)
                        <span style="font-weight: bold; font-size: 12px;">TUNTAS</span>
                        @else
                        <span style="color: #999;">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 20px; color: #999; font-style: italic;">
                        Tidak ada kegiatan ekstrakurikuler
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Tabel Absensi -->
    <div class="mb-6">
        <h3 style="font-size: 14px; font-weight: bold; margin-bottom: 8px; text-transform: uppercase; background: #f8f9fa; padding: 6px 10px; border: 1px solid #000;">
            C. ABSENSI
        </h3>
        <table class="formal-table w-full">
            <thead>
                <tr>
                    <th style="width: 40px;">NO</th>
                    <th style="width: 150px;">JENIS</th>
                    <th style="width: 100px;">JUMLAH</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td style="text-align: left; font-weight: 500;">Hadir</td>
                    <td class="text-center" style="font-weight: bold;">{{ $attendanceStats['hadir'] ?? 0 }}</td>
                    <td class="text-center">Kehadiran normal</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td style="text-align: left; font-weight: 500;">Sakit</td>
                    <td class="text-center" style="font-weight: bold;">{{ $attendanceStats['sakit'] ?? 0 }}</td>
                    <td class="text-center">Dengan surat dokter</td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td style="text-align: left; font-weight: 500;">Izin</td>
                    <td class="text-center" style="font-weight: bold;">{{ $attendanceStats['izin'] ?? 0 }}</td>
                    <td class="text-center">Dengan surat izin</td>
                </tr>
                <tr>
                    <td class="text-center">4</td>
                    <td style="text-align: left; font-weight: 500;">Alpha</td>
                    <td class="text-center" style="font-weight: bold;">{{ $attendanceStats['alpha'] ?? 0 }}</td>
                    <td class="text-center">Tanpa keterangan</td>
                </tr>
                <tr style="background: #f8f9fa;">
                    <td colspan="2" style="text-align: left; font-weight: bold;">TOTAL HARI</td>
                    <td class="text-center" style="font-weight: bold;">{{ $attendanceStats['total_days'] ?? 0 }}</td>
                    <td class="text-center">Kehadiran: {{ number_format($attendanceStats['percentage'] ?? 0, 1) }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Catatan Wali Kelas -->
    @if($raport && $raport->homeroom_teacher_notes)
    <div class="mb-6">
        <h3 style="font-size: 14px; font-weight: bold; margin-bottom: 8px; text-transform: uppercase; background: #f8f9fa; padding: 6px 10px; border: 1px solid #000;">
            D. CATATAN WALI KELAS
        </h3>
        <div style="border: 1px solid #000; padding: 15px; min-height: 80px; background: #fff;">
            <p style="margin: 0; line-height: 1.5; font-size: 13px;">{{ $raport->homeroom_teacher_notes }}</p>
        </div>
    </div>
    @endif

    <!-- Tanda Tangan -->
    <div class="signature-section">
        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
            <div style="text-align: center; width: 45%;">
                <p style="margin-bottom: 50px; font-size: 12px;">Mengetahui,<br>Orang Tua/Wali</p>
                <div style="border-bottom: 1px solid #000; width: 80%; margin: 0 auto; height: 1px;"></div>
                <p style="margin-top: 5px; font-size: 12px;">Nama Orang Tua/Wali</p>
            </div>
            <div style="text-align: center; width: 45%;">
                <p style="margin-bottom: 50px; font-size: 12px;">{{ $school['name'] }}, {{ now()->format('d F Y') }}<br>Wali Kelas</p>
                <div style="border-bottom: 1px solid #000; width: 80%; margin: 0 auto; height: 1px;"></div>
                <p style="margin-top: 5px; font-size: 12px;">{{ strtoupper($waliKelas->full_name ?? 'NAMA WALI KELAS') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
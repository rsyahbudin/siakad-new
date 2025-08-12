@extends('layouts.dashboard')
@section('title', 'Raport Digital')

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
<div class="flex justify-between items-center mb-6 no-print">
    <h2 class="text-2xl font-bold">Raport Digital</h2>
    <div class="flex items-center gap-4">
        <a href="{{ route('siswa.all-raports') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
            Lihat Semua Raport
        </a>
        @if($raport && $raport->is_finalized)
        <button onclick="window.print()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center gap-2">
            Cetak Raport
        </button>
        @else
        <button class="bg-gray-300 text-gray-500 px-4 py-2 rounded flex items-center gap-2 cursor-not-allowed" title="Raport belum final, tidak bisa diunduh" disabled>
            Cetak Raport
        </button>
        @endif
    </div>
</div>

<!-- Filter Tahun Ajaran dan Semester -->
<div class="bg-white rounded-lg shadow p-6 mb-6 no-print">
    <h3 class="text-lg font-semibold mb-4">Pilih Tahun Ajaran dan Semester</h3>
    <form method="GET" action="{{ route('siswa.raport') }}" class="flex items-center gap-4">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran</label>
            <select name="academic_year_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach($academicYears as $year)
                <option value="{{ $year->id }}" {{ $selectedYear->id == $year->id ? 'selected' : '' }}>
                    {{ $year->year }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
            <select name="semester" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="1" {{ $selectedSemester == 1 ? 'selected' : '' }}>Ganjil</option>
                <option value="2" {{ $selectedSemester == 2 ? 'selected' : '' }}>Genap</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                Tampilkan
            </button>
        </div>
    </form>
</div>

<div class="printable-area bg-white print-table" style="max-width: 210mm; margin: 0 auto; padding: 20mm;">
    <!-- Header Raport -->
    <div class="text-center mb-8" style="border-bottom: 3px double #000; padding-bottom: 15px;">
        <div class="flex items-center justify-center gap-6 mb-3">
            <div style="width: 80px; height: 80px; border: 2px solid #000; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                <span style="font-size: 12px; font-weight: bold; color: #666;">LOGO</span>
            </div>
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
                    <th rowspan="2" style="width: 80px; vertical-align: middle;">PREDIKAT</th>
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
                $predikat = '-';
                
                if ($setting) {
                    if (!is_null($grade->final_grade)) {
                        $nilaiAkhir = $grade->final_grade;
                    } else {
                        $nilaiAkhir = ($grade->assignment_grade * $setting->assignment_weight +
                                     $grade->uts_grade * $setting->uts_weight +
                                     $grade->uas_grade * $setting->uas_weight) / 100;
                    }
                    
                    // Menentukan predikat berdasarkan nilai
                    if ($nilaiAkhir >= 90) $predikat = 'A';
                    elseif ($nilaiAkhir >= 80) $predikat = 'B';
                    elseif ($nilaiAkhir >= 70) $predikat = 'C';
                    elseif ($nilaiAkhir >= 60) $predikat = 'D';
                    else $predikat = 'E';
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
                    <td class="text-center" style="font-weight: bold; font-size: 14px;">
                        {{ $predikat }}
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
                        <span style="font-weight: bold; font-size: 12px;">
                            {{ $extracurricular->pivot->grade }}
                        </span>
                        @else
                        <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td style="text-align: left;">{{ $extracurricular->pivot->notes ?: 'Tidak ada catatan' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 20px; color: #999; font-style: italic;">
                        Belum mengikuti kegiatan ekstrakurikuler
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Layout Kehadiran dan Catatan Wali Kelas -->
    <div class="mb-6" style="display: flex; gap: 20px;">
        <!-- Kolom Kiri: Kehadiran -->
        <div style="flex: 0 0 300px;">
            <h3 style="font-size: 14px; font-weight: bold; margin-bottom: 8px; text-transform: uppercase; background: #f8f9fa; padding: 6px 10px; border: 1px solid #000;">
                C. KETIDAKHADIRAN
            </h3>
            <table class="formal-table w-full">
                <thead>
                    <tr>
                        <th>JENIS</th>
                        <th>JUMLAH</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: left; padding: 8px;">Sakit</td>
                        <td style="text-align: center; font-weight: bold;">{{ $attendance_sick }} hari</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; padding: 8px;">Izin</td>
                        <td style="text-align: center; font-weight: bold;">{{ $attendance_permit }} hari</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; padding: 8px;">Tanpa Keterangan</td>
                        <td style="text-align: center; font-weight: bold;">{{ $attendance_absent }} hari</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Kolom Kanan: Catatan Wali Kelas -->
        <div style="flex: 1;">
            <h3 style="font-size: 14px; font-weight: bold; margin-bottom: 8px; text-transform: uppercase; background: #f8f9fa; padding: 6px 10px; border: 1px solid #000;">
                D. CATATAN WALI KELAS
            </h3>
            <div style="border: 2px solid #000; padding: 15px; height: 120px; background: #fafafa;">
                <p style="line-height: 1.6; margin: 0; text-align: justify; font-size: 12px;">
                    {{ $raport->homeroom_teacher_notes ?? 'Tidak ada catatan khusus dari wali kelas untuk semester ini.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Tanda Tangan -->
    <div class="signature-section">
        <table class="w-full" style="border: 2px solid #000; font-size: 12px;">
            <tr>
                <td style="width: 33.33%; text-align: center; padding: 15px; border-right: 1px solid #000; vertical-align: top;">
                    <div style="margin-bottom: 10px;">
                        <strong>MENGETAHUI,</strong><br>
                        <strong>ORANG TUA/WALI</strong>
                    </div>
                    <div style="height: 60px; margin: 20px 0;"></div>
                    <div style="border-top: 1px solid #000; padding-top: 5px; margin-top: 10px;">
                        <strong>(...........................)</strong>
                    </div>
                </td>
                <td style="width: 33.33%; text-align: center; padding: 15px; border-right: 1px solid #000; vertical-align: top;">
                    <div style="margin-bottom: 10px;">
                        <strong>WALI KELAS</strong>
                    </div>
                    <div style="height: 60px; margin: 20px 0;"></div>
                    <div style="border-top: 1px solid #000; padding-top: 5px; margin-top: 10px;">
                        <strong>{{ strtoupper($waliKelas->full_name ?? '(...........................)') }}</strong><br>
                        <small>NIP. {{ $waliKelas->nip ?? '..............................' }}</small>
                    </div>
                </td>
                <td style="width: 33.33%; text-align: center; padding: 15px; vertical-align: top;">
                    <div style="margin-bottom: 10px;">
                        <strong>{{ strtoupper($school['address'] ? explode(',', $school['address'])[0] : '________') }}, {{ strtoupper(\Carbon\Carbon::now()->isoFormat('D MMMM YYYY')) }}</strong><br>
                        <strong>KEPALA SEKOLAH</strong>
                    </div>
                    <div style="height: 60px; margin: 20px 0;"></div>
                    <div style="border-top: 1px solid #000; padding-top: 5px; margin-top: 10px;">
                        <strong>{{ strtoupper($kepalaSekolah->full_name ?? '(...........................)') }}</strong><br>
                        <small>NIP. {{ $kepalaSekolah->nip ?? '..............................' }}</small>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection
@extends('layouts.dashboard')
@section('title', 'Raport Digital')

@push('styles')
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

        /* Sembunyikan elemen dashboard lainnya */
        aside,
        header,
        .no-print {
            display: none !important;
        }

        main.flex-1 {
            padding: 0 !important;
            margin: 0 !important;
        }
    }
</style>
@endpush

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

<div class="printable-area bg-white p-8 rounded-lg shadow-lg border">
    <!-- Header Raport -->
    <div class="text-center mb-8 border-b pb-4">
        <div class="flex items-center justify-center gap-4 mb-2">
            <div class="w-14 h-14 rounded-full border border-gray-300 flex items-center justify-center bg-blue-50">
                <span class="text-lg font-bold text-blue-600">LOGO</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">LAPORAN HASIL BELAJAR</h1>
                <h2 class="text-xl font-semibold text-gray-700">{{ $school['name'] }}</h2>
                <p class="text-xs text-gray-500">NPSN: {{ $school['npsn'] }}</p>
            </div>
        </div>
        <p class="text-sm text-gray-600">Alamat: {{ $school['address'] }}</p>
        <p class="text-sm text-gray-500">Tahun Ajaran {{ $selectedYear->year }} • Semester {{ $selectedSemester == 1 ? 'Ganjil' : 'Genap' }}</p>
    </div>

    <!-- Status Finalisasi Raport -->
    <div class="mb-4">
        @if($raport && $raport->is_finalized)
        <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded font-semibold text-sm">Status: Final</span>
        <span class="ml-2 text-green-700">Raport sudah final, nilai tidak bisa diubah.</span>
        @else
        <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded font-semibold text-sm">Status: Draft</span>
        <span class="ml-2 text-yellow-700">Raport ini masih draft, nilai bisa berubah.</span>
        @endif
    </div>

    <!-- Informasi Siswa -->
    <div class="grid grid-cols-2 gap-x-8 gap-y-2 mb-8 text-sm">
        <div><span class="font-semibold w-32 inline-block">Nama Siswa</span>: {{ $student->full_name }}</div>
        <div><span class="font-semibold w-32 inline-block">NIS / NISN</span>: {{ $student->nis }} / {{ $student->nisn }}</div>
        <div><span class="font-semibold w-32 inline-block">Kelas</span>: {{ $kelas->name }}</div>
        <div><span class="font-semibold w-32 inline-block">Wali Kelas</span>: {{ $waliKelas->full_name ?? '-' }}</div>
    </div>

    <!-- Tabel Nilai -->
    <h3 class="text-lg font-bold mb-2">A. Nilai Akademik</h3>
    <table class="min-w-full border text-sm">
        <thead class="bg-gray-100 font-semibold">
            <tr>
                <th class="border px-4 py-2 text-center">No</th>
                <th class="border px-4 py-2 text-left">Mata Pelajaran</th>
                <th class="border px-4 py-2 text-center">KKM</th>
                <th class="border px-4 py-2 text-center">Nilai Akhir</th>
                <th class="border px-4 py-2 text-center">Nilai Sikap</th>
                <th class="border px-4 py-2 text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($grades as $index => $grade)
            @php
            $setting = $subjectSettings->get($grade->subject_id);
            $nilaiAkhir = null;
            $status = false;
            if ($setting) {
            if (!is_null($grade->final_grade)) {
            $nilaiAkhir = $grade->final_grade;
            } else {
            $nilaiAkhir = ($grade->assignment_grade * $setting->assignment_weight +
            $grade->uts_grade * $setting->uts_weight +
            $grade->uas_grade * $setting->uas_weight) / 100;
            }
            $status = $nilaiAkhir >= $setting->kkm;
            }
            @endphp
            <tr>
                <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
                <td class="border px-4 py-2">{{ $grade->subject->name }}</td>
                <td class="border px-4 py-2 text-center">{{ $setting->kkm ?? '-' }}</td>
                <td class="border px-4 py-2 text-center font-semibold">{{ $nilaiAkhir !== null ? number_format($nilaiAkhir, 2) : '-' }}</td>
                <td class="border px-4 py-2 text-center font-medium">
                    @if($grade->attitude_grade)
                    <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($grade->attitude_grade === 'Baik') bg-green-100 text-green-800
                            @elseif($grade->attitude_grade === 'Cukup') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                        {{ $grade->attitude_grade }}
                    </span>
                    @else
                    <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="border px-4 py-2 text-center font-medium {{ $status ? 'text-green-600' : 'text-red-600' }}">
                    {{ $status ? 'Tuntas' : 'Tidak Tuntas' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="border px-4 py-2 text-center text-gray-500">Data nilai belum tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Kehadiran -->
    <div class="mt-8">
        <h3 class="text-lg font-bold mb-2">B. Kehadiran</h3>
        <table class="min-w-full border text-sm">
            <thead class="bg-gray-100 font-semibold">
                <tr>
                    <th class="border px-4 py-2 text-center">Sakit</th>
                    <th class="border px-4 py-2 text-center">Izin</th>
                    <th class="border px-4 py-2 text-center">Tanpa Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border px-4 py-2 text-center font-bold">{{ $attendance_sick }} hari</td>
                    <td class="border px-4 py-2 text-center font-bold">{{ $attendance_permit }} hari</td>
                    <td class="border px-4 py-2 text-center font-bold">{{ $attendance_absent }} hari</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Ekstrakurikuler -->
    <div class="mt-8">
        <h3 class="text-lg font-bold mb-2">C. Ekstrakurikuler</h3>
        @php
        $myExtracurriculars = $student->getActiveExtracurriculars($selectedYear->id);
        @endphp

        <table class="min-w-full border text-sm">
            <thead class="bg-gray-100 font-semibold">
                <tr>
                    <th class="border px-4 py-2 text-center">No</th>
                    <th class="border px-4 py-2 text-left">Nama Ekstrakurikuler</th>
                    <th class="border px-4 py-2 text-center">Posisi</th>
                    <th class="border px-4 py-2 text-center">Nilai</th>
                    <th class="border px-4 py-2 text-left">Prestasi</th>
                    <th class="border px-4 py-2 text-left">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myExtracurriculars as $index => $extracurricular)
                <tr>
                    <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
                    <td class="border px-4 py-2">{{ $extracurricular->name }}</td>
                    <td class="border px-4 py-2 text-center">{{ $extracurricular->pivot->position }}</td>
                    <td class="border px-4 py-2 text-center font-medium">
                        @if($extracurricular->pivot->grade)
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($extracurricular->pivot->grade === 'Sangat Baik') bg-green-100 text-green-800
                            @elseif($extracurricular->pivot->grade === 'Baik') bg-blue-100 text-blue-800
                            @elseif($extracurricular->pivot->grade === 'Cukup') bg-yellow-100 text-yellow-800
                            @elseif($extracurricular->pivot->grade === 'Kurang') bg-red-100 text-red-800
                            @endif">
                            {{ $extracurricular->pivot->grade }}
                        </span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="border px-4 py-2">{{ $extracurricular->pivot->achievements ?: '-' }}</td>
                    <td class="border px-4 py-2">{{ $extracurricular->pivot->notes ?: '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="border px-4 py-2 text-center text-gray-500">Belum mengikuti ekstrakurikuler apapun.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Catatan Wali Kelas -->
    <div class="mt-8">
        <h3 class="text-lg font-bold mb-2">D. Catatan Wali Kelas</h3>
        <div class="bg-white rounded-lg border border-gray-200 p-4 min-h-[100px]">
            <p class="text-gray-700 leading-relaxed">{{ $raport->homeroom_teacher_notes ?? 'Tidak ada catatan dari wali kelas.' }}</p>
        </div>
    </div>

    <!-- Tanda Tangan -->
    <div class="mt-12 grid grid-cols-3 gap-6 text-center text-sm">
        <div>
            <p>Mengetahui,</p>
            <p>Orang Tua/Wali</p>
            <br><br><br>
            <p>(..............................)</p>
        </div>
        <div>
            <p>Wali Kelas</p>
            <br><br><br>
            <p class="font-semibold underline">{{ $waliKelas->full_name ?? '..............................' }}</p>
            <p>NIP. {{ $waliKelas->nip ?? '..............................' }}</p>
        </div>
        <div>
            <p>{{ $school['address'] ? explode(',', $school['address'])[0] : '________' }}, {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</p>
            <p>Kepala Sekolah</p>
            <br><br><br>
            <p class="font-semibold underline">{{ $kepalaSekolah->full_name ?? '(..............................)' }}</p>
            <p>NIP. {{ $kepalaSekolah->nip ?? '..............................' }}</p>
        </div>
    </div>

</div>
@endsection
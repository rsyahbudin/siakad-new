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
    <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
        </svg>
        Cetak Raport
    </button>
</div>

<div class="printable-area bg-white p-8 rounded-lg shadow-lg border">
    <!-- Header Raport -->
    <div class="text-center mb-8 border-b pb-4">
        <h1 class="text-2xl font-bold text-gray-800">LAPORAN HASIL BELAJAR SISWA</h1>
        <h2 class="text-xl font-semibold text-gray-700">SMA NEGERI HARAPAN BANGSA</h2>
        <p class="text-sm text-gray-500">Tahun Ajaran {{ $activeYear->year }} - Semester {{ $activeYear->semester }}</p>
    </div>

    <!-- Informasi Siswa -->
    <div class="grid grid-cols-2 gap-x-8 gap-y-2 mb-8 text-sm">
        <div><span class="font-semibold w-32 inline-block">Nama Siswa</span>: {{ $student->full_name }}</div>
        <div><span class="font-semibold w-32 inline-block">NIS / NISN</span>: {{ $student->nis }} / {{ $student->nisn }}</div>
        <div><span class="font-semibold w-32 inline-block">Kelas</span>: {{ $classroom->name }}</div>
        <div><span class="font-semibold w-32 inline-block">Wali Kelas</span>: {{ $classroom->homeroomTeacher->full_name ?? '-' }}</div>
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
                <th class="border px-4 py-2 text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($grades as $index => $grade)
            @php
            $setting = $subjectSettings->get($grade->subject_id);
            $nilaiAkhir = 0;
            $status = false;
            if ($setting) {
            $nilaiAkhir = ($grade->assignment_grade * $setting->assignment_weight +
            $grade->uts_grade * $setting->uts_weight +
            $grade->uas_grade * $setting->uas_weight) / 100;
            $status = $nilaiAkhir >= $setting->kkm;
            }
            @endphp
            <tr>
                <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
                <td class="border px-4 py-2">{{ $grade->subject->name }}</td>
                <td class="border px-4 py-2 text-center">{{ $setting->kkm ?? '-' }}</td>
                <td class="border px-4 py-2 text-center font-semibold">{{ number_format($nilaiAkhir, 2) }}</td>
                <td class="border px-4 py-2 text-center font-medium {{ $status ? 'text-green-600' : 'text-red-600' }}">
                    {{ $status ? 'Tuntas' : 'Tidak Tuntas' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="border px-4 py-2 text-center text-gray-500">Data nilai belum tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Absensi dan Catatan -->
    <div class="grid grid-cols-2 gap-8 mt-8 text-sm">
        <div>
            <h3 class="text-lg font-bold mb-2">B. Ketidakhadiran</h3>
            <table class="w-full border">
                <tr class="border-b">
                    <td class="px-4 py-2 border-r">Sakit</td>
                    <td class="px-4 py-2 text-center">{{ $raport->attendance_sick ?? 0 }} hari</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2 border-r">Izin</td>
                    <td class="px-4 py-2 text-center">{{ $raport->attendance_permit ?? 0 }} hari</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border-r">Tanpa Keterangan</td>
                    <td class="px-4 py-2 text-center">{{ $raport->attendance_absent ?? 0 }} hari</td>
                </tr>
            </table>
        </div>
        <div>
            <h3 class="text-lg font-bold mb-2">C. Catatan Wali Kelas</h3>
            <div class="border p-4 h-32">
                <p>{{ $raport->homeroom_teacher_notes ?? 'Tidak ada catatan.' }}</p>
            </div>
        </div>
    </div>

    <!-- Tanda Tangan -->
    <div class="mt-12 flex justify-between text-center text-sm">
        <div>
            <p>Mengetahui,</p>
            <p>Orang Tua/Wali</p>
            <br><br><br>
            <p>(..............................)</p>
        </div>
        <div>
            <p>Jakarta, {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</p>
            <p>Wali Kelas,</p>
            <br><br><br>
            <p class="font-semibold underline">{{ $classroom->homeroomTeacher->full_name ?? '..............................' }}</p>
            <p>NIP. {{ $classroom->homeroomTeacher->nip ?? '..............................' }}</p>
        </div>
    </div>

</div>
@endsection
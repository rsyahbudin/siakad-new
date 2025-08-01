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
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Lihat Semua Raport
        </a>
        @if($raport && $raport->is_finalized)
        <button onclick="window.print()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak Raport
        </button>
        @else
        <button class="bg-gray-300 text-gray-500 px-4 py-2 rounded flex items-center gap-2 cursor-not-allowed" title="Raport belum final, tidak bisa diunduh" disabled>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
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
        <h1 class="text-2xl font-bold text-gray-800">LAPORAN HASIL BELAJAR SISWA</h1>
        <h2 class="text-xl font-semibold text-gray-700">SMA NEGERI HARAPAN BANGSA</h2>
        <p class="text-sm text-gray-500">Tahun Ajaran {{ $selectedYear->year }} - Semester {{ $selectedSemester == 1 ? 'Ganjil' : 'Genap' }}</p>
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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <div>
            <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                B. Kehadiran
            </h3>

            <!-- Attendance Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Sakit</p>
                            <p class="text-xl font-bold text-gray-900">{{ $attendance_sick }} hari</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Izin</p>
                            <p class="text-xl font-bold text-gray-900">{{ $attendance_permit }} hari</p>
                        </div>
                    </div>
                </div>

                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Alpha</p>
                            <p class="text-xl font-bold text-gray-900">{{ $attendance_absent }} hari</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Details Table -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <h4 class="font-medium text-gray-900">Detail Ketidakhadiran</h4>
                </div>
                <div class="divide-y divide-gray-200">
                    <div class="flex items-center justify-between px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900">Sakit</span>
                        </div>
                        <span class="font-bold text-gray-900">{{ $attendance_sick }} hari</span>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900">Izin</span>
                        </div>
                        <span class="font-bold text-gray-900">{{ $attendance_permit }} hari</span>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900">Tanpa Keterangan</span>
                        </div>
                        <span class="font-bold text-gray-900">{{ $attendance_absent }} hari</span>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                C. Catatan Wali Kelas
            </h3>
            <div class="bg-white rounded-lg border border-gray-200 p-4 min-h-[200px]">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-700 leading-relaxed">{{ $raport->homeroom_teacher_notes ?? 'Tidak ada catatan dari wali kelas.' }}</p>
                    </div>
                </div>
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
            <p class="font-semibold underline">{{ $waliKelas->full_name ?? '..............................' }}</p>
            <p>NIP. {{ $waliKelas->nip ?? '..............................' }}</p>
        </div>
    </div>

</div>
@endsection
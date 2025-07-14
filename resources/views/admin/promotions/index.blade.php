@extends('layouts.dashboard')
@section('title', 'Proses Kenaikan & Kelulusan')
@section('content')
<h2 class="text-2xl font-bold mb-4">Proses Kenaikan & Kelulusan Massal</h2>
<p class="mb-6">Tahun Ajaran yang akan diproses: <span class="font-semibold">{{ $academicYear->year }}</span></p>

@if (session('success'))
<div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-md shadow-sm">
    <p>{{ session('success') }}</p>
</div>
@endif
@if (session('error'))
<div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-md shadow-sm">
    <p>{{ session('error') }}</p>
</div>
@endif

@php
$totalNaik = $promotionStatus->sum('count_naik');
$totalTidakNaik = $promotionStatus->sum('count_tidak_naik');
$totalBelum = $promotionStatus->sum('count_belum');
$totalSiswa = $promotionStatus->sum('student_count');
@endphp

<!-- Ringkasan Statistik Seluruh Sekolah -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="flex items-center bg-green-50 p-4 rounded-lg border border-green-200 shadow">
        <div class="p-2 bg-green-100 rounded-lg">
            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-green-600">Total Naik/Lulus</p>
            <p class="text-2xl font-bold text-green-900">{{ $totalNaik }}</p>
        </div>
    </div>
    <div class="flex items-center bg-red-50 p-4 rounded-lg border border-red-200 shadow">
        <div class="p-2 bg-red-100 rounded-lg">
            <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-red-600">Total Tidak Naik/Tidak Lulus</p>
            <p class="text-2xl font-bold text-red-900">{{ $totalTidakNaik }}</p>
        </div>
    </div>
    <div class="flex items-center bg-gray-50 p-4 rounded-lg border border-gray-200 shadow">
        <div class="p-2 bg-gray-100 rounded-lg">
            <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
            </svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Belum Diputuskan</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalBelum }}</p>
        </div>
    </div>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-4 border-b">
        <h3 class="text-lg font-semibold">Status Kesiapan Penilaian per Kelas</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 text-left">Kelas</th>
                    <th class="py-2 px-4 text-left">Wali Kelas</th>
                    <th class="py-2 px-4 text-center">Jumlah Siswa</th>
                    <th class="py-2 px-4 text-center">Data Tersimpan</th>
                    <th class="py-2 px-4 text-center">Rekap Keputusan</th>
                    <th class="py-2 px-4 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promotionStatus as $status)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4 font-medium">{{ $status->assignment->classroom->name }}</td>
                    <td class="py-2 px-4">{{ $status->assignment->homeroomTeacher->user->name ?? 'N/A' }}</td>
                    <td class="py-2 px-4 text-center">{{ $status->student_count }}</td>
                    <td class="py-2 px-4 text-center">{{ $status->promotion_count }}</td>
                    <td class="py-2 px-4 text-center">
                        <div class="flex flex-col gap-1 items-center">
                            <span class="inline-flex items-center bg-green-100 text-green-800 rounded px-2 py-0.5 text-xs font-semibold min-w-[90px]" title="{{ $status->is_last_grade ? 'Lulus' : 'Naik Kelas' }}">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $status->count_naik }} {{ $status->is_last_grade ? 'Lulus' : 'Naik' }}
                            </span>
                            <span class="inline-flex items-center bg-red-100 text-red-800 rounded px-2 py-0.5 text-xs font-semibold min-w-[90px]" title="{{ $status->is_last_grade ? 'Tidak Lulus' : 'Tinggal Kelas' }}">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{ $status->count_tidak_naik }} {{ $status->is_last_grade ? 'Tidak Lulus' : 'Tinggal' }}
                            </span>
                            <span class="inline-flex items-center bg-gray-100 text-gray-800 rounded px-2 py-0.5 text-xs font-semibold min-w-[90px]" title="Belum Diputuskan">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                                </svg>
                                {{ $status->count_belum }} Belum
                            </span>
                        </div>
                    </td>
                    <td class="py-2 px-4">
                        @if($status->is_ready)
                        <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">{{ $status->status_message }}</span>
                        @else
                        <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full">{{ $status->status_message }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-4 px-4 text-center text-gray-500">Tidak ada data kelas untuk tahun ajaran ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 bg-gray-50 flex justify-end">
        <form action="{{ route('admin.promotions.process') }}" method="POST">
            @csrf
            <button type="submit"
                class="px-6 py-2 text-white font-semibold rounded-lg shadow-md
                           {{ $allReady ? 'bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50' : 'bg-gray-400 cursor-not-allowed' }}"
                {{ !$allReady ? 'disabled' : '' }}
                onclick="return confirm('Apakah Anda yakin ingin menjalankan proses kenaikan dan kelulusan? Tindakan ini tidak dapat diurungkan.')">
                Proses Kenaikan & Kelulusan
            </button>
        </form>
    </div>
</div>
@if(!$allReady)
<div class="mt-4 p-4 text-sm text-yellow-800 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
    <strong>Perhatian:</strong> Tombol proses akan aktif setelah semua wali kelas menyelesaikan pengisian data kenaikan/kelulusan untuk kelas mereka masing-masing.
</div>
@endif

@endsection
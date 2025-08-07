@extends('layouts.dashboard')

@section('title', 'Kenaikan Kelas & Kelulusan')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kenaikan Kelas & Kelulusan</h1>
                <p class="text-gray-600 mt-1">Kelola proses kenaikan kelas dan kelulusan siswa</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('kepala.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Disabled Message -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8">
        <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-yellow-900 mb-2">Fitur Tidak Tersedia</h2>
            <p class="text-yellow-800 mb-4 max-w-md">{{ $message }}</p>
            <div class="bg-yellow-100 rounded-lg p-4 max-w-md">
                <h3 class="font-medium text-yellow-900 mb-2">Kapan Fitur Ini Tersedia?</h3>
                <ul class="text-sm text-yellow-800 space-y-1">
                    <li>• Hanya pada akhir semester Genap</li>
                    <li>• Setelah semua wali kelas menyelesaikan keputusan</li>
                    <li>• Setelah tahun ajaran berikutnya dibuat</li>
                    <li>• Setelah semua nilai dan raport difinalisasi</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Information Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Informasi Proses Kenaikan Kelas</h3>
                <div class="text-sm text-gray-600 space-y-2">
                    <p>Proses kenaikan kelas dan kelulusan adalah fitur yang sangat penting yang hanya dapat diakses pada waktu tertentu untuk memastikan keakuratan data akademik.</p>
                    <p>Fitur ini memungkinkan kepala sekolah untuk:</p>
                    <ul class="list-disc list-inside space-y-1 ml-4">
                        <li>Memindahkan siswa ke kelas berikutnya berdasarkan keputusan wali kelas</li>
                        <li>Mengubah status siswa kelas XII menjadi 'Lulus'</li>
                        <li>Membuat data tahun ajaran baru secara otomatis</li>
                        <li>Memastikan semua proses akademik telah selesai</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

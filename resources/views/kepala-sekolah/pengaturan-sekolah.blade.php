@extends('layouts.dashboard')

@section('title', 'Pengaturan Sekolah')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pengaturan Sekolah</h1>
                <p class="text-gray-600 mt-1">Kelola pengaturan sistem dan akademik sekolah</p>
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

    <!-- Academic Overview (read-only) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 5.477 18.246 5 16.5 5c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Akademik Aktif</h2>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Tahun Ajaran Aktif</span>
                    <span class="text-sm font-medium text-gray-900">{{ $activeYear->year ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Semester Aktif</span>
                    <span class="text-sm font-medium text-gray-900">{{ $activeSemester->name ?? '-' }}</span>
                </div>
                <p class="text-xs text-gray-500">Pengaturan detail KKM dan bobot nilai dikelola oleh Admin pada menu Pengaturan KKM.</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Sekolah (untuk Raport)</h2>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Nama Sekolah</span>
                    <span class="text-sm font-medium text-gray-900">{{ \App\Models\AppSetting::getValue('school_name', '-') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">NPSN</span>
                    <span class="text-sm font-medium text-gray-900">{{ \App\Models\AppSetting::getValue('school_npsn', '-') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Alamat</span>
                    <span class="text-sm font-medium text-gray-900 text-right">{{ \App\Models\AppSetting::getValue('school_address', '-') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Telepon</span>
                    <span class="text-sm font-medium text-gray-900">{{ \App\Models\AppSetting::getValue('school_phone', '-') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Email</span>
                    <span class="text-sm font-medium text-gray-900">{{ \App\Models\AppSetting::getValue('school_email', '-') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Website</span>
                    <span class="text-sm font-medium text-gray-900">{{ \App\Models\AppSetting::getValue('school_website', '-') }}</span>
                </div>
                <p class="text-xs text-gray-500">Informasi ini akan ditampilkan pada header raport siswa bila tersedia.</p>
            </div>
        </div>
    </div>
</div>
@endsection
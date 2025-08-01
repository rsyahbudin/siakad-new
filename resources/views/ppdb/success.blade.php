@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pendaftaran Berhasil!</h1>
            <p class="text-gray-600">Terima kasih telah mendaftar PPDB. Data Anda telah kami terima.</p>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-green-800 font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Application Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Detail Pendaftaran</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Pendaftaran</label>
                        <p class="text-lg font-semibold text-blue-600">{{ $application->application_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $application->status_badge_class }}">
                            {{ $application->status_label }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <p class="text-gray-900">{{ $application->full_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NISN</label>
                        <p class="text-gray-900">{{ $application->nisn }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="text-gray-900">{{ $application->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jalur Pendaftaran</label>
                        <p class="text-gray-900">{{ $application->entry_path_label }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jurusan yang Diminati</label>
                        <p class="text-gray-900">{{ $application->desired_major_label }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Pendaftaran</label>
                        <p class="text-gray-900">{{ $application->submitted_at ? $application->submitted_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Important Information -->
        <div class="mt-6 bg-blue-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-blue-900 mb-4">Informasi Penting</h3>
            <div class="space-y-3 text-sm text-blue-800">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span><strong>Simpan nomor pendaftaran Anda:</strong> {{ $application->application_number }}</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Gunakan nomor pendaftaran dan NISN untuk mengecek status pendaftaran</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Tim admin akan memverifikasi dokumen dan mengumumkan hasil seleksi</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Jika lulus, akun siswa dan wali murid akan dibuat otomatis</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('ppdb.status-check') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors">
                Cek Status Pendaftaran
            </a>
            <a href="{{ route('ppdb.register') }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors">
                Daftar Lagi
            </a>
        </div>

        <!-- Requirements Based on Entry Path -->
        <div class="mt-6 bg-yellow-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-yellow-900 mb-4">Persyaratan Kelulusan</h3>
            <div class="space-y-3 text-sm text-yellow-800">
                @if($application->entry_path === 'tes')
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Wajib mengikuti tes masuk dengan nilai minimal 70</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Semua dokumen harus lengkap dan valid</span>
                </div>
                @elseif($application->entry_path === 'prestasi')
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Rata-rata nilai rapor minimal 85</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Piagam prestasi minimal tingkat kabupaten</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Tidak perlu mengikuti tes masuk</span>
                </div>
                @elseif($application->entry_path === 'afirmasi')
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Kelulusan berdasarkan kelengkapan dokumen</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Surat keterangan tidak mampu/KIP/PKH harus valid</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Tidak perlu mengikuti tes masuk</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
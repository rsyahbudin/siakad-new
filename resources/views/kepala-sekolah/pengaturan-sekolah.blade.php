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

    <!-- Informasi Sekolah -->
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

    <!-- KKM Settings Overview (Read-Only) -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Pengaturan KKM (Read-Only)</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    Hanya Lihat
                </span>
            </div>
        </div>
        <div class="p-6">
            <div class="mb-6">
                <h3 class="text-md font-medium text-gray-900 mb-4">Bobot Semester untuk Nilai Akhir Tahun</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Bobot Semester Ganjil</span>
                            <span class="text-lg font-bold text-blue-600">{{ $semesterWeights->ganjil_weight ?? 50 }}%</span>
                        </div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Bobot Semester Genap</span>
                            <span class="text-lg font-bold text-blue-600">{{ $semesterWeights->genap_weight ?? 50 }}%</span>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-2">Bobot ini digunakan untuk menghitung nilai akhir tahun dalam proses kenaikan kelas.</p>
            </div>

            <div class="mb-6">
                <h3 class="text-md font-medium text-gray-900 mb-4">Batas Mapel Gagal</h3>
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Batas Maksimal Mapel Gagal untuk Kenaikan/Kelulusan</span>
                        <span class="text-lg font-bold text-green-600">{{ \App\Models\AppSetting::getValue('max_failed_subjects', 2) }} Mapel</span>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-2">Pengaturan ini berlaku untuk semua semester dalam tahun ajaran aktif.</p>
            </div>

            <div>
                <h3 class="text-md font-medium text-gray-900 mb-4">KKM & Bobot Mata Pelajaran (Semester {{ $activeSemester->name ?? '-' }})</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">KKM</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot Tugas (%)</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot UTS (%)</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot UAS (%)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($subjects as $i => $subject)
                            @php $setting = $subject->subjectSettings->first(); @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm text-gray-900">{{ $i + 1 }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $subject->name }}</div>
                                    @if($subject->major)
                                    <div class="text-sm text-gray-500">{{ $subject->major->name }}</div>
                                    @else
                                    <div class="text-sm text-gray-500">Umum</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $setting->kkm ?? 75 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        {{ $setting->assignment_weight ?? 30 }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        {{ $setting->uts_weight ?? 30 }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                        {{ $setting->uas_weight ?? 40 }}%
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 5.477 18.246 5 16.5 5c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        <p class="text-sm">Belum ada data mata pelajaran.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-medium text-blue-900 mb-1">Informasi Pengaturan KKM</h4>
                        <p class="text-sm text-blue-800">
                            Data pengaturan KKM dan bobot semester ini dikelola oleh Admin pada menu Pengaturan KKM.
                            Kepala Sekolah hanya dapat melihat data ini untuk monitoring dan tidak dapat mengubahnya.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
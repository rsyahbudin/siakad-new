@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Selamat Datang, Admin!</h1>
                <p class="text-blue-100 mt-2">Kelola sistem akademik sekolah dengan mudah dan efisien</p>
                <p class="text-sm text-blue-200 mt-1">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-24 h-24 text-blue-200 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6">
        <!-- Total Students -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Siswa</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_students']) }}</p>
                    <p class="text-xs text-green-600 mt-1">Siswa Aktif</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Teachers -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Guru</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_teachers']) }}</p>
                    <p class="text-xs text-blue-600 mt-1">Guru Aktif</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Classrooms -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Kelas</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_classrooms']) }}</p>
                    <p class="text-xs text-purple-600 mt-1">Kelas Aktif</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>


        <!-- Total Schedules -->
        <!-- <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Jadwal</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_schedules']) }}</p>
                    <p class="text-xs text-orange-600 mt-1">Jadwal Aktif</p>
                </div>
                <div class="p-3 bg-orange-100 rounded-lg">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div> -->
    </div>

    <!-- PPDB & Transfer Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- PPDB Statistics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Statistik PPDB</h3>
                <a href="{{ route('admin.ppdb.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</a>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['ppdb_pending'] }}</p>
                    <p class="text-sm text-gray-600">Menunggu</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-2xl font-bold text-green-600">{{ $stats['ppdb_approved'] }}</p>
                    <p class="text-sm text-gray-600">Diterima</p>
                </div>
            </div>
        </div>

        <!-- Transfer Student Statistics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Statistik Siswa Pindahan</h3>
                <a href="{{ route('admin.transfer.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</a>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['transfer_pending'] }}</p>
                    <p class="text-sm text-gray-600">Menunggu</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-2xl font-bold text-green-600">{{ $stats['transfer_approved'] }}</p>
                    <p class="text-sm text-gray-600">Diterima</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Statistics by Grade Level -->
    @if($stats['active_year'])
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Statistik Siswa per Angkatan</h3>
            <span class="text-sm text-gray-600">Tahun Ajaran: {{ $stats['active_year']->year }}</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
            $gradeLabels = [
            '10' => 'Kelas X',
            '11' => 'Kelas XI',
            '12' => 'Kelas XII'
            ];
            $gradeColors = [
            '10' => 'bg-blue-50 text-blue-600',
            '11' => 'bg-green-50 text-green-600',
            '12' => 'bg-purple-50 text-purple-600'
            ];
            @endphp

            @foreach($gradeLabels as $grade => $label)
            <div class="text-center p-4 {{ $gradeColors[$grade] }} rounded-lg">
                <p class="text-2xl font-bold">{{ $stats['students_by_grade']->get($grade)->total ?? 0 }}</p>
                <p class="text-sm font-medium">{{ $label }}</p>
                <p class="text-xs opacity-75">Siswa Aktif & Pindahan</p>
            </div>
            @endforeach
        </div>
        
    </div>
    @endif

    <!-- Recent Activities -->
    <!-- <div class="grid grid-cols-1 lg:grid-cols-2 gap-6"> -->
    <!-- Recent Grades -->
    <!-- <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Nilai Terbaru</h3>
                <a href="{{ route('nilai.admin') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($stats['recent_grades'] as $grade)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">{{ $grade->student->full_name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">{{ $grade->subject->name ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-lg {{ $grade->final_grade >= 75 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $grade->final_grade ?? 'N/A' }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $grade->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p>Belum ada nilai yang diinput</p>
                </div>
                @endforelse
            </div>
        </div> -->

    <!-- Recent PPDB Applications -->
    <!-- <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Pendaftar PPDB Terbaru</h3>
                <a href="{{ route('admin.ppdb.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($stats['recent_ppdb'] as $application)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">{{ $application->full_name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">{{ $application->entry_path ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            @if($application->status === 'lulus') bg-green-100 text-green-800
                            @elseif($application->status === 'ditolak') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($application->status ?? 'pending') }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1">{{ $application->created_at ? $application->created_at->diffForHumans() : 'N/A' }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <p>Belum ada pendaftar PPDB</p>
                </div>
                @endforelse
            </div>
        </div> -->
    <!-- </div> -->

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('pembagian.kelas') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Pembagian Kelas</p>
                    <p class="text-sm text-gray-600">Kelola penempatan siswa</p>
                </div>
            </a>

            <a href="{{ route('admin.ppdb.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="p-2 bg-green-100 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">PPDB</p>
                    <p class="text-sm text-gray-600">Kelola pendaftaran</p>
                </div>
            </a>

            <a href="{{ route('jadwal.admin.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <div class="p-2 bg-purple-100 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Jadwal</p>
                    <p class="text-sm text-gray-600">Kelola jadwal pelajaran</p>
                </div>
            </a>

            <a href="{{ route('admin.system-settings.index') }}" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                <div class="p-2 bg-orange-100 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Pengaturan</p>
                    <p class="text-sm text-gray-600">Konfigurasi sistem</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
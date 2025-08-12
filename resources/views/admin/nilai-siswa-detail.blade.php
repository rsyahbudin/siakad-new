@extends('layouts.dashboard')
@section('title', 'Detail Nilai Siswa')
@section('content')

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-green-800">Berhasil!</h3>
            <div class="mt-2 text-sm text-green-700">
                {{ session('success') }}
            </div>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">Error</h3>
            <div class="mt-2 text-sm text-red-700">
                {{ session('error') }}
            </div>
        </div>
    </div>
</div>
@endif

<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Nilai Siswa</h1>
            <p class="text-gray-600">Rekap lengkap nilai akademik siswa per tahun ajaran dan semester</p>
        </div>

    </div>
</div>

<!-- Student Info Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
    <div class="flex items-center space-x-4">
        <!-- Student Avatar -->
        <div class="flex-shrink-0 w-16 h-16">
            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                <span class="text-xl font-bold text-white">
                    {{ strtoupper(substr($student->user->name ?? 'A', 0, 2)) }}
                </span>
            </div>
        </div>

        <!-- Student Details -->
        <div class="flex-1">
            <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $student->user->name }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="font-medium">NIS:</span>
                    <span class="ml-1">{{ $student->nis }}</span>
                </div>
                @if($kelas)
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="font-medium">Kelas:</span>
                    <span class="ml-1">{{ $kelas->name }}</span>
                </div>
                @endif
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-medium">Status:</span>
                    <span class="ml-1 px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Aktif</span>
                </div>
            </div>
        </div>
    </div>
</div>

@php
$tahunAjaranIds = array_keys($rekap);
$totalSubjects = 0;
$totalPassed = 0;
$totalFailed = 0;
@endphp

@forelse($rekap as $tahunId => $semesters)
@php
// Calculate statistics for this academic year
foreach($semesters as $semester => $mapels) {
foreach($mapels as $mapel => $nilai) {
$totalSubjects++;
if(is_numeric($nilai['kkm']) && $nilai['final_grade'] !== null) {
if($nilai['final_grade'] >= $nilai['kkm']) {
$totalPassed++;
} else {
$totalFailed++;
}
}
}
}
@endphp

<div class="mb-8">
    <!-- Academic Year Header -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-4 rounded-xl mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold mb-1">Tahun Ajaran {{ $tahunAjaranMap[$tahunId] ?? $tahunId }}</h3>
                <p class="text-blue-100">Rekap nilai lengkap per semester</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-blue-100">Total Mapel</div>
                <div class="text-2xl font-bold">{{ $totalSubjects }}</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Lulus</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPassed }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tidak Lulus</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalFailed }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Persentase</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSubjects > 0 ? round(($totalPassed / $totalSubjects) * 100, 1) : 0 }}%</p>
                </div>
            </div>
        </div>
    </div>

    @foreach($semesters as $semester => $mapels)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <!-- Semester Header -->
        <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">Semester {{ $semester }}</h4>
                        <p class="text-sm text-gray-600">{{ count($mapels) }} mata pelajaran</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600">Status</div>
                    <div class="text-sm font-medium text-green-600">Selesai</div>
                </div>
            </div>
        </div>

        <!-- Grades Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Mata Pelajaran
                            </div>
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UTS</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UAS</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sikap</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($mapels as $mapel => $nilai)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $mapel }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm text-gray-900">{{ $nilai['tugas'] !== null ? number_format($nilai['tugas'],2) : '-' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm text-gray-900">{{ $nilai['uts'] !== null ? number_format($nilai['uts'],2) : '-' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm text-gray-900">{{ $nilai['uas'] !== null ? number_format($nilai['uas'],2) : '-' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($nilai['attitude_grade'])
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($nilai['attitude_grade'] === 'Baik') bg-green-100 text-green-800
                                    @elseif($nilai['attitude_grade'] === 'Cukup') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                {{ $nilai['attitude_grade'] }}
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-lg font-bold {{ $nilai['final_grade'] !== null ? 'text-blue-600' : 'text-gray-400' }}">
                                {{ $nilai['final_grade'] !== null ? number_format($nilai['final_grade'],2) : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if(is_numeric($nilai['kkm']))
                            @if($nilai['final_grade'] !== null)
                            @if($nilai['final_grade'] >= $nilai['kkm'])
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Lulus
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Tidak Lulus
                            </span>
                            @endif
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                            @elseif($nilai['kkm'] === 'Belum diatur')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Belum diatur
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>

@empty
<!-- Empty State -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
    </div>
    <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Data Nilai</h3>
    <p class="text-gray-600 mb-6">Siswa ini belum memiliki data nilai yang dapat ditampilkan.</p>
    <div class="flex justify-center space-x-3">
        <a href="{{ route('nilai.admin') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar
        </a>
    </div>
</div>
@endforelse


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Performance optimization: Lazy loading for large tables
        var tables = document.querySelectorAll('table tbody');
        tables.forEach(function(table) {
            if (table.children.length > 20) {
                // Add virtual scrolling for large datasets
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                        }
                    });
                });

                var rows = table.querySelectorAll('tr');
                for (var i = 0; i < rows.length; i++) {
                    if (i > 10) { // Only observe rows after first 10
                        rows[i].style.opacity = '0.8';
                        observer.observe(rows[i]);
                    }
                }
            }
        });

        // Add smooth scrolling for better UX
        var semesterHeaders = document.querySelectorAll('.bg-gradient-to-r.from-gray-50');
        semesterHeaders.forEach(function(header) {
            header.addEventListener('click', function() {
                var table = this.nextElementSibling;
                if (table) {
                    table.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        console.log('Student grade detail view loaded');
    });
</script>
@endsection
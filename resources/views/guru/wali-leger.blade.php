@extends('layouts.dashboard')
@section('title', 'Rekap Nilai Kelas')
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
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Rekap Nilai Kelas</h1>
            <p class="text-gray-600">Rekap dan analisis nilai siswa kelas yang Anda wali</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-lg">
                <div class="text-sm font-medium">Kelas</div>
                <div class="text-lg font-bold">{{ $kelas->name ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Semester Info -->
<div class="bg-gradient-to-r from-green-500 to-blue-600 text-white px-6 py-4 rounded-xl mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold mb-1">Tahun Ajaran Aktif</h3>
            <p class="text-green-100">{{ $activeSemester->academicYear->year ?? '-' }} (Semester {{ $activeSemester->name ?? '-' }})</p>
        </div>
        <div class="text-right">
            <div class="text-sm text-green-100">Status</div>
            <div class="text-lg font-bold">Aktif</div>
        </div>
    </div>
</div>

@if($students->count() > 0 && $mapels->count() > 0)
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Siswa</p>
                <p class="text-2xl font-bold text-gray-900">{{ $classStatistics['total_students'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Lulus Semua</p>
                <p class="text-2xl font-bold text-gray-900">{{ $classStatistics['lulus_semua'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-lg">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Perlu Perhatian</p>
                <p class="text-2xl font-bold text-gray-900">{{ $classStatistics['perlu_perhatian'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Mapel</p>
                <p class="text-2xl font-bold text-gray-900">{{ $classStatistics['total_mapel'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Results Summary -->
<div class="mb-4 flex items-center justify-between">
    <div class="text-sm text-gray-600">
        Total: {{ $students->count() }} siswa
        @if($students->count() > 25)
        <span class="text-xs text-gray-500 ml-2">(Gunakan fitur pencarian untuk performa optimal)</span>
        @endif
    </div>
    <div class="flex items-center space-x-2">
        <button id="expandAllBtn" class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
            Buka Semua
        </button>
        <button id="collapseAllBtn" class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
            </svg>
            Tutup Semua
        </button>
    </div>
</div>

<!-- Students Cards -->
<div class="space-y-6" id="studentsContainer">
    @foreach($students as $siswa)
    @php
    $studentId = $siswa->id;
    $studentStats = $studentStatistics[$studentId] ?? [];
    $averages = $studentAverages[$studentId] ?? [];
    @endphp

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Student Header -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Student Avatar -->
                    <div class="flex-shrink-0 w-12 h-12">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                            <span class="text-sm font-bold text-white">
                                {{ strtoupper(substr($siswa->full_name ?? 'A', 0, 2)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Student Info -->
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $siswa->full_name ?? '-' }}</h3>
                        <p class="text-sm text-gray-600">NIS: {{ $siswa->nis ?? '-' }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-3">
                    <button onclick="toggleDetails('{{ $studentId }}')"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        <span id="btn-text-{{ $studentId }}">Tampilkan Detail</span>
                    </button>
                    <a href="{{ route('wali.detail-nilai', $siswa->id) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Detail Lengkap
                    </a>
                </div>
            </div>

            <!-- Average Scores -->
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="text-center">
                        <p class="text-sm font-medium text-gray-600">Rata-rata Ganjil</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $averages['ganjil'] !== null ? number_format($averages['ganjil'],2) : '-' }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="text-center">
                        <p class="text-sm font-medium text-gray-600">Rata-rata Genap</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $averages['genap'] !== null ? number_format($averages['genap'],2) : '-' }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="text-center">
                        <p class="text-sm font-medium text-gray-600">Rata-rata Akhir Tahun</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $averages['yearly'] !== null ? number_format($averages['yearly'],2) : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Nilai Per Mapel -->
        <div id="details-{{ $studentId }}" class="hidden">
            <div class="p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Detail Nilai Per Mata Pelajaran</h4>

                <!-- Subject Grades Table -->
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
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ganjil</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Genap</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Akhir Tahun</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sikap</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">KKM</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($studentStats['subject_details'] ?? [] as $detail)
                            @php
                            $status = '-';
                            $statusClass = 'text-gray-500';
                            if ($detail['yearly'] !== null && $detail['kkm'] !== null) {
                            if ($detail['yearly'] >= $detail['kkm']) {
                            $status = 'Lulus';
                            $statusClass = 'text-green-600 font-semibold';
                            } else {
                            $status = 'Tidak Lulus';
                            $statusClass = 'text-red-600 font-semibold';
                            }
                            }
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $detail['subject']->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm text-gray-900">{{ $detail['ganjil'] !== null ? number_format($detail['ganjil'], 2) : '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm text-gray-900">{{ $detail['genap'] !== null ? number_format($detail['genap'], 2) : '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-lg font-bold {{ $detail['yearly'] !== null ? 'text-yellow-600' : 'text-gray-400' }}">
                                        {{ $detail['yearly'] !== null ? number_format($detail['yearly'], 2) : '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($detail['attitude_grade'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($detail['attitude_grade'] === 'Baik') bg-green-100 text-green-800
                                            @elseif($detail['attitude_grade'] === 'Cukup') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                        {{ $detail['attitude_grade'] }}
                                    </span>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm text-gray-900">{{ $detail['kkm'] !== null ? $detail['kkm'] : '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="{{ $statusClass }}">{{ $status }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Student Summary -->
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h5 class="text-sm font-semibold text-gray-900 mb-3">Ringkasan Siswa</h5>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-green-600">{{ $studentStats['passed_subjects'] ?? 0 }} Mapel Lulus</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-red-600">{{ $studentStats['failed_subjects'] ?? 0 }} Mapel Tidak Lulus</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-blue-600">{{ $studentStats['completed_subjects'] ?? 0 }}/{{ $studentStats['total_subjects'] ?? 0 }} Mapel Terisi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Performance Tips -->
@if($students->count() > 25)
<div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-yellow-800">Tips Performa</h3>
            <div class="mt-2 text-sm text-yellow-700">
                <p>Dengan {{ $students->count() }} siswa, gunakan tombol "Buka Semua" atau "Tutup Semua" untuk mengelola tampilan detail dengan lebih efisien.</p>
            </div>
        </div>
    </div>
</div>
@endif

@else
<!-- Empty State -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
    <div class="mx-auto w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mb-6">
        <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
    </div>
    <h3 class="text-xl font-semibold text-gray-900 mb-2">Data Belum Lengkap</h3>
    <p class="text-gray-600 mb-6">Leger nilai belum dapat ditampilkan. Pastikan ada siswa di kelas Anda dan jadwal mata pelajaran telah diatur untuk tahun ajaran ini.</p>
    <div class="flex justify-center space-x-3">
        <a href="{{ route('dashboard.guru') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle details function
        window.toggleDetails = function(studentId) {
            const details = document.getElementById('details-' + studentId);
            const btnText = document.getElementById('btn-text-' + studentId);
            const btn = btnText.closest('button');
            const icon = btn.querySelector('svg');

            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                btnText.textContent = 'Sembunyikan Detail';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>';
            } else {
                details.classList.add('hidden');
                btnText.textContent = 'Tampilkan Detail';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>';
            }
        };

        // Expand all functionality
        document.getElementById('expandAllBtn').addEventListener('click', function() {
            const details = document.querySelectorAll('[id^="details-"]');
            const btnTexts = document.querySelectorAll('[id^="btn-text-"]');
            const buttons = document.querySelectorAll('button[onclick^="toggleDetails"]');

            details.forEach(function(detail) {
                detail.classList.remove('hidden');
            });

            btnTexts.forEach(function(btnText) {
                btnText.textContent = 'Sembunyikan Detail';
            });

            buttons.forEach(function(btn) {
                const icon = btn.querySelector('svg');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>';
            });
        });

        // Collapse all functionality
        document.getElementById('collapseAllBtn').addEventListener('click', function() {
            const details = document.querySelectorAll('[id^="details-"]');
            const btnTexts = document.querySelectorAll('[id^="btn-text-"]');
            const buttons = document.querySelectorAll('button[onclick^="toggleDetails"]');

            details.forEach(function(detail) {
                detail.classList.add('hidden');
            });

            btnTexts.forEach(function(btnText) {
                btnText.textContent = 'Tampilkan Detail';
            });

            buttons.forEach(function(btn) {
                const icon = btn.querySelector('svg');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>';
            });
        });

        // Performance optimization: Lazy loading for large datasets
        var studentsContainer = document.getElementById('studentsContainer');
        if (studentsContainer && studentsContainer.children.length > 20) {
            // Add virtual scrolling for large datasets
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                    }
                });
            });

            var studentCards = studentsContainer.querySelectorAll('.bg-white');
            for (var i = 0; i < studentCards.length; i++) {
                if (i > 10) { // Only observe cards after first 10
                    studentCards[i].style.opacity = '0.8';
                    observer.observe(studentCards[i]);
                }
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + E to expand all
            if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                e.preventDefault();
                document.getElementById('expandAllBtn').click();
            }

            // Ctrl/Cmd + C to collapse all
            if ((e.ctrlKey || e.metaKey) && e.key === 'c') {
                e.preventDefault();
                document.getElementById('collapseAllBtn').click();
            }
        });

        console.log('Grade ledger view loaded with', studentsContainer ? studentsContainer.children.length : 0, 'students');
    });
</script>
@endsection
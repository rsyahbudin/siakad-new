@extends('layouts.dashboard')

@section('title', 'Lihat Absensi')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Lihat Absensi</h1>
                <p class="text-gray-600 mt-1">Lihat data absensi siswa per mata pelajaran</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($month)->format('F Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Info Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $schedule->subject->name }}</h2>
                    <div class="flex items-center gap-4 text-sm text-gray-600 mt-1">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span>{{ $schedule->classroom->name }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($schedule->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->time_end)->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" class="flex items-center gap-2">
                    <input type="month" name="month" value="{{ $month }}"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <button type="submit" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if($attendanceByDate->isEmpty())
    <!-- Empty State -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Absensi</h3>
        <p class="text-gray-600 mb-4">Belum ada data absensi untuk bulan yang dipilih.</p>
        <p class="text-sm text-gray-500">Silakan pilih bulan lain atau mulai mengisi absensi.</p>
    </div>
    @else
    <!-- Statistics Cards -->
    @php
    $totalDays = $attendanceByDate->count();
    $totalStudents = $students->count();
    $totalRecords = $attendanceByDate->flatten()->count();
    $statusCounts = $attendanceByDate->flatten()->groupBy('status')->map->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Hari Mengajar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalDays }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Hadir</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['hadir'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Izin</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['izin'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Alpha</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['alpha'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Details -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Detail Absensi per Tanggal</h2>
                    <p class="text-sm text-gray-600">Data absensi siswa berdasarkan tanggal</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="exportToExcel()" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Excel
                    </button>
                    <button type="button" onclick="printAttendance()" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Search and Filter Controls -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Search Box -->
                        <div class="relative">
                            <input type="text" id="searchAttendance" placeholder="Cari nama siswa atau NIS..."
                                class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <!-- Filter by Status -->
                        <select id="filterStatus" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alpha">Alpha</option>
                        </select>

                        <!-- Filter by Date -->
                        <select id="filterDate" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Tanggal</option>
                            @foreach($attendanceByDate->keys() as $date)
                            <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- View Toggle -->
                    <div class="flex items-center gap-1 bg-gray-100 rounded-lg p-1">
                        <button id="gridView" class="px-3 py-1.5 text-sm font-medium rounded-md bg-white shadow-sm text-gray-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </button>
                        <button id="listView" class="px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Grid View (Default) -->
            <div id="gridViewContainer" class="space-y-6">
                @foreach($attendanceByDate as $date => $attendances)
                <div class="attendance-day-group border border-gray-200 rounded-lg" data-date="{{ $date }}">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 rounded-t-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h3>
                                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($date)->format('l') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('teacher.attendance.edit', [$schedule->id, $date]) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="attendance-content p-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
                            @foreach($attendances as $attendance)
                            <div class="attendance-card bg-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow"
                                data-name="{{ strtolower($attendance->student->user->name) }}"
                                data-nis="{{ strtolower($attendance->student->nis) }}"
                                data-status="{{ $attendance->status }}"
                                data-date="{{ $date }}">
                                <div class="p-4">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600">{{ substr($attendance->student->user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $attendance->student->user->name }}</h4>
                                                <p class="text-sm text-gray-600">{{ $attendance->student->nis }}</p>
                                            </div>
                                        </div>
                                        @php
                                        $statusColors = [
                                        'hadir' => 'green',
                                        'izin' => 'yellow',
                                        'sakit' => 'blue',
                                        'alpha' => 'red'
                                        ];
                                        $statusLabels = [
                                        'hadir' => 'Hadir',
                                        'izin' => 'Izin',
                                        'sakit' => 'Sakit',
                                        'alpha' => 'Alpha'
                                        ];
                                        $statusIcons = [
                                        'hadir' => 'M5 13l4 4L19 7',
                                        'izin' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'sakit' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                                        'alpha' => 'M6 18L18 6M6 6l12 12'
                                        ];
                                        @endphp
                                        <span class="inline-flex items-center gap-1 bg-{{ $statusColors[$attendance->status] }}-100 text-{{ $statusColors[$attendance->status] }}-800 px-2 py-1 rounded-full text-xs font-medium">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusIcons[$attendance->status] }}"></path>
                                            </svg>
                                            {{ $statusLabels[$attendance->status] }}
                                        </span>
                                    </div>

                                    <div class="space-y-2">
                                        @if($attendance->notes)
                                        <div class="flex items-start gap-2 text-sm">
                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span class="text-gray-600">{{ $attendance->notes }}</span>
                                        </div>
                                        @endif
                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ \Carbon\Carbon::parse($attendance->attendance_time)->format('H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- List View (Hidden by default) -->
            <div id="listViewContainer" class="hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($attendanceByDate as $date => $attendances)
                            @foreach($attendances as $attendance)
                            <tr class="attendance-row"
                                data-name="{{ strtolower($attendance->student->user->name) }}"
                                data-nis="{{ strtolower($attendance->student->nis) }}"
                                data-status="{{ $attendance->status }}"
                                data-date="{{ $date }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $attendance->student->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendance->student->nis }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    $statusColors = [
                                    'hadir' => 'green',
                                    'izin' => 'yellow',
                                    'sakit' => 'blue',
                                    'alpha' => 'red'
                                    ];
                                    $statusLabels = [
                                    'hadir' => 'Hadir',
                                    'izin' => 'Izin',
                                    'sakit' => 'Sakit',
                                    'alpha' => 'Alpha'
                                    ];
                                    @endphp
                                    <span class="inline-flex items-center gap-1 bg-{{ $statusColors[$attendance->status] }}-100 text-{{ $statusColors[$attendance->status] }}-800 px-2 py-1 rounded-full text-xs font-medium">
                                        {{ $statusLabels[$attendance->status] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($attendance->attendance_time)->format('H:i') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $attendance->notes ?? '-' }}</td>
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- No Results Message -->
            <div id="noResults" class="hidden text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Hasil</h3>
                <p class="text-gray-600">Tidak ada data absensi yang sesuai dengan pencarian Anda.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <a href="{{ route('teacher.attendance.index') }}" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
            @if(!$attendanceByDate->isEmpty())
            <div class="flex items-center gap-3">
                <a href="{{ route('teacher.attendance.take', $schedule->id) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ambil Absensi Baru
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    function exportToExcel() {
        // Implementation for Excel export
        alert('Fitur export Excel akan segera tersedia!');
    }

    function printAttendance() {
        // Implementation for printing
        window.print();
    }

    // Search and filter functionality
    function filterAttendance() {
        const searchTerm = document.getElementById('searchAttendance').value.toLowerCase();
        const filterStatus = document.getElementById('filterStatus').value;
        const filterDate = document.getElementById('filterDate').value;
        const attendanceCards = document.querySelectorAll('.attendance-card');
        const attendanceRows = document.querySelectorAll('.attendance-row');
        const dayGroups = document.querySelectorAll('.attendance-day-group');
        const noResults = document.getElementById('noResults');
        const gridViewContainer = document.getElementById('gridViewContainer');
        const listViewContainer = document.getElementById('listViewContainer');

        let visibleCount = 0;

        // Filter grid view
        attendanceCards.forEach(card => {
            const name = card.dataset.name;
            const nis = card.dataset.nis;
            const status = card.dataset.status;
            const date = card.dataset.date;

            const matchesSearch = name.includes(searchTerm) || nis.includes(searchTerm);
            const matchesStatus = !filterStatus || status === filterStatus;
            const matchesDate = !filterDate || date === filterDate;

            if (matchesSearch && matchesStatus && matchesDate) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Filter list view
        attendanceRows.forEach(row => {
            const name = row.dataset.name;
            const nis = row.dataset.nis;
            const status = row.dataset.status;
            const date = row.dataset.date;

            const matchesSearch = name.includes(searchTerm) || nis.includes(searchTerm);
            const matchesStatus = !filterStatus || status === filterStatus;
            const matchesDate = !filterDate || date === filterDate;

            if (matchesSearch && matchesStatus && matchesDate) {
                row.style.display = 'table-row';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Hide empty day groups
        dayGroups.forEach(group => {
            const visibleCards = group.querySelectorAll('.attendance-card[style="display: block"]').length;
            if (visibleCards === 0) {
                group.style.display = 'none';
            } else {
                group.style.display = 'block';
            }
        });

        // Show/hide no results message
        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
            gridViewContainer.classList.add('hidden');
            listViewContainer.classList.add('hidden');
        } else {
            noResults.classList.add('hidden');
            if (gridViewContainer.classList.contains('hidden') && listViewContainer.classList.contains('hidden')) {
                gridViewContainer.classList.remove('hidden');
            }
        }
    }

    // View toggle functionality
    function toggleView(view) {
        const gridViewContainer = document.getElementById('gridViewContainer');
        const listViewContainer = document.getElementById('listViewContainer');
        const gridViewBtn = document.getElementById('gridView');
        const listViewBtn = document.getElementById('listView');

        if (view === 'grid') {
            gridViewContainer.classList.remove('hidden');
            listViewContainer.classList.add('hidden');
            gridViewBtn.classList.add('bg-white', 'shadow-sm', 'text-gray-900');
            gridViewBtn.classList.remove('text-gray-600');
            listViewBtn.classList.remove('bg-white', 'shadow-sm', 'text-gray-900');
            listViewBtn.classList.add('text-gray-600');
        } else {
            listViewContainer.classList.remove('hidden');
            gridViewContainer.classList.add('hidden');
            listViewBtn.classList.add('bg-white', 'shadow-sm', 'text-gray-900');
            listViewBtn.classList.remove('text-gray-600');
            gridViewBtn.classList.remove('bg-white', 'shadow-sm', 'text-gray-900');
            gridViewBtn.classList.add('text-gray-600');
        }
    }

    // Add event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Search and filter event listeners
        document.getElementById('searchAttendance').addEventListener('input', filterAttendance);
        document.getElementById('filterStatus').addEventListener('change', filterAttendance);
        document.getElementById('filterDate').addEventListener('change', filterAttendance);

        // View toggle event listeners
        document.getElementById('gridView').addEventListener('click', () => toggleView('grid'));
        document.getElementById('listView').addEventListener('click', () => toggleView('list'));

        console.log('Attendance view loaded');
    });
</script>

@endsection
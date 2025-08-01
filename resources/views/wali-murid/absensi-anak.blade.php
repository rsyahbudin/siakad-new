@extends('layouts.dashboard')

@section('title', 'Absensi Anak')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Absensi Anak</h1>
                <p class="text-gray-600 mt-1">Lihat data kehadiran anak Anda di sekolah</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>{{ $student->user->name }}</span>
                    <span class="text-gray-400">•</span>
                    <span>{{ $student->classrooms->first()->name ?? 'Kelas belum ditentukan' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Summary Cards -->
    @php
    $totalAttendance = $semesterStats['total_days'] ?? 0;
    $totalHadir = $semesterStats['hadir'] ?? 0;
    $totalSakit = $semesterStats['sakit'] ?? 0;
    $totalIzin = $semesterStats['izin'] ?? 0;
    $totalAlpha = $semesterStats['alpha'] ?? 0;
    $attendanceRate = $totalAttendance > 0 ? round(($totalHadir / $totalAttendance) * 100, 1) : 0;
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
                    <p class="text-sm font-medium text-gray-600">Kehadiran</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $attendanceRate }}%</p>
                    <p class="text-xs text-gray-500">{{ $totalHadir }} dari {{ $totalAttendance }} hari</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sakit</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSakit }}</p>
                    <p class="text-xs text-gray-500">Hari tidak hadir</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $totalIzin }}</p>
                    <p class="text-xs text-gray-500">Hari izin</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAlpha }}</p>
                    <p class="text-xs text-gray-500">Hari tidak hadir</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6">
        @if($attendance->isEmpty())
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Absensi</h3>
            <p class="text-gray-600">Data absensi untuk semester ini belum tersedia.</p>
        </div>
        @else
        <!-- Detailed Daily Attendance -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Detail Absensi Harian</h2>
                        <p class="text-sm text-gray-600">Riwayat kehadiran anak Anda per hari</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="toggleViewMode()" id="viewToggle" class="inline-flex items-center gap-2 bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            Tabel
                        </button>
                        <button type="button" onclick="exportData()" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                @if($attendanceRecords->isEmpty())
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Absensi Harian</h3>
                    <p class="text-gray-600 mb-4">Data absensi harian untuk periode ini belum tersedia.</p>
                    <div class="text-sm text-gray-500">
                        <p>• Coba pilih bulan/tahun yang berbeda</p>
                        <p>• Pastikan guru sudah mengisi absensi untuk anak Anda</p>
                        <p>• Data absensi hanya tersedia untuk semester aktif</p>
                    </div>
                </div>
                @else
                <!-- Enhanced Filter and Search -->
                <div class="mb-6">
                    <div class="flex flex-col lg:flex-row gap-4">
                        <div class="flex-1">
                            <label for="searchInput" class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" id="searchInput" placeholder="Cari berdasarkan mata pelajaran, guru, atau status..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-32">
                                <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select id="statusFilter" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="">Semua</option>
                                    <option value="hadir">Hadir</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="izin">Izin</option>
                                    <option value="alpha">Alpha</option>
                                </select>
                            </div>

                            <div class="w-32">
                                <label for="dateFilter" class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                                <select id="dateFilter" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="">Semua</option>
                                    <option value="today">Hari Ini</option>
                                    <option value="week">Minggu Ini</option>
                                    <option value="month">Bulan Ini</option>
                                    <option value="semester">Semester Ini</option>
                                </select>
                            </div>

                            <div class="w-32">
                                <label for="sortBy" class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                                <select id="sortBy" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="date_desc">Terbaru</option>
                                    <option value="date_asc">Terlama</option>
                                    <option value="subject">Mata Pelajaran</option>
                                    <option value="status">Status</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Filter Buttons -->
                    <div class="mt-4 flex flex-wrap gap-2">
                        <button type="button" onclick="quickFilter('hadir')" class="inline-flex items-center gap-1 bg-green-100 hover:bg-green-200 text-green-800 px-3 py-1 rounded-full text-xs font-medium transition-colors">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            Hadir
                        </button>
                        <button type="button" onclick="quickFilter('sakit')" class="inline-flex items-center gap-1 bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1 rounded-full text-xs font-medium transition-colors">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            Sakit
                        </button>
                        <button type="button" onclick="quickFilter('izin')" class="inline-flex items-center gap-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium transition-colors">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                            Izin
                        </button>
                        <button type="button" onclick="quickFilter('alpha')" class="inline-flex items-center gap-1 bg-red-100 hover:bg-red-200 text-red-800 px-3 py-1 rounded-full text-xs font-medium transition-colors">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            Alpha
                        </button>
                        <button type="button" onclick="clearFilters()" class="inline-flex items-center gap-1 bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-xs font-medium transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Table View -->
                <div id="tableView" class="overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mata Pelajaran
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Guru
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Waktu
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Catatan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="attendanceTableBody">
                                @foreach($attendanceRecords as $record)
                                <tr class="hover:bg-gray-50 transition-colors attendance-row"
                                    data-subject="{{ strtolower($record->schedule->subject->name) }}"
                                    data-teacher="{{ strtolower($record->teacher->user->name) }}"
                                    data-status="{{ $record->status }}"
                                    data-date="{{ $record->attendance_date }}">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ \Carbon\Carbon::parse($record->attendance_date)->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::parse($record->attendance_date)->format('l') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $record->schedule->subject->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $record->schedule->classroom->name }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-6 h-6">
                                                <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <span class="text-xs font-medium text-gray-600">
                                                        {{ substr($record->teacher->user->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <div class="text-sm font-medium text-gray-900">{{ $record->teacher->user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @php
                                        $statusClasses = [
                                        'hadir' => 'bg-green-100 text-green-800',
                                        'sakit' => 'bg-blue-100 text-blue-800',
                                        'izin' => 'bg-yellow-100 text-yellow-800',
                                        'alpha' => 'bg-red-100 text-red-800'
                                        ];
                                        $statusIcons = [
                                        'hadir' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>',
                                        'sakit' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>',
                                        'izin' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                                        'alpha' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                                        ];
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $statusClasses[$record->status] }}">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                {!! $statusIcons[$record->status] !!}
                                            </svg>
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($record->attendance_time)->format('H:i') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                        @if($record->notes)
                                        <div class="max-w-xs truncate" title="{{ $record->notes }}">
                                            {{ $record->notes }}
                                        </div>
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

                <!-- Card View (Hidden by default) -->
                <div id="cardView" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="attendanceCardContainer">
                        @foreach($attendanceRecords as $record)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow attendance-card"
                            data-subject="{{ strtolower($record->schedule->subject->name) }}"
                            data-teacher="{{ strtolower($record->teacher->user->name) }}"
                            data-status="{{ $record->status }}"
                            data-date="{{ $record->attendance_date }}">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($record->attendance_date)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($record->attendance_date)->format('l') }}
                                        </div>
                                    </div>
                                </div>
                                @php
                                $statusClasses = [
                                'hadir' => 'bg-green-100 text-green-800',
                                'sakit' => 'bg-blue-100 text-blue-800',
                                'izin' => 'bg-yellow-100 text-yellow-800',
                                'alpha' => 'bg-red-100 text-red-800'
                                ];
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $statusClasses[$record->status] }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </div>

                            <div class="space-y-2">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $record->schedule->subject->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $record->schedule->classroom->name }}</div>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-5 h-5 bg-gray-200 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-600">
                                            {{ substr($record->teacher->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="ml-2">
                                        <div class="text-xs font-medium text-gray-900">{{ $record->teacher->user->name }}</div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>{{ \Carbon\Carbon::parse($record->attendance_time)->format('H:i') }}</span>
                                    @if($record->notes)
                                    <span class="truncate max-w-32" title="{{ $record->notes }}">{{ $record->notes }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                @if($attendanceRecords->hasPages())
                <div class="mt-6">
                    {{ $attendanceRecords->links() }}
                </div>
                @endif
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
</div>


<script>
    // Global variables
    let currentView = 'table'; // 'table' or 'card'

    function exportData() {
        try {
            // Create CSV content
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Tanggal,Mata Pelajaran,Guru,Status,Waktu,Catatan\n";

            // Get visible rows based on current view
            let visibleElements;
            if (currentView === 'table') {
                visibleElements = document.querySelectorAll('.attendance-row:not([style*="display: none"])');
            } else {
                visibleElements = document.querySelectorAll('.attendance-card:not([style*="display: none"])');
            }

            if (visibleElements.length === 0) {
                alert('Tidak ada data untuk di-export!');
                return;
            }

            visibleElements.forEach(element => {
                if (currentView === 'table') {
                    // Handle table rows
                    const cells = element.querySelectorAll('td');
                    if (cells.length >= 6) {
                        const date = cells[0].textContent.trim();
                        const subject = cells[1].textContent.trim();
                        const teacher = cells[2].textContent.trim();
                        const status = cells[3].textContent.trim();
                        const time = cells[4].textContent.trim();
                        const notes = cells[5].textContent.trim();

                        csvContent += `"${date}","${subject}","${teacher}","${status}","${time}","${notes}"\n`;
                    }
                } else {
                    // Handle card elements
                    const date = element.querySelector('[data-date]')?.getAttribute('data-date') || '';
                    const subject = element.querySelector('.text-sm.font-medium')?.textContent.trim() || '';
                    const teacher = element.querySelector('.text-xs.font-medium')?.textContent.trim() || '';
                    const status = element.querySelector('.inline-flex')?.textContent.trim() || '';
                    const time = element.querySelector('.text-xs.text-gray-500 span')?.textContent.trim() || '';
                    const notes = element.querySelector('.truncate')?.textContent.trim() || '';

                    csvContent += `"${date}","${subject}","${teacher}","${status}","${time}","${notes}"\n`;
                }
            });

            // Create download link
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "absensi_anak.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            console.log('Export successful');
        } catch (error) {
            console.error('Export error:', error);
            alert('Terjadi kesalahan saat export data!');
        }
    }

    function toggleViewMode() {
        try {
            const tableView = document.getElementById('tableView');
            const cardView = document.getElementById('cardView');
            const viewToggle = document.getElementById('viewToggle');

            if (!tableView || !cardView || !viewToggle) {
                console.error('Required elements not found');
                return;
            }

            if (currentView === 'table') {
                // Switch to card view
                tableView.classList.add('hidden');
                cardView.classList.remove('hidden');
                viewToggle.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>Kartu';
                viewToggle.className = 'inline-flex items-center gap-2 bg-purple-100 hover:bg-purple-200 text-purple-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors';
                currentView = 'card';
            } else {
                // Switch to table view
                cardView.classList.add('hidden');
                tableView.classList.remove('hidden');
                viewToggle.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>Tabel';
                viewToggle.className = 'inline-flex items-center gap-2 bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors';
                currentView = 'table';
            }

            console.log('View toggled to:', currentView);
        } catch (error) {
            console.error('Toggle view error:', error);
        }
    }

    function quickFilter(status) {
        try {
            const statusFilter = document.getElementById('statusFilter');
            const dateFilter = document.getElementById('dateFilter');
            const searchInput = document.getElementById('searchInput');

            if (!statusFilter || !dateFilter || !searchInput) {
                console.error('Filter elements not found');
                return;
            }

            // Clear other filters
            dateFilter.value = '';
            searchInput.value = '';

            // Set status filter
            statusFilter.value = status;

            // Apply filter
            filterData();

            console.log('Quick filter applied:', status);
        } catch (error) {
            console.error('Quick filter error:', error);
        }
    }

    function clearFilters() {
        try {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const dateFilter = document.getElementById('dateFilter');
            const sortBy = document.getElementById('sortBy');

            if (!searchInput || !statusFilter || !dateFilter || !sortBy) {
                console.error('Filter elements not found');
                return;
            }

            searchInput.value = '';
            statusFilter.value = '';
            dateFilter.value = '';
            sortBy.value = 'date_desc';

            filterData();

            console.log('Filters cleared');
        } catch (error) {
            console.error('Clear filters error:', error);
        }
    }

    function filterData() {
        try {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const dateFilter = document.getElementById('dateFilter');

            if (!searchInput || !statusFilter || !dateFilter) {
                console.error('Filter elements not found');
                return;
            }

            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;
            const dateValue = dateFilter.value;

            // Get current date for filtering
            const today = new Date();
            const todayStr = today.toISOString().split('T')[0];
            const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
            const monthAgo = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

            // Get all rows and cards
            const attendanceRows = document.querySelectorAll('.attendance-row');
            const attendanceCards = document.querySelectorAll('.attendance-card');

            let visibleCount = 0;

            // Filter table rows
            attendanceRows.forEach(row => {
                const subject = row.getAttribute('data-subject') || '';
                const teacher = row.getAttribute('data-teacher') || '';
                const status = row.getAttribute('data-status') || '';
                const date = row.getAttribute('data-date') || '';

                let showRow = true;

                // Search filter
                if (searchTerm) {
                    const searchText = `${subject} ${teacher} ${status}`.toLowerCase();
                    if (!searchText.includes(searchTerm)) {
                        showRow = false;
                    }
                }

                // Status filter
                if (statusValue && status !== statusValue) {
                    showRow = false;
                }

                // Date filter
                if (dateValue && date) {
                    switch (dateValue) {
                        case 'today':
                            if (date !== todayStr) showRow = false;
                            break;
                        case 'week':
                            if (date < weekAgo) showRow = false;
                            break;
                        case 'month':
                            if (date < monthAgo) showRow = false;
                            break;
                        case 'semester':
                            break;
                    }
                }

                if (showRow) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Filter card views
            attendanceCards.forEach(card => {
                const subject = card.getAttribute('data-subject') || '';
                const teacher = card.getAttribute('data-teacher') || '';
                const status = card.getAttribute('data-status') || '';
                const date = card.getAttribute('data-date') || '';

                let showCard = true;

                // Search filter
                if (searchTerm) {
                    const searchText = `${subject} ${teacher} ${status}`.toLowerCase();
                    if (!searchText.includes(searchTerm)) {
                        showCard = false;
                    }
                }

                // Status filter
                if (statusValue && status !== statusValue) {
                    showCard = false;
                }

                // Date filter
                if (dateValue && date) {
                    switch (dateValue) {
                        case 'today':
                            if (date !== todayStr) showCard = false;
                            break;
                        case 'week':
                            if (date < weekAgo) showCard = false;
                            break;
                        case 'month':
                            if (date < monthAgo) showCard = false;
                            break;
                        case 'semester':
                            break;
                    }
                }

                if (showCard) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide no results message
            const noResultsMsg = document.getElementById('noResultsMessage');
            if (visibleCount === 0) {
                if (!noResultsMsg) {
                    const msg = document.createElement('div');
                    msg.id = 'noResultsMessage';
                    msg.className = 'text-center py-8 text-gray-500';
                    msg.innerHTML = '<p>Tidak ada data yang sesuai dengan filter yang dipilih.</p>';

                    const tableView = document.getElementById('tableView');
                    const cardView = document.getElementById('cardView');

                    if (!tableView.classList.contains('hidden')) {
                        tableView.appendChild(msg);
                    } else {
                        cardView.appendChild(msg);
                    }
                }
            } else {
                if (noResultsMsg) {
                    noResultsMsg.remove();
                }
            }

            console.log('Filter applied, visible count:', visibleCount);
        } catch (error) {
            console.error('Filter data error:', error);
        }
    }

    function sortData() {
        try {
            const sortBy = document.getElementById('sortBy');
            if (!sortBy) {
                console.error('Sort element not found');
                return;
            }

            const sortValue = sortBy.value;
            const tableView = document.getElementById('tableView');
            const cardView = document.getElementById('cardView');

            if (currentView === 'card') {
                // Sort cards
                const cardContainer = document.getElementById('attendanceCardContainer');
                if (!cardContainer) {
                    console.error('Card container not found');
                    return;
                }

                const cards = Array.from(cardContainer.children);

                cards.sort((a, b) => {
                    switch (sortValue) {
                        case 'date_desc':
                            return new Date(b.getAttribute('data-date')) - new Date(a.getAttribute('data-date'));
                        case 'date_asc':
                            return new Date(a.getAttribute('data-date')) - new Date(b.getAttribute('data-date'));
                        case 'subject':
                            return a.getAttribute('data-subject').localeCompare(b.getAttribute('data-subject'));
                        case 'status':
                            return a.getAttribute('data-status').localeCompare(b.getAttribute('data-status'));
                        default:
                            return 0;
                    }
                });

                cards.forEach(card => cardContainer.appendChild(card));
            } else {
                // Sort table rows
                const tbody = document.getElementById('attendanceTableBody');
                if (!tbody) {
                    console.error('Table body not found');
                    return;
                }

                const rows = Array.from(tbody.children);

                rows.sort((a, b) => {
                    switch (sortValue) {
                        case 'date_desc':
                            return new Date(b.getAttribute('data-date')) - new Date(a.getAttribute('data-date'));
                        case 'date_asc':
                            return new Date(a.getAttribute('data-date')) - new Date(b.getAttribute('data-date'));
                        case 'subject':
                            return a.getAttribute('data-subject').localeCompare(b.getAttribute('data-subject'));
                        case 'status':
                            return a.getAttribute('data-status').localeCompare(b.getAttribute('data-status'));
                        default:
                            return 0;
                    }
                });

                rows.forEach(row => tbody.appendChild(row));
            }

            console.log('Data sorted by:', sortValue);
        } catch (error) {
            console.error('Sort data error:', error);
        }
    }

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        try {
            console.log('Initializing attendance page...');

            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const dateFilter = document.getElementById('dateFilter');
            const sortBy = document.getElementById('sortBy');

            if (!searchInput || !statusFilter || !dateFilter || !sortBy) {
                console.error('Required elements not found on page load');
                return;
            }

            // Add event listeners
            searchInput.addEventListener('input', filterData);
            statusFilter.addEventListener('change', filterData);
            dateFilter.addEventListener('change', filterData);
            sortBy.addEventListener('change', sortData);

            // Initial filter
            filterData();

            console.log('Attendance page initialized successfully');
        } catch (error) {
            console.error('Page initialization error:', error);
        }
    });
</script>

@endsection
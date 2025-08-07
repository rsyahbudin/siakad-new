@extends('layouts.dashboard')

@section('title', 'Jadwal Ujian')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900">Jadwal Ujian Saya</h1>
                        </div>
                        <p class="text-gray-600 text-lg">Kelola dan lihat jadwal ujian UTS dan UAS Anda</p>
                        
                        @if($activeSemester && $activeAcademicYear)
                        <div class="mt-4 inline-flex items-center gap-2 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-blue-900">
                                Semester: <strong>{{ $activeSemester->name }} - {{ $activeAcademicYear->year }}</strong>
                            </span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-900">{{ $examSchedules->flatten()->count() }}</div>
                            <div class="text-sm text-gray-500">Total Ujian</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 rounded-lg p-4">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <span class="text-red-800 font-medium">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Ujian</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $examSchedules->total() }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Jadwal UTS</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $totalUts }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Jadwal UAS</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalUas }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search Section -->
        @if($examSchedules->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <h3 class="text-lg font-semibold text-gray-900">Filter & Pencarian</h3>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Cari mata pelajaran..." 
                               class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <!-- Filter by Exam Type -->
                    <select id="examTypeFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Jenis Ujian</option>
                        <option value="uts">UTS</option>
                        <option value="uas">UAS</option>
                    </select>

                    <!-- Filter by Status -->
                    <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="upcoming">Akan Datang</option>
                        <option value="tomorrow">Besok</option>
                        <option value="ongoing">Sedang Berlangsung</option>
                        <option value="finished">Selesai</option>
                    </select>

                    <!-- Sort -->
                    <select id="sortBy" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="date_asc">Tanggal (Terlama)</option>
                        <option value="date_desc">Tanggal (Terbaru)</option>
                        <option value="subject_asc">Mata Pelajaran (A-Z)</option>
                        <option value="subject_desc">Mata Pelajaran (Z-A)</option>
                    </select>
                </div>
            </div>
        </div>
        @endif

        <!-- Important Guidelines -->
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-6 mb-8">
            <div class="flex items-start gap-4">
                <div class="p-2 bg-yellow-100 rounded-lg flex-shrink-0">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-yellow-900 mb-3">Panduan Penting Ujian</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex items-center gap-2 text-yellow-800">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm">Datang 30 menit sebelum ujian</span>
                        </div>
                        <div class="flex items-center gap-2 text-yellow-800">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            <span class="text-sm">Bawa alat tulis yang diperlukan</span>
                        </div>
                        <div class="flex items-center gap-2 text-yellow-800">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span class="text-sm">Jangan bawa HP atau alat elektronik</span>
                        </div>
                        <div class="flex items-center gap-2 text-yellow-800">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm">Ikuti instruksi pengawas dengan baik</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exam Schedule Table -->
        @if($examSchedules->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Daftar Jadwal Ujian</h2>
                    </div>
                    <div class="text-sm text-gray-500">
                        <span id="resultCount">{{ $examSchedules->count() }}</span> dari {{ $examSchedules->total() }} jadwal
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="examTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <button class="flex items-center gap-2 hover:text-gray-700" onclick="sortTable('subject')">
                                    Mata Pelajaran
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                </button>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jenis Ujian
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <button class="flex items-center gap-2 hover:text-gray-700" onclick="sortTable('date')">
                                    Tanggal & Waktu
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                </button>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ruangan
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="examTableBody">
                        @foreach($examSchedules as $schedule)
                        <tr class="hover:bg-gray-50 transition-colors exam-row" 
                            data-subject="{{ strtolower($schedule->subject->name) }}"
                            data-exam-type="{{ $schedule->exam_type }}"
                            data-date="{{ $schedule->exam_date->format('Y-m-d') }}"
                            data-status="{{ $schedule->getStatusAttribute() }}">
                            
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex-1">
                                        <div class="text-sm font-semibold text-gray-900 mb-1">{{ $schedule->subject->name }}</div>
                                        @if($schedule->is_general_subject)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            Mata Pelajaran Umum
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            {{ $schedule->major->name ?? 'Jurusan Spesifik' }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($schedule->exam_type === 'uts')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    UTS
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    UAS
                                </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 text-sm font-medium text-gray-900">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $schedule->exam_date->format('d/m/Y') }}
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Durasi: {{ $schedule->start_time->diffInMinutes($schedule->end_time) }} menit
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $schedule->classroom->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $schedule->classroom->grade }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                $now = now();
                                $examDateTime = \Carbon\Carbon::parse($schedule->exam_date->format('Y-m-d') . ' ' . $schedule->end_time->format('H:i:s'));
                                $startDateTime = \Carbon\Carbon::parse($schedule->exam_date->format('Y-m-d') . ' ' . $schedule->start_time->format('H:i:s'));
                                @endphp

                                @if($now->gt($examDateTime))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Selesai
                                </span>
                                @elseif($now->between($startDateTime, $examDateTime))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 animate-pulse">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                    Sedang Berlangsung
                                </span>
                                @elseif($now->diffInDays($startDateTime) <= 1)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Besok
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Akan Datang
                                    </span>
                                    @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $examSchedules->links() }}
            </div>
        </div>
        @endif

        <!-- Empty State -->
        @if($examSchedules->count() == 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="flex flex-col items-center">
                <div class="p-4 bg-gray-100 rounded-full mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Jadwal Ujian</h3>
                <p class="text-gray-500 max-w-md">
                    Jadwal ujian akan ditampilkan di sini ketika tersedia untuk semester aktif. 
                    Silakan hubungi admin jika ada pertanyaan.
                </p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- JavaScript for Filter and Sort -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const examTypeFilter = document.getElementById('examTypeFilter');
    const statusFilter = document.getElementById('statusFilter');
    const sortBy = document.getElementById('sortBy');
    const examRows = document.querySelectorAll('.exam-row');
    const resultCount = document.getElementById('resultCount');

    // Filter and search functionality
    function filterAndSearch() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const examTypeValue = examTypeFilter?.value || '';
        const statusValue = statusFilter?.value || '';
        
        let visibleCount = 0;

        examRows.forEach(row => {
            const subject = row.getAttribute('data-subject') || '';
            const examType = row.getAttribute('data-exam-type') || '';
            const status = getRowStatus(row);

            const matchesSearch = subject.includes(searchTerm);
            const matchesExamType = !examTypeValue || examType === examTypeValue;
            const matchesStatus = !statusValue || status === statusValue;

            if (matchesSearch && matchesExamType && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (resultCount) {
            resultCount.textContent = visibleCount;
        }
    }

    // Get status from row
    function getRowStatus(row) {
        const statusElement = row.querySelector('td:last-child span');
        if (!statusElement) return '';
        
        const statusText = statusElement.textContent.trim();
        
        if (statusText.includes('Selesai')) return 'finished';
        if (statusText.includes('Sedang Berlangsung')) return 'ongoing';
        if (statusText.includes('Besok')) return 'tomorrow';
        if (statusText.includes('Akan Datang')) return 'upcoming';
        
        return '';
    }

    // Sort functionality
    function sortTable() {
        const sortValue = sortBy?.value || 'date_asc';
        const tbody = document.getElementById('examTableBody');
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('.exam-row'));
        
        rows.sort((a, b) => {
            switch (sortValue) {
                case 'date_asc':
                    return new Date(a.getAttribute('data-date')) - new Date(b.getAttribute('data-date'));
                case 'date_desc':
                    return new Date(b.getAttribute('data-date')) - new Date(a.getAttribute('data-date'));
                case 'subject_asc':
                    return a.getAttribute('data-subject').localeCompare(b.getAttribute('data-subject'));
                case 'subject_desc':
                    return b.getAttribute('data-subject').localeCompare(a.getAttribute('data-subject'));
                default:
                    return 0;
            }
        });

        // Reappend sorted rows
        rows.forEach(row => tbody.appendChild(row));
        
        // Reapply filters
        filterAndSearch();
    }

    // Event listeners
    if (searchInput) {
        searchInput.addEventListener('input', filterAndSearch);
    }
    
    if (examTypeFilter) {
        examTypeFilter.addEventListener('change', filterAndSearch);
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', filterAndSearch);
    }
    
    if (sortBy) {
        sortBy.addEventListener('change', sortTable);
    }

    // Initial sort
    sortTable();
});

// Table header sort functionality
function sortTable(column) {
    const sortBy = document.getElementById('sortBy');
    if (!sortBy) return;

    if (column === 'subject') {
        sortBy.value = sortBy.value === 'subject_asc' ? 'subject_desc' : 'subject_asc';
    } else if (column === 'date') {
        sortBy.value = sortBy.value === 'date_asc' ? 'date_desc' : 'date_asc';
    }
    
    sortBy.dispatchEvent(new Event('change'));
}
</script>
@endsection
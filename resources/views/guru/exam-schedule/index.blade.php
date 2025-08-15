@extends('layouts.dashboard')

@section('title', 'Jadwal Pengawasan Ujian')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-1">
                    <h1 class="text-3xl font-bold text-gray-900">Jadwal Pengawasan Ujian</h1>
                    <p class="text-gray-600">Kelola dan pantau jadwal pengawasan ujian UTS dan UAS</p>
                    @if($activeSemester && $activeAcademicYear)
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span class="text-gray-700">
                            <span class="font-medium">{{ $activeSemester->name }}</span> -
                            <span class="font-medium">{{ $activeAcademicYear->year }}</span>
                        </span>
                    </div>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-blue-600 uppercase tracking-wide">Total Jadwal</p>
                                <p class="text-xl font-bold text-blue-900">{{ $examSchedules->total() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <span class="text-red-800 font-medium">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Pengawasan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Pengawasan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $examSchedules->total() }}</p>
                        <p class="text-xs text-gray-400 mt-1">Semua jadwal</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- UTS -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Pengawasan UTS</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalUts }}</p>
                        <p class="text-xs text-yellow-600 mt-1">Ujian Tengah Semester</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- UAS -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Pengawasan UAS</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalUas }}</p>
                        <p class="text-xs text-green-600 mt-1">Ujian Akhir Semester</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Kelas Berbeda -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Kelas Berbeda</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $examSchedules->unique('classroom_id')->count() }}</p>
                        <p class="text-xs text-purple-600 mt-1">Ruang kelas</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guidelines Section -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-3">Panduan Pengawasan Ujian</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-blue-800">
                                <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                                <span class="text-sm">Datang 30 menit sebelum ujian dimulai</span>
                            </div>
                            <div class="flex items-center gap-2 text-blue-800">
                                <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                                <span class="text-sm">Periksa kelengkapan alat tulis siswa</span>
                            </div>
                            <div class="flex items-center gap-2 text-blue-800">
                                <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                                <span class="text-sm">Pastikan tidak ada HP atau alat elektronik</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-blue-800">
                                <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                                <span class="text-sm">Awasi dengan ketat selama ujian berlangsung</span>
                            </div>
                            <div class="flex items-center gap-2 text-blue-800">
                                <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                                <span class="text-sm">Catat kejadian penting dalam berita acara</span>
                            </div>
                            <div class="flex items-center gap-2 text-blue-800">
                                <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                                <span class="text-sm">Kumpulkan lembar jawaban tepat waktu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search Section -->
        @if($examSchedules->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Mata Pelajaran</label>
                        <div class="relative">
                            <input type="text" id="searchSubject"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ketik nama mata pelajaran...">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Filter by Exam Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Ujian</label>
                        <select id="filterExamType" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua</option>
                            <option value="UTS">UTS</option>
                            <option value="UAS">UAS</option>
                        </select>
                    </div>

                    <!-- Filter by Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="filterStatus" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="upcoming">Belum Dilaksanakan</option>
                            <option value="tomorrow">Besok</option>
                            <option value="ongoing">Sedang Dilaksanakan</option>
                            <option value="finished">Telah Dilaksanakan</option>
                        </select>
                    </div>
                </div>

                <!-- Sort and Reset -->
                <div class="flex items-end gap-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                        <select id="sortBy" class="px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="date_asc">Tanggal (Terlama)</option>
                            <option value="date_desc">Tanggal (Terbaru)</option>
                            <option value="subject_asc">Mata Pelajaran (A-Z)</option>
                            <option value="subject_desc">Mata Pelajaran (Z-A)</option>
                        </select>
                    </div>
                    <button id="resetFilter" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Exam Schedule Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Jadwal Pengawasan</h2>
                            <p class="text-sm text-gray-500">Daftar lengkap jadwal pengawasan ujian</p>
                        </div>
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
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal & Waktu</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ruangan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100" id="examTableBody">
                        @foreach($examSchedules as $schedule)
                        <tr class="hover:bg-gray-50 transition-colors exam-row"
                            data-subject="{{ strtolower($schedule->subject->name) }}"
                            data-exam-type="{{ $schedule->exam_type }}"
                            data-date="{{ $schedule->exam_date->format('Y-m-d') }}"
                            data-start-time="{{ $schedule->start_time->format('H:i') }}">

                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm font-semibold text-gray-900 truncate">{{ $schedule->subject->name }}</div>
                                        <div class="mt-1">
                                            @if($schedule->is_general_subject)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-700 border border-purple-200">
                                                Mata Pelajaran Umum
                                            </span>
                                            @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-orange-100 text-orange-700 border border-orange-200">
                                                {{ $schedule->major->name ?? 'Jurusan Spesifik' }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @if(strtolower($schedule->exam_type) === 'uts')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    UTS
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    UAS
                                </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $schedule->classroom->name }}</div>
                                <div class="text-sm text-gray-500">{{ $schedule->classroom->grade }}</div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="text-sm font-semibold text-gray-900">{{ $schedule->exam_date->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-600">
                                        {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $schedule->start_time->diffInMinutes($schedule->end_time) }} menit
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $schedule->classroom->name }}</div>
                                <div class="text-xs text-gray-500">
                                    Kapasitas: {{ $schedule->classroom->capacity ?? 'N/A' }} siswa
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @php
                                $now = now();
                                $examDateTime = \Carbon\Carbon::parse($schedule->exam_date->format('Y-m-d') . ' ' . $schedule->end_time->format('H:i:s'));
                                $startDateTime = \Carbon\Carbon::parse($schedule->exam_date->format('Y-m-d') . ' ' . $schedule->start_time->format('H:i:s'));
                                $status = '';
                                @endphp

                                @if($now->gt($examDateTime))
                                @php $status = 'finished'; @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200" data-status="finished">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Telah Dilaksanakan
                                </span>
                                @elseif($now->between($startDateTime, $examDateTime))
                                @php $status = 'ongoing'; @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200 animate-pulse" data-status="ongoing">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-ping"></div>
                                    Sedang Dilaksanakan
                                </span>
                                @elseif($now->diffInDays($startDateTime) <= 1)
                                    @php $status='tomorrow' ; @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200" data-status="tomorrow">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Besok
                                    </span>
                                    @else
                                    @php $status = 'upcoming'; @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200" data-status="upcoming">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Belum Dilaksanakan
                                    </span>
                                    @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $examSchedules->links() }}
            </div>
        </div>
        @endif

        <!-- Empty State -->
        @if($examSchedules->count() == 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center">
            <div class="flex flex-col items-center max-w-md mx-auto">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Jadwal</h3>
                <p class="text-gray-500 text-center leading-relaxed">
                    Jadwal pengawasan ujian belum tersedia untuk semester aktif.
                    Silakan hubungi administrator untuk informasi lebih lanjut.
                </p>
            </div>
        </div>
        @endif

        <!-- No Results State (Hidden by default, shown when filter returns no results) -->
        <div id="noResults" class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center hidden">
            <div class="flex flex-col items-center max-w-md mx-auto">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak Ada Hasil</h3>
                <p class="text-gray-500 text-center leading-relaxed">
                    Tidak ditemukan jadwal yang sesuai dengan filter yang dipilih.
                    Coba ubah kriteria pencarian atau reset filter.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchSubject');
        const examTypeFilter = document.getElementById('filterExamType');
        const statusFilter = document.getElementById('filterStatus');
        const sortSelect = document.getElementById('sortBy');
        const resetButton = document.getElementById('resetFilter');
        const examRows = document.querySelectorAll('.exam-row');
        const resultCount = document.getElementById('resultCount');
        const examTable = document.getElementById('examTable');
        const noResults = document.getElementById('noResults');

        function filterAndSortTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const examTypeValue = examTypeFilter.value;
            const statusValue = statusFilter.value;
            const sortValue = sortSelect.value;

            let visibleRows = [];
            let filteredRows = Array.from(examRows);

            // Apply filters
            filteredRows = filteredRows.filter(row => {
                const subject = row.dataset.subject;
                const examType = row.dataset.examType;
                const statusSpan = row.querySelector('[data-status]');
                const status = statusSpan ? statusSpan.dataset.status : '';

                const matchesSearch = subject.includes(searchTerm);
                const matchesExamType = !examTypeValue || examType === examTypeValue;
                const matchesStatus = !statusValue || status === statusValue;

                return matchesSearch && matchesExamType && matchesStatus;
            });

            // Apply sorting
            filteredRows.sort((a, b) => {
                switch (sortValue) {
                    case 'date_asc':
                        return new Date(a.dataset.date + ' ' + a.dataset.startTime) -
                            new Date(b.dataset.date + ' ' + b.dataset.startTime);
                    case 'date_desc':
                        return new Date(b.dataset.date + ' ' + b.dataset.startTime) -
                            new Date(a.dataset.date + ' ' + a.dataset.startTime);
                    case 'subject_asc':
                        return a.dataset.subject.localeCompare(b.dataset.subject);
                    case 'subject_desc':
                        return b.dataset.subject.localeCompare(a.dataset.subject);
                    default:
                        return 0;
                }
            });

            // Hide all rows first
            examRows.forEach(row => row.style.display = 'none');

            // Show filtered and sorted rows
            if (filteredRows.length > 0) {
                const tbody = document.getElementById('examTableBody');
                filteredRows.forEach(row => {
                    row.style.display = 'table-row';
                    tbody.appendChild(row); // Re-append to maintain sort order
                });

                examTable.style.display = 'table';
                noResults.classList.add('hidden');
                resultCount.textContent = filteredRows.length;
            } else {
                examTable.style.display = 'none';
                noResults.classList.remove('hidden');
                resultCount.textContent = '0';
            }
        }

        // Event listeners
        searchInput.addEventListener('input', debounce(filterAndSortTable, 300));
        examTypeFilter.addEventListener('change', filterAndSortTable);
        statusFilter.addEventListener('change', filterAndSortTable);
        sortSelect.addEventListener('change', filterAndSortTable);

        resetButton.addEventListener('click', function() {
            searchInput.value = '';
            examTypeFilter.value = '';
            statusFilter.value = '';
            sortSelect.value = 'date_asc';
            filterAndSortTable();
        });

        // Debounce function for search input
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Initial sort
        filterAndSortTable();

        // Auto-refresh status for ongoing exams every minute
        setInterval(() => {
            const ongoingSpans = document.querySelectorAll('[data-status="ongoing"]');
            const now = new Date();

            ongoingSpans.forEach(span => {
                const row = span.closest('.exam-row');
                const examDate = row.dataset.date;
                const endTime = row.querySelector('td:nth-child(4) .text-gray-600').textContent.split(' - ')[1];
                const examEndDateTime = new Date(examDate + ' ' + endTime);

                if (now > examEndDateTime) {
                    // Update to finished status
                    span.setAttribute('data-status', 'finished');
                    span.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200';
                    span.innerHTML = `
                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Selesai
                `;
                }
            });
        }, 60000); // Check every minute
    });
</script>

<style>
    /* Custom scrollbar for table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Smooth transitions */
    .exam-row {
        transition: all 0.2s ease-in-out;
    }

    /* Loading animation for search */
    #searchSubject:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Enhanced hover effects */
    .hover\:shadow-md:hover {
        transform: translateY(-1px);
    }
</style>

@endsection
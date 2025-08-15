@extends('layouts.dashboard')
@section('title', 'Jadwal Mengajar')
@section('content')

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
<div class="space-y-4">
    <div class="mb-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Jadwal Mengajar</h1>
                <p class="text-gray-600">Kelola dan lihat jadwal mengajar Anda</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-lg">
                    <div class="text-sm font-medium">Tahun Ajaran Aktif</div>
                    <div class="text-lg font-bold">{{ $activeYear->year ?? '-' }} Semester {{ $activeSemester->name ?? '-' }}</div>
                </div>
                <a href="{{ route('jadwal.guru.download-pdf', request()->query()) }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Filter Jadwal</h3>
            <a href="{{ route('jadwal.guru') }}" class="text-sm text-blue-600 hover:text-blue-800">Reset Filter</a>
        </div>

        <form method="GET" action="{{ route('jadwal.guru') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4" id="filterForm">
            <!-- Day Filter -->
            <div>
                <label for="day" class="block text-sm font-medium text-gray-700 mb-2">Hari</label>
                <select name="day" id="day" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Hari</option>
                    @foreach($availableDays as $day)
                    <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Classroom Filter -->
            <div>
                <label for="classroom" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select name="classroom" id="classroom" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Kelas</option>
                    @foreach($availableClassrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ request('classroom') == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Subject Filter -->
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                <select name="subject" id="subject" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($availableSubjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Jadwal</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSchedules }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Kelas Diajar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalClassrooms }}</p>
                </div>
            </div>
        </div>

        <!-- <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Mata Pelajaran</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSubjects }}</p>
                </div>
            </div>
        </div> -->

        <!-- <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Jam Mengajar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSchedules * 2 }}</p>
                </div>
            </div>
        </div> -->
    </div>

    @if($schedules->isEmpty())
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="mx-auto w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Jadwal Mengajar</h3>
        <p class="text-gray-600 mb-6">Jadwal mengajar Anda akan muncul di sini setelah admin mengatur jadwal untuk Anda.</p>
        <div class="flex justify-center space-x-3">
            <a href="{{ route('dashboard.guru') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
    @else
    <!-- Results Summary -->
    <div class="mb-4 flex items-center justify-between">
        <div class="text-sm text-gray-600">
            Menampilkan {{ $schedules->firstItem() ?? 0 }} - {{ $schedules->lastItem() ?? 0 }} dari {{ $schedules->total() }} jadwal
            @if($schedules->total() > 25)
            <span class="text-xs text-gray-500 ml-2">(25 per halaman untuk performa optimal)</span>
            @endif
        </div>
        <div class="text-sm text-gray-600">
            Halaman {{ $schedules->currentPage() }} dari {{ $schedules->lastPage() }}
        </div>
    </div>
    <!-- Schedule Content -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Jadwal Mengajar Mingguan</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">Total: {{ $schedules->count() }} jadwal</span>
                </div>
            </div>
        </div>

        <!-- Schedule Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Hari
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Jam
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Kelas
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Mata Pelajaran
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Durasi
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($schedules->sortBy(['day', 'time_start']) as $jadwal)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-sm font-semibold text-blue-600">
                                        {{ substr($jadwal->day, 0, 3) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $jadwal->day }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">
                                {{ \Carbon\Carbon::parse($jadwal->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->time_end)->format('H:i') }}
                            </div>
                            <!-- <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($jadwal->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->time_end)->format('H:i') }}
                            </div> -->
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $jadwal->classroom->name ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $jadwal->subject->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $jadwal->subject->code ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @php
                                $start = \Carbon\Carbon::parse($jadwal->time_start);
                                $end = \Carbon\Carbon::parse($jadwal->time_end);
                                $duration = $start->diffInMinutes($end);
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $duration }} menit
                                </span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($schedules->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan {{ $schedules->firstItem() ?? 0 }} - {{ $schedules->lastItem() ?? 0 }} dari {{ $schedules->total() }} jadwal
                </div>
                <div class="flex items-center space-x-2">
                    {{ $schedules->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Weekly Schedule Overview -->
    <!-- <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Ringkasan Mingguan</h3>
                <span class="text-sm text-gray-600">Berdasarkan filter yang aktif</span>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                @php
                $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                $dayColors = [
                'Senin' => 'bg-blue-50 border-blue-200 text-blue-800',
                'Selasa' => 'bg-green-50 border-green-200 text-green-800',
                'Rabu' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
                'Kamis' => 'bg-purple-50 border-purple-200 text-purple-800',
                'Jumat' => 'bg-red-50 border-red-200 text-red-800',
                'Sabtu' => 'bg-indigo-50 border-indigo-200 text-indigo-800',
                'Minggu' => 'bg-gray-50 border-gray-200 text-gray-800'
                ];

                // Get all schedules for weekly overview (not paginated)
                $allSchedulesForWeek = \App\Models\Schedule::with(['classroom', 'subject'])
                ->where('teacher_id', auth()->user()->teacher->id)
                ->whereHas('classroomAssignment', function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
                });

                // Apply same filters as main query
                if (request('day')) {
                $allSchedulesForWeek->where('day', request('day'));
                }
                if (request('classroom')) {
                $allSchedulesForWeek->whereHas('classroomAssignment', function ($q) {
                $q->where('classroom_id', request('classroom'));
                });
                }
                if (request('subject')) {
                $allSchedulesForWeek->where('subject_id', request('subject'));
                }

                $allSchedulesForWeek = $allSchedulesForWeek->get();
                @endphp

                @foreach($days as $day)
                <div class="border rounded-lg p-4 {{ $dayColors[$day] }}">
                    <h4 class="font-semibold text-sm mb-2">{{ $day }}</h4>
                    @php
                    $daySchedules = $allSchedulesForWeek->where('day', $day);
                    @endphp
                    @if($daySchedules->count() > 0)
                    <div class="space-y-2">
                        @foreach($daySchedules->sortBy('time_start') as $schedule)
                        <div class="text-xs bg-white bg-opacity-50 rounded p-2">
                            <div class="font-medium">{{ $schedule->subject->name ?? '-' }}</div>
                            <div class="text-gray-600">{{ \Carbon\Carbon::parse($schedule->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->time_end)->format('H:i') }}</div>
                            <div class="text-gray-500">{{ $schedule->classroom->name ?? '-' }}</div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-xs text-gray-500 italic">Tidak ada jadwal</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div> -->
    @endif



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submit form when filters change
            const filterForm = document.getElementById('filterForm');
            const filterSelects = filterForm.querySelectorAll('select');

            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    // Show loading state
                    const submitBtn = filterForm.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading...';
                    submitBtn.disabled = true;

                    // Submit form
                    filterForm.submit();
                });
            });

            // Reset form functionality
            const resetLink = document.querySelector('a[href*="jadwal.guru"]');
            if (resetLink) {
                resetLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = this.href;
                });
            }
        });
    </script>
    @endsection
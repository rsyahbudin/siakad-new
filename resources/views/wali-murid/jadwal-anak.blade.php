@extends('layouts.dashboard')

@section('title', 'Jadwal Anak')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('wali.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Jadwal Anak</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-xl shadow-lg p-8 text-white">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">Jadwal Pelajaran</h1>
                        <p class="text-indigo-100 text-lg">{{ $student->full_name }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="flex items-center gap-2 bg-white bg-opacity-10 rounded-lg px-3 py-2">
                        <svg class="w-4 h-4 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0v6m3-3a2 2 0 100-4 2 2 0 000 4z"></path>
                        </svg>
                        <span class="font-medium text-gray-900">NIS:</span>
                        <span class="text-gray-900">{{ $student->nis }}</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white bg-opacity-10 rounded-lg px-3 py-2">
                        <svg class="w-4 h-4 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="font-medium text-gray-900">Kelas:</span>
                        <span class="text-gray-900">{{ $student->classStudents->first()?->classroomAssignment?->classroom?->name ?? 'Kelas tidak ditemukan' }}</span>
                    </div>
                </div>
            </div>

            <div class="text-center lg:text-right">
                <div class="text-4xl font-bold mb-1">{{ $schedules->count() }}</div>
                <div class="text-sm text-indigo-100">Total Mata Pelajaran</div>
                <div class="mt-3">
                    <div class="text-lg font-semibold mb-1">{{ $schedules->groupBy('day')->count() }}</div>
                    <div class="text-sm text-indigo-100">Hari Aktif</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
        $totalSubjects = $schedules->count();
        $activeDays = $schedules->groupBy('day')->count();
        $totalHours = $schedules->sum(function($schedule) {
        return \Carbon\Carbon::parse($schedule->time_end)->diffInHours(\Carbon\Carbon::parse($schedule->time_start));
        });
        $avgSubjectsPerDay = $activeDays > 0 ? round($totalSubjects / $activeDays, 1) : 0;
        @endphp

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Mata Pelajaran</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSubjects }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Hari Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeDays }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Jam Belajar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalHours }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Rata-rata per Hari</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $avgSubjectsPerDay }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @if($schedules->count() > 0)
    <!-- Schedule Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @php
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $dayColors = [
        'Senin' => ['bg' => 'bg-red-500', 'light' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200'],
        'Selasa' => ['bg' => 'bg-green-500', 'light' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200'],
        'Rabu' => ['bg' => 'bg-yellow-500', 'light' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200'],
        'Kamis' => ['bg' => 'bg-blue-500', 'light' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
        'Jumat' => ['bg' => 'bg-purple-500', 'light' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200'],
        'Sabtu' => ['bg' => 'bg-gray-500', 'light' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200']
        ];
        $dayIcons = [
        'Senin' => '1',
        'Selasa' => '2',
        'Rabu' => '3',
        'Kamis' => '4',
        'Jumat' => '5',
        'Sabtu' => '6'
        ];
        @endphp

        @foreach($days as $day)
        @php
        $daySchedules = $schedules->where('day', $day)->sortBy(function($schedule) {
        return \Carbon\Carbon::parse($schedule->time_start);
        });
        $colors = $dayColors[$day] ?? $dayColors['Senin'];
        @endphp

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-shadow overflow-hidden">
            <!-- Day Header -->
            <div class="{{ $colors['bg'] }} px-6 py-4">
                <div class="flex items-center justify-between text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold">{{ $dayIcons[$day] }}</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">{{ $day }}</h3>
                            <p class="text-sm opacity-90">{{ $daySchedules->count() }} mata pelajaran</p>
                        </div>
                    </div>
                    @if($daySchedules->count() > 0)
                    <div class="text-right">
                        <div class="text-sm opacity-90">
                            {{ \Carbon\Carbon::parse($daySchedules->first()->time_start)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($daySchedules->last()->time_end)->format('H:i') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Schedule Content -->
            <div class="p-6">
                @if($daySchedules->count() > 0)
                <div class="space-y-4">
                    @foreach($daySchedules as $schedule)
                    <div class="border {{ $colors['border'] }} {{ $colors['light'] }} rounded-lg p-4 hover:shadow-sm transition-shadow">
                        <!-- Time and Room -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1 bg-white px-3 py-1 rounded-full text-sm font-medium {{ $colors['text'] }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($schedule->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->time_end)->format('H:i') }}
                                </div>
                                <div class="flex items-center gap-1 bg-white px-3 py-1 rounded-full text-sm font-medium text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $schedule->classroom->name ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($schedule->time_end)->diffInMinutes(\Carbon\Carbon::parse($schedule->time_start)) }} menit
                            </div>
                        </div>

                        <!-- Subject -->
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">
                            {{ $schedule->subject->name ?? 'N/A' }}
                        </h4>

                        <!-- Teacher -->
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ $schedule->teacher->full_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <!-- Empty Day State -->
                <div class="text-center py-8">
                    <div class="w-16 h-16 {{ $colors['light'] }} rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-medium text-gray-900 mb-1">Libur</h4>
                    <p class="text-sm text-gray-500">Tidak ada jadwal pelajaran</p>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="flex flex-col items-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Jadwal Pelajaran</h3>
            <p class="text-gray-500 max-w-md">
                Jadwal pelajaran untuk {{ $student->full_name }} belum tersedia.
                Silakan hubungi pihak sekolah untuk informasi lebih lanjut.
            </p>
            <div class="mt-6">
                <a href="{{ route('wali.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Add some custom styles for better mobile experience -->
<style>
    @media (max-width: 768px) {
        .space-y-6>*+* {
            margin-top: 1.5rem;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .lg\:grid-cols-2 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .xl\:grid-cols-3 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
    }

    @media (min-width: 1024px) {
        .lg\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (min-width: 1280px) {
        .xl\:grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
</style>
@endsection
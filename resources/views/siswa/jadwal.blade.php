@extends('layouts.dashboard')
@section('title', 'Jadwal Siswa')
@section('content')

<!-- Header Section -->
<div class="bg-white shadow-sm border-b border-gray-200 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Jadwal Pelajaran</h1>
                <p class="mt-1 text-sm text-gray-600">Jadwal pelajaran mingguan Anda</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="bg-blue-50 px-4 py-2 rounded-lg">
                    <p class="text-sm font-medium text-blue-700">
                        Semester Aktif: {{ $activeSemester->academicYear->year ?? '-' }}
                        ({{ $activeSemester->name ?? '-' }})
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @php
        $totalSubjects = 0;
        $totalHours = 0;
        $uniqueTeachers = collect();
        $daysWithSchedule = 0;

        foreach($weeklySchedules ?? [] as $day => $schedules) {
        if(count($schedules) > 0) {
        $daysWithSchedule++;
        foreach($schedules as $schedule) {
        $totalSubjects++;
        if($schedule->teacher) {
        $uniqueTeachers->push($schedule->teacher->id);
        }
        // Calculate hours (simple calculation)
        if($schedule->time_start && $schedule->time_end) {
        $start = \Carbon\Carbon::parse($schedule->time_start);
        $end = \Carbon\Carbon::parse($schedule->time_end);
        $totalHours += $start->diffInHours($end, false);
        }
        }
        }
        }
        $uniqueTeachersCount = $uniqueTeachers->unique()->count();
        @endphp

        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-90">Total Mata Pelajaran</p>
                    <p class="text-2xl font-bold">{{ $totalSubjects }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-90">Total Jam Pelajaran</p>
                    <p class="text-2xl font-bold">{{ $totalHours }} Jam</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-90">Guru Pengajar</p>
                    <p class="text-2xl font-bold">{{ $uniqueTeachersCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-90">Hari Aktif</p>
                    <p class="text-2xl font-bold">{{ $daysWithSchedule }}/6</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="Cari mata pelajaran atau guru..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="lg:w-48">
                <select id="dayFilter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Hari</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Schedule Cards -->
    <div class="space-y-8" id="scheduleContainer">
        @php
        $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $dayColors = [
        'Senin' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-500', 'border' => 'border-blue-200', 'gradient' => 'from-blue-500 to-blue-600'],
        'Selasa' => ['bg' => 'bg-green-500', 'text' => 'text-green-500', 'border' => 'border-green-200', 'gradient' => 'from-green-500 to-green-600'],
        'Rabu' => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-500', 'border' => 'border-yellow-200', 'gradient' => 'from-yellow-500 to-yellow-600'],
        'Kamis' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-500', 'border' => 'border-purple-200', 'gradient' => 'from-purple-500 to-purple-600'],
        'Jumat' => ['bg' => 'bg-red-500', 'text' => 'text-red-500', 'border' => 'border-red-200', 'gradient' => 'from-red-500 to-red-600'],
        'Sabtu' => ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-500', 'border' => 'border-indigo-200', 'gradient' => 'from-indigo-500 to-indigo-600']
        ];
        @endphp

        @foreach($days as $day)
        <div class="schedule-day" data-day="{{ $day }}">
            <!-- Day Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r {{ $dayColors[$day]['gradient'] }} flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $day }}</h2>
                        <p class="text-sm text-gray-600">{{ isset($weeklySchedules[$day]) ? count($weeklySchedules[$day]) : 0 }} Mata Pelajaran</p>
                    </div>
                </div>
                <div class="px-4 py-2 bg-gray-100 rounded-full">
                    <span class="text-sm font-medium text-gray-700">
                        {{ isset($weeklySchedules[$day]) ? count($weeklySchedules[$day]) : 0 }} Jadwal
                    </span>
                </div>
            </div>

            <!-- Schedule Cards for this day -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @if(isset($weeklySchedules[$day]) && count($weeklySchedules[$day]) > 0)
                @foreach($weeklySchedules[$day] as $jadwal)
                <div class="schedule-card bg-white rounded-xl shadow-lg border-l-4 border-{{ $dayColors[$day]['bg'] }} p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 h-full flex flex-col"
                    data-subject="{{ strtolower($jadwal->subject->name ?? '') }}"
                    data-teacher="{{ strtolower($jadwal->teacher->full_name ?? '') }}">

                    <!-- Time Badge -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <div class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                                {{ $jadwal->time_start ?? '-' }}
                            </div>
                            <span class="text-gray-400 text-sm">-</span>
                            <div class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                {{ $jadwal->time_end ?? '-' }}
                            </div>
                        </div>
                        <div class="w-3 h-3 rounded-full bg-gradient-to-r {{ $dayColors[$day]['gradient'] }}"></div>
                    </div>

                    <!-- Subject Info -->
                    <div class="flex-1 mb-4">
                        <div class="flex items-start mb-3">
                            <div class="w-12 h-12 bg-gradient-to-r {{ $dayColors[$day]['gradient'] }} rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <span class="text-white text-sm font-bold">
                                    {{ substr($jadwal->subject->name ?? 'N/A', 0, 2) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-lg leading-tight mb-1">
                                    {{ $jadwal->subject->name ?? 'Mata Pelajaran Tidak Ditemukan' }}
                                </h3>
                                @if($jadwal->subject)
                                <p class="text-sm text-gray-500 truncate">Kode: {{ $jadwal->subject->code ?? 'N/A' }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Teacher Info -->
                    <div class="border-t border-gray-100 pt-4 mt-auto">
                        @if($jadwal->teacher)
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <span class="text-gray-700 text-sm font-medium">
                                    {{ substr($jadwal->teacher->full_name ?? 'N/A', 0, 2) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $jadwal->teacher->full_name ?? 'Guru Tidak Ditemukan' }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">
                                    {{ $jadwal->teacher->nip ?? 'NIP: N/A' }}
                                </p>
                            </div>
                        </div>
                        @else
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-400 text-sm">Guru belum ditugaskan</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
                @else
                <!-- Empty State for this day -->
                <div class="col-span-full">
                    <div class="bg-gray-50 rounded-xl p-12 text-center border-2 border-dashed border-gray-200">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">Tidak ada jadwal</h3>
                        <p class="text-gray-500">Belum ada jadwal pelajaran untuk hari {{ $day }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if(empty($weeklySchedules) || count(array_filter($weeklySchedules)) === 0)
    <div class="text-center py-16">
        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h3 class="mt-6 text-xl font-medium text-gray-900">Belum ada jadwal</h3>
        <p class="mt-2 text-gray-500">Jadwal pelajaran Anda belum tersedia. Silakan hubungi wali kelas atau admin.</p>
    </div>
    @endif
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const dayFilter = document.getElementById('dayFilter');
        const scheduleCards = document.querySelectorAll('.schedule-card');
        const scheduleDays = document.querySelectorAll('.schedule-day');

        // Performance optimization: Lazy loading for large datasets
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '50px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Apply lazy loading to cards if there are many
        scheduleCards.forEach(function(card, index) {
            if (index > 6) { // Only observe cards after first 6
                card.style.opacity = '0.8';
                card.style.transform = 'translateY(10px)';
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                observer.observe(card);
            }
        });

        // Debounced search for better performance
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                const searchTerm = searchInput.value.toLowerCase();

                scheduleCards.forEach(function(card) {
                    const subject = card.getAttribute('data-subject');
                    const teacher = card.getAttribute('data-teacher');

                    if (subject.includes(searchTerm) || teacher.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show/hide day sections based on visible cards
                scheduleDays.forEach(function(day) {
                    const visibleCards = day.querySelectorAll('.schedule-card[style="display: block"], .schedule-card:not([style*="display: none"])');
                    if (visibleCards.length > 0 || searchTerm === '') {
                        day.style.display = 'block';
                    } else {
                        day.style.display = 'none';
                    }
                });
            }, 150); // 150ms debounce
        });

        // Day filter functionality
        dayFilter.addEventListener('change', function() {
            const selectedDay = this.value;

            scheduleDays.forEach(function(day) {
                const dayName = day.getAttribute('data-day');
                if (!selectedDay || dayName === selectedDay) {
                    day.style.display = 'block';
                } else {
                    day.style.display = 'none';
                }
            });
        });

        // Optimized hover effects with throttling
        let hoverTimeout;
        scheduleCards.forEach(function(card) {
            card.addEventListener('mouseenter', function() {
                clearTimeout(hoverTimeout);
                hoverTimeout = setTimeout(() => {
                    this.style.transform = 'translateY(-4px) scale(1.02)';
                }, 50);
            });

            card.addEventListener('mouseleave', function() {
                clearTimeout(hoverTimeout);
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Add smooth scrolling for day headers
        const dayHeaders = document.querySelectorAll('.schedule-day h2');
        dayHeaders.forEach(function(header) {
            header.addEventListener('click', function() {
                const daySection = this.closest('.schedule-day');
                daySection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });

        // Add keyboard navigation for accessibility
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
            }
        });

        // Performance monitoring
        const totalCards = scheduleCards.length;
        if (totalCards > 20) {
            console.log(`Performance mode: ${totalCards} schedule cards detected`);
        }

        console.log('Student schedule view loaded with performance optimizations for large datasets');
    });
</script>
@endsection
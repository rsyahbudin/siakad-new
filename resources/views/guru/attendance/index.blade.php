@extends('layouts.dashboard')

@section('title', 'Manajemen Absensi')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Absensi</h1>
                <p class="text-gray-600 mt-1">Kelola absensi siswa sesuai jadwal mengajar Anda</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ $schedulesByDay->flatten()->count() }} Jadwal</span>
                    <span class="text-gray-400">({{ $schedulesByDay->flatten()->unique('subject_id')->count() }} Mata Pelajaran)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
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
                    <p class="text-sm font-medium text-gray-600">Total Jadwal</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $schedulesByDay->flatten()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \Carbon\Carbon::now()->format('d') }}</p>
                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::now()->format('l, F Y') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Mata Pelajaran</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $schedulesByDay->flatten()->unique('subject_id')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Kelas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $schedulesByDay->flatten()->unique('classroom_id')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedules Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Jadwal Mengajar</h2>
                    <p class="text-sm text-gray-600">Pilih jadwal untuk mengambil atau melihat absensi siswa</p>
                </div>

                <!-- Search and Filter Controls -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Search Box -->
                    <div class="relative">
                        <input type="text" id="searchSchedules" placeholder="Cari mata pelajaran atau kelas..."
                            class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <!-- Filter by Day -->
                    <select id="filterDay" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Hari</option>
                        @foreach($schedulesByDay->keys() as $day)
                        <option value="{{ $day }}">{{ $day }}</option>
                        @endforeach
                    </select>

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
        </div>

        <div class="p-6">
            @if($schedulesByDay->isEmpty())
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Jadwal Mengajar</h3>
                <p class="text-gray-600 mb-4">Anda belum memiliki jadwal mengajar untuk semester ini.</p>
                <p class="text-sm text-gray-500">Silakan hubungi administrator untuk mengatur jadwal mengajar Anda.</p>
            </div>
            @else
            <!-- Grid View (Default) -->
            <div id="gridViewContainer" class="space-y-6">
                @foreach($schedulesByDay as $day => $schedules)
                <div class="schedule-day-group border border-gray-200 rounded-lg" data-day="{{ $day }}">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 rounded-t-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $day }}</h3>
                                    <p class="text-sm text-gray-600">{{ $schedules->count() }} jadwal mengajar</p>
                                </div>
                            </div>
                            <button class="toggle-day-btn text-sm text-blue-600 hover:text-blue-700 font-medium">
                                <span class="show-text">Tampilkan</span>
                                <span class="hide-text hidden">Sembunyikan</span>
                            </button>
                        </div>
                    </div>

                    <div class="schedule-content p-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
                            @foreach($schedules as $schedule)
                            <div class="schedule-card bg-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow"
                                data-subject="{{ strtolower($schedule->subject->name) }}"
                                data-classroom="{{ strtolower($schedule->classroom->name) }}"
                                data-day="{{ $day }}">
                                <div class="p-4">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-{{ ['blue', 'green', 'purple', 'orange', 'red'][$loop->index % 5] }}-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-{{ ['blue', 'green', 'purple', 'orange', 'red'][$loop->index % 5] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900">{{ $schedule->subject->name }}</h4>
                                                <p class="text-sm text-gray-600">{{ $schedule->classroom->name }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ \Carbon\Carbon::parse($schedule->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->time_end)->format('H:i') }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('teacher.attendance.take', $schedule->id) }}"
                                            class="flex-1 inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Ambil Absensi
                                        </a>
                                        <a href="{{ route('teacher.attendance.view', $schedule->id) }}"
                                            class="inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Lihat
                                        </a>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($schedulesByDay as $day => $schedules)
                            @foreach($schedules as $schedule)
                            <tr class="schedule-row" data-subject="{{ strtolower($schedule->subject->name) }}"
                                data-classroom="{{ strtolower($schedule->classroom->name) }}" data-day="{{ $day }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $day }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $schedule->subject->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $schedule->classroom->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($schedule->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->time_end)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('teacher.attendance.take', $schedule->id) }}"
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md text-xs font-medium transition-colors">
                                            Ambil Absensi
                                        </a>
                                        <a href="{{ route('teacher.attendance.view', $schedule->id) }}"
                                            class="text-gray-600 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 px-3 py-1 rounded-md text-xs font-medium transition-colors">
                                            Lihat
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchSchedules');
        const filterDay = document.getElementById('filterDay');
        const gridViewBtn = document.getElementById('gridView');
        const listViewBtn = document.getElementById('listView');
        const gridViewContainer = document.getElementById('gridViewContainer');
        const listViewContainer = document.getElementById('listViewContainer');
        const scheduleCards = document.querySelectorAll('.schedule-card');
        const scheduleRows = document.querySelectorAll('.schedule-row');
        const dayGroups = document.querySelectorAll('.schedule-day-group');

        // Search functionality
        function filterSchedules() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedDay = filterDay.value.toLowerCase();

            scheduleCards.forEach(card => {
                const subject = card.dataset.subject;
                const classroom = card.dataset.classroom;
                const day = card.dataset.day;

                const matchesSearch = subject.includes(searchTerm) || classroom.includes(searchTerm);
                const matchesDay = !selectedDay || day === selectedDay;

                if (matchesSearch && matchesDay) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            scheduleRows.forEach(row => {
                const subject = row.dataset.subject;
                const classroom = row.dataset.classroom;
                const day = row.dataset.day;

                const matchesSearch = subject.includes(searchTerm) || classroom.includes(searchTerm);
                const matchesDay = !selectedDay || day === selectedDay;

                if (matchesSearch && matchesDay) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });

            // Hide empty day groups
            dayGroups.forEach(group => {
                const visibleCards = group.querySelectorAll('.schedule-card[style="display: block"]').length;
                if (visibleCards === 0) {
                    group.style.display = 'none';
                } else {
                    group.style.display = 'block';
                }
            });
        }

        // Event listeners
        searchInput.addEventListener('input', filterSchedules);
        filterDay.addEventListener('change', filterSchedules);

        // View toggle
        gridViewBtn.addEventListener('click', function() {
            gridViewContainer.classList.remove('hidden');
            listViewContainer.classList.add('hidden');
            gridViewBtn.classList.add('bg-white', 'shadow-sm', 'text-gray-900');
            gridViewBtn.classList.remove('text-gray-600');
            listViewBtn.classList.remove('bg-white', 'shadow-sm', 'text-gray-900');
            listViewBtn.classList.add('text-gray-600');
        });

        listViewBtn.addEventListener('click', function() {
            listViewContainer.classList.remove('hidden');
            gridViewContainer.classList.add('hidden');
            listViewBtn.classList.add('bg-white', 'shadow-sm', 'text-gray-900');
            listViewBtn.classList.remove('text-gray-600');
            gridViewBtn.classList.remove('bg-white', 'shadow-sm', 'text-gray-900');
            gridViewBtn.classList.add('text-gray-600');
        });

        // Toggle day groups
        document.querySelectorAll('.toggle-day-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const content = this.closest('.schedule-day-group').querySelector('.schedule-content');
                const showText = this.querySelector('.show-text');
                const hideText = this.querySelector('.hide-text');

                if (content.style.display === 'none') {
                    content.style.display = 'block';
                    showText.classList.remove('hidden');
                    hideText.classList.add('hidden');
                } else {
                    content.style.display = 'none';
                    showText.classList.add('hidden');
                    hideText.classList.remove('hidden');
                }
            });
        });

        // Hover effects
        scheduleCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.transition = 'transform 0.2s ease';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Initialize with first day expanded
        if (dayGroups.length > 0) {
            const firstGroup = dayGroups[0];
            const firstContent = firstGroup.querySelector('.schedule-content');
            const firstBtn = firstGroup.querySelector('.toggle-day-btn');
            const firstShowText = firstBtn.querySelector('.show-text');
            const firstHideText = firstBtn.querySelector('.hide-text');

            firstContent.style.display = 'block';
            firstShowText.classList.add('hidden');
            firstHideText.classList.remove('hidden');
        }
    });
</script>

@endsection
@extends('layouts.dashboard')
@section('title', isset($jadwal) ? 'Edit Slot Jadwal' : 'Tambah Slot Jadwal')
@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ isset($jadwal) ? 'Edit Slot Jadwal' : 'Tambah Slot Jadwal' }}</h2>
                <p class="text-gray-600 mt-1">{{ $assignment->classroom->name }} - {{ $assignment->academicYear->year ?? '' }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('jadwal.admin.index', ['assignment_id' => $assignment->id]) }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Jadwal
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan:</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <form method="POST" action="{{ isset($jadwal) ? route('jadwal.admin.update', $jadwal) : route('jadwal.admin.store') }}">
            @csrf
            @if(isset($jadwal)) @method('PUT') @endif
            <input type="hidden" name="classroom_assignment_id" value="{{ $assignment->id }}">

            <!-- Form Content -->
            <div class="p-6 space-y-6">
                <!-- Basic Information Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informasi Dasar
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Day Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Hari <span class="text-red-500">*</span>
                            </label>
                            @php
                            $selectedDay = old('day', $day ?? $jadwal->day ?? request('day'));
                            $isDayPreselected = !empty($selectedDay);
                            @endphp
                            @if($isDayPreselected)
                            <!-- Read-only display when pre-selected -->
                            <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-700">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">{{ $selectedDay }}</span>
                                    <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded">Sudah dipilih</span>
                                </div>
                            </div>
                            <input type="hidden" name="day" value="{{ $selectedDay }}">
                            @else
                            <!-- Editable dropdown when not pre-selected -->
                            <select name="day" id="day" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('day') border-red-500 @enderror">
                                <option value="">Pilih hari</option>
                                @foreach(config('siakad.school_days') as $hari)
                                <option value="{{ $hari }}" {{ $selectedDay == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                @endforeach
                            </select>
                            @endif
                            @error('day')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Time Slot Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Pelajaran <span class="text-red-500">*</span>
                            </label>
                            @php
                            $timeSlots = config('siakad.time_slots');
                            $breakTimes = config('siakad.break_times');
                            $selectedJam = old('jam', $jam ?? request('jam'));
                            $isJamPreselected = !empty($selectedJam);

                            // For edit mode, get the slot from existing schedule
                            if(isset($jadwal) && !$isJamPreselected) {
                            foreach($timeSlots as $slotNumber => $slot) {
                            if($slot['start'] === $jadwal->time_start && $slot['end'] === $jadwal->time_end) {
                            $selectedJam = $slotNumber;
                            $isJamPreselected = true;
                            break;
                            }
                            }
                            }

                            $selectedSlot = $isJamPreselected ? ($timeSlots[$selectedJam] ?? null) : null;
                            @endphp
                            @if($isJamPreselected && !isset($jadwal))
                            <!-- Read-only display when pre-selected for new schedule -->
                            <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-700">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="font-medium">{{ $selectedSlot['name'] ?? 'Jam ' . $selectedJam }}</span>
                                        <span class="text-sm text-gray-500 ml-2">({{ $selectedSlot['start'] ?? '' }} - {{ $selectedSlot['end'] ?? '' }})</span>
                                    </div>
                                    <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded">Sudah dipilih</span>
                                </div>
                            </div>
                            <input type="hidden" name="jam" value="{{ $selectedJam }}">
                            @else
                            <!-- Editable dropdown for edit mode or when not pre-selected -->
                            <select name="jam" id="jam" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jam') border-red-500 @enderror">
                                <option value="">Pilih jam pelajaran</option>
                                @foreach($timeSlots as $slotNumber => $slot)
                                @php
                                $isAvailable = true;
                                if(isset($availableSlots) && !isset($availableSlots[$slotNumber])) {
                                $isAvailable = false;
                                }
                                @endphp
                                <option value="{{ $slotNumber }}"
                                    {{ $selectedJam == $slotNumber ? 'selected' : '' }}
                                    data-start="{{ $slot['start'] }}"
                                    data-end="{{ $slot['end'] }}"
                                    {{ !$isAvailable ? 'disabled' : '' }}>
                                    {{ $slot['name'] }} ({{ $slot['start'] }} - {{ $slot['end'] }})
                                    {{ !$isAvailable ? ' - Sudah Terisi' : '' }}
                                </option>
                                @endforeach
                            </select>
                            @endif
                            <p class="text-gray-500 text-sm mt-1">
                                @if($isDayPreselected && $isJamPreselected && !isset($jadwal))
                                Hari dan jam pelajaran sudah dipilih dari jadwal utama
                                @elseif(isset($jadwal))
                                Pilih jam pelajaran untuk mengubah jadwal
                                @else
                                Pilih jam pelajaran yang tersedia
                                @endif
                            </p>
                            @error('jam')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Break Times Information -->
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-orange-800 mb-2">☕ Waktu Istirahat</h4>
                            <div class="text-sm text-orange-700 space-y-1">
                                @foreach($breakTimes as $breakKey => $break)
                                <div class="flex items-center">
                                    <span class="inline-block w-2 h-2 bg-orange-400 rounded-full mr-2"></span>
                                    <strong>{{ $break['name'] }}:</strong> {{ $break['start'] }} - {{ $break['end'] }}
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subject and Teacher Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 5.477 18.246 5 16.5 5c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Mata Pelajaran & Guru
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Subject Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mata Pelajaran <span class="text-red-500">*</span>
                            </label>
                            <select name="subject_id" id="subject_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('subject_id') border-red-500 @enderror">
                                <option value="">Pilih mata pelajaran</option>
                                @foreach($subjects as $mapel)
                                <option value="{{ $mapel->id }}" {{ old('subject_id', $jadwal->subject_id ?? request('subject_id')) == $mapel->id ? 'selected' : '' }}>{{ $mapel->name }}</option>
                                @endforeach
                            </select>
                            @error('subject_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Teacher Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Guru Pengampu <span class="text-red-500">*</span>
                            </label>
                            <select name="teacher_id" id="teacher_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('teacher_id') border-red-500 @enderror">
                                <option value="">Pilih guru</option>
                                @foreach($teachers as $guru)
                                <option value="{{ $guru->id }}" data-mapel="{{ $guru->subject_id }}" {{ old('teacher_id', $jadwal->teacher_id ?? '') == $guru->id ? 'selected' : '' }}>{{ $guru->full_name }}</option>
                                @endforeach
                            </select>
                            <div id="teacher-conflict-warning" class="hidden mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            <strong>Peringatan:</strong> Guru ini mungkin memiliki jadwal bentrok pada waktu yang sama.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @error('teacher_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Hidden Time Inputs -->
                <input type="hidden" name="time_start" id="time_start" value="{{ $selectedSlot['start'] ?? ($jadwal->time_start ?? '') }}">
                <input type="hidden" name="time_end" id="time_end" value="{{ $selectedSlot['end'] ?? ($jadwal->time_end ?? '') }}">
            </div>

            <!-- Form Actions -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    <span class="font-medium">Kelas:</span> {{ $assignment->classroom->name }}
                    @if($isDayPreselected && $isJamPreselected)
                    <span class="mx-2">•</span>
                    <span class="font-medium">Slot:</span> <span class="text-blue-600">{{ $selectedDay }} - {{ $selectedSlot['name'] ?? 'Jam ' . $selectedJam }}</span>
                    @endif
                    @if(isset($jadwal))
                    <span class="mx-2">•</span>
                    <span class="font-medium">Status:</span> <span class="text-blue-600">Mengedit jadwal</span>
                    @endif
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('jadwal.admin.index', ['assignment_id' => $assignment->id]) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ isset($jadwal) ? 'Update Jadwal' : 'Simpan Jadwal' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subjectSelect = document.getElementById('subject_id');
        const teacherSelect = document.getElementById('teacher_id');
        const jamSelect = document.getElementById('jam');
        const timeStartInput = document.getElementById('time_start');
        const timeEndInput = document.getElementById('time_end');
        const conflictWarning = document.getElementById('teacher-conflict-warning');

        function filterTeachers() {
            const subjectId = subjectSelect.value;
            Array.from(teacherSelect.options).forEach(opt => {
                if (!opt.value) return opt.style.display = '';
                opt.style.display = (subjectId === '' || opt.getAttribute('data-mapel') === subjectId) ? '' : 'none';
            });
            // Jika guru terpilih tidak sesuai mapel, reset
            if (teacherSelect.selectedIndex > 0 && teacherSelect.options[teacherSelect.selectedIndex].style.display === 'none') {
                teacherSelect.selectedIndex = 0;
            }
        }

        function updateTimeSlots() {
            if (!jamSelect) return; // If jamSelect doesn't exist (pre-selected case)

            const selectedOption = jamSelect.options[jamSelect.selectedIndex];
            if (selectedOption && selectedOption.dataset.start && selectedOption.dataset.end) {
                timeStartInput.value = selectedOption.dataset.start;
                timeEndInput.value = selectedOption.dataset.end;
            }
        }

        function checkTeacherConflicts() {
            const teacherId = teacherSelect.value;
            const day = document.getElementById('day') ? document.getElementById('day').value : '{{ $selectedDay ?? "" }}';
            const timeStart = timeStartInput.value;
            const timeEnd = timeEndInput.value;

            if (teacherId && day && timeStart && timeEnd) {
                // Here you could make an AJAX call to check for conflicts
                // For now, we'll just show a warning
                conflictWarning.classList.remove('hidden');
            } else {
                conflictWarning.classList.add('hidden');
            }
        }

        // Only add event listeners if elements exist (for pre-selected values)
        if (subjectSelect) {
            subjectSelect.addEventListener('change', filterTeachers);
        }
        if (jamSelect) {
            jamSelect.addEventListener('change', updateTimeSlots);
        }
        if (teacherSelect) {
            teacherSelect.addEventListener('change', checkTeacherConflicts);
        }

        // Initialize
        filterTeachers();
        updateTimeSlots();
    });
</script>
@endsection
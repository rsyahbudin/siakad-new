@extends('layouts.dashboard')

@section('title', 'Edit Event Kalender Akademik')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Event Kalender Akademik</h1>
                    <p class="text-gray-600">Tahun Ajaran {{ $activeYear->year }}</p>
                </div>
                <a href="{{ route('academic-calendar.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <form action="{{ route('academic-calendar.update', $academicCalendar) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Event *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $academicCalendar->title) }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                                placeholder="Masukkan judul event">
                            @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                placeholder="Masukkan deskripsi event (opsional)">{{ old('description', $academicCalendar->description) }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $academicCalendar->start_date->format('Y-m-d')) }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_date') border-red-500 @enderror">
                            @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $academicCalendar->end_date ? $academicCalendar->end_date->format('Y-m-d') : '') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_date') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Kosongkan jika event hanya 1 hari</p>
                            @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- All Day Toggle -->
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_all_day" id="is_all_day" value="1" {{ old('is_all_day', $academicCalendar->is_all_day) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Event sepanjang hari</span>
                            </label>
                        </div>

                        <!-- Start Time -->
                        <div id="start_time_group" class="{{ old('is_all_day', $academicCalendar->is_all_day) ? 'hidden' : '' }}">
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai</label>
                            <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $academicCalendar->start_time ? $academicCalendar->start_time->format('H:i') : '') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_time') border-red-500 @enderror">
                            @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Time -->
                        <div id="end_time_group" class="{{ old('is_all_day', $academicCalendar->is_all_day) ? 'hidden' : '' }}">
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai</label>
                            <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $academicCalendar->end_time ? $academicCalendar->end_time->format('H:i') : '') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_time') border-red-500 @enderror">
                            @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Event *</label>
                            <select name="type" id="type" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror">
                                <option value="">Pilih tipe event</option>
                                <option value="academic" {{ old('type', $academicCalendar->type) == 'academic' ? 'selected' : '' }}>Akademik</option>
                                <option value="holiday" {{ old('type', $academicCalendar->type) == 'holiday' ? 'selected' : '' }}>Libur</option>
                                <option value="exam" {{ old('type', $academicCalendar->type) == 'exam' ? 'selected' : '' }}>Ujian</option>
                                <option value="meeting" {{ old('type', $academicCalendar->type) == 'meeting' ? 'selected' : '' }}>Rapat</option>
                                <option value="other" {{ old('type', $academicCalendar->type) == 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Prioritas *</label>
                            <select name="priority" id="priority" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('priority') border-red-500 @enderror">
                                <option value="">Pilih prioritas</option>
                                <option value="low" {{ old('priority', $academicCalendar->priority) == 'low' ? 'selected' : '' }}>Rendah</option>
                                <option value="medium" {{ old('priority', $academicCalendar->priority) == 'medium' ? 'selected' : '' }}>Sedang</option>
                                <option value="high" {{ old('priority', $academicCalendar->priority) == 'high' ? 'selected' : '' }}>Tinggi</option>
                            </select>
                            @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('academic-calendar.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Update Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const allDayCheckbox = document.getElementById('is_all_day');
        const startTimeGroup = document.getElementById('start_time_group');
        const endTimeGroup = document.getElementById('end_time_group');
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');

        // Toggle time inputs based on all day checkbox
        allDayCheckbox.addEventListener('change', function() {
            if (this.checked) {
                startTimeGroup.classList.add('hidden');
                endTimeGroup.classList.add('hidden');
                startTimeInput.value = '';
                endTimeInput.value = '';
            } else {
                startTimeGroup.classList.remove('hidden');
                endTimeGroup.classList.remove('hidden');
            }
        });

        // Auto-fill end date if empty
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        startDateInput.addEventListener('change', function() {
            if (!endDateInput.value) {
                endDateInput.value = this.value;
            }
        });


    });
</script>
@endsection
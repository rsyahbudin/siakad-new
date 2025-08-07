@extends('layouts.dashboard')

@section('title', 'Edit Jadwal Ujian')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Jadwal Ujian</h1>
                <p class="text-gray-600 mt-1">Perbarui informasi jadwal ujian</p>
                @if($activeSemester && $activeAcademicYear)
                <div class="mt-2 flex items-center gap-2 text-sm text-blue-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Mengedit jadwal untuk: <strong>{{ $activeSemester->name }} - {{ $activeAcademicYear->year }}</strong></span>
                </div>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.exam-schedules.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <span class="text-red-800 font-medium">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Active Semester Notice -->
    @if($activeSemester && $activeAcademicYear)
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-blue-900">Informasi Semester Aktif</h3>
                <p class="text-sm text-blue-800 mt-1">
                    Saat ini hanya dapat mengedit jadwal ujian untuk semester <strong>{{ $activeSemester->name }}</strong>
                    tahun ajaran <strong>{{ $activeAcademicYear->year }}</strong>.
                    Untuk mengubah semester aktif, silakan gunakan menu Master Data > Tahun Ajaran.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Form Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Form Edit Jadwal Ujian</h2>
            </div>
        </div>

        <form action="{{ route('admin.exam-schedules.update', $examSchedule->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Academic Year and Semester -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="academic_year_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Tahun Ajaran <span class="text-red-500">*</span>
                    </label>
                    <select name="academic_year_id" id="academic_year_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('academic_year_id') border-red-300 @enderror" required>
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach($academicYears as $academicYear)
                        <option value="{{ $academicYear->id }}" {{ old('academic_year_id', $examSchedule->academic_year_id) == $academicYear->id ? 'selected' : '' }}>
                            {{ $academicYear->year }}
                        </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="semester_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Semester <span class="text-red-500">*</span>
                    </label>
                    <select name="semester_id" id="semester_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('semester_id') border-red-300 @enderror" required>
                        <option value="">Pilih Semester</option>
                        @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}" {{ old('semester_id', $examSchedule->semester_id) == $semester->id ? 'selected' : '' }}>
                            {{ $semester->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('semester_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Subject and Classroom -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Mata Pelajaran <span class="text-red-500">*</span>
                    </label>
                    <select name="subject_id" id="subject_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('subject_id') border-red-300 @enderror" required>
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $examSchedule->subject_id) == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }} {{ $subject->major ? '(' . $subject->major->name . ')' : '(Umum)' }}
                        </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="classroom_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    <select name="classroom_id" id="classroom_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('classroom_id') border-red-300 @enderror" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ old('classroom_id', $examSchedule->classroom_id) == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('classroom_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Exam Type and Supervisor -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="exam_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Ujian <span class="text-red-500">*</span>
                    </label>
                    <select name="exam_type" id="exam_type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('exam_type') border-red-300 @enderror" required>
                        <option value="">Pilih Jenis Ujian</option>
                        <option value="uts" {{ old('exam_type', $examSchedule->exam_type) == 'uts' ? 'selected' : '' }}>UTS (Ujian Tengah Semester)</option>
                        <option value="uas" {{ old('exam_type', $examSchedule->exam_type) == 'uas' ? 'selected' : '' }}>UAS (Ujian Akhir Semester)</option>
                    </select>
                    @error('exam_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="supervisor_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pengawas <span class="text-red-500">*</span>
                    </label>
                    <select name="supervisor_id" id="supervisor_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('supervisor_id') border-red-300 @enderror" required>
                        <option value="">Pilih Pengawas</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('supervisor_id', $examSchedule->supervisor_id) == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->full_name }} - {{ $teacher->subject->name ?? 'Tidak ada mapel' }}
                        </option>
                        @endforeach
                    </select>
                    @error('supervisor_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Date and Time -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="exam_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Ujian <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="exam_date" id="exam_date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('exam_date') border-red-300 @enderror" value="{{ old('exam_date', $examSchedule->exam_date->format('Y-m-d')) }}" required>
                    @error('exam_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Waktu Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="start_time" id="start_time" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_time') border-red-300 @enderror" value="{{ old('start_time', \Carbon\Carbon::parse($examSchedule->start_time)->format('H:i')) }}" required>
                    @error('start_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Waktu Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="end_time" id="end_time" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_time') border-red-300 @enderror" value="{{ old('end_time', \Carbon\Carbon::parse($examSchedule->end_time)->format('H:i')) }}" required>
                    @error('end_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Subject Type and Major -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="is_general_subject" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Mata Pelajaran <span class="text-red-500">*</span>
                    </label>
                    <select name="is_general_subject" id="is_general_subject" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('is_general_subject') border-red-300 @enderror" required>
                        <option value="">Pilih Jenis Mata Pelajaran</option>
                        <option value="1" {{ old('is_general_subject', $examSchedule->is_general_subject) == '1' ? 'selected' : '' }}>Mata Pelajaran Umum</option>
                        <option value="0" {{ old('is_general_subject', $examSchedule->is_general_subject) == '0' ? 'selected' : '' }}>Mata Pelajaran Jurusan</option>
                    </select>
                    @error('is_general_subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="major_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Jurusan <span class="text-gray-500 text-xs">(Opsional)</span>
                    </label>
                    <select name="major_id" id="major_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('major_id') border-red-300 @enderror" {{ $examSchedule->is_general_subject ? 'disabled' : '' }}>
                        <option value="">Pilih Jurusan (jika mapel jurusan)</option>
                        @foreach($majors as $major)
                        <option value="{{ $major->id }}" {{ old('major_id', $examSchedule->major_id) == $major->id ? 'selected' : '' }}>
                            {{ $major->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('major_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.exam-schedules.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Jadwal Ujian
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isGeneralSubjectSelect = document.getElementById('is_general_subject');
        const majorIdSelect = document.getElementById('major_id');

        // Handle change in is_general_subject
        isGeneralSubjectSelect.addEventListener('change', function() {
            if (this.value === '0') {
                // Jika mapel jurusan, enable major_id
                majorIdSelect.removeAttribute('disabled');
                majorIdSelect.classList.remove('bg-gray-100');
                majorIdSelect.classList.add('bg-white');
            } else {
                // Jika mapel umum, disable major_id
                majorIdSelect.setAttribute('disabled', 'disabled');
                majorIdSelect.classList.add('bg-gray-100');
                majorIdSelect.classList.remove('bg-white');
                majorIdSelect.value = '';
            }
        });

        // Set initial state
        if (isGeneralSubjectSelect.value === '0') {
            majorIdSelect.removeAttribute('disabled');
            majorIdSelect.classList.remove('bg-gray-100');
            majorIdSelect.classList.add('bg-white');
        } else {
            majorIdSelect.setAttribute('disabled', 'disabled');
            majorIdSelect.classList.add('bg-gray-100');
            majorIdSelect.classList.remove('bg-white');
        }
    });
</script>
@endsection
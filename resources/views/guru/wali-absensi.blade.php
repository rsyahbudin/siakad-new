@extends('layouts.dashboard')

@section('title', 'Absensi Semester')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Akumulasi Absensi Semester</h1>
                <p class="text-gray-600 mt-1">Lihat dan kelola akumulasi absensi siswa dari absensi harian guru mata pelajaran</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span>{{ $kelas->name }}</span>
                    <span class="text-gray-400">•</span>
                    <span>{{ $activeSemester->name }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Akumulasi dari Absensi Harian
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <!-- Success Message -->
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-green-800 font-medium">{{ session('success') }}</span>
        </div>
</div>
@endif

    @if(session('error'))
    <!-- Error Message -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <span class="text-red-800 font-medium">{{ session('error') }}</span>
</div>
</div>
@endif

    <!-- Search Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama/NIS/NISN siswa..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Cari
            </button>
    @if($q)
            <a href="{{ route('wali.absensi') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Reset
            </a>
    @endif
</form>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Siswa</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $students->count() }}</p>
                </div>
            </div>
        </div>

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
                    <p class="text-sm font-medium text-gray-600">Total Hadir</p>
                    <p class="text-2xl font-bold text-gray-900" id="totalHadir">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Sakit</p>
                    <p class="text-2xl font-bold text-gray-900" id="totalSakit">0</p>
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
                    <p class="text-sm font-medium text-gray-600">Total Alpha</p>
                    <p class="text-2xl font-bold text-gray-900" id="totalAlpha">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Data Absensi Siswa</h2>
                    <p class="text-sm text-gray-600">Lihat akumulasi absensi dan kelola data semester. Gunakan "Simpan & Kunci" untuk mengunci data yang sudah benar.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="previewData()" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Preview Data
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

        <form method="POST" action="{{ route('wali.absensi.store') }}" id="attendanceForm">
        @csrf
            <input type="hidden" name="semester_id" value="{{ $activeSemester->id }}">
            <input type="hidden" name="classroom_id" value="{{ $kelas->id }}">

            <div class="p-6">
                @if($students->isEmpty())
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Siswa</h3>
                    <p class="text-gray-600">Tidak ada siswa dalam kelas ini.</p>
                </div>
                @else
                <!-- Search and Filter Controls -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <!-- Search Box -->
                            <div class="relative">
                                <input type="text" id="searchStudents" placeholder="Cari nama siswa atau NIS..."
                                    class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>

                            <!-- Filter by Total Absence -->
                            <select id="filterTotal" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Siswa</option>
                                <option value="0">Tidak Ada Absen (0)</option>
                                <option value="1-5">Sedikit (1-5)</option>
                                <option value="6-10">Sedang (6-10)</option>
                                <option value="11+">Banyak (11+)</option>
                            </select>
                        </div>

                        <!-- Student Count -->
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span id="studentCount">{{ $students->count() }}</span> siswa
                        </div>
                    </div>
                </div>

                <!-- Students List -->
                <div class="space-y-4" id="studentsList">
                    @foreach($students as $student)
                    @php
                    $studentId = $student->student->id;
                    $attendance = $accumulatedAttendances[$studentId] ?? null;
                    $sakit = $attendance ? $attendance['sakit'] : 0;
                    $izin = $attendance ? $attendance['izin'] : 0;
                    $alpha = $attendance ? $attendance['alpha'] : 0;
                    $hadir = $attendance ? $attendance['hadir'] : 0;
                    $total = $sakit + $izin + $alpha;
                    $percentage = $attendance ? $attendance['percentage'] : 0;
                    $isLocked = $attendance ? $attendance['is_locked'] : false;
                    @endphp
                    <div class="student-item bg-gray-50 rounded-lg p-4"
                        data-name="{{ strtolower($student->student->user->name) }}"
                        data-nis="{{ strtolower($student->student->nis) }}"
                        data-total="{{ $total }}">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-sm font-medium text-blue-600">{{ substr($student->student->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $student->student->user->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $student->student->nis }}</p>
                                    @if($isLocked)
                                    <span class="inline-flex items-center gap-1 bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium mt-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        Data Dikunci
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full lg:w-auto">
                                <div class="flex items-center gap-2">
                                    <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Hadir:</label>
                                    <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium" data-hadir="{{ $hadir }}">
                                        {{ $hadir }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Sakit:</label>
                                    <input type="number" name="attendances[{{ $student->student->id }}][sakit]"
                                        value="{{ $sakit }}" min="0"
                                        class="w-16 border border-gray-300 rounded-lg px-2 py-1 text-sm text-center attendance-input"
                                        data-student="{{ $student->student->id }}" data-type="sakit"
                                        {{ $isFinalized || $isLocked ? 'disabled' : '' }}>
                                </div>
                                <div class="flex items-center gap-2">
                                    <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Izin:</label>
                                    <input type="number" name="attendances[{{ $student->student->id }}][izin]"
                                        value="{{ $izin }}" min="0"
                                        class="w-16 border border-gray-300 rounded-lg px-2 py-1 text-sm text-center attendance-input"
                                        data-student="{{ $student->student->id }}" data-type="izin"
                                        {{ $isFinalized || $isLocked ? 'disabled' : '' }}>
                                </div>
                                <div class="flex items-center gap-2">
                                    <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Alpha:</label>
                                    <input type="number" name="attendances[{{ $student->student->id }}][alpha]"
                                        value="{{ $alpha }}" min="0"
                                        class="w-16 border border-gray-300 rounded-lg px-2 py-1 text-sm text-center attendance-input"
                                        data-student="{{ $student->student->id }}" data-type="alpha"
                                        {{ $isFinalized || $isLocked ? 'disabled' : '' }}>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium total-badge" data-student="{{ $student->student->id }}">
                                        Total: {{ $total }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">
                                        {{ $percentage }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- No Results Message -->
                <div id="noResults" class="hidden text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Hasil</h3>
                    <p class="text-gray-600">Tidak ada siswa yang sesuai dengan pencarian Anda.</p>
        </div>
                @endif
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="resetForm()" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </button>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit" name="action" value="save" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors" {{ $isFinalized ? 'disabled' : '' }}>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Absensi
                        </button>
                        <button type="submit" name="action" value="lock" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors" {{ $isFinalized ? 'disabled' : '' }}>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Simpan & Kunci
                        </button>
                    </div>
                </div>
        </div>
    </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.attendance-input');

        inputs.forEach(input => {
            input.addEventListener('input', function() {
                updateStudentTotal(this);
                updateSummary();
            });
        });

        // Initial calculation
        updateSummary();
    });

    function updateStudentTotal(input) {
        const studentId = input.dataset.student;
        const type = input.dataset.type;
        const value = parseInt(input.value) || 0;

        // Get all inputs for this student
        const studentInputs = document.querySelectorAll(`[data-student="${studentId}"]`);
        let total = 0;

        studentInputs.forEach(input => {
            total += parseInt(input.value) || 0;
        });

        // Update total badge
        const totalBadge = document.querySelector(`.total-badge[data-student="${studentId}"]`);
        if (totalBadge) {
            totalBadge.textContent = `Total: ${total}`;
        }
    }

    function updateSummary() {
        const inputs = document.querySelectorAll('.attendance-input');
        let totalSakit = 0;
        let totalIzin = 0;
        let totalAlpha = 0;
        let totalHadir = 0;

        // Calculate from input fields
        inputs.forEach(input => {
            const value = parseInt(input.value) || 0;
            const type = input.dataset.type;

            switch (type) {
                case 'sakit':
                    totalSakit += value;
                    break;
                case 'izin':
                    totalIzin += value;
                    break;
                case 'alpha':
                    totalAlpha += value;
                    break;
            }
        });

        // Calculate total hadir from displayed values
        const hadirSpans = document.querySelectorAll('[data-hadir]');
        hadirSpans.forEach(span => {
            totalHadir += parseInt(span.dataset.hadir) || 0;
        });

        document.getElementById('totalSakit').textContent = totalSakit;
        document.getElementById('totalIzin').textContent = totalIzin;
        document.getElementById('totalAlpha').textContent = totalAlpha;
        document.getElementById('totalHadir').textContent = totalHadir;
    }

    function resetForm() {
        const inputs = document.querySelectorAll('.attendance-input');
        inputs.forEach(input => {
            input.value = '0';
        });
        updateSummary();

        // Update all total badges
        const students = document.querySelectorAll('[data-student]');
        students.forEach(student => {
            const studentId = student.dataset.student;
            const totalBadge = document.querySelector(`.total-badge[data-student="${studentId}"]`);
            if (totalBadge) {
                totalBadge.textContent = 'Total: 0';
            }
        });
    }

    function previewData() {
        const form = document.getElementById('attendanceForm');
        const formData = new FormData(form);

        // Create preview modal
        let previewHtml = '<div class="modal fade" id="previewModal" tabindex="-1">';
        previewHtml += '<div class="modal-dialog modal-lg"><div class="modal-content">';
        previewHtml += '<div class="modal-header"><h5 class="modal-title">Preview Data Absensi</h5>';
        previewHtml += '<button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>';
        previewHtml += '<div class="modal-body">';
        previewHtml += '<div class="table-responsive"><table class="table table-sm">';
        previewHtml += '<thead><tr><th>Nama</th><th>Sakit</th><th>Izin</th><th>Alpha</th><th>Total</th></tr></thead><tbody>';

        // Add preview data here
        previewHtml += '</tbody></table></div></div>';
        previewHtml += '<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>';
        previewHtml += '</div></div></div>';

        document.body.insertAdjacentHTML('beforeend', previewHtml);

        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();

        // Remove modal after hidden
        document.getElementById('previewModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }

    function exportData() {
        // Implementation for data export
        alert('Fitur export akan segera tersedia!');
    }

    // Search and filter functionality
    function filterStudents() {
        const searchTerm = document.getElementById('searchStudents').value.toLowerCase();
        const filterTotal = document.getElementById('filterTotal').value;
        const studentItems = document.querySelectorAll('.student-item');
        const studentsList = document.getElementById('studentsList');
        const noResults = document.getElementById('noResults');
        const studentCount = document.getElementById('studentCount');

        let visibleCount = 0;

        studentItems.forEach(item => {
            const name = item.dataset.name;
            const nis = item.dataset.nis;
            const total = parseInt(item.dataset.total) || 0;

            const matchesSearch = name.includes(searchTerm) || nis.includes(searchTerm);
            let matchesTotal = true;

            if (filterTotal) {
                switch (filterTotal) {
                    case '0':
                        matchesTotal = total === 0;
                        break;
                    case '1-5':
                        matchesTotal = total >= 1 && total <= 5;
                        break;
                    case '6-10':
                        matchesTotal = total >= 6 && total <= 10;
                        break;
                    case '11+':
                        matchesTotal = total >= 11;
                        break;
                }
            }

            if (matchesSearch && matchesTotal) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Update student count
        studentCount.textContent = visibleCount;

        // Show/hide no results message
        if (visibleCount === 0) {
            studentsList.style.display = 'none';
            noResults.classList.remove('hidden');
        } else {
            studentsList.style.display = 'block';
            noResults.classList.add('hidden');
        }
    }

    // Add event listeners for search and filter
    document.addEventListener('DOMContentLoaded', function() {
        // Search and filter event listeners
        document.getElementById('searchStudents').addEventListener('input', filterStudents);
        document.getElementById('filterTotal').addEventListener('change', filterStudents);

        // Update total data attributes when inputs change
        document.querySelectorAll('.attendance-input').forEach(input => {
            input.addEventListener('input', function() {
                const studentItem = this.closest('.student-item');
                const studentId = this.dataset.student;
                const studentInputs = document.querySelectorAll(`[data-student="${studentId}"]`);
                let total = 0;

                studentInputs.forEach(input => {
                    total += parseInt(input.value) || 0;
                });

                studentItem.dataset.total = total;
            });
        });
    });
</script>
@endpush
@endsection
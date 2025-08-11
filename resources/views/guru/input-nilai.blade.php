@extends('layouts.dashboard')
@section('title', 'Input Nilai Siswa')
@section('content')

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-green-800">Berhasil!</h3>
            <div class="mt-2 text-sm text-green-700">
                {{ session('success') }}
            </div>
        </div>
    </div>
</div>
@endif

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
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Input Nilai Siswa</h1>
            <p class="text-gray-600">Kelola dan input nilai siswa untuk mata pelajaran yang Anda ajar</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('nilai.import.show') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                </svg>
                Impor dari Excel
            </a>
        </div>
    </div>
</div>

<!-- Semester Info -->
<div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-4 rounded-xl mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold mb-1">Semester Aktif</h3>
            <p class="text-blue-100">{{ $activeSemester->academicYear->year ?? '-' }} (Semester {{ $activeSemester->name ?? '-' }})</p>
        </div>
        <div class="text-right">
            <div class="text-sm text-blue-100">Status</div>
            <div class="text-lg font-bold">Aktif</div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Pilih Kelas & Mata Pelajaran</h3>
        <div class="text-sm text-gray-600">Pilih untuk mulai input nilai</div>
    </div>

    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="assignment_id" class="block text-sm font-medium text-gray-700 mb-2">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Kelas
                </div>
            </label>
            <select name="assignment_id" id="assignment_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" onchange="this.form.submit()">
                <option value="">- Pilih Kelas -</option>
                @foreach($assignments as $assignment)
                <option value="{{ $assignment->id }}" {{ $selectedAssignment == $assignment->id ? 'selected' : '' }}>
                    {{ $assignment->classroom->name }} ({{ $assignment->academicYear->year ?? '' }})
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Mata Pelajaran
                </div>
            </label>
            <select name="subject_id" id="subject_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" onchange="this.form.submit()">
                <option value="">- Pilih Mata Pelajaran -</option>
                @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" {{ $selectedSubject == $subject->id ? 'selected' : '' }}>
                    {{ $subject->name }}
                </option>
                @endforeach
            </select>
        </div>
    </form>
</div>

@if($selectedAssignment && $selectedSubject)
<!-- Grade Settings Info -->
@if($bobot)
<div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">Pengaturan Bobot & KKM</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                <div class="bg-white rounded-lg p-3 border border-blue-200">
                    <div class="text-blue-600 font-medium">Tugas</div>
                    <div class="text-2xl font-bold text-blue-900">{{ $bobot->assignment_weight }}%</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-blue-200">
                    <div class="text-blue-600 font-medium">UTS</div>
                    <div class="text-2xl font-bold text-blue-900">{{ $bobot->uts_weight }}%</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-blue-200">
                    <div class="text-blue-600 font-medium">UAS</div>
                    <div class="text-2xl font-bold text-blue-900">{{ $bobot->uas_weight }}%</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-blue-200">
                    <div class="text-blue-600 font-medium">KKM</div>
                    <div class="text-2xl font-bold text-blue-900">{{ $bobot->kkm }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-8">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-lg font-semibold text-yellow-900 mb-2">Pengaturan Belum Diatur</h3>
            <p class="text-yellow-800">Bobot dan KKM untuk mata pelajaran ini belum diatur. Nilai akhir dan status tidak dapat dihitung secara otomatis.</p>
        </div>
    </div>
</div>
@endif

@if($isFinalized)
<!-- Finalization Warning -->
<div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-8">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-lg font-semibold text-red-900 mb-2">Raport Telah Difinalisasi</h3>
            <p class="text-red-800">Anda tidak dapat lagi mengubah atau menyimpan nilai untuk kelas ini karena raport semester ini telah difinalisasi oleh wali kelas.</p>
        </div>
    </div>
</div>
@endif

<!-- Grade Input Form -->
<form method="POST" action="{{ route('nilai.input.store') }}" id="gradeForm">
    @csrf
    <input type="hidden" name="assignment_id" value="{{ $selectedAssignment }}">
    <input type="hidden" name="subject_id" value="{{ $selectedSubject }}">

    <!-- Results Summary -->
    <div class="mb-4 flex items-center justify-between">
        <div class="text-sm text-gray-600">
            @if(method_exists($students, 'firstItem'))
            Menampilkan {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} dari {{ $students->total() }} siswa
            @if($students->total() > 20)
            <span class="text-xs text-gray-500 ml-2">(20 per halaman untuk performa optimal)</span>
            @endif
            @else
            Total: {{ $students->count() }} siswa
            @endif
        </div>
        <div class="text-sm text-gray-600">
            @if(method_exists($students, 'currentPage'))
            Halaman {{ $students->currentPage() }} dari {{ $students->lastPage() }}
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Input Nilai Siswa</h3>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-600">
                        Total: {{ method_exists($students, 'total') ? $students->total() : $students->count() }} siswa
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" id="selectAllBtn" class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200 transition-colors">
                            Pilih Semua
                        </button>
                        <button type="button" id="clearAllBtn" class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded hover:bg-gray-200 transition-colors">
                            Hapus Semua
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grade Table -->
        <div class="overflow-x-auto max-h-96" id="gradeTableContainer">
            <table class="min-w-full divide-y divide-gray-200" id="gradeTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Nama Siswa
                            </div>
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Tugas
                            </div>
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                UTS
                            </div>
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                UAS
                            </div>
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Sikap
                            </div>
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Nilai Akhir
                            </div>
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Status
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($students as $i => $siswa)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-sm font-semibold text-blue-600">{{ $i+1 }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $siswa->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $siswa->nis ?? '' }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Tugas Input -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <input type="number"
                                name="nilai[{{ $siswa->id }}][tugas]"
                                value="{{ $grades[$siswa->id]->assignment_grade ?? '' }}"
                                class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $isFinalized ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                min="0"
                                max="100"
                                {{ $isFinalized ? 'disabled' : '' }}
                                onchange="calculateFinalGrade({{ $siswa->id }})">
                        </td>

                        <!-- UTS Input -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <input type="number"
                                name="nilai[{{ $siswa->id }}][uts]"
                                value="{{ $grades[$siswa->id]->uts_grade ?? '' }}"
                                class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $isFinalized ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                min="0"
                                max="100"
                                {{ $isFinalized ? 'disabled' : '' }}
                                onchange="calculateFinalGrade({{ $siswa->id }})">
                        </td>

                        <!-- UAS Input -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <input type="number"
                                name="nilai[{{ $siswa->id }}][uas]"
                                value="{{ $grades[$siswa->id]->uas_grade ?? '' }}"
                                class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $isFinalized ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                min="0"
                                max="100"
                                {{ $isFinalized ? 'disabled' : '' }}
                                onchange="calculateFinalGrade({{ $siswa->id }})">
                        </td>

                        <!-- Sikap Input -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <select name="nilai[{{ $siswa->id }}][sikap]"
                                class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $isFinalized ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                {{ $isFinalized ? 'disabled' : '' }}>
                                <option value="">- Pilih -</option>
                                <option value="Baik" {{ ($grades[$siswa->id]->attitude_grade ?? '') === 'Baik' ? 'selected' : '' }}>Baik</option>
                                <option value="Cukup" {{ ($grades[$siswa->id]->attitude_grade ?? '') === 'Cukup' ? 'selected' : '' }}>Cukup</option>
                                <option value="Kurang Baik" {{ ($grades[$siswa->id]->attitude_grade ?? '') === 'Kurang Baik' ? 'selected' : '' }}>Kurang Baik</option>
                            </select>
                        </td>

                        <!-- Nilai Akhir (Calculated) -->
                        @php
                        $nilaiAkhir = null;
                        $status = null;
                        if($bobot) {
                        if(isset($grades[$siswa->id]) && !is_null($grades[$siswa->id]->final_grade)) {
                        $nilaiAkhir = $grades[$siswa->id]->final_grade;
                        } else {
                        $tugas = $grades[$siswa->id]->assignment_grade ?? 0;
                        $uts = $grades[$siswa->id]->uts_grade ?? 0;
                        $uas = $grades[$siswa->id]->uas_grade ?? 0;
                        $bobotTugas = $bobot->assignment_weight ?? 0;
                        $bobotUts = $bobot->uts_weight ?? 0;
                        $bobotUas = $bobot->uas_weight ?? 0;
                        $nilaiAkhir = ($tugas * $bobotTugas + $uts * $bobotUts + $uas * $bobotUas) / 100;
                        }
                        $status = $nilaiAkhir >= $bobot->kkm;
                        }
                        @endphp
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-lg font-bold {{ $nilaiAkhir !== null ? ($status ? 'text-green-600' : 'text-red-600') : 'text-gray-400' }}">
                                {{ $nilaiAkhir !== null ? number_format($nilaiAkhir, 2) : '-' }}
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($bobot && $nilaiAkhir !== null)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    @if($status)
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    @else
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    @endif
                                </svg>
                                {{ $status ? 'Tuntas' : 'Tidak Tuntas' }}
                            </span>
                            @else
                            <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($students, 'hasPages') && $students->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} dari {{ $students->total() }} siswa
                </div>
                <div class="flex items-center space-x-2">
                    {{ $students->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Submit Button -->
    <div class="mt-8 flex items-center justify-between">
        <div class="text-sm text-gray-600">
            <span class="font-medium">{{ method_exists($students, 'count') ? $students->count() : $students->count() }}</span> siswa akan disimpan
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-600">
                <span id="selectedCount">0</span> siswa dipilih
            </div>
            <div class="flex items-center space-x-2">
                <div id="loadingIndicator" class="hidden">
                    <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <button type="submit" id="submitBtn"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors {{ $isFinalized ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ $isFinalized ? 'disabled' : '' }}>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ $isFinalized ? 'Tidak Dapat Disimpan' : 'Simpan Nilai' }}
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Performance Tips -->
@if(method_exists($students, 'count') && $students->count() > 50)
<div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-yellow-800">Tips Performa</h3>
            <div class="mt-2 text-sm text-yellow-700">
                <p>Dengan {{ $students->count() }} siswa, gunakan fitur "Pilih Semua" untuk mengisi nilai secara cepat, atau gunakan pagination untuk performa yang lebih baik.</p>
            </div>
        </div>
    </div>
</div>
@endif
@endif

@endsection

@push('scripts')
<script>
    // Auto-calculate final grade when input values change
    function calculateFinalGrade(studentId) {
        // This function can be enhanced with AJAX to calculate grades in real-time
        // For now, it just validates the input ranges
        var inputs = document.querySelectorAll('input[type="number"]');
        for (var i = 0; i < inputs.length; i++) {
            var input = inputs[i];
            var value = parseInt(input.value);
            if (value < 0) input.value = 0;
            if (value > 100) input.value = 100;
        }
    }

    // Form validation
    document.getElementById('gradeForm').addEventListener('submit', function(e) {
        var inputs = document.querySelectorAll('input[type="number"]');
        var isValid = true;

        for (var i = 0; i < inputs.length; i++) {
            var input = inputs[i];
            var value = parseInt(input.value);
            if (input.value && (value < 0 || value > 100)) {
                input.classList.add('border-red-500');
                isValid = false;
            } else {
                input.classList.remove('border-red-500');
            }
        }

        if (!isValid) {
            e.preventDefault();
            alert('Nilai harus antara 0-100');
        }
    });

    // Bulk actions functionality
    document.addEventListener('DOMContentLoaded', function() {
        var selectAllBtn = document.getElementById('selectAllBtn');
        var clearAllBtn = document.getElementById('clearAllBtn');
        var selectedCount = document.getElementById('selectedCount');
        var numberInputs = document.querySelectorAll('input[type="number"]');
        var selectInputs = document.querySelectorAll('select');

        // Select all functionality
        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function() {
                for (var i = 0; i < numberInputs.length; i++) {
                    if (!numberInputs[i].disabled) {
                        numberInputs[i].value = '75'; // Default value
                    }
                }
                for (var i = 0; i < selectInputs.length; i++) {
                    if (!selectInputs[i].disabled) {
                        selectInputs[i].value = 'Baik'; // Default value
                    }
                }
                updateSelectedCount();
            });
        }

        // Clear all functionality
        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', function() {
                for (var i = 0; i < numberInputs.length; i++) {
                    if (!numberInputs[i].disabled) {
                        numberInputs[i].value = '';
                    }
                }
                for (var i = 0; i < selectInputs.length; i++) {
                    if (!selectInputs[i].disabled) {
                        selectInputs[i].value = '';
                    }
                }
                updateSelectedCount();
            });
        }

        // Update selected count
        function updateSelectedCount() {
            var filledInputs = 0;
            for (var i = 0; i < numberInputs.length; i++) {
                if (numberInputs[i].value && !numberInputs[i].disabled) {
                    filledInputs++;
                }
            }
            for (var i = 0; i < selectInputs.length; i++) {
                if (selectInputs[i].value && !selectInputs[i].disabled) {
                    filledInputs++;
                }
            }
            if (selectedCount) {
                selectedCount.textContent = Math.floor(filledInputs / 4); // Divide by 4 because each student has 4 inputs
            }
        }

        // Auto-save functionality with debouncing
        var autoSaveTimer;
        var allInputs = document.querySelectorAll('input, select');

        for (var i = 0; i < allInputs.length; i++) {
            allInputs[i].addEventListener('change', function() {
                clearTimeout(autoSaveTimer);
                updateSelectedCount();
                autoSaveTimer = setTimeout(function() {
                    // Auto-save logic can be implemented here
                    console.log('Auto-save triggered');
                }, 2000);
            });
        }

        // Initial count update
        updateSelectedCount();

        // Performance optimization: Lazy loading for large tables
        var table = document.querySelector('table tbody');
        if (table && table.children.length > 50) {
            // Add virtual scrolling for large datasets
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                    }
                });
            });

            var rows = table.querySelectorAll('tr');
            for (var i = 0; i < rows.length; i++) {
                if (i > 20) { // Only observe rows after first 20
                    rows[i].style.opacity = '0.7';
                    observer.observe(rows[i]);
                }
            }
        }
    });

    // Keyboard shortcuts for better UX
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            document.getElementById('gradeForm').submit();
        }

        // Tab navigation optimization
        if (e.key === 'Tab') {
            var activeElement = document.activeElement;
            if (activeElement && activeElement.tagName === 'INPUT') {
                // Auto-validate on tab
                var value = parseInt(activeElement.value);
                if (activeElement.value && (value < 0 || value > 100)) {
                    activeElement.classList.add('border-red-500');
                } else {
                    activeElement.classList.remove('border-red-500');
                }
            }
        }
    });

    // Loading indicator for form submission
    var submitBtn = document.getElementById('submitBtn');
    var loadingIndicator = document.getElementById('loadingIndicator');

    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            if (!submitBtn.disabled) {
                loadingIndicator.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Menyimpan...';
            }
        });
    }

    // Optimize table rendering for large datasets
    var tableContainer = document.getElementById('gradeTableContainer');
    if (tableContainer) {
        // Add scroll event listener for virtual scrolling
        tableContainer.addEventListener('scroll', function() {
            // Implement virtual scrolling if needed
            var scrollTop = tableContainer.scrollTop;
            var scrollHeight = tableContainer.scrollHeight;
            var clientHeight = tableContainer.clientHeight;

            // Load more data when user scrolls near bottom
            if (scrollTop + clientHeight >= scrollHeight - 100) {
                // This can be enhanced with AJAX to load more data
                console.log('Near bottom, could load more data');
            }
        });
    }
</script>
@endpush
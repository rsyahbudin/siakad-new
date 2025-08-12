@extends('layouts.dashboard')
@section('title', 'Nilai Akademik')
@section('content')

<!-- Header Section -->
<div class="bg-white shadow-sm border-b border-gray-200 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nilai Akademik</h1>
                <p class="mt-1 text-sm text-gray-600">Nilai akademik semester berjalan Anda</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="bg-green-50 px-4 py-2 rounded-lg">
                    <p class="text-sm font-medium text-green-700">
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
        // Default to Ganjil semester for initial load
        $currentGrades = $gradesGanjil;
        $totalSubjects = $currentGrades->count();
        $totalGrades = 0;
        $averageGrade = 0;
        $passedSubjects = 0;
        $failedSubjects = 0;
        $totalFinalGrade = 0;
        $subjectsWithGrades = 0;

        foreach($currentGrades as $grade) {
        if ($grade->final_grade !== null) {
        $totalFinalGrade += $grade->final_grade;
        $subjectsWithGrades++;

        // Check if passed (assuming KKM is 75)
        if ($grade->final_grade >= 75) {
        $passedSubjects++;
        } else {
        $failedSubjects++;
        }
        }
        }

        $averageGrade = $subjectsWithGrades > 0 ? $totalFinalGrade / $subjectsWithGrades : 0;
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
                    <p class="text-2xl font-bold" id="stat-total-subjects">{{ $totalSubjects }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-90">Rata-rata Nilai</p>
                    <p class="text-2xl font-bold" id="stat-average-grade">{{ number_format($averageGrade, 1) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-90">Lulus</p>
                    <p class="text-2xl font-bold" id="stat-passed-subjects">{{ $passedSubjects }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-90">Tidak Lulus</p>
                    <p class="text-2xl font-bold" id="stat-failed-subjects">{{ $failedSubjects }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="Cari mata pelajaran..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
            <div class="lg:w-48">
                <select id="gradeFilter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Semua Nilai</option>
                    <option value="passed">Lulus (≥75)</option>
                    <option value="failed">Tidak Lulus (&lt;75)</option>
                    <option value="no-grade">Belum Ada Nilai</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Semester Tabs -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button id="tab-ganjil" class="tab-button border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600" data-semester="ganjil">
                    Semester Ganjil
                </button>
                <button id="tab-genap" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700" data-semester="genap">
                    Semester Genap
                </button>
            </nav>
        </div>
    </div>

    <!-- Grades Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Nilai Akademik <span id="current-semester">Semester Ganjil</span></h2>
            <p class="text-sm text-gray-600 mt-1">Detail nilai per mata pelajaran</p>
        </div>

        <div class="overflow-x-auto">
            <!-- Semester Ganjil Table -->
            <table id="table-ganjil" class="semester-table min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Mata Pelajaran
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Tugas
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                UTS
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                UAS
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Sikap
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Nilai Akhir
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                    @if(isset($gradesGanjil) && count($gradesGanjil) > 0)
                    @foreach($gradesGanjil as $grade)
                    <tr class="grade-row hover:bg-gray-50 transition-colors duration-200"
                        data-subject="{{ strtolower($grade->subject->name ?? '') }}"
                        data-status="{{ $grade->final_grade !== null ? ($grade->final_grade >= 75 ? 'passed' : 'failed') : 'no-grade' }}">

                        <!-- Subject Name -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-4">
                                    <span class="text-white text-sm font-bold">
                                        {{ substr($grade->subject->name ?? 'N/A', 0, 2) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $grade->subject->name ?? 'Mata Pelajaran Tidak Ditemukan' }}
                                    </div>
                                    @if($grade->scheduleTeacher && $grade->scheduleTeacher->teacher)
                                    <div class="text-xs text-gray-500">
                                        {{ $grade->scheduleTeacher->teacher->full_name ?? 'Guru Tidak Ditemukan' }}
                                    </div>
                                    @elseif($grade->scheduleTeacher)
                                    <div class="text-xs text-gray-500">
                                        Guru belum ditugaskan
                                    </div>
                                    @else
                                    <div class="text-xs text-gray-500">
                                        Jadwal tidak ditemukan
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Assignment Grade -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($grade->assignment_grade !== null)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $grade->assignment_grade }}
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <!-- UTS Grade -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($grade->uts_grade !== null)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ $grade->uts_grade }}
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <!-- UAS Grade -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($grade->uas_grade !== null)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $grade->uas_grade }}
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <!-- Attitude Grade -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($grade->attitude_grade)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($grade->attitude_grade === 'Baik') bg-green-100 text-green-800
                                    @elseif($grade->attitude_grade === 'Cukup') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                {{ $grade->attitude_grade }}
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <!-- Final Grade -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                            $nilaiAkhir = null;
                            if (!is_null($grade->final_grade)) {
                            $nilaiAkhir = $grade->final_grade;
                            } elseif (isset($subjectSettings[$grade->subject_id])) {
                            $setting = $subjectSettings[$grade->subject_id];
                            $nilaiAkhir = ($grade->assignment_grade * $setting->assignment_weight +
                            $grade->uts_grade * $setting->uts_weight +
                            $grade->uas_grade * $setting->uas_weight) / 100;
                            }
                            @endphp

                            @if($nilaiAkhir !== null)
                            <span class="text-lg font-bold {{ $nilaiAkhir >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($nilaiAkhir, 2) }}
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($nilaiAkhir !== null)
                            @if($nilaiAkhir >= 75)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Lulus
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Tidak Lulus
                            </span>
                            @endif
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Belum Ada
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td class="px-6 py-12 text-center" colspan="7">
                            <div class="flex flex-col items-center">
                                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data nilai</h3>
                                <p class="text-gray-500">Nilai akademik semester ganjil belum tersedia.</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <!-- Semester Genap Table -->
            <table id="table-genap" class="semester-table min-w-full divide-y divide-gray-200 hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Mata Pelajaran
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Tugas
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                UTS
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                UAS
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Sikap
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Nilai Akhir
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                    @if(isset($gradesGenap) && count($gradesGenap) > 0)
                    @foreach($gradesGenap as $grade)
                    <tr class="grade-row hover:bg-gray-50 transition-colors duration-200"
                        data-subject="{{ strtolower($grade->subject->name ?? '') }}"
                        data-status="{{ $grade->final_grade !== null ? ($grade->final_grade >= 75 ? 'passed' : 'failed') : 'no-grade' }}">

                        <!-- Subject Name -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-4">
                                    <span class="text-white text-sm font-bold">
                                        {{ substr($grade->subject->name ?? 'N/A', 0, 2) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $grade->subject->name ?? 'Mata Pelajaran Tidak Ditemukan' }}
                                    </div>
                                    @if($grade->scheduleTeacher && $grade->scheduleTeacher->teacher)
                                    <div class="text-xs text-gray-500">
                                        {{ $grade->scheduleTeacher->teacher->full_name ?? 'Guru Tidak Ditemukan' }}
                                    </div>
                                    @elseif($grade->scheduleTeacher)
                                    <div class="text-xs text-gray-500">
                                        Guru belum ditugaskan
                                    </div>
                                    @else
                                    <div class="text-xs text-gray-500">
                                        Jadwal tidak ditemukan
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Assignment Grade -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($grade->assignment_grade !== null)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $grade->assignment_grade }}
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <!-- UTS Grade -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($grade->uts_grade !== null)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ $grade->uts_grade }}
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <!-- UAS Grade -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($grade->uas_grade !== null)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $grade->uas_grade }}
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <!-- Attitude Grade -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($grade->attitude_grade)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                         @if($grade->attitude_grade === 'Baik') bg-green-100 text-green-800
                                         @elseif($grade->attitude_grade === 'Cukup') bg-yellow-100 text-yellow-800
                                         @else bg-red-100 text-red-800
                                         @endif">
                                {{ $grade->attitude_grade }}
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <!-- Final Grade -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                            $nilaiAkhir = null;
                            if (!is_null($grade->final_grade)) {
                            $nilaiAkhir = $grade->final_grade;
                            } elseif (isset($subjectSettings[$grade->subject_id])) {
                            $setting = $subjectSettings[$grade->subject_id];
                            $nilaiAkhir = ($grade->assignment_grade * $setting->assignment_weight +
                            $grade->uts_grade * $setting->uts_weight +
                            $grade->uas_grade * $setting->uas_weight) / 100;
                            }
                            @endphp

                            @if($nilaiAkhir !== null)
                            <span class="text-lg font-bold {{ $nilaiAkhir >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($nilaiAkhir, 2) }}
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($nilaiAkhir !== null)
                            @if($nilaiAkhir >= 75)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Lulus
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Tidak Lulus
                            </span>
                            @endif
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Belum Ada
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td class="px-6 py-12 text-center" colspan="7">
                            <div class="flex flex-col items-center">
                                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data nilai</h3>
                                <p class="text-gray-500">Nilai akademik semester genap belum tersedia.</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            </table>
        </div>
    </div>
</div>

<!-- Data for JavaScript -->
<script>
    // Pass PHP data to JavaScript
    window.gradesData = {
        ganjil: @json($gradesGanjil),
        genap: @json($gradesGenap)
    };
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const gradeFilter = document.getElementById('gradeFilter');
        const gradeRows = document.querySelectorAll('.grade-row');
        const tabButtons = document.querySelectorAll('.tab-button');
        const currentSemesterSpan = document.getElementById('current-semester');

        // Performance optimization: Lazy loading for large tables
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

        // Apply lazy loading to rows if there are many
        gradeRows.forEach(function(row, index) {
            if (index > 10) { // Only observe rows after first 10
                row.style.opacity = '0.8';
                row.style.transform = 'translateY(10px)';
                row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                observer.observe(row);
            }
        });

        // Debounced search for better performance
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                const searchTerm = searchInput.value.toLowerCase();

                gradeRows.forEach(function(row) {
                    const subject = row.getAttribute('data-subject');

                    if (subject.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }, 150); // 150ms debounce
        });

        // Grade filter functionality
        gradeFilter.addEventListener('change', function() {
            const selectedStatus = this.value;

            gradeRows.forEach(function(row) {
                const status = row.getAttribute('data-status');

                if (!selectedStatus || status === selectedStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Add hover effects and animations
        gradeRows.forEach(function(row) {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f9fafb';
            });

            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });

        // Add keyboard navigation for accessibility
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
            }
        });

        // Tab switching functionality
        tabButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const semester = this.getAttribute('data-semester');

                // Update tab button styles
                tabButtons.forEach(function(btn) {
                    btn.classList.remove('border-blue-500', 'text-blue-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                });
                this.classList.remove('border-transparent', 'text-gray-500');
                this.classList.add('border-blue-500', 'text-blue-600');

                // Update table visibility
                const tables = document.querySelectorAll('.semester-table');
                tables.forEach(function(table) {
                    table.classList.add('hidden');
                });
                document.getElementById(`table-${semester}`).classList.remove('hidden');

                // Update header text
                currentSemesterSpan.textContent = `Semester ${semester.charAt(0).toUpperCase() + semester.slice(1)}`;

                // Update statistics based on selected semester
                updateStatistics(semester);

                // Reinitialize search and filter for new content
                initializeSearchAndFilter();
            });
        });

        // Function to update statistics based on semester
        function updateStatistics(semester) {
            const currentTable = document.getElementById(`table-${semester}`);
            const gradeRows = currentTable.querySelectorAll('.grade-row');

            let totalSubjects = gradeRows.length;
            let totalFinalGrade = 0;
            let subjectsWithGrades = 0;
            let passedSubjects = 0;
            let failedSubjects = 0;

            gradeRows.forEach(function(row) {
                const finalGradeCell = row.querySelector('td:nth-child(6)'); // Final grade column
                const finalGradeText = finalGradeCell.textContent.trim();

                if (finalGradeText !== '-') {
                    const finalGrade = parseFloat(finalGradeText);
                    if (!isNaN(finalGrade)) {
                        totalFinalGrade += finalGrade;
                        subjectsWithGrades++;

                        if (finalGrade >= 75) {
                            passedSubjects++;
                        } else {
                            failedSubjects++;
                        }
                    }
                }
            });

            const averageGrade = subjectsWithGrades > 0 ? totalFinalGrade / subjectsWithGrades : 0;

            // Update statistics display
            document.getElementById('stat-total-subjects').textContent = totalSubjects;
            document.getElementById('stat-average-grade').textContent = averageGrade.toFixed(1);
            document.getElementById('stat-passed-subjects').textContent = passedSubjects;
            document.getElementById('stat-failed-subjects').textContent = failedSubjects;
        }

        // Initialize search and filter functionality
        function initializeSearchAndFilter() {
            const currentGradeRows = document.querySelectorAll('.semester-table:not(.hidden) .grade-row');

            // Debounced search for better performance
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    const searchTerm = searchInput.value.toLowerCase();

                    currentGradeRows.forEach(function(row) {
                        const subject = row.getAttribute('data-subject');

                        if (subject.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }, 150); // 150ms debounce
            });

            // Grade filter functionality
            gradeFilter.addEventListener('change', function() {
                const selectedStatus = this.value;

                currentGradeRows.forEach(function(row) {
                    const status = row.getAttribute('data-status');

                    if (!selectedStatus || status === selectedStatus) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // Initialize for first load
        initializeSearchAndFilter();

        // Performance monitoring
        const totalRows = gradeRows.length;
        if (totalRows > 20) {
            console.log(`Performance mode: ${totalRows} grade rows detected`);
        }

        console.log('Student grades view loaded with performance optimizations for large datasets');
    });
</script>
@endsection
@extends('layouts.dashboard')

@section('title', 'Laporan Persemester')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Laporan Persemester</h1>
        <p class="text-gray-600">Laporan data akademik per semester untuk keperluan pelaporan ke pemerintah</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <form method="GET" action="{{ route('kepala.laporan-persemester') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-64">
                <label for="academic_year_id" class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran</label>
                <select name="academic_year_id" id="academic_year_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ $selectedYear && $selectedYear->id == $year->id ? 'selected' : '' }}>
                        {{ $year->year }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-48">
                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                <select name="semester" id="semester" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="Ganjil" {{ $selectedSemester == 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                    <option value="Genap" {{ $selectedSemester == 'Genap' ? 'selected' : '' }}>Semester Genap</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    Tampilkan Data
                </button>

                @if($selectedYear)
                <div class="flex gap-2">
                    <a href="{{ route('kepala.download-laporan-persemester', ['academic_year_id' => $selectedYear->id, 'semester' => $selectedSemester, 'format' => 'pdf']) }}"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download PDF
                    </a>
                    <a href="{{ route('kepala.download-laporan-persemester', ['academic_year_id' => $selectedYear->id, 'semester' => $selectedSemester, 'format' => 'excel']) }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Excel
                    </a>
                </div>
                @endif
            </div>
        </form>
    </div>

    @if($selectedYear)
    <!-- Report Content -->
    <div class="space-y-8">
        <!-- Header Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">LAPORAN PERSEMESTER</h2>
                <p class="text-lg text-gray-600 mb-1">Tahun Ajaran: {{ $selectedYear->year }}</p>
                <p class="text-lg text-gray-600">Semester: {{ $selectedSemester }}</p>
                <p class="text-sm text-gray-500 mt-2">Dibuat pada: {{ now()->format('d F Y H:i') }}</p>
            </div>
        </div>

        <!-- 1. Data Siswa -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                1. Data Siswa
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-blue-600">{{ $studentData['total_students'] }}</div>
                    <div class="text-sm text-blue-700">Total Siswa</div>
                </div>
                <div class="bg-indigo-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-indigo-600">{{ $studentData['total_male'] }}</div>
                    <div class="text-sm text-indigo-700">Laki-laki</div>
                </div>
                <div class="bg-pink-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-pink-600">{{ $studentData['total_female'] }}</div>
                    <div class="text-sm text-pink-700">Perempuan</div>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-yellow-600">{{ $studentData['transfer_students'] }}</div>
                    <div class="text-sm text-yellow-700">Siswa Pindahan</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-green-600">{{ $studentData['mutations_in'] }}</div>
                    <div class="text-sm text-green-700">Mutasi Masuk</div>
                </div>
                <div class="bg-red-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-red-600">{{ $studentData['mutations_out'] }}</div>
                    <div class="text-sm text-red-700">Mutasi Keluar</div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Siswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laki-laki</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perempuan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($studentData['students_per_grade_major'] as $gradeMajor)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $gradeMajor['grade_name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $gradeMajor['major_name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $gradeMajor['class_count'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $gradeMajor['student_count'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600">{{ $gradeMajor['male_count'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-pink-600">{{ $gradeMajor['female_count'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Data Guru & Kelas -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                2. Data Guru & Kelas
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-blue-600">{{ $teacherData['teacher_count'] }}</div>
                    <div class="text-sm text-blue-700">Jumlah Guru</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-green-600">{{ $teacherData['subjects_taught'] }}</div>
                    <div class="text-sm text-green-700">Mata Pelajaran</div>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-purple-600">{{ $teacherData['classroom_count'] }}</div>
                    <div class="text-sm text-purple-700">Kelas</div>
                </div>
            </div>
        </div>

        <!-- 3. Data Absensi -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                3. Data Absensi
            </h3>

            <div class="mb-6">
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-yellow-600">{{ number_format($attendanceData['overall_attendance_percentage'], 1) }}%</div>
                    <div class="text-sm text-yellow-700">Persentase Kehadiran Rata-rata</div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Siswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sakit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Izin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alpha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($attendanceData['attendance_per_class'] as $attendance)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $attendance['classroom'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance['total_students'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">{{ $attendance['sick'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600">{{ $attendance['permit'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">{{ $attendance['absent'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $attendance['attendance_percentage'] }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. Data Pengaturan Akademik -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                4. Data Pengaturan Akademik
            </h3>

            @if($academicSettings['semester_weights'])
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Bobot Semester untuk Nilai Akhir Tahun</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-blue-600">{{ $academicSettings['semester_weights']['ganjil_weight'] }}%</div>
                        <div class="text-sm text-blue-700">Bobot Semester Ganjil</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-green-600">{{ $academicSettings['semester_weights']['genap_weight'] }}%</div>
                        <div class="text-sm text-green-700">Bobot Semester Genap</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Batas Maksimal Mata Pelajaran Gagal</h4>
                <div class="bg-red-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-red-600">{{ $academicSettings['max_failed_subjects'] }}</div>
                    <div class="text-sm text-red-700">Mata Pelajaran</div>
                </div>
            </div>

            @if(count($academicSettings['subject_settings']) > 0)
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4">KKM dan Bobot Mata Pelajaran</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KKM</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot Tugas (%)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot UTS (%)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot UAS (%)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($academicSettings['subject_settings'] as $setting)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $setting['subject_name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $setting['kkm'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $setting['assignment_weight'] }}%</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $setting['uts_weight'] }}%</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $setting['uas_weight'] }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- 5. Data Nilai Rapor -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                5. Data Nilai Rapor
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-blue-600">{{ $gradeData['total_subjects'] }}</div>
                    <div class="text-sm text-blue-700">Mata Pelajaran</div>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-purple-600">{{ $gradeData['average_grade'] }}</div>
                    <div class="text-sm text-purple-700">Rata-rata Nilai</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-green-600">{{ count($gradeData['grades_by_class']) }}</div>
                    <div class="text-sm text-green-700">Jumlah Kelas</div>
                </div>
            </div>

            @if(count($gradeData['grades_by_class']) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata Nilai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Siswa</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($gradeData['grades_by_class'] as $grade)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $grade['classroom_name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $grade['grade_level'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $grade['major_name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $grade['average_grade'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $grade['total_students'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(count($gradeData['grades_by_grade_level']) > 0)
            <div class="mt-8">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Rata-rata Nilai per Tingkatan</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata Nilai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Siswa</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($gradeData['grades_by_grade_level'] as $grade)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $grade['grade_name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $grade['average_grade'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $grade['total_students'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            @else
            <div class="text-center py-8 text-gray-500">
                <p>Tidak ada data nilai untuk semester ini</p>
            </div>
            @endif
        </div>

        <!-- 6. Data Ekstrakurikuler -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                6. Data Ekstrakurikuler
            </h3>

            <div class="mb-6">
                <div class="bg-indigo-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-indigo-600">{{ $extracurricularData['total_participants'] }}</div>
                    <div class="text-sm text-indigo-700">Total Peserta Ekstrakurikuler</div>
                </div>
            </div>

            @if(count($extracurricularData['extracurriculars']) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ekstrakurikuler</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Peserta</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($extracurricularData['extracurriculars'] as $extracurricular)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $extracurricular['name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $extracurricular['category'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $extracurricular['student_count'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <p>Tidak ada data ekstrakurikuler untuk semester ini</p>
            </div>
            @endif
        </div>

        <!-- 7. Data Kenaikan Kelas (hanya semester genap) -->
        @if($selectedSemester == 'Genap' && $promotionData)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                7. Data Kenaikan Kelas & Kelulusan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-green-600">{{ $promotionData['promoted_students'] }}</div>
                    <div class="text-sm text-green-700">Siswa Naik Kelas</div>
                </div>
                <div class="bg-red-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-red-600">{{ $promotionData['retained_students'] }}</div>
                    <div class="text-sm text-red-700">Siswa Tinggal Kelas</div>
                </div>
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-blue-600">{{ $promotionData['graduated_students'] }}</div>
                    <div class="text-sm text-blue-700">Siswa Lulus (XII)</div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Pilih Tahun Ajaran dan Semester</h3>
        <p class="mt-1 text-sm text-gray-500">Silakan pilih tahun ajaran dan semester untuk melihat laporan persemester.</p>
    </div>
    @endif
</div>
@endsection
@extends('layouts.dashboard')

@section('title', 'Konversi Nilai Siswa Pindahan')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="inline-flex items-center justify-center w-10 h-10 bg-indigo-100 rounded-full">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">Konversi Nilai Siswa Pindahan</h1>
                </div>
                <p class="text-gray-600">{{ $transferStudent->full_name }} - {{ $transferStudent->registration_number }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $transferStudent->status_badge_class }}">
                    {{ $transferStudent->status_label }}
                </span>
            </div>
        </div>
    </div>

    <!-- Student Info -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900">Informasi Siswa</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="space-y-2">
                <p class="text-sm font-semibold text-gray-700">Nama</p>
                <p class="font-medium text-gray-900">{{ $transferStudent->full_name }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-sm font-semibold text-gray-700">Sekolah Asal</p>
                <p class="font-medium text-gray-900">{{ $transferStudent->previous_school_name }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-sm font-semibold text-gray-700">Jurusan Tujuan</p>
                <p class="font-medium text-gray-900">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $transferStudent->desired_major === 'IPA' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                        {{ $transferStudent->desired_major }}
                    </span>
                </p>
            </div>
            <div class="space-y-2">
                <p class="text-sm font-semibold text-gray-700">Skala Nilai</p>
                <p class="font-medium text-gray-900">{{ $transferStudent->grade_scale_label ?? $transferStudent->grade_scale }}</p>
            </div>
        </div>

        <!-- Jurusan Info -->
        <div class="mt-6 p-4 bg-gradient-to-r {{ $transferStudent->desired_major === 'IPA' ? 'from-blue-50 to-indigo-50' : 'from-green-50 to-emerald-50' }} border border-gray-200 rounded-xl">
            <div class="flex items-center gap-3 mb-3">
                <div class="flex items-center justify-center w-6 h-6 {{ $transferStudent->desired_major === 'IPA' ? 'bg-blue-100' : 'bg-green-100' }} rounded-full">
                    <svg class="w-3 h-3 {{ $transferStudent->desired_major === 'IPA' ? 'text-blue-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Mata Pelajaran {{ $transferStudent->desired_major }}</h3>
            </div>
            <p class="text-sm text-gray-700">
                @if($transferStudent->desired_major === 'IPA')
                Mata pelajaran yang akan dikonversi: <strong>Matematika, Fisika, Kimia, Biologi</strong> dan mata pelajaran umum lainnya.
                @elseif($transferStudent->desired_major === 'IPS')
                Mata pelajaran yang akan dikonversi: <strong>Matematika, Ekonomi, Geografi, Sejarah, Sosiologi</strong> dan mata pelajaran umum lainnya.
                @endif
            </p>
        </div>
    </div>

    <!-- Grade Conversion Form -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900">Konversi Nilai</h2>
            </div>
            @if($transferStudent->hasGradeConversion())
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-xl">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm font-semibold text-green-800">Nilai sudah dikonversi</span>
                </div>
                <div class="px-4 py-2 bg-blue-50 border border-blue-200 rounded-xl">
                    <span class="text-sm font-semibold text-blue-800">Rata-rata: {{ $transferStudent->getAverageGrade() }}</span>
                </div>
            </div>
            @endif
        </div>

        @if (session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-8">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-green-800 font-semibold">Berhasil!</h3>
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-8">
            <div class="flex items-center gap-3 mb-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-red-800 font-semibold">Terjadi kesalahan</h3>
                    <p class="text-red-700 text-sm">Mohon perbaiki field yang ditandai di bawah ini</p>
                </div>
            </div>
            <ul class="list-disc list-inside text-red-700 space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Auto Convert Section -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-blue-900">Konversi Otomatis</h3>
            </div>
            <p class="text-blue-800 mb-6">Input nilai asli dari sekolah asal, sistem akan otomatis mengkonversi ke skala 0-100.</p>

            <form method="POST" action="{{ route('admin.transfer.auto-convert-grades', $transferStudent) }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($subjects as $subject)
                    <div class="space-y-3">
                        <label for="original_grades_{{ $subject->id }}" class="block text-sm font-semibold text-gray-700">
                            {{ $subject->name }}
                        </label>
                        <input type="text"
                            id="original_grades_{{ $subject->id }}"
                            name="original_grades[{{ $subject->name }}]"
                            value="{{ $transferStudent->original_grades[$subject->name] ?? '' }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Nilai asli">
                        <p class="text-xs text-gray-500">
                            @if($transferStudent->grade_scale === '0-4')
                            Masukkan nilai 0-4
                            @elseif($transferStudent->grade_scale === 'A-F')
                            Masukkan nilai A, B, C, D, E, F
                            @elseif($transferStudent->grade_scale === 'Predikat')
                            Masukkan: Sangat Baik, Baik, Cukup, Kurang
                            @else
                            Masukkan nilai 0-100
                            @endif
                        </p>
                    </div>
                    @endforeach
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Konversi Otomatis
                        </span>
                    </button>
                    <span class="text-sm text-gray-600">Sistem akan otomatis mengkonversi ke skala 0-100</span>
                </div>
            </form>
        </div>

        <!-- Manual Convert Section -->
        <div class="bg-gradient-to-r from-gray-50 to-slate-50 border border-gray-200 rounded-2xl p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Konversi Manual (Opsional)</h3>
            </div>
            <p class="text-gray-700 mb-6">Jika ingin mengubah hasil konversi otomatis, bisa edit manual di bawah ini. Input nilai asli sesuai skala yang dipilih siswa.</p>

            <!-- Panduan Penggunaan -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <h4 class="font-semibold text-blue-900 mb-3">📋 Panduan Pengisian Konversi Manual</h4>
                <div class="text-sm text-blue-800 space-y-2">
                    @if($transferStudent->grade_scale === 'A-F')
                    <p><strong>Skala A-F:</strong> Pilih huruf nilai dari dropdown (A, A-, B+, B, B-, C+, C, C-, D+, D, D-, E, F)</p>
                    <p><strong>Contoh:</strong> Jika nilai asli "B+", pilih "B+ (80-84)" dari dropdown</p>
                    <p><strong>Konversi:</strong> A=90, A-=85, B+=85, B=80, B-=75, C+=65, C=60, C-=55, D+=65, D=60, D-=55, E=50, F=0</p>
                    @elseif($transferStudent->grade_scale === 'Predikat')
                    <p><strong>Skala Predikat:</strong> Pilih predikat dari dropdown (Sangat Baik, Baik, Cukup, Kurang, Sangat Kurang)</p>
                    <p><strong>Contoh:</strong> Jika nilai asli "Baik", pilih "Baik (80-89)" dari dropdown</p>
                    <p><strong>Konversi:</strong> Sangat Baik=90, Baik=80, Cukup=70, Kurang=60, Sangat Kurang=50</p>
                    @elseif($transferStudent->grade_scale === '0-4')
                    <p><strong>Skala 0-4:</strong> Masukkan angka desimal antara 0.00 sampai 4.00</p>
                    <p><strong>Contoh:</strong> Jika nilai asli 3.5, masukkan "3.50"</p>
                    <p><strong>Konversi:</strong> Nilai × 25 (contoh: 3.5 × 25 = 87.5)</p>
                    @else
                    <p><strong>Skala 0-100:</strong> Masukkan angka antara 0 sampai 100</p>
                    <p><strong>Contoh:</strong> Jika nilai asli 85, masukkan "85"</p>
                    <p><strong>Konversi:</strong> Nilai tetap sama (85 = 85)</p>
                    @endif
                    <p><strong>Tips:</strong> Gunakan fitur "Konversi Otomatis" untuk mengkonversi semua nilai sekaligus, atau isi manual di kolom "Nilai Konversi"</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.transfer.save-grade-conversion', $transferStudent) }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($subjects as $subject)
                    <div class="space-y-4 p-4 bg-white rounded-xl border border-gray-200">
                        <label class="block text-sm font-semibold text-gray-700">{{ $subject->name }}</label>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nilai Asli ({{ $transferStudent->grade_scale_label }})
                                </label>
                                @if($transferStudent->grade_scale === 'A-F')
                                <select name="original_grades[{{ $subject->name }}]"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Pilih Nilai</option>
                                    <option value="A" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'A' ? 'selected' : '' }}>A (90-100)</option>
                                    <option value="A-" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'A-' ? 'selected' : '' }}>A- (85-89)</option>
                                    <option value="B+" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'B+' ? 'selected' : '' }}>B+ (80-84)</option>
                                    <option value="B" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'B' ? 'selected' : '' }}>B (75-79)</option>
                                    <option value="B-" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'B-' ? 'selected' : '' }}>B- (70-74)</option>
                                    <option value="C+" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'C+' ? 'selected' : '' }}>C+ (65-69)</option>
                                    <option value="C" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'C' ? 'selected' : '' }}>C (60-64)</option>
                                    <option value="C-" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'C-' ? 'selected' : '' }}>C- (55-59)</option>
                                    <option value="D+" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'D+' ? 'selected' : '' }}>D+ (50-54)</option>
                                    <option value="D" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'D' ? 'selected' : '' }}>D (45-49)</option>
                                    <option value="D-" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'D-' ? 'selected' : '' }}>D- (40-44)</option>
                                    <option value="E" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'E' ? 'selected' : '' }}>E (35-39)</option>
                                    <option value="F" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'F' ? 'selected' : '' }}>F (0-34)</option>
                                </select>
                                @elseif($transferStudent->grade_scale === 'Predikat')
                                <select name="original_grades[{{ $subject->name }}]"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Pilih Predikat</option>
                                    <option value="Sangat Baik" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik (90-100)</option>
                                    <option value="Baik" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'Baik' ? 'selected' : '' }}>Baik (80-89)</option>
                                    <option value="Cukup" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'Cukup' ? 'selected' : '' }}>Cukup (70-79)</option>
                                    <option value="Kurang" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'Kurang' ? 'selected' : '' }}>Kurang (60-69)</option>
                                    <option value="Sangat Kurang" {{ ($transferStudent->original_grades[$subject->name] ?? '') === 'Sangat Kurang' ? 'selected' : '' }}>Sangat Kurang (0-59)</option>
                                </select>
                                @elseif($transferStudent->grade_scale === '0-4')
                                <input type="number"
                                    name="original_grades[{{ $subject->name }}]"
                                    value="{{ $transferStudent->original_grades[$subject->name] ?? '' }}"
                                    step="0.01" min="0" max="4"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="0.00 - 4.00">
                                @else
                                <input type="number"
                                    name="original_grades[{{ $subject->name }}]"
                                    value="{{ $transferStudent->original_grades[$subject->name] ?? '' }}"
                                    step="0.01" min="0" max="100"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="0 - 100">
                                @endif
                            </div>

                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nilai Konversi (0-100)
                                </label>
                                <input type="number"
                                    name="converted_grades[{{ $subject->name }}]"
                                    value="{{ $transferStudent->converted_grades[$subject->name] ?? '' }}"
                                    step="0.01" min="0" max="100"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="0 - 100">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="space-y-3">
                    <label for="conversion_notes" class="block text-sm font-semibold text-gray-700">Catatan Konversi</label>
                    <textarea id="conversion_notes" name="conversion_notes" rows="4"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Catatan tentang proses konversi nilai...">{{ $transferStudent->conversion_notes }}</textarea>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Konversi Manual
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Status Aplikasi</h3>
                    <p class="text-sm text-gray-600">
                        @if($transferStudent->isEligibleForApproval())
                        <span class="inline-flex items-center gap-2 text-green-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Siswa memenuhi syarat untuk disetujui
                        </span>
                        @else
                        <span class="inline-flex items-center gap-2 text-yellow-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Siswa belum memenuhi syarat (dokumen atau konversi nilai belum lengkap)
                        </span>
                        @endif
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.transfer.show', $transferStudent) }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Detail
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto convert all grades when auto convert form is submitted
        document.addEventListener('DOMContentLoaded', function() {
            const autoConvertForm = document.querySelector('form[action*="auto-convert-grades"]');
            if (autoConvertForm) {
                autoConvertForm.addEventListener('submit', function(e) {
                    // Show loading state
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    submitButton.innerHTML = '<span class="flex items-center gap-2"><svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Converting...</span>';
                    submitButton.disabled = true;
                });
            }
        });
    </script>
    @endsection
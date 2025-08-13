@extends('layouts.dashboard')

@section('title', 'Detail Siswa Pindahan')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Siswa Pindahan</h1>
                </div>
                <p class="text-gray-600">Nomor Registrasi: <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $transferStudent->registration_number }}</span></p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $transferStudent->status_badge_class }}">
                    {{ $transferStudent->status_label }}
                </span>
                <a href="{{ route('admin.transfer.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl font-medium transition-colors">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-6">
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

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-red-800 font-semibold">Terjadi Kesalahan</h3>
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Application Details -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Basic Information -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Informasi Siswa</h2>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                            <p class="text-gray-900 font-medium">{{ $transferStudent->full_name }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">NISN</label>
                            <p class="text-gray-900 font-mono">{{ $transferStudent->nisn }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">NIS Sekolah Asal</label>
                            <p class="text-gray-900">{{ $transferStudent->nis_previous ?? '-' }}</p>
                        </div>
                        @if($transferStudent->status === 'approved')
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">NIS yang Akan Digenerate</label>
                            <p class="text-gray-900 font-mono bg-blue-50 px-3 py-2 rounded-lg">
                                {{ \App\Services\NISGeneratorService::getNISFormatExample() }}
                            </p>
                            <p class="text-xs text-gray-500">Format: YY + 6 digit urutan (berbeda dari NIS sebelumnya)</p>
                        </div>
                        @endif
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Email Siswa</label>
                            <p class="text-gray-900">{{ $transferStudent->email }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Tempat Lahir</label>
                            <p class="text-gray-900">{{ $transferStudent->birth_place }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Tanggal Lahir</label>
                            <p class="text-gray-900">{{ $transferStudent->birth_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Jenis Kelamin</label>
                            <p class="text-gray-900">{{ $transferStudent->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Agama</label>
                            <p class="text-gray-900">{{ $transferStudent->religion }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Nomor Telepon</label>
                            <p class="text-gray-900">{{ $transferStudent->phone_number }}</p>
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Alamat</label>
                            <p class="text-gray-900">{{ $transferStudent->address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parent Information -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Informasi Orang Tua/Wali</h2>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Nama Orang Tua/Wali</label>
                            <p class="text-gray-900 font-medium">{{ $transferStudent->parent_name }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Nomor Telepon</label>
                            <p class="text-gray-900">{{ $transferStudent->parent_phone }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Email Orang Tua</label>
                            <p class="text-gray-900">{{ $transferStudent->parent_email }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Pekerjaan</label>
                            <p class="text-gray-900">{{ $transferStudent->parent_occupation ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Alamat Orang Tua</label>
                            <p class="text-gray-900">{{ $transferStudent->parent_address ?? 'Sama dengan alamat siswa' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- School Information -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-yellow-50 to-orange-50 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 bg-yellow-100 rounded-full">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Informasi Sekolah & Pindahan</h2>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <p class="text-sm font-semibold text-gray-700">Sekolah Asal</p>
                            <p class="font-medium text-gray-900">{{ $transferStudent->previous_school_name }}</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm font-semibold text-gray-700">Kelas & Jurusan Asal</p>
                            <p class="font-medium text-gray-900">{{ $transferStudent->previous_grade }} {{ $transferStudent->previous_major }}</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm font-semibold text-gray-700">Tujuan</p>
                            <p class="font-medium text-gray-900">{{ $transferStudent->desired_grade }} {{ $transferStudent->desired_major }}</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm font-semibold text-gray-700">Skala Nilai</p>
                            <p class="font-medium text-gray-900">{{ $transferStudent->grade_scale_label }}</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm font-semibold text-gray-700">Status Konversi</p>
                            <p class="font-medium">
                                @if($transferStudent->hasGradeConversion())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Sudah dikonversi
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    Belum dikonversi
                                </span>
                                @endif
                            </p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm font-semibold text-gray-700">Rata-rata Nilai</p>
                            <p class="font-medium text-gray-900">{{ $transferStudent->getAverageGrade() }}</p>
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
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 bg-purple-100 rounded-full">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Dokumen yang Diupload</h2>
                    </div>
                </div>
                <div class="p-8">
                    <div class="space-y-4">
                        @php
                        $requiredDocuments = $transferStudent->getRequiredDocuments();
                        @endphp
                        @foreach($requiredDocuments as $field => $label)
                        <div class="flex items-center justify-between p-6 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $transferStudent->{$field . '_file'} ? 'bg-green-100' : 'bg-red-100' }}">
                                    @if($transferStudent->{$field . '_file'})
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @else
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $label }}</h4>
                                    @if($transferStudent->{$field . '_file'})
                                    <p class="text-sm text-green-600">✓ Dokumen tersedia</p>
                                    @else
                                    <p class="text-sm text-red-600">✗ Dokumen belum diupload</p>
                                    @endif
                                </div>
                            </div>
                            @if($transferStudent->{$field . '_file'})
                            @php
                            $extension = pathinfo($transferStudent->{$field . '_file'}, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp
                            <div class="flex gap-2">
                                @if($isImage)
                                <button onclick="previewImage('{{ route('admin.transfer.download', ['transferStudent' => $transferStudent, 'documentType' => $field]) }}', '{{ $label }}')"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                    Preview
                                </button>
                                @endif
                                <a href="{{ route('admin.transfer.download', ['transferStudent' => $transferStudent, 'documentType' => $field]) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                    Download
                                </a>
                            </div>
                            @else
                            <span class="text-gray-400 text-sm">Tidak tersedia</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Grade Conversion -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 bg-indigo-100 rounded-full">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Konversi Nilai</h2>
                        </div>
                        @if(!$transferStudent->hasGradeConversion())
                        <a href="{{ route('admin.transfer.grade-conversion', $transferStudent) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-semibold transition-colors">
                            Input Konversi Nilai
                        </a>
                        @else
                        <a href="{{ route('admin.transfer.grade-conversion', $transferStudent) }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl text-sm font-semibold transition-colors">
                            Edit Konversi Nilai
                        </a>
                        @endif
                    </div>
                </div>
                <div class="p-8">
                    @if($transferStudent->original_grades && $transferStudent->converted_grades)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 font-semibold text-gray-700">Mata Pelajaran</th>
                                    <th class="text-center py-3 font-semibold text-gray-700">Nilai Asal</th>
                                    <th class="text-center py-3 font-semibold text-gray-700">Nilai Konversi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transferStudent->original_grades as $subject => $originalGrade)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 text-gray-900 font-medium">{{ $subject }}</td>
                                    <td class="py-3 text-center text-gray-900">{{ $originalGrade }}</td>
                                    <td class="py-3 text-center text-gray-900 font-semibold">
                                        {{ $transferStudent->converted_grades[$subject] ?? '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($transferStudent->conversion_notes)
                    <div class="mt-6 bg-gray-50 rounded-xl p-4">
                        <h4 class="font-semibold text-gray-900 mb-2">Catatan Konversi:</h4>
                        <p class="text-gray-700 text-sm">{{ $transferStudent->conversion_notes }}</p>
                    </div>
                    @endif
                    @else
                    <div class="text-center py-12 text-gray-500">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Konversi nilai belum dilakukan</h3>
                        <p class="text-sm text-gray-400 mb-6">Konversi nilai harus dilakukan sebelum approval</p>
                        <a href="{{ route('admin.transfer.grade-conversion', $transferStudent) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-semibold transition-colors">
                            Mulai Konversi Nilai
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Panel -->
        <div class="space-y-8">
            <!-- Status Update -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-6 bg-gradient-to-r from-red-50 to-pink-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Update Status</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.transfer.update', $transferStudent) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-3">
                            <label for="status" class="block text-sm font-semibold text-gray-700">Status</label>
                            <select id="status" name="status" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="pending" {{ $transferStudent->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="approved" {{ $transferStudent->status === 'approved' ? 'selected' : '' }}
                                    {{ !$transferStudent->isEligibleForApproval() ? 'disabled' : '' }}>
                                    Disetujui {{ !$transferStudent->isEligibleForApproval() ? '(Belum memenuhi syarat)' : '' }}
                                </option>
                                <option value="rejected" {{ $transferStudent->status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            @if(!$transferStudent->isEligibleForApproval())
                            <div class="flex items-center gap-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <p class="text-sm text-red-700">
                                    Siswa belum memenuhi syarat untuk disetujui. Pastikan dokumen lengkap dan konversi nilai sudah dilakukan.
                                </p>
                            </div>
                            @endif
                        </div>

                        <div class="space-y-3">
                            <label for="notes" class="block text-sm font-semibold text-gray-700">Catatan</label>
                            <textarea id="notes" name="notes" rows="4"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Catatan untuk siswa...">{{ $transferStudent->notes }}</textarea>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors {{ !$transferStudent->isEligibleForApproval() && request('status') === 'approved' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ !$transferStudent->isEligibleForApproval() && request('status') === 'approved' ? 'disabled' : '' }}>
                                Update Status
                            </button>
                            @if(!$transferStudent->isEligibleForApproval())
                            <a href="{{ route('admin.transfer.grade-conversion', $transferStudent) }}"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors text-center">
                                Lakukan Konversi Nilai
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Requirements Check -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-6 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Kelayakan Penerimaan</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $transferStudent->hasAllRequiredDocuments() ? 'bg-green-100' : 'bg-red-100' }}">
                                    @if($transferStudent->hasAllRequiredDocuments())
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Dokumen Lengkap</h4>
                                    <p class="text-sm text-gray-600">{{ $transferStudent->hasAllRequiredDocuments() ? 'Semua dokumen tersedia' : 'Beberapa dokumen belum diupload' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $transferStudent->hasGradeConversion() ? 'bg-green-100' : 'bg-yellow-100' }}">
                                    @if($transferStudent->hasGradeConversion())
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Konversi Nilai</h4>
                                    <p class="text-sm text-gray-600">
                                        @if($transferStudent->hasGradeConversion())
                                        Nilai sudah dikonversi ke skala 0-100
                                        @else
                                        Konversi nilai harus dilakukan sebelum approval
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Eligibility Status -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Status Kelayakan</h3>
                </div>
                <div class="p-6">
                    @if($transferStudent->isEligibleForApproval())
                    <div class="flex items-center p-4 bg-green-50 border border-green-200 rounded-xl">
                        <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-green-800 font-semibold">Memenuhi Persyaratan</span>
                    </div>
                    @else
                    <div class="flex items-center p-4 bg-red-50 border border-red-200 rounded-xl">
                        <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="text-red-800 font-semibold">Belum Memenuhi Persyaratan</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Processing Info -->
            <div class="bg-gray-50 rounded-2xl p-6">
                <h4 class="font-semibold text-gray-900 mb-4">Informasi Proses</h4>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span class="font-medium">Tanggal Daftar:</span>
                        <span>{{ $transferStudent->submitted_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($transferStudent->processed_at)
                    <div class="flex justify-between">
                        <span class="font-medium">Tanggal Proses:</span>
                        <span>{{ $transferStudent->processed_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                    @if($transferStudent->processedBy)
                    <div class="flex justify-between">
                        <span class="font-medium">Diproses oleh:</span>
                        <span>{{ $transferStudent->processedBy->name }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl max-w-4xl max-h-full overflow-auto">
        <div class="flex items-center justify-between p-6 border-b">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900"></h3>
            <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <img id="modalImage" src="" alt="Preview" class="max-w-full max-h-96 object-contain">
        </div>
    </div>
</div>

<script>
    function previewImage(imageUrl, title) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });

    // Form validation for approval
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const submitButton = document.querySelector('button[type="submit"]');
        const isEligible = {
            {
                $transferStudent - > isEligibleForApproval() ? 'true' : 'false'
            }
        };

        statusSelect.addEventListener('change', function() {
            if (this.value === 'approved' && !isEligible) {
                alert('⚠️ Siswa belum memenuhi syarat untuk disetujui. Pastikan dokumen lengkap dan konversi nilai sudah dilakukan.');
                this.value = 'pending';
                return false;
            }
        });

        // Prevent form submission if trying to approve without eligibility
        document.querySelector('form').addEventListener('submit', function(e) {
            if (statusSelect.value === 'approved' && !isEligible) {
                e.preventDefault();
                alert('⚠️ Siswa belum memenuhi syarat untuk disetujui. Pastikan dokumen lengkap dan konversi nilai sudah dilakukan.');
                return false;
            }
        });
    });
</script>
@endsection
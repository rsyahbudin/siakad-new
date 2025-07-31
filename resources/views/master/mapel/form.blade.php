@extends('layouts.dashboard')
@section('title', isset($mapel) ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran')
@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ isset($mapel) ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran' }}</h1>
                <p class="text-gray-600 mt-1">{{ isset($mapel) ? 'Perbarui informasi mata pelajaran' : 'Tambahkan mata pelajaran baru ke sistem' }}</p>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form method="POST" action="{{ isset($mapel) ? route('mapel.update', $mapel) : route('mapel.store') }}" class="p-6 space-y-6">
            @csrf
            @if(isset($mapel))
            @method('PUT')
            @endif

            <!-- Informasi Dasar Section -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-200">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Dasar</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Mapel -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Kode Mata Pelajaran
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="code" value="{{ old('code', $mapel->code ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('code') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                            placeholder="Contoh: MAT, BIN, ING">
                        @error('code')
                        <div class="flex items-center gap-2 mt-2 text-red-600 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </div>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Kode unik untuk identifikasi mata pelajaran (maksimal 10 karakter)</p>
                    </div>

                    <!-- Nama Mapel -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Nama Mata Pelajaran
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $mapel->name ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                            placeholder="Contoh: Matematika, Bahasa Indonesia, Bahasa Inggris">
                        @error('name')
                        <div class="flex items-center gap-2 mt-2 text-red-600 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </div>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Nama lengkap mata pelajaran (maksimal 100 karakter)</p>
                    </div>
                </div>
            </div>

            <!-- Jurusan Section -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-200">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900">Kategori Jurusan</h2>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Jurusan
                        <span class="text-gray-500 text-xs ml-1">(Opsional)</span>
                    </label>
                    <select name="major_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('major_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        <option value="">- Pilih Jurusan (Opsional) -</option>
                        @foreach($majors as $major)
                        <option value="{{ $major->id }}" {{ old('major_id', $mapel->major_id ?? '') == $major->id ? 'selected' : '' }}>
                            {{ $major->short_name }} - {{ $major->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('major_id')
                    <div class="flex items-center gap-2 mt-2 text-red-600 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $message }}
                    </div>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Pilih jurusan jika mata pelajaran khusus untuk jurusan tertentu, atau biarkan kosong untuk mata pelajaran umum</p>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-200">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900">Preview</h2>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900" id="preview-name">
                                {{ old('name', $mapel->name ?? 'Nama Mata Pelajaran') }}
                            </div>
                            <div class="text-sm text-gray-600">
                                Kode: <span class="font-medium" id="preview-code">{{ old('code', $mapel->code ?? 'KODE') }}</span>
                                @if(old('major_id', $mapel->major_id ?? ''))
                                • Jurusan: <span class="font-medium" id="preview-major">
                                    @php
                                    $selectedMajor = $majors->firstWhere('id', old('major_id', $mapel->major_id ?? ''));
                                    @endphp
                                    {{ $selectedMajor ? $selectedMajor->short_name : 'Jurusan' }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('mapel.index') }}" class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ isset($mapel) ? 'Update Mata Pelajaran' : 'Simpan Mata Pelajaran' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.querySelector('input[name="name"]');
        const codeInput = document.querySelector('input[name="code"]');
        const majorSelect = document.querySelector('select[name="major_id"]');
        const previewName = document.getElementById('preview-name');
        const previewCode = document.getElementById('preview-code');
        const previewMajor = document.getElementById('preview-major');

        // Update preview when inputs change
        nameInput.addEventListener('input', function() {
            previewName.textContent = this.value || 'Nama Mata Pelajaran';
        });

        codeInput.addEventListener('input', function() {
            previewCode.textContent = this.value || 'KODE';
        });

        majorSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (this.value && previewMajor) {
                previewMajor.textContent = selectedOption.text.split(' - ')[0];
            }
        });
    });
</script>
@endsection
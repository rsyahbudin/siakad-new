@extends('layouts.dashboard')
@section('title', isset($kelas) ? 'Edit Kelas' : 'Tambah Kelas')
@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ isset($kelas) ? 'Edit Kelas' : 'Tambah Kelas' }}</h1>
                <p class="text-gray-600 mt-1">{{ isset($kelas) ? 'Perbarui informasi kelas' : 'Tambahkan kelas baru ke sistem' }}</p>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form method="POST" action="{{ isset($kelas) ? route('kelas.update', $kelas) : route('kelas.store') }}" class="p-6 space-y-6">
            @csrf
            @if(isset($kelas))
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
                    <!-- Nama Kelas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Nama Kelas
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $kelas->name ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                            placeholder="Contoh: X IPA 1, XI IPS 2">
                        @error('name')
                        <div class="flex items-center gap-2 mt-2 text-red-600 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </div>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Nama kelas yang akan digunakan (maksimal 50 karakter)</p>
                    </div>

                    <!-- Kapasitas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Kapasitas
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="number" name="capacity" value="{{ old('capacity', $kelas->capacity ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('capacity') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                            placeholder="Contoh: 30, 35">
                        @error('capacity')
                        <div class="flex items-center gap-2 mt-2 text-red-600 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </div>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Jumlah maksimal siswa yang dapat ditampung dalam kelas</p>
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
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <select name="major_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('major_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        <option value="">- Pilih Jurusan -</option>
                        @foreach($majors as $jurusan)
                        <option value="{{ $jurusan->id }}" {{ old('major_id', $kelas->major_id ?? '') == $jurusan->id ? 'selected' : '' }}>
                            {{ $jurusan->short_name }} - {{ $jurusan->name }}
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
                    <p class="text-xs text-gray-500 mt-1">Pilih jurusan yang sesuai dengan kelas ini</p>
                </div>
            </div>

            <!-- Wali Kelas Section -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-200">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900">Wali Kelas</h2>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Pilih Wali Kelas
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <select name="homeroom_teacher_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('homeroom_teacher_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        <option value="">- Pilih Wali Kelas -</option>
                        @foreach($teachers as $guru)
                        <option value="{{ $guru->id }}" {{ old('homeroom_teacher_id', $homeroom_teacher_id ?? '') == $guru->id ? 'selected' : '' }}>
                            {{ $guru->full_name }} - {{ $guru->subject->name ?? 'Guru Umum' }}
                        </option>
                        @endforeach
                    </select>
                    @error('homeroom_teacher_id')
                    <div class="flex items-center gap-2 mt-2 text-red-600 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $message }}
                    </div>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Pilih guru yang akan menjadi wali kelas untuk kelas ini</p>
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
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900" id="preview-name">
                                {{ old('name', $kelas->name ?? 'Nama Kelas') }}
                            </div>
                            <div class="text-sm text-gray-600">
                                Kapasitas: <span class="font-medium" id="preview-capacity">{{ old('capacity', $kelas->capacity ?? '0') }}</span> siswa
                                @if(old('major_id', $kelas->major_id ?? ''))
                                • Jurusan: <span class="font-medium" id="preview-major">
                                    @php
                                    $selectedMajor = $majors->firstWhere('id', old('major_id', $kelas->major_id ?? ''));
                                    @endphp
                                    {{ $selectedMajor ? $selectedMajor->short_name : 'Jurusan' }}
                                </span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-600 mt-1">
                                Wali Kelas: <span class="font-medium" id="preview-teacher">
                                    @if(old('homeroom_teacher_id', $homeroom_teacher_id ?? ''))
                                    @php
                                    $selectedTeacher = $teachers->firstWhere('id', old('homeroom_teacher_id', $homeroom_teacher_id ?? ''));
                                    @endphp
                                    {{ $selectedTeacher ? $selectedTeacher->full_name : 'Belum dipilih' }}
                                    @else
                                    Belum dipilih
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('kelas.index') }}" class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ isset($kelas) ? 'Update Kelas' : 'Simpan Kelas' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.querySelector('input[name="name"]');
        const capacityInput = document.querySelector('input[name="capacity"]');
        const majorSelect = document.querySelector('select[name="major_id"]');
        const teacherSelect = document.querySelector('select[name="homeroom_teacher_id"]');
        const previewName = document.getElementById('preview-name');
        const previewCapacity = document.getElementById('preview-capacity');
        const previewMajor = document.getElementById('preview-major');
        const previewTeacher = document.getElementById('preview-teacher');

        // Update preview when inputs change
        nameInput.addEventListener('input', function() {
            previewName.textContent = this.value || 'Nama Kelas';
        });

        capacityInput.addEventListener('input', function() {
            previewCapacity.textContent = this.value || '0';
        });

        majorSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (this.value && previewMajor) {
                previewMajor.textContent = selectedOption.text.split(' - ')[0];
            }
        });

        teacherSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (this.value && previewTeacher) {
                previewTeacher.textContent = selectedOption.text.split(' - ')[0];
            } else if (previewTeacher) {
                previewTeacher.textContent = 'Belum dipilih';
            }
        });
    });
</script>
@endsection
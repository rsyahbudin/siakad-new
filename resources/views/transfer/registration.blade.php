@extends('layouts.app')

@section('title', 'Pendaftaran Siswa Pindahan')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pendaftaran Siswa Pindahan</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">Lengkapi formulir di bawah ini untuk mendaftar sebagai siswa pindahan. Semua data akan diverifikasi oleh admin sekolah.</p>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-8">
            <div class="flex items-center gap-3 mb-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-red-800 font-semibold">Terjadi kesalahan pada formulir</h3>
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

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <form method="POST" action="{{ route('transfer.register.store') }}" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf

                <!-- Progress Indicator -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Progress Pendaftaran</h2>
                        <span class="text-sm text-gray-500">Langkah 1 dari 5</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 20%"></div>
                    </div>
                </div>

                <!-- Data Siswa -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                            <span class="text-blue-600 font-semibold text-sm">1</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Data Siswa</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="full_name" class="block text-sm font-semibold text-gray-700">Nama Lengkap *</label>
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('full_name') ? 'border-red-500 ring-red-200' : '' }}"
                                placeholder="Masukkan nama lengkap">
                            @error('full_name')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="nisn" class="block text-sm font-semibold text-gray-700">NISN *</label>
                            <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}" required maxlength="10"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('nisn') ? 'border-red-500 ring-red-200' : '' }}"
                                placeholder="10 digit angka">
                            <p class="text-xs text-gray-500">NISN adalah Nomor Induk Siswa Nasional</p>
                            @error('nisn')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="nis_previous" class="block text-sm font-semibold text-gray-700">NIS Sekolah Asal</label>
                            <input type="text" id="nis_previous" name="nis_previous" value="{{ old('nis_previous') }}"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Nomor Induk Siswa dari sekolah asal">
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-semibold text-gray-700">Email Siswa *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('email') ? 'border-red-500 ring-red-200' : '' }}"
                                placeholder="email@example.com">
                            @error('email')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="birth_place" class="block text-sm font-semibold text-gray-700">Tempat Lahir *</label>
                            <input type="text" id="birth_place" name="birth_place" value="{{ old('birth_place') }}" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Kota tempat lahir">
                        </div>

                        <div class="space-y-2">
                            <label for="birth_date" class="block text-sm font-semibold text-gray-700">Tanggal Lahir *</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>

                        <div class="space-y-2">
                            <label for="gender" class="block text-sm font-semibold text-gray-700">Jenis Kelamin *</label>
                            <select id="gender" name="gender" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="religion" class="block text-sm font-semibold text-gray-700">Agama *</label>
                            <select id="religion" name="religion" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Pilih Agama</option>
                                <option value="Islam" {{ old('religion') === 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('religion') === 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('religion') === 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('religion') === 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('religion') === 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('religion') === 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="phone_number" class="block text-sm font-semibold text-gray-700">Nomor Telepon *</label>
                            <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="081234567890">
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="address" class="block text-sm font-semibold text-gray-700">Alamat Lengkap *</label>
                            <textarea id="address" name="address" rows="3" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Alamat lengkap siswa">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Data Orang Tua -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                            <span class="text-green-600 font-semibold text-sm">2</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Data Orang Tua/Wali</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="parent_name" class="block text-sm font-semibold text-gray-700">Nama Orang Tua/Wali *</label>
                            <input type="text" id="parent_name" name="parent_name" value="{{ old('parent_name') }}" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Nama lengkap orang tua/wali">
                        </div>

                        <div class="space-y-2">
                            <label for="parent_phone" class="block text-sm font-semibold text-gray-700">Nomor Telepon Orang Tua *</label>
                            <input type="tel" id="parent_phone" name="parent_phone" value="{{ old('parent_phone') }}" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="081234567890">
                        </div>

                        <div class="space-y-2">
                            <label for="parent_email" class="block text-sm font-semibold text-gray-700">Email Orang Tua *</label>
                            <input type="email" id="parent_email" name="parent_email" value="{{ old('parent_email') }}" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('parent_email') ? 'border-red-500 ring-red-200' : '' }}"
                                placeholder="email@example.com">
                            <p class="text-xs text-gray-500">Email ini akan digunakan untuk login akun wali murid</p>
                            @error('parent_email')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="parent_occupation" class="block text-sm font-semibold text-gray-700">Pekerjaan Orang Tua</label>
                            <input type="text" id="parent_occupation" name="parent_occupation" value="{{ old('parent_occupation') }}"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Pekerjaan orang tua/wali">
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="parent_address" class="block text-sm font-semibold text-gray-700">Alamat Orang Tua</label>
                            <textarea id="parent_address" name="parent_address" rows="3"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Alamat orang tua/wali (kosongkan jika sama dengan alamat siswa)">{{ old('parent_address') }}</textarea>
                            <p class="text-xs text-gray-500">Kosongkan jika sama dengan alamat siswa</p>
                        </div>
                    </div>
                </div>

                <!-- Data Sekolah Asal -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-center w-8 h-8 bg-yellow-100 rounded-full">
                            <span class="text-yellow-600 font-semibold text-sm">3</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Data Sekolah Asal</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="previous_school_name" class="block text-sm font-semibold text-gray-700">Nama Sekolah Asal *</label>
                            <input type="text" id="previous_school_name" name="previous_school_name" value="{{ old('previous_school_name') }}" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Nama sekolah sebelumnya">
                        </div>

                        <div class="space-y-2">
                            <label for="previous_school_npsn" class="block text-sm font-semibold text-gray-700">NPSN Sekolah Asal</label>
                            <input type="text" id="previous_school_npsn" name="previous_school_npsn" value="{{ old('previous_school_npsn') }}"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Nomor Pokok Sekolah Nasional">
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="previous_school_address" class="block text-sm font-semibold text-gray-700">Alamat Sekolah Asal *</label>
                            <textarea id="previous_school_address" name="previous_school_address" rows="3" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Alamat lengkap sekolah asal">{{ old('previous_school_address') }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <label for="previous_grade" class="block text-sm font-semibold text-gray-700">Kelas Terakhir di Sekolah Asal *</label>
                            <select id="previous_grade" name="previous_grade" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Pilih Kelas</option>
                                <option value="X" {{ old('previous_grade') === 'X' ? 'selected' : '' }}>Kelas X</option>
                                <option value="XI" {{ old('previous_grade') === 'XI' ? 'selected' : '' }}>Kelas XI</option>
                                <option value="XII" {{ old('previous_grade') === 'XII' ? 'selected' : '' }}>Kelas XII</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="previous_major" class="block text-sm font-semibold text-gray-700">Jurusan di Sekolah Asal *</label>
                            <select id="previous_major" name="previous_major" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Pilih Jurusan</option>
                                <option value="IPA" {{ old('previous_major') === 'IPA' ? 'selected' : '' }}>IPA</option>
                                <option value="IPS" {{ old('previous_major') === 'IPS' ? 'selected' : '' }}>IPS</option>
                                <option value="Bahasa" {{ old('previous_major') === 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                                <option value="Lainnya" {{ old('previous_major') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="previous_academic_year" class="block text-sm font-semibold text-gray-700">Tahun Ajaran Terakhir *</label>
                            <input type="text" id="previous_academic_year" name="previous_academic_year" value="{{ old('previous_academic_year') }}" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Contoh: 2024/2025">
                        </div>

                        <div class="space-y-2">
                            <label for="grade_scale" class="block text-sm font-semibold text-gray-700">Skala Nilai Sekolah Asal *</label>
                            <select id="grade_scale" name="grade_scale" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Pilih Skala Nilai</option>
                                <option value="0-100" {{ old('grade_scale') === '0-100' ? 'selected' : '' }}>Skala 0-100</option>
                                <option value="0-4" {{ old('grade_scale') === '0-4' ? 'selected' : '' }}>Skala 0-4</option>
                                <option value="A-F" {{ old('grade_scale') === 'A-F' ? 'selected' : '' }}>Skala A-F</option>
                                <option value="Predikat" {{ old('grade_scale') === 'Predikat' ? 'selected' : '' }}>Predikat (Sangat Baik/Baik/Cukup/Kurang)</option>
                            </select>
                            <p class="text-xs text-gray-500">Pilih skala nilai yang digunakan di sekolah asal</p>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="transfer_reason" class="block text-sm font-semibold text-gray-700">Alasan Pindah Sekolah *</label>
                            <textarea id="transfer_reason" name="transfer_reason" rows="3" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Jelaskan alasan pindah sekolah">{{ old('transfer_reason') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Tujuan Pindah -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-center w-8 h-8 bg-purple-100 rounded-full">
                            <span class="text-purple-600 font-semibold text-sm">4</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Tujuan Pindah</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="desired_grade" class="block text-sm font-semibold text-gray-700">Kelas yang Dituju *</label>
                            <select id="desired_grade" name="desired_grade" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Pilih Kelas</option>
                                <option value="X" {{ old('desired_grade') === 'X' ? 'selected' : '' }}>Kelas X</option>
                                <option value="XI" {{ old('desired_grade') === 'XI' ? 'selected' : '' }}>Kelas XI</option>
                                <option value="XII" {{ old('desired_grade') === 'XII' ? 'selected' : '' }}>Kelas XII</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="desired_major" class="block text-sm font-semibold text-gray-700">Jurusan yang Diinginkan *</label>
                            <select id="desired_major" name="desired_major" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Pilih Jurusan</option>
                                <option value="IPA" {{ old('desired_major') === 'IPA' ? 'selected' : '' }}>IPA</option>
                                <option value="IPS" {{ old('desired_major') === 'IPS' ? 'selected' : '' }}>IPS</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Upload Dokumen -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-full">
                            <span class="text-red-600 font-semibold text-sm">5</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Upload Dokumen</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="raport_file" class="block text-sm font-semibold text-gray-700">Rapor Sekolah Asal *</label>
                            <div class="relative">
                                <input type="file" id="raport_file" name="raport_file" required accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <p class="text-xs text-gray-500">Format: PDF, JPG, PNG. Maksimal 2MB</p>
                        </div>

                        <div class="space-y-2">
                            <label for="photo_file" class="block text-sm font-semibold text-gray-700">Pas Foto 3x4 *</label>
                            <div class="relative">
                                <input type="file" id="photo_file" name="photo_file" required accept=".jpg,.jpeg,.png"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <p class="text-xs text-gray-500">Format: JPG, PNG. Maksimal 1MB</p>
                        </div>

                        <div class="space-y-2">
                            <label for="family_card_file" class="block text-sm font-semibold text-gray-700">Fotokopi Kartu Keluarga *</label>
                            <div class="relative">
                                <input type="file" id="family_card_file" name="family_card_file" required accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <p class="text-xs text-gray-500">Format: PDF, JPG, PNG. Maksimal 2MB</p>
                        </div>

                        <div class="space-y-2">
                            <label for="transfer_certificate_file" class="block text-sm font-semibold text-gray-700">Surat Pindah Sekolah *</label>
                            <div class="relative">
                                <input type="file" id="transfer_certificate_file" name="transfer_certificate_file" required accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <p class="text-xs text-gray-500">Format: PDF, JPG, PNG. Maksimal 2MB</p>
                        </div>

                        <div class="space-y-2">
                            <label for="birth_certificate_file" class="block text-sm font-semibold text-gray-700">Akta Kelahiran *</label>
                            <div class="relative">
                                <input type="file" id="birth_certificate_file" name="birth_certificate_file" required accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <p class="text-xs text-gray-500">Format: PDF, JPG, PNG. Maksimal 2MB</p>
                        </div>

                        <div class="space-y-2">
                            <label for="health_certificate_file" class="block text-sm font-semibold text-gray-700">Surat Keterangan Sehat</label>
                            <div class="relative">
                                <input type="file" id="health_certificate_file" name="health_certificate_file" accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <p class="text-xs text-gray-500">Opsional. Format: PDF, JPG, PNG. Maksimal 2MB</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 border-t border-gray-200">
                    <a href="{{ route('transfer.status-check') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Cek Status Pendaftaran
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Daftar Siswa Pindahan
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-8">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-blue-900 mb-4">Informasi Penting</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-blue-800">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm">Pastikan semua dokumen yang diupload jelas dan dapat dibaca</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm">Proses verifikasi akan dilakukan oleh admin sekolah</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm">Setelah pendaftaran, Anda akan mendapat nomor registrasi untuk cek status</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm">Jika diterima, akun siswa dan wali murid akan otomatis dibuat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
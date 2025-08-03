@extends('layouts.app')

@section('title', 'Pendaftaran Siswa Pindahan')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pendaftaran Siswa Pindahan</h1>
            <p class="text-gray-600">Lengkapi formulir di bawah ini untuk mendaftar sebagai siswa pindahan</p>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <span class="text-red-800 font-medium">Terjadi kesalahan pada formulir:</span>
            </div>
            <ul class="list-disc list-inside text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-green-800 font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form method="POST" action="{{ route('transfer.register.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <!-- Data Siswa -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Data Siswa</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('full_name') ? 'border-red-500' : '' }}">
                            @error('full_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">NISN *</label>
                            <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}" required maxlength="10"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('nisn') ? 'border-red-500' : '' }}"
                                placeholder="10 digit angka">
                            @error('nisn')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nis_previous" class="block text-sm font-medium text-gray-700 mb-2">NIS Sekolah Asal</label>
                            <input type="text" id="nis_previous" name="nis_previous" value="{{ old('nis_previous') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Siswa *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('email') ? 'border-red-500' : '' }}">
                            @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir *</label>
                            <input type="text" id="birth_place" name="birth_place" value="{{ old('birth_place') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir *</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin *</label>
                            <select id="gender" name="gender" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">Agama *</label>
                            <select id="religion" name="religion" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Agama</option>
                                <option value="Islam" {{ old('religion') === 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('religion') === 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('religion') === 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('religion') === 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('religion') === 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('religion') === 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                        </div>

                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon *</label>
                            <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                            <textarea id="address" name="address" rows="3" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Data Orang Tua -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Data Orang Tua/Wali</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="parent_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Orang Tua/Wali *</label>
                            <input type="text" id="parent_name" name="parent_name" value="{{ old('parent_name') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="parent_phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon Orang Tua *</label>
                            <input type="tel" id="parent_phone" name="parent_phone" value="{{ old('parent_phone') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="parent_email" class="block text-sm font-medium text-gray-700 mb-2">Email Orang Tua *</label>
                            <input type="email" id="parent_email" name="parent_email" value="{{ old('parent_email') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('parent_email') ? 'border-red-500' : '' }}">
                            <p class="text-sm text-gray-600 mt-1">Email ini akan digunakan untuk login akun wali murid</p>
                            @error('parent_email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="parent_occupation" class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Orang Tua</label>
                            <input type="text" id="parent_occupation" name="parent_occupation" value="{{ old('parent_occupation') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="md:col-span-2">
                            <label for="parent_address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Orang Tua</label>
                            <textarea id="parent_address" name="parent_address" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('parent_address') }}</textarea>
                            <p class="text-sm text-gray-600 mt-1">Kosongkan jika sama dengan alamat siswa</p>
                        </div>
                    </div>
                </div>

                <!-- Data Sekolah Asal -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Data Sekolah Asal</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="previous_school_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Sekolah Asal *</label>
                            <input type="text" id="previous_school_name" name="previous_school_name" value="{{ old('previous_school_name') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="previous_school_npsn" class="block text-sm font-medium text-gray-700 mb-2">NPSN Sekolah Asal</label>
                            <input type="text" id="previous_school_npsn" name="previous_school_npsn" value="{{ old('previous_school_npsn') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="md:col-span-2">
                            <label for="previous_school_address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Sekolah Asal *</label>
                            <textarea id="previous_school_address" name="previous_school_address" rows="3" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('previous_school_address') }}</textarea>
                        </div>

                        <div>
                            <label for="previous_grade" class="block text-sm font-medium text-gray-700 mb-2">Kelas Terakhir di Sekolah Asal *</label>
                            <select id="previous_grade" name="previous_grade" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Kelas</option>
                                <option value="10" {{ old('previous_grade') === '10' ? 'selected' : '' }}>Kelas X</option>
                                <option value="11" {{ old('previous_grade') === '11' ? 'selected' : '' }}>Kelas XI</option>
                                <option value="12" {{ old('previous_grade') === '12' ? 'selected' : '' }}>Kelas XII</option>
                            </select>
                        </div>

                        <div>
                            <label for="previous_major" class="block text-sm font-medium text-gray-700 mb-2">Jurusan di Sekolah Asal *</label>
                            <select id="previous_major" name="previous_major" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Jurusan</option>
                                <option value="IPA" {{ old('previous_major') === 'IPA' ? 'selected' : '' }}>IPA</option>
                                <option value="IPS" {{ old('previous_major') === 'IPS' ? 'selected' : '' }}>IPS</option>
                                <option value="Bahasa" {{ old('previous_major') === 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                                <option value="Lainnya" {{ old('previous_major') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label for="previous_academic_year" class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran Terakhir *</label>
                            <input type="text" id="previous_academic_year" name="previous_academic_year" value="{{ old('previous_academic_year') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Contoh: 2024/2025">
                        </div>

                        <div class="md:col-span-2">
                            <label for="transfer_reason" class="block text-sm font-medium text-gray-700 mb-2">Alasan Pindah Sekolah *</label>
                            <textarea id="transfer_reason" name="transfer_reason" rows="3" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('transfer_reason') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Tujuan Pindah -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Tujuan Pindah</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="target_grade" class="block text-sm font-medium text-gray-700 mb-2">Kelas yang Dituju *</label>
                            <select id="target_grade" name="target_grade" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Kelas</option>
                                <option value="10" {{ old('target_grade') === '10' ? 'selected' : '' }}>Kelas X</option>
                                <option value="11" {{ old('target_grade') === '11' ? 'selected' : '' }}>Kelas XI</option>
                                <option value="12" {{ old('target_grade') === '12' ? 'selected' : '' }}>Kelas XII</option>
                            </select>
                        </div>

                        <div>
                            <label for="target_major" class="block text-sm font-medium text-gray-700 mb-2">Jurusan yang Dituju *</label>
                            <select id="target_major" name="target_major" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Jurusan</option>
                                <option value="IPA" {{ old('target_major') === 'IPA' ? 'selected' : '' }}>IPA</option>
                                <option value="IPS" {{ old('target_major') === 'IPS' ? 'selected' : '' }}>IPS</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Upload Dokumen -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Upload Dokumen</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="raport_file" class="block text-sm font-medium text-gray-700 mb-2">Rapor Sekolah Asal *</label>
                            <input type="file" id="raport_file" name="raport_file" required accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-600 mt-1">Format: PDF, JPG, PNG. Maksimal 2MB</p>
                        </div>

                        <div>
                            <label for="photo_file" class="block text-sm font-medium text-gray-700 mb-2">Pas Foto 3x4 *</label>
                            <input type="file" id="photo_file" name="photo_file" required accept=".jpg,.jpeg,.png"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-600 mt-1">Format: JPG, PNG. Maksimal 1MB</p>
                        </div>

                        <div>
                            <label for="family_card_file" class="block text-sm font-medium text-gray-700 mb-2">Fotokopi Kartu Keluarga *</label>
                            <input type="file" id="family_card_file" name="family_card_file" required accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-600 mt-1">Format: PDF, JPG, PNG. Maksimal 2MB</p>
                        </div>

                        <div>
                            <label for="transfer_letter_file" class="block text-sm font-medium text-gray-700 mb-2">Surat Pindah Sekolah *</label>
                            <input type="file" id="transfer_letter_file" name="transfer_letter_file" required accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-600 mt-1">Format: PDF, JPG, PNG. Maksimal 2MB</p>
                        </div>

                        <div>
                            <label for="birth_certificate_file" class="block text-sm font-medium text-gray-700 mb-2">Akta Kelahiran *</label>
                            <input type="file" id="birth_certificate_file" name="birth_certificate_file" required accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-600 mt-1">Format: PDF, JPG, PNG. Maksimal 2MB</p>
                        </div>

                        <div>
                            <label for="previous_certificate_file" class="block text-sm font-medium text-gray-700 mb-2">Ijazah/Sertifikat Sekolah Asal</label>
                            <input type="file" id="previous_certificate_file" name="previous_certificate_file" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-600 mt-1">Opsional. Format: PDF, JPG, PNG. Maksimal 2MB</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <a href="{{ route('transfer.status-check') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        Cek Status Pendaftaran
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-colors">
                        Daftar Siswa Pindahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">Informasi Penting</h3>
            <ul class="text-blue-800 space-y-2">
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Pastikan semua dokumen yang diupload jelas dan dapat dibaca</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Proses verifikasi akan dilakukan oleh admin sekolah</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Setelah pendaftaran, Anda akan mendapat nomor registrasi untuk cek status</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Jika diterima, akun siswa dan wali murid akan otomatis dibuat</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
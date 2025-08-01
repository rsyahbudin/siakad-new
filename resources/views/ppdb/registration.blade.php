@extends('layouts.app')

@section('title', 'Pendaftaran PPDB')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pendaftaran PPDB</h1>
            <p class="text-gray-600">Penerimaan Peserta Didik Baru Tahun Ajaran 2025/2026</p>
        </div>

        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Registration Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Form Pendaftaran</h2>
                <p class="text-sm text-gray-600">Silakan isi data dengan lengkap dan benar</p>
            </div>

            <form method="POST" action="{{ route('ppdb.register.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <!-- Jalur Pendaftaran -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-blue-900 mb-4">Jalur Pendaftaran</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-center p-3 border border-blue-200 rounded-lg cursor-pointer hover:bg-blue-100">
                            <input type="radio" name="entry_path" value="tes" class="text-blue-600 focus:ring-blue-500" required>
                            <div class="ml-3">
                                <div class="font-medium text-blue-900">Jalur Tes</div>
                                <div class="text-sm text-blue-700">Tes masuk dengan nilai minimal 70</div>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border border-blue-200 rounded-lg cursor-pointer hover:bg-blue-100">
                            <input type="radio" name="entry_path" value="prestasi" class="text-blue-600 focus:ring-blue-500" required>
                            <div class="ml-3">
                                <div class="font-medium text-blue-900">Jalur Prestasi</div>
                                <div class="text-sm text-blue-700">Rata-rata rapor minimal 85</div>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border border-blue-200 rounded-lg cursor-pointer hover:bg-blue-100">
                            <input type="radio" name="entry_path" value="afirmasi" class="text-blue-600 focus:ring-blue-500" required>
                            <div class="ml-3">
                                <div class="font-medium text-blue-900">Jalur Afirmasi</div>
                                <div class="text-sm text-blue-700">Berdasarkan kelengkapan dokumen</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Jurusan -->
                <div class="bg-green-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-green-900 mb-4">Jurusan yang Diminati</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex items-center p-3 border border-green-200 rounded-lg cursor-pointer hover:bg-green-100">
                            <input type="radio" name="desired_major" value="IPA" class="text-green-600 focus:ring-green-500" required>
                            <div class="ml-3">
                                <div class="font-medium text-green-900">IPA (Ilmu Pengetahuan Alam)</div>
                                <div class="text-sm text-green-700">Fisika, Kimia, Biologi</div>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border border-green-200 rounded-lg cursor-pointer hover:bg-green-100">
                            <input type="radio" name="desired_major" value="IPS" class="text-green-600 focus:ring-green-500" required>
                            <div class="ml-3">
                                <div class="font-medium text-green-900">IPS (Ilmu Pengetahuan Sosial)</div>
                                <div class="text-sm text-green-700">Ekonomi, Geografi, Sosiologi</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Data Calon Siswa -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Data Calon Siswa</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">NISN (10 digit)</label>
                            <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}" maxlength="10" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                            <input type="text" id="birth_place" name="birth_place" value="{{ old('birth_place') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                            <select id="gender" name="gender" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">Agama</label>
                            <input type="text" id="religion" name="religion" value="{{ old('religion') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                            <textarea id="address" name="address" rows="3" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Data Orang Tua -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Data Orang Tua</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="parent_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Orang Tua</label>
                            <input type="text" id="parent_name" name="parent_name" value="{{ old('parent_name') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="parent_phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon Orang Tua</label>
                            <input type="tel" id="parent_phone" name="parent_phone" value="{{ old('parent_phone') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="parent_occupation" class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Orang Tua</label>
                            <input type="text" id="parent_occupation" name="parent_occupation" value="{{ old('parent_occupation') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label for="parent_address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Orang Tua (Opsional)</label>
                            <textarea id="parent_address" name="parent_address" rows="2"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('parent_address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Rata-rata Nilai Rapor (Jalur Prestasi) -->
                <div id="raport_score_section" class="hidden">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Rata-rata Nilai Rapor</h3>
                    <div class="max-w-xs">
                        <label for="average_raport_score" class="block text-sm font-medium text-gray-700 mb-2">Rata-rata Nilai Semester 1-5</label>
                        <input type="number" id="average_raport_score" name="average_raport_score" value="{{ old('average_raport_score') }}"
                            min="0" max="100" step="0.01"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-600 mt-1">Minimal 85 untuk jalur prestasi</p>
                    </div>
                </div>

                <!-- Upload Dokumen -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Upload Dokumen</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="raport_file" class="block text-sm font-medium text-gray-700 mb-2">Rapor Semester 1-5 *</label>
                            <input type="file" id="raport_file" name="raport_file" accept=".pdf,.jpg,.jpeg,.png" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-600 mt-1">Format: PDF, JPG, JPEG, PNG (Max 2MB)</p>
                        </div>
                        <div>
                            <label for="photo_file" class="block text-sm font-medium text-gray-700 mb-2">Pas Foto 3x4 *</label>
                            <input type="file" id="photo_file" name="photo_file" accept=".jpg,.jpeg,.png" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-600 mt-1">Format: JPG, JPEG, PNG (Max 1MB)</p>
                        </div>
                        <div>
                            <label for="family_card_file" class="block text-sm font-medium text-gray-700 mb-2">Fotokopi Kartu Keluarga *</label>
                            <input type="file" id="family_card_file" name="family_card_file" accept=".pdf,.jpg,.jpeg,.png" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-600 mt-1">Format: PDF, JPG, JPEG, PNG (Max 2MB)</p>
                        </div>
                        <div id="achievement_certificate_section" class="hidden">
                            <label for="achievement_certificate_file" class="block text-sm font-medium text-gray-700 mb-2">Piagam Prestasi (Min. Tingkat Kabupaten) *</label>
                            <input type="file" id="achievement_certificate_file" name="achievement_certificate_file" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-600 mt-1">Format: PDF, JPG, JPEG, PNG (Max 2MB)</p>
                        </div>
                        <div id="financial_document_section" class="hidden">
                            <label for="financial_document_file" class="block text-sm font-medium text-gray-700 mb-2">Surat Keterangan Tidak Mampu/KIP/PKH *</label>
                            <input type="file" id="financial_document_file" name="financial_document_file" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-600 mt-1">Format: PDF, JPG, JPEG, PNG (Max 2MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        Daftar PPDB
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Section -->
        <div class="mt-8 bg-blue-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-blue-900 mb-4">Informasi Penting</h3>
            <div class="space-y-3 text-sm text-blue-800">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Pastikan semua dokumen yang diperlukan sudah diupload dengan benar</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Simpan nomor pendaftaran yang akan diberikan setelah submit</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Anda dapat mengecek status pendaftaran menggunakan nomor pendaftaran dan NISN</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const entryPathInputs = document.querySelectorAll('input[name="entry_path"]');
        const raportScoreSection = document.getElementById('raport_score_section');
        const achievementSection = document.getElementById('achievement_certificate_section');
        const financialSection = document.getElementById('financial_document_section');
        const achievementInput = document.getElementById('achievement_certificate_file');
        const financialInput = document.getElementById('financial_document_file');

        function updateFormFields() {
            const selectedPath = document.querySelector('input[name="entry_path"]:checked')?.value;

            // Reset all sections
            raportScoreSection.classList.add('hidden');
            achievementSection.classList.add('hidden');
            financialSection.classList.add('hidden');
            achievementInput.removeAttribute('required');
            financialInput.removeAttribute('required');

            if (selectedPath === 'prestasi') {
                raportScoreSection.classList.remove('hidden');
                achievementSection.classList.remove('hidden');
                achievementInput.setAttribute('required', 'required');
            } else if (selectedPath === 'afirmasi') {
                financialSection.classList.remove('hidden');
                financialInput.setAttribute('required', 'required');
            }
        }

        entryPathInputs.forEach(input => {
            input.addEventListener('change', updateFormFields);
        });

        // Initialize form state
        updateFormFields();
    });
</script>
@endsection
@extends('layouts.dashboard')

@section('title', 'Konversi Nilai - ' . $transferStudent->full_name)

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Konversi Nilai</h1>
                <p class="text-gray-600 mt-1">{{ $transferStudent->full_name }} - {{ $transferStudent->registration_number }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $transferStudent->status_badge_class }}">
                    {{ $transferStudent->status_label }}
                </span>
                <a href="{{ route('admin.transfer.show', $transferStudent) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-green-800 font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center gap-3 mb-2">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <span class="text-red-800 font-medium">Terjadi kesalahan:</span>
        </div>
        <ul class="list-disc list-inside text-red-700 space-y-1">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Student Info Summary -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Siswa</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sekolah Asal</label>
                <p class="text-gray-900">{{ $transferStudent->previous_school_name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas & Jurusan Asal</label>
                <p class="text-gray-900">{{ $transferStudent->previous_grade }} {{ $transferStudent->previous_major }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan</label>
                <p class="text-gray-900">{{ $transferStudent->target_grade }} {{ $transferStudent->target_major }}</p>
            </div>
        </div>
    </div>

    <!-- Grade Conversion Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Form Konversi Nilai</h2>
            <p class="text-sm text-gray-600 mt-1">Input nilai asli dari rapor sekolah asal dan nilai hasil konversi sesuai kurikulum sekolah</p>
        </div>

        <form method="POST" action="{{ route('admin.transfer.save-grade-conversion', $transferStudent) }}" class="p-6">
            @csrf

            <!-- Subject Input Section -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-md font-semibold text-gray-900">Daftar Mata Pelajaran</h3>
                    <button type="button" onclick="addSubject()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Mata Pelajaran
                    </button>
                </div>

                <div id="subjects-container" class="space-y-4">
                    @if($transferStudent->original_grades && $transferStudent->converted_grades)
                    @foreach($transferStudent->original_grades as $subject => $originalGrade)
                    <div class="subject-row grid grid-cols-1 md:grid-cols-4 gap-4 p-4 border border-gray-200 rounded-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                            <input type="text" name="subjects[]" value="{{ $subject }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Nama mata pelajaran">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Asal</label>
                            <input type="number" name="original_grades[]" value="{{ $originalGrade }}" min="0" max="100" step="0.01" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Konversi</label>
                            <input type="number" name="converted_grades[]" value="{{ $transferStudent->converted_grades[$subject] ?? '' }}" min="0" max="100" step="0.01" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0-100">
                        </div>
                        <div class="flex items-end">
                            <button type="button" onclick="removeSubject(this)" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                Hapus
                            </button>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <!-- Default subjects for new conversion -->
                    @php
                    $defaultSubjects = [
                    'Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Fisika', 'Kimia', 'Biologi'
                    ];
                    if($transferStudent->target_major === 'IPS') {
                    $defaultSubjects = [
                    'Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Sejarah', 'Geografi', 'Ekonomi', 'Sosiologi'
                    ];
                    }
                    @endphp
                    @foreach($defaultSubjects as $subject)
                    <div class="subject-row grid grid-cols-1 md:grid-cols-4 gap-4 p-4 border border-gray-200 rounded-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                            <input type="text" name="subjects[]" value="{{ $subject }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Nama mata pelajaran">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Asal</label>
                            <input type="number" name="original_grades[]" min="0" max="100" step="0.01" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Konversi</label>
                            <input type="number" name="converted_grades[]" min="0" max="100" step="0.01" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0-100">
                        </div>
                        <div class="flex items-end">
                            <button type="button" onclick="removeSubject(this)" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                Hapus
                            </button>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>

                <!-- Conversion Notes -->
                <div>
                    <label for="conversion_notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan Konversi</label>
                    <textarea id="conversion_notes" name="conversion_notes" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Catatan tentang proses konversi nilai, alasan penyesuaian, dll.">{{ $transferStudent->conversion_notes }}</textarea>
                    <p class="text-sm text-gray-600 mt-1">Jelaskan alasan atau metode konversi yang digunakan</p>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        <strong>Catatan:</strong> Konversi nilai wajib dilakukan sebelum menyetujui aplikasi siswa pindahan
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-colors">
                        Simpan Konversi Nilai
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Conversion Guidelines -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-4">Panduan Konversi Nilai</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
            <div class="space-y-2">
                <h4 class="font-semibold">Prinsip Konversi:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Sesuaikan dengan kurikulum sekolah saat ini</li>
                    <li>Pertimbangkan perbedaan standar penilaian</li>
                    <li>Berikan catatan yang jelas untuk setiap penyesuaian</li>
                </ul>
            </div>
            <div class="space-y-2">
                <h4 class="font-semibold">Hal yang Perlu Diperhatikan:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Nilai harus dalam rentang 0-100</li>
                    <li>Dokumentasikan alasan konversi dengan baik</li>
                    <li>Konsultasi dengan guru mata pelajaran jika diperlukan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    function addSubject() {
        const container = document.getElementById('subjects-container');
        const newRow = document.createElement('div');
        newRow.className = 'subject-row grid grid-cols-1 md:grid-cols-4 gap-4 p-4 border border-gray-200 rounded-lg';
        newRow.innerHTML = `
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
            <input type="text" name="subjects[]" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Nama mata pelajaran">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Asal</label>
            <input type="number" name="original_grades[]" min="0" max="100" step="0.01" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="0-100">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Konversi</label>
            <input type="number" name="converted_grades[]" min="0" max="100" step="0.01" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="0-100">
        </div>
        <div class="flex items-end">
            <button type="button" onclick="removeSubject(this)" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                Hapus
            </button>
        </div>
    `;
        container.appendChild(newRow);
    }

    function removeSubject(button) {
        const container = document.getElementById('subjects-container');
        const rows = container.querySelectorAll('.subject-row');

        // Prevent removing the last row
        if (rows.length > 1) {
            button.closest('.subject-row').remove();
        } else {
            alert('Minimal harus ada satu mata pelajaran');
        }
    }

    // Auto-calculate conversion based on simple formula (optional)
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('subjects-container');

        container.addEventListener('input', function(e) {
            if (e.target.name === 'original_grades[]') {
                const row = e.target.closest('.subject-row');
                const convertedInput = row.querySelector('input[name="converted_grades[]"]');

                // Simple conversion: if original grade is empty, don't auto-fill
                if (e.target.value && !convertedInput.value) {
                    // You can implement your conversion logic here
                    // For now, just copy the original value
                    convertedInput.value = e.target.value;
                }
            }
        });
    });
</script>
@endsection
@extends('layouts.dashboard')

@section('title', 'Detail Siswa Pindahan')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Siswa Pindahan</h1>
                <p class="text-gray-600 mt-1">Nomor Registrasi: {{ $transferStudent->registration_number }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $transferStudent->status_badge_class }}">
                    {{ $transferStudent->status_label }}
                </span>
                <a href="{{ route('admin.transfer.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
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

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <span class="text-red-800 font-medium">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Application Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Siswa</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <p class="text-gray-900">{{ $transferStudent->full_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                            <p class="text-gray-900">{{ $transferStudent->nisn }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NIS Sekolah Asal</label>
                            <p class="text-gray-900">{{ $transferStudent->nis_previous ?? '-' }}</p>
                        </div>
                        @if($transferStudent->status === 'approved')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NIS yang Akan Digenerate</label>
                            <p class="text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">
                                {{ \App\Services\NISGeneratorService::getNISFormatExample() }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Format: YY + 6 digit urutan (berbeda dari NIS sebelumnya)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Penempatan Kelas</label>
                            <p class="text-gray-900 font-semibold">Kelas {{ $transferStudent->target_grade }} {{ $transferStudent->target_major }}</p>
                            <p class="text-xs text-gray-500 mt-1">Siswa akan ditempatkan otomatis di kelas sesuai kelas dan jurusan tujuan</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Siswa</label>
                            <p class="text-gray-900">{{ $transferStudent->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                            <p class="text-gray-900">{{ $transferStudent->birth_place }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                            <p class="text-gray-900">{{ $transferStudent->birth_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                            <p class="text-gray-900">{{ $transferStudent->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Agama</label>
                            <p class="text-gray-900">{{ $transferStudent->religion }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <p class="text-gray-900">{{ $transferStudent->phone_number }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                            <p class="text-gray-900">{{ $transferStudent->address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parent Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Orang Tua/Wali</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Orang Tua/Wali</label>
                            <p class="text-gray-900">{{ $transferStudent->parent_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <p class="text-gray-900">{{ $transferStudent->parent_phone }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Orang Tua</label>
                            <p class="text-gray-900">{{ $transferStudent->parent_email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan</label>
                            <p class="text-gray-900">{{ $transferStudent->parent_occupation ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Orang Tua</label>
                            <p class="text-gray-900">{{ $transferStudent->parent_address ?? 'Sama dengan alamat siswa' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- School Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Sekolah & Pindahan</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sekolah Asal</label>
                            <p class="text-gray-900">{{ $transferStudent->previous_school_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NPSN Sekolah Asal</label>
                            <p class="text-gray-900">{{ $transferStudent->previous_school_npsn ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Sekolah Asal</label>
                            <p class="text-gray-900">{{ $transferStudent->previous_school_address }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kelas Terakhir</label>
                            <p class="text-gray-900">{{ $transferStudent->previous_grade }} {{ $transferStudent->previous_major }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran Terakhir</label>
                            <p class="text-gray-900">{{ $transferStudent->previous_academic_year }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kelas & Jurusan Tujuan</label>
                            <p class="text-gray-900">{{ $transferStudent->target_grade }} {{ $transferStudent->target_major }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Pindah</label>
                            <p class="text-gray-900">{{ $transferStudent->transfer_reason }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Dokumen yang Diupload</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                        $requiredDocuments = $transferStudent->getRequiredDocuments();
                        @endphp
                        @foreach($requiredDocuments as $field => $label)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $label }}</h4>
                                @if($transferStudent->{$field . '_file'})
                                <p class="text-sm text-green-600">✓ Dokumen tersedia</p>
                                @else
                                <p class="text-sm text-red-600">✗ Dokumen belum diupload</p>
                                @endif
                            </div>
                            @if($transferStudent->{$field . '_file'})
                            @php
                            $extension = pathinfo($transferStudent->{$field . '_file'}, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp
                            <div class="flex gap-2">
                                @if($isImage)
                                <button onclick="previewImage('{{ route('admin.transfer.download', ['transferStudent' => $transferStudent, 'documentType' => $field]) }}', '{{ $label }}')"
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                    Preview
                                </button>
                                @endif
                                <a href="{{ route('admin.transfer.download', ['transferStudent' => $transferStudent, 'documentType' => $field]) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
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
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Konversi Nilai</h2>
                        @if($transferStudent->status === 'approved' && !$transferStudent->hasGradeConversion())
                        <a href="{{ route('admin.transfer.grade-conversion', $transferStudent) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Input Konversi Nilai
                        </a>
                        @elseif($transferStudent->status !== 'approved')
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-2 rounded-lg">
                            Konversi nilai hanya dapat dilakukan setelah siswa diterima
                        </span>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    @if($transferStudent->original_grades && $transferStudent->converted_grades)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-2 font-medium text-gray-700">Mata Pelajaran</th>
                                    <th class="text-center py-2 font-medium text-gray-700">Nilai Asal</th>
                                    <th class="text-center py-2 font-medium text-gray-700">Nilai Konversi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transferStudent->original_grades as $subject => $originalGrade)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2 text-gray-900">{{ $subject }}</td>
                                    <td class="py-2 text-center text-gray-900">{{ $originalGrade }}</td>
                                    <td class="py-2 text-center text-gray-900">
                                        {{ $transferStudent->converted_grades[$subject] ?? '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($transferStudent->conversion_notes)
                    <div class="mt-4 bg-gray-50 rounded-lg p-3">
                        <h4 class="font-medium text-gray-900 mb-1">Catatan Konversi:</h4>
                        <p class="text-gray-700 text-sm">{{ $transferStudent->conversion_notes }}</p>
                    </div>
                    @endif
                    @if($transferStudent->status === 'approved')
                    <div class="mt-4">
                        <a href="{{ route('admin.transfer.grade-conversion', $transferStudent) }}"
                            class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Edit Konversi Nilai
                        </a>
                    </div>
                    @endif
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        @if($transferStudent->status === 'approved')
                        <p class="mb-3">Konversi nilai belum dilakukan</p>
                        <a href="{{ route('admin.transfer.grade-conversion', $transferStudent) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Mulai Konversi Nilai
                        </a>
                        @else
                        <p class="mb-3">Siswa harus diterima terlebih dahulu</p>
                        <span class="bg-gray-400 text-white px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed">
                            Konversi Nilai Tidak Tersedia
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Panel -->
        <div class="space-y-6">
            <!-- Status Update -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Update Status</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.transfer.update', $transferStudent) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="pending" {{ $transferStudent->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="approved" {{ $transferStudent->status === 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="rejected" {{ $transferStudent->status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $transferStudent->notes }}</textarea>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Requirements Check -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Kelayakan Penerimaan</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">Dokumen Lengkap</h4>
                                <p class="text-sm text-gray-600">{{ $transferStudent->hasAllRequiredDocuments() ? 'Semua dokumen tersedia' : 'Beberapa dokumen belum diupload' }}</p>
                            </div>
                            @if($transferStudent->hasAllRequiredDocuments())
                            <span class="text-green-600">✓</span>
                            @else
                            <span class="text-red-600">✗</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">Konversi Nilai</h4>
                                <p class="text-sm text-gray-600">
                                    @if($transferStudent->status === 'approved')
                                    {{ $transferStudent->hasGradeConversion() ? 'Nilai sudah dikonversi' : 'Konversi nilai belum dilakukan' }}
                                    @else
                                    Konversi nilai dilakukan setelah diterima
                                    @endif
                                </p>
                            </div>
                            @if($transferStudent->status === 'approved')
                            @if($transferStudent->hasGradeConversion())
                            <span class="text-green-600">✓</span>
                            @else
                            <span class="text-yellow-600">⏳</span>
                            @endif
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Eligibility Status -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Status Kelayakan</h3>
                </div>
                <div class="p-6">
                    @if($transferStudent->isEligibleForApproval())
                    <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-green-800 font-medium">Memenuhi Persyaratan</span>
                    </div>
                    @else
                    <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                        <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="text-red-800 font-medium">Belum Memenuhi Persyaratan</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Processing Info -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-2">Informasi Proses</h4>
                <div class="space-y-2 text-sm text-gray-600">
                    <p><strong>Tanggal Daftar:</strong> {{ $transferStudent->submitted_at->format('d/m/Y H:i') }}</p>
                    @if($transferStudent->processed_at)
                    <p><strong>Tanggal Proses:</strong> {{ $transferStudent->processed_at->format('d/m/Y H:i') }}</p>
                    @endif
                    @if($transferStudent->processedBy)
                    <p><strong>Diproses oleh:</strong> {{ $transferStudent->processedBy->name }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg max-w-4xl max-h-full overflow-auto">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900"></h3>
            <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-4">
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
</script>
@endsection
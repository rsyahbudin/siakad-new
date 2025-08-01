@extends('layouts.dashboard')

@section('title', 'Detail Pendaftaran PPDB')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pendaftaran PPDB</h1>
                <p class="text-gray-600 mt-1">Nomor Pendaftaran: {{ $application->application_number }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $application->status_badge_class }}">
                    {{ $application->status_label }}
                </span>
                <a href="{{ route('admin.ppdb.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
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
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Pendaftar</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <p class="text-gray-900">{{ $application->full_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                            <p class="text-gray-900">{{ $application->nisn }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                            <p class="text-gray-900">{{ $application->birth_place }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                            <p class="text-gray-900">{{ $application->birth_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                            <p class="text-gray-900">{{ $application->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Agama</label>
                            <p class="text-gray-900">{{ $application->religion }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <p class="text-gray-900">{{ $application->phone_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <p class="text-gray-900">{{ $application->email }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                            <p class="text-gray-900">{{ $application->address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parent Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Orang Tua</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Orang Tua</label>
                            <p class="text-gray-900">{{ $application->parent_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon Orang Tua</label>
                            <p class="text-gray-900">{{ $application->parent_phone }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Orang Tua</label>
                            <p class="text-gray-900">{{ $application->parent_occupation ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Orang Tua</label>
                            <p class="text-gray-900">{{ $application->parent_address ?? $application->address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Detail Pendaftaran</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jalur Pendaftaran</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($application->entry_path === 'tes') bg-blue-100 text-blue-800
                                @elseif($application->entry_path === 'prestasi') bg-green-100 text-green-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                {{ $application->entry_path_label }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jurusan yang Diminati</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $application->desired_major_label }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pendaftaran</label>
                            <p class="text-gray-900">{{ $application->submitted_at ? $application->submitted_at->format('d/m/Y H:i') : '-' }}</p>
                        </div>
                        @if($application->processed_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Diproses</label>
                            <p class="text-gray-900">{{ $application->processed_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                        @if($application->processedBy)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Diproses Oleh</label>
                            <p class="text-gray-900">{{ $application->processedBy->name }}</p>
                        </div>
                        @endif
                        @if($application->test_score)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Tes</label>
                            <p class="text-gray-900">{{ $application->test_score }}</p>
                        </div>
                        @endif
                        @if($application->average_raport_score)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rata-rata Nilai Rapor</label>
                            <p class="text-gray-900">{{ $application->average_raport_score }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Dokumen Pendaftar</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                        $requiredDocuments = $application->getRequiredDocuments();
                        @endphp
                        @foreach($requiredDocuments as $field => $label)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $label }}</h4>
                                @if($application->$field)
                                <p class="text-sm text-green-600">✓ Dokumen tersedia</p>
                                @else
                                <p class="text-sm text-red-600">✗ Dokumen belum diupload</p>
                                @endif
                            </div>
                            @if($application->$field)
                            <a href="{{ route('admin.ppdb.download', ['application' => $application, 'documentType' => $field]) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                Download
                            </a>
                            @else
                            <span class="text-gray-400 text-sm">Tidak tersedia</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
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
                    <form method="POST" action="{{ route('admin.ppdb.update', $application) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="lulus" {{ $application->status === 'lulus' ? 'selected' : '' }}>Lulus</option>
                                <option value="ditolak" {{ $application->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        @if($application->entry_path === 'tes')
                        <div>
                            <label for="test_score" class="block text-sm font-medium text-gray-700 mb-2">Nilai Tes</label>
                            <input type="number" id="test_score" name="test_score" value="{{ $application->test_score }}"
                                min="0" max="100" step="0.01"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-600 mt-1">Minimal 70 untuk kelulusan</p>
                        </div>
                        @endif

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $application->notes }}</textarea>
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
                    <h3 class="text-lg font-semibold text-gray-900">Persyaratan Kelulusan</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @if($application->entry_path === 'tes')
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">Nilai Tes ≥ 70</h4>
                                <p class="text-sm text-gray-600">{{ $application->test_score ?? 'Belum diinput' }}</p>
                            </div>
                            @if($application->test_score && $application->test_score >= 70)
                            <span class="text-green-600">✓</span>
                            @elseif($application->test_score)
                            <span class="text-red-600">✗</span>
                            @else
                            <span class="text-yellow-600">?</span>
                            @endif
                        </div>
                        @elseif($application->entry_path === 'prestasi')
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">Rata-rata Rapor ≥ 85</h4>
                                <p class="text-sm text-gray-600">{{ $application->average_raport_score ?? 'Belum diinput' }}</p>
                            </div>
                            @if($application->average_raport_score && $application->average_raport_score >= 85)
                            <span class="text-green-600">✓</span>
                            @elseif($application->average_raport_score)
                            <span class="text-red-600">✗</span>
                            @else
                            <span class="text-yellow-600">?</span>
                            @endif
                        </div>
                        @elseif($application->entry_path === 'afirmasi')
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">Dokumen Lengkap</h4>
                                <p class="text-sm text-gray-600">Berdasarkan kelengkapan dokumen</p>
                            </div>
                            @if($application->hasAllRequiredDocuments())
                            <span class="text-green-600">✓</span>
                            @else
                            <span class="text-red-600">✗</span>
                            @endif
                        </div>
                        @endif

                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">Dokumen Lengkap</h4>
                                <p class="text-sm text-gray-600">{{ $application->hasAllRequiredDocuments() ? 'Semua dokumen tersedia' : 'Beberapa dokumen belum diupload' }}</p>
                            </div>
                            @if($application->hasAllRequiredDocuments())
                            <span class="text-green-600">✓</span>
                            @else
                            <span class="text-red-600">✗</span>
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
                    @if($application->isEligibleForApproval())
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
                        <span class="text-red-800 font-medium">Tidak Memenuhi Persyaratan</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Status Pendaftaran')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Status Pendaftaran</h1>
            <p class="text-gray-600">Detail status pendaftaran PPDB Anda</p>
        </div>

        <!-- Application Status -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Status Pendaftaran</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $application->status_badge_class }}">
                        {{ $application->status_label }}
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Pendaftaran</label>
                        <p class="text-lg font-semibold text-blue-600">{{ $application->application_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Pendaftaran</label>
                        <p class="text-gray-900">{{ $application->submitted_at ? $application->submitted_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <p class="text-gray-900">{{ $application->full_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NISN</label>
                        <p class="text-gray-900">{{ $application->nisn }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="text-gray-900">{{ $application->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jalur Pendaftaran</label>
                        <p class="text-gray-900">{{ $application->entry_path_label }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jurusan yang Diminati</label>
                        <p class="text-gray-900">{{ $application->desired_major_label }}</p>
                    </div>
                    @if($application->test_score)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nilai Tes</label>
                        <p class="text-gray-900">{{ $application->test_score }}</p>
                    </div>
                    @endif
                    @if($application->average_raport_score)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Rata-rata Nilai Rapor</label>
                        <p class="text-gray-900">{{ $application->average_raport_score }}</p>
                    </div>
                    @endif
                    @if($application->processed_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Diproses</label>
                        <p class="text-gray-900">{{ $application->processed_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                    @if($application->processedBy)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Diproses Oleh</label>
                        <p class="text-gray-900">{{ $application->processedBy->name }}</p>
                    </div>
                    @endif
                </div>

                @if($application->notes)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900">{{ $application->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Document Status -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Status Dokumen</h3>
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
                            <p class="text-sm text-green-600">✓ Dokumen telah diupload</p>
                            @else
                            <p class="text-sm text-red-600">✗ Dokumen belum diupload</p>
                            @endif
                        </div>
                        @if($application->$field)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Tersedia
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Belum Upload
                        </span>
                        @endif
                    </div>
                    @endforeach
                </div>

                @if($application->hasAllRequiredDocuments())
                <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-green-800 font-medium">Semua dokumen yang diperlukan telah diupload</span>
                    </div>
                </div>
                @else
                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <span class="text-yellow-800 font-medium">Beberapa dokumen belum diupload</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Requirements Check -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Persyaratan Kelulusan</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @if($application->entry_path === 'tes')
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Nilai Tes Minimal 70</h4>
                            <p class="text-sm text-gray-600">Nilai tes saat ini: {{ $application->test_score ?? 'Belum diinput' }}</p>
                        </div>
                        @if($application->test_score && $application->test_score >= 70)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            ✓ Memenuhi
                        </span>
                        @elseif($application->test_score)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            ✗ Tidak Memenuhi
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Belum Diinput
                        </span>
                        @endif
                    </div>
                    @elseif($application->entry_path === 'prestasi')
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Rata-rata Nilai Rapor Minimal 85</h4>
                            <p class="text-sm text-gray-600">Rata-rata nilai saat ini: {{ $application->average_raport_score ?? 'Belum diinput' }}</p>
                        </div>
                        @if($application->average_raport_score && $application->average_raport_score >= 85)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            ✓ Memenuhi
                        </span>
                        @elseif($application->average_raport_score)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            ✗ Tidak Memenuhi
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Belum Diinput
                        </span>
                        @endif
                    </div>
                    @elseif($application->entry_path === 'afirmasi')
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Kelengkapan Dokumen</h4>
                            <p class="text-sm text-gray-600">Berdasarkan kelengkapan dokumen yang diupload</p>
                        </div>
                        @if($application->hasAllRequiredDocuments())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            ✓ Dokumen Lengkap
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            ✗ Dokumen Tidak Lengkap
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('ppdb.status-check') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors">
                Cek Status Lain
            </a>
            <a href="{{ route('ppdb.register') }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors">
                Daftar PPDB
            </a>
        </div>
    </div>
</div>
@endsection
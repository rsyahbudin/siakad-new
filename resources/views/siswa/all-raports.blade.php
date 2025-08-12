@extends('layouts.dashboard')
@section('title', 'Semua Raport')

@section('content')
<!-- Header Section -->
<div class="bg-white shadow-sm border-b border-gray-200 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Riwayat Raport</h1>
                <p class="mt-1 text-sm text-gray-600">Lihat semua raport yang telah diterbitkan</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('siswa.raport') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Raport
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Student Info Card -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Informasi Siswa</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nama Siswa</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $student->full_name }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">NIS</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $student->nis }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">NISN</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $student->nisn }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reports Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @if($allRaports->isEmpty())
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum ada raport</h3>
            <p class="text-gray-500 mb-6">Anda belum memiliki raport yang telah difinalisasi oleh wali kelas.</p>
            <a href="{{ route('siswa.raport') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Lihat Raport Saat Ini
            </a>
        </div>
    </div>
    @else
    <!-- Reports List -->
    <div class="space-y-8">
        @foreach($allRaports as $academicYearId => $raports)
        @php
        $academicYear = $academicYears->firstWhere('id', $academicYearId);
        @endphp
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ $academicYear->year }}</h3>
                        <p class="text-sm text-gray-600">Tahun Ajaran {{ $academicYear->year }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $raports->count() }} Raport
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach($raports as $raport)
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-900">
                                Semester {{ $raport->semester == 1 ? 'Ganjil' : 'Genap' }}
                            </h4>
                            <div class="flex items-center space-x-2">
                                @if($raport->is_finalized)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Final
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    Draft
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Kelas:</span>
                                <span class="font-medium text-gray-900">{{ $raport->classroom->name }}</span>
                            </div>

                            @if($raport->is_finalized)
                            <!-- Attendance Summary -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h5 class="font-medium text-gray-900 mb-3 flex items-center">
                                    <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Ringkasan Kehadiran
                                </h5>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="text-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-lg font-bold text-gray-900">{{ $raport->attendance_sick }}</p>
                                        <p class="text-xs text-gray-500">Sakit</p>
                                    </div>
                                    <div class="text-center">
                                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-lg font-bold text-gray-900">{{ $raport->attendance_permit }}</p>
                                        <p class="text-xs text-gray-500">Izin</p>
                                    </div>
                                    <div class="text-center">
                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                        <p class="text-lg font-bold text-gray-900">{{ $raport->attendance_absent }}</p>
                                        <p class="text-xs text-gray-500">Alpha</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between items-center text-xs text-gray-500 pt-2 border-t border-gray-200">
                                <span>Tanggal Finalisasi:</span>
                                <span class="font-medium">{{ $raport->finalized_at ? $raport->finalized_at->format('d/m/Y H:i') : '-' }}</span>
                            </div>
                            @else
                            <div class="text-center py-6 bg-yellow-50 rounded-lg border border-yellow-200">
                                <svg class="w-8 h-8 text-yellow-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <p class="text-sm font-medium text-yellow-800">Raport belum difinalisasi</p>
                                <p class="text-xs text-yellow-600 mt-1">Nilai masih dapat berubah</p>
                            </div>
                            @endif
                        </div>

                        <div class="mt-6 flex gap-3">
                            <a href="{{ route('siswa.raport', ['academic_year_id' => $academicYear->id, 'semester' => $raport->semester]) }}"
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Lihat Detail
                            </a>
                            @if($raport->is_finalized)
                            <button data-academic-year="{{ $academicYear->id }}" data-semester="{{ $raport->semester }}"
                                class="print-raport-btn inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Event listener untuk tombol print
        document.querySelectorAll('.print-raport-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const academicYearId = this.getAttribute('data-academic-year');
                const semester = this.getAttribute('data-semester');
                downloadRaportPDF(academicYearId, semester);
            });
        });
    });

    function downloadRaportPDF(academicYearId, semester) {
        // Buka halaman raport dalam tab baru untuk print
        const url = `{{ route('siswa.raport') }}?academic_year_id=${academicYearId}&semester=${semester}`;
        const newWindow = window.open(url, '_blank');

        // Tunggu halaman load lalu print
        newWindow.onload = function() {
            setTimeout(() => {
                // Trigger the downloadPDF function if it exists
                if (typeof newWindow.downloadPDF === 'function') {
                    newWindow.downloadPDF();
                } else {
                    newWindow.print();
                }
            }, 1000);
        };
    }
</script>
@endsection
@extends('layouts.dashboard')
@section('title', 'Semua Raport')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Semua Raport</h2>
    <a href="{{ route('siswa.raport') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Raport
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6 border-b">
        <h3 class="text-lg font-semibold">Riwayat Raport {{ $student->full_name }}</h3>
        <p class="text-sm text-gray-600">NIS: {{ $student->nis }} | NISN: {{ $student->nisn }}</p>
    </div>

    @if($allRaports->isEmpty())
    <div class="p-8 text-center">
        <div class="text-gray-400 mb-4">
            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada raport</h3>
        <p class="text-gray-500">Anda belum memiliki raport yang telah difinalisasi.</p>
    </div>
    @else
    <div class="divide-y divide-gray-200">
        @foreach($allRaports as $academicYearId => $raports)
        @php
        $academicYear = $academicYears->firstWhere('id', $academicYearId);
        @endphp
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900">{{ $academicYear->year }}</h4>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                    {{ $raports->count() }} Raport
                </span>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                @foreach($raports as $raport)
                <div class="border rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="font-medium text-gray-900">
                            Semester {{ $raport->semester == 1 ? 'Ganjil' : 'Genap' }}
                        </h5>
                        <div class="flex items-center gap-2">
                            @if($raport->is_finalized)
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                Final
                            </span>
                            @else
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                Draft
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Kelas:</span>
                            <span class="font-medium">{{ $raport->classroom->name }}</span>
                        </div>

                        @if($raport->is_finalized)
                        <!-- Attendance Summary -->
                        <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                            <h6 class="font-medium text-gray-900 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Kehadiran
                            </h6>
                            <div class="grid grid-cols-3 gap-2 text-xs">
                                <div class="text-center">
                                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-1">
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="font-medium text-gray-900">{{ $raport->attendance_sick }}</p>
                                    <p class="text-gray-500">Sakit</p>
                                </div>
                                <div class="text-center">
                                    <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-1">
                                        <svg class="w-3 h-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="font-medium text-gray-900">{{ $raport->attendance_permit }}</p>
                                    <p class="text-gray-500">Izin</p>
                                </div>
                                <div class="text-center">
                                    <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-1">
                                        <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                    <p class="font-medium text-gray-900">{{ $raport->attendance_absent }}</p>
                                    <p class="text-gray-500">Alpha</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between text-xs text-gray-500 mt-2">
                            <span>Finalisasi:</span>
                            <span>{{ $raport->finalized_at ? $raport->finalized_at->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                        @else
                        <div class="text-center py-3 text-gray-500 bg-yellow-50 rounded-lg border border-yellow-200">
                            <svg class="w-6 h-6 text-yellow-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <p class="text-sm font-medium">Raport belum difinalisasi</p>
                        </div>
                        @endif
                    </div>

                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('siswa.raport', ['academic_year_id' => $academicYear->id, 'semester' => $raport->semester]) }}"
                            class="flex-1 bg-blue-600 text-white text-center py-2 px-3 rounded text-sm hover:bg-blue-700 transition">
                            Lihat Detail
                        </a>
                        @if($raport->is_finalized)
                        <button data-academic-year="{{ $academicYear->id }}" data-semester="{{ $raport->semester }}"
                            class="print-raport-btn bg-green-600 text-white py-2 px-3 rounded text-sm hover:bg-green-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
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
                printRaport(academicYearId, semester);
            });
        });
    });

    function printRaport(academicYearId, semester) {
        // Buka halaman raport dalam tab baru untuk print
        const url = `{{ route('siswa.raport') }}?academic_year_id=${academicYearId}&semester=${semester}`;
        const newWindow = window.open(url, '_blank');

        // Tunggu halaman load lalu print
        newWindow.onload = function() {
            setTimeout(() => {
                newWindow.print();
            }, 1000);
        };
    }
</script>
@endsection
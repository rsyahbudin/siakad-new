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
                        <div class="flex justify-between">
                            <span>Absensi Sakit:</span>
                            <span>{{ $raport->attendance_sick }} hari</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Absensi Izin:</span>
                            <span>{{ $raport->attendance_permit }} hari</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Absensi Alpha:</span>
                            <span>{{ $raport->attendance_absent }} hari</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Finalisasi:</span>
                            <span class="text-xs">{{ $raport->finalized_at ? $raport->finalized_at->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                        @else
                        <div class="text-center py-2 text-gray-500">
                            Raport belum difinalisasi
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
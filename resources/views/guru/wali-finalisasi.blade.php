@extends('layouts.dashboard')
@section('title', 'Finalisasi Raport')
@section('content')
<h2 class="text-2xl font-bold mb-4">Finalisasi Raport untuk Kelas {{ $kelas->name ?? '' }}</h2>
<p class="mb-4">Tahun Ajaran Aktif: <span class="font-semibold">{{ $activeYear->year ?? '-' }} Semester {{ $activeYear->semester ?? '-' }}</span></p>

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 p-3 bg-red-100 text-red-800 rounded shadow-sm">{{ session('error') }}</div>
@endif


<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        @if($isAllFinalized)
        <div class="p-4 text-center bg-green-50 text-green-800 rounded-lg">
            <p class="font-bold text-lg">Semua raport telah difinalisasi.</p>
            <p class="text-sm mt-1">Data raport untuk semester ini telah dikunci dan tidak dapat diubah lagi.</p>
        </div>
        @else
        <div class="p-4 mb-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800">
            <h4 class="font-bold">Perhatian!</h4>
            <p>Proses finalisasi akan mengunci semua data raport untuk kelas ini. Setelah difinalisasi, data nilai, absensi, dan catatan tidak dapat diubah lagi. Pastikan semua data sudah benar sebelum melanjutkan.</p>
        </div>
        <form method="POST" action="{{ route('wali.finalisasi.store') }}" onsubmit="return confirm('Apakah Anda yakin ingin memfinalisasi semua raport? Tindakan ini tidak dapat dibatalkan.');">
            @csrf
            <button type="submit" class="w-full bg-red-600 text-white px-8 py-3 rounded-lg hover:bg-red-700 shadow-lg transition font-bold text-lg">
                Finalisasi Seluruh Raport Kelas {{ $kelas->name }}
            </button>
        </form>
        @endif
    </div>
    <table class="min-w-full text-sm">
        <thead class="bg-blue-100">
            <tr>
                <th class="py-2 px-4 text-left">Nama Siswa</th>
                <th class="py-2 px-4 text-center">Status Raport</th>
            </tr>
        </thead>
        <tbody>
            @forelse($raports->sortBy('student.full_name') as $raport)
            <tr class="border-b hover:bg-blue-50">
                <td class="py-2 px-4 font-medium">{{ $raport->student->full_name }}</td>
                <td class="py-2 px-4 text-center">
                    @if($raport->is_finalized)
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        Final
                    </span>
                    @else
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-700">
                        Draft
                    </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="py-4 text-center text-gray-500">Tidak ada data raport untuk ditampilkan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
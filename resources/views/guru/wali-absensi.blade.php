@extends('layouts.dashboard')
@section('title', 'Rekap Absensi Siswa')
@section('content')
<h2 class="text-2xl font-bold mb-4">Rekap Absensi untuk Kelas {{ $kelas->name }}</h2>
<p class="mb-4">Tahun Ajaran Aktif: <span class="font-semibold">{{ $activeYear->year ?? '-' }} Semester {{ $activeYear->semester ?? '-' }}</span></p>

<div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-800">
    <h4 class="font-bold">Informasi</h4>
    <p class="text-sm">Halaman ini menampilkan rekapitulasi absensi yang dihitung secara otomatis berdasarkan input harian dari semua guru mata pelajaran. Anda dapat melakukan penyesuaian jika diperlukan sebelum menyimpan data final ke raport.</p>
</div>

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow-sm">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('wali.absensi.store') }}">
    @csrf
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-blue-100">
                <tr>
                    <th class="py-2 px-4 text-left">Nama Siswa</th>
                    <th class="py-2 px-4 text-center w-24">Sakit</th>
                    <th class="py-2 px-4 text-center w-24">Izin</th>
                    <th class="py-2 px-4 text-center w-24">Alpha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                @php
                $rekap = $rekapAbsensi->get($student->id);
                $sakit = $rekap ? (optional($rekap->firstWhere('status', 'Sakit'))->total ?? 0) : 0;
                $izin = $rekap ? (optional($rekap->firstWhere('status', 'Izin'))->total ?? 0) : 0;
                $alpha = $rekap ? (optional($rekap->firstWhere('status', 'Alpha'))->total ?? 0) : 0;

                $raportData = $raports->get($student->id);
                @endphp
                <tr class="border-b hover:bg-blue-50">
                    <td class="py-2 px-4 font-medium">{{ $student->full_name }}</td>
                    <td class="py-2 px-4">
                        <input type="number" name="attendance[{{ $student->id }}][sick]" value="{{ $sakit }}" class="w-full border rounded px-2 py-1 text-center">
                    </td>
                    <td class="py-2 px-4">
                        <input type="number" name="attendance[{{ $student->id }}][permit]" value="{{ $izin }}" class="w-full border rounded px-2 py-1 text-center">
                    </td>
                    <td class="py-2 px-4">
                        <input type="number" name="attendance[{{ $student->id }}][absent]" value="{{ $alpha }}" class="w-full border rounded px-2 py-1 text-center">
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-4 text-center text-gray-500">Tidak ada siswa di kelas ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6 flex justify-end">
        <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded-lg hover:bg-blue-700 shadow transition">Simpan Rekap Absensi</button>
    </div>
</form>
@endsection
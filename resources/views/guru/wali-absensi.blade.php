@extends('layouts.dashboard')
@section('title', 'Input Absensi Semester')
@section('content')
<h2 class="text-2xl font-bold mb-4">Input Absensi Semester Kelas {{ $kelas->name }}</h2>
<p class="mb-4">Tahun Ajaran Aktif: <span class="font-semibold">{{ $activeSemester->academicYear->year ?? '-' }} Semester {{ $activeSemester->name ?? '-' }}</span></p>

@if (session('success'))
<div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-md shadow-sm">
    <p class="font-bold">Berhasil!</p>
    <p>{{ session('success') }}</p>
</div>
@endif

<div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-800">
    <h4 class="font-bold">Informasi</h4>
    <p class="text-sm">Silakan masukkan total rekapitulasi absensi (Sakit, Izin, Alpha) untuk setiap siswa selama satu semester. Data ini akan digunakan untuk pengisian raport.</p>
</div>

{{-- Search Bar --}}
<form method="GET" class="mb-4 flex gap-2 items-center">
    <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama/NIS/NISN siswa..." class="border rounded px-3 py-1 w-64 text-sm">
    <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">Cari</button>
    @if($q)
    <a href="{{ route('wali.absensi') }}" class="text-xs text-gray-500 ml-2">Reset</a>
    @endif
</form>

{{-- Form Input Absensi Semester --}}
<div class="mb-8">
    <form method="POST" action="{{ route('wali.absensi.store') }}?{{ $q ? 'q=' . urlencode($q) : '' }}&page={{ request('page', 1) }}">
        @csrf
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full text-sm">
                <thead class="bg-blue-100 sticky top-0 z-10">
                    <tr>
                        <th class="py-2 px-4 text-left">Nama Siswa</th>
                        <th class="py-2 px-4 text-center w-32">Sakit</th>
                        <th class="py-2 px-4 text-center w-32">Izin</th>
                        <th class="py-2 px-4 text-center w-32">Alpha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $row)
                    @php
                    $student = $row->student;
                    $attendance = $rekapAbsensi->get($student->id);
                    @endphp
                    <tr class="border-b hover:bg-blue-50">
                        <td class="py-2 px-4 font-medium">{{ $student->full_name }}</td>
                        <td class="py-2 px-4">
                            <input type="number" name="attendances[{{ $student->id }}][sakit]" value="{{ $attendance->sakit ?? 0 }}" min="0" class="border rounded px-2 py-1 w-full text-sm text-center">
                        </td>
                        <td class="py-2 px-4">
                            <input type="number" name="attendances[{{ $student->id }}][izin]" value="{{ $attendance->izin ?? 0 }}" min="0" class="border rounded px-2 py-1 w-full text-sm text-center">
                        </td>
                        <td class="py-2 px-4">
                            <input type="number" name="attendances[{{ $student->id }}][alpha]" value="{{ $attendance->alpha ?? 0 }}" min="0" class="border rounded px-2 py-1 w-full text-sm text-center">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-between items-center">
            <div class="text-xs text-gray-500">
                Menampilkan {{ $students->firstItem() }} - {{ $students->lastItem() }} dari {{ $students->total() }} siswa
            </div>
            <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded-lg hover:bg-blue-700 shadow transition">Simpan Rekap Absensi</button>
        </div>
    </form>
    <div class="mt-4">{{ $students->links() }}</div>
</div>
@endsection
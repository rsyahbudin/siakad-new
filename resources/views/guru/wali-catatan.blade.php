@extends('layouts.dashboard')
@section('title', 'Catatan Raport')
@section('content')
<h2 class="text-2xl font-bold mb-4">Catatan Raport untuk Kelas {{ $kelas->name }}</h2>
<p class="mb-4">Tahun Ajaran Aktif: <span class="font-semibold">{{ $activeYear->year ?? '-' }} Semester {{ $activeYear->semester ?? '-' }}</span></p>

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow-sm">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('wali.catatan.store') }}">
    @csrf
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-blue-100">
                <tr>
                    <th class="py-2 px-4 text-left w-12">#</th>
                    <th class="py-2 px-4 text-left">Nama Siswa</th>
                    <th class="py-2 px-4 text-left">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $i => $student)
                <tr class="border-b hover:bg-blue-50">
                    <td class="py-2 px-4 text-center">{{ $i + 1 }}</td>
                    <td class="py-2 px-4 font-medium">{{ $student->full_name }}</td>
                    <td class="py-2 px-4">
                        <textarea name="notes[{{ $student->id }}]" rows="2" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">{{ $raports->get($student->id)?->homeroom_teacher_notes ?? '' }}</textarea>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-4 text-center text-gray-500">Tidak ada siswa di kelas ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6 flex justify-end">
        <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded-lg hover:bg-blue-700 shadow transition">Simpan Semua Catatan</button>
    </div>
</form>
@endsection
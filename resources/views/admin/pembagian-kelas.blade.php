@extends('layouts.dashboard')
@section('title', 'Pembagian Kelas')
@section('content')
<h2 class="text-2xl font-bold mb-4">Pembagian Kelas Tahun Ajaran {{ $activeYear->year ?? '-' }} Semester {{ $activeYear->semester ?? '-' }}</h2>
@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif

<div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
    <form method="GET" class="flex gap-2 w-full md:w-auto">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/NIS..." class="border rounded px-3 py-2 w-full md:w-64 focus:outline-none focus:ring focus:border-blue-400">
        <select name="kelas_filter" class="border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400">
            <option value="">- Semua Kelas -</option>
            @foreach($classroomAssignments as $assignment)
            <option value="{{ $assignment->id }}" {{ request('kelas_filter') == $assignment->id ? 'selected' : '' }}>{{ $assignment->classroom->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">Cari</button>
    </form>
</div>

<form method="POST" action="{{ route('pembagian.kelas.store') }}" class="relative">
    @csrf
    <div class="overflow-x-auto bg-white rounded shadow max-h-[60vh]">
        <table class="min-w-full text-sm sticky-header">
            <thead class="bg-blue-100 sticky top-0 z-10">
                <tr>
                    <th class="py-2 px-4 text-left">#</th>
                    <th class="py-2 px-4 text-left">NIS</th>
                    <th class="py-2 px-4 text-left">Nama</th>
                    <th class="py-2 px-4 text-left">Kelas Saat Ini</th>
                    <th class="py-2 px-4 text-left">Pilih Kelas Baru</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $i => $siswa)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4">{{ $i+1 }}</td>
                    <td class="py-2 px-4">{{ $siswa->nis ?? '-' }}</td>
                    <td class="py-2 px-4">{{ $siswa->user->name ?? '-' }}</td>
                    <td class="py-2 px-4">
                        {{ $siswa->classrooms->first()->name ?? '-' }}
                    </td>
                    <td class="py-2 px-4">
                        <select name="assignments[{{ $siswa->id }}]" class="border rounded px-2 py-1">
                            <option value="">- Pilih Kelas -</option>
                            @foreach($classroomAssignments as $assignment)
                            <option value="{{ $assignment->id }}" {{ (optional($siswa->classStudents->first())->classroom_assignment_id ?? '') == $assignment->id ? 'selected' : '' }}>{{ $assignment->classroom->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-4 text-center text-gray-500">Tidak ada data siswa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="fixed bottom-8 right-8 z-20">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-full font-bold shadow-lg">Simpan Pembagian Kelas</button>
    </div>
</form>

{{-- Pagination (Blade only, simple) --}}
@if(method_exists($students, 'links'))
<div class="mt-4">{{ $students->links() }}</div>
@endif

<style>
    .sticky-header thead th {
        position: sticky;
        top: 0;
        background: #DBEAFE;
        z-index: 2;
    }
</style>
@endsection
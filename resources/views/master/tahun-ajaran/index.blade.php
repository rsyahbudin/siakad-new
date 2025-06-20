@extends('layouts.dashboard')
@section('title', 'Manajemen Tahun Ajaran')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-2xl font-bold">Manajemen Tahun Ajaran</h3>
    <a href="{{ route('tahun-ajaran.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">+ Tambah Tahun Ajaran</a>
</div>
@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif
<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full text-sm">
        <thead class="bg-blue-100">
            <tr>
                <th class="py-2 px-4 text-left">#</th>
                <th class="py-2 px-4 text-left">Tahun</th>
                <th class="py-2 px-4 text-left">Semester</th>
                <th class="py-2 px-4 text-left">Tanggal Mulai</th>
                <th class="py-2 px-4 text-left">Tanggal Selesai</th>
                <th class="py-2 px-4 text-left">Status Aktif</th>
                <th class="py-2 px-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($years as $i => $year)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-2 px-4">{{ $i+1 }}</td>
                <td class="py-2 px-4">{{ $year->year }}</td>
                <td class="py-2 px-4">{{ $year->semester == 1 ? 'Ganjil' : 'Genap' }}</td>
                <td class="py-2 px-4">{{ $year->start_date }}</td>
                <td class="py-2 px-4">{{ $year->end_date }}</td>
                <td class="py-2 px-4">
                    @if($year->is_active)
                    <span class="inline-block px-2 py-1 bg-green-200 text-green-800 rounded text-xs">Aktif</span>
                    @else
                    <form action="{{ route('tahun-ajaran.set-active', $year) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-block px-2 py-1 bg-gray-300 hover:bg-blue-500 hover:text-white text-gray-800 rounded text-xs">Aktifkan</button>
                    </form>
                    @endif
                </td>
                <td class="py-2 px-4 flex gap-2">
                    <a href="{{ route('tahun-ajaran.edit', $year) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</a>
                    <form action="{{ route('tahun-ajaran.destroy', $year) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tahun ajaran ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-4 text-center text-gray-500">Belum ada data tahun ajaran.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
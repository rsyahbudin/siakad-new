@extends('layouts.dashboard')
@section('title', 'Manajemen Mata Pelajaran')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-2xl font-bold">Manajemen Mata Pelajaran</h3>
    <a href="{{ route('mapel.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">+ Tambah Mapel</a>
</div>
@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif
<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full text-sm">
        <thead class="bg-blue-100">
            <tr>
                <th class="py-2 px-4 text-left">#</th>
                <th class="py-2 px-4 text-left">Kode</th>
                <th class="py-2 px-4 text-left">Nama Mapel</th>
                <th class="py-2 px-4 text-left">Jurusan</th>
                <th class="py-2 px-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subjects as $i => $mapel)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-2 px-4">{{ $i+1 }}</td>
                <td class="py-2 px-4">{{ $mapel->code }}</td>
                <td class="py-2 px-4">{{ $mapel->name }}</td>
                <td class="py-2 px-4">{{ $mapel->major->short_name ?? '-' }}</td>
                <td class="py-2 px-4 flex gap-2">
                    <a href="{{ route('mapel.edit', $mapel) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</a>
                    <form action="{{ route('mapel.destroy', $mapel) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mapel ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-4 text-center text-gray-500">Belum ada data mata pelajaran.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
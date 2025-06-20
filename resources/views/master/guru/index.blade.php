@extends('layouts.dashboard')
@section('title', 'Manajemen Guru')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-2xl font-bold">Manajemen Guru</h3>
    <a href="{{ route('guru.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">+ Tambah Guru</a>
</div>
@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif
<div class="mb-4 flex justify-end">
    <form method="GET" class="flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/NIP/email..." class="border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">Cari</button>
    </form>
</div>
<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full text-sm">
        <thead class="bg-blue-100">
            <tr>
                <th class="py-2 px-4 text-left">#</th>
                <th class="py-2 px-4 text-left">NIP</th>
                <th class="py-2 px-4 text-left">Nama</th>
                <th class="py-2 px-4 text-left">Email</th>
                <th class="py-2 px-4 text-left">No HP</th>
                <th class="py-2 px-4 text-left">Mapel Diampu</th>
                <th class="py-2 px-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teachers as $i => $guru)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-2 px-4">{{ $i+1 }}</td>
                <td class="py-2 px-4">{{ $guru->nip }}</td>
                <td class="py-2 px-4">{{ $guru->full_name }}</td>
                <td class="py-2 px-4">{{ $guru->user->email }}</td>
                <td class="py-2 px-4">{{ $guru->phone_number }}</td>
                <td class="py-2 px-4">
                    @if($guru->subject_id && $guru->subject)
                    <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">{{ $guru->subject->name }}</span>
                    @else
                    <span class="text-gray-400 italic">Belum diatur</span>
                    @endif
                </td>
                <td class="py-2 px-4 flex gap-2">
                    <a href="{{ route('guru.edit', $guru) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</a>
                    <form action="{{ route('guru.destroy', $guru) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus guru ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-4 text-center text-gray-500">Belum ada data guru.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
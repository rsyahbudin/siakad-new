@extends('layouts.dashboard')
@section('title', 'Manajemen Siswa')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-2xl font-bold">Manajemen Siswa</h3>
    <a href="{{ route('siswa.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">+ Tambah Siswa</a>
</div>
@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif
<div class="mb-4 flex justify-end">
    <form method="GET" class="flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/NIS/NISN/email..." class="border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">Cari</button>
    </form>
</div>
<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full text-sm">
        <thead class="bg-blue-100">
            <tr>
                <th class="py-2 px-4 text-left">#</th>
                <th class="py-2 px-4 text-left">NIS</th>
                <th class="py-2 px-4 text-left">NISN</th>
                <th class="py-2 px-4 text-left">Nama</th>
                <th class="py-2 px-4 text-left">L/P</th>
                <th class="py-2 px-4 text-left">Tempat, Tgl Lahir</th>
                <th class="py-2 px-4 text-left">Agama</th>
                <th class="py-2 px-4 text-left">Alamat</th>
                <th class="py-2 px-4 text-left">Nama Orang Tua</th>
                <th class="py-2 px-4 text-left">No HP Orang Tua</th>
                <th class="py-2 px-4 text-left">Nama</th>
                <th class="py-2 px-4 text-left">Email</th>
                <th class="py-2 px-4 text-left">Kelas</th>
                <th class="py-2 px-4 text-left">No HP</th>
                <th class="py-2 px-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $i => $siswa)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-2 px-4">{{ $i+1 }}</td>
                <td class="py-2 px-4">{{ $siswa->nis }}</td>
                <td class="py-2 px-4">{{ $siswa->nisn }}</td>
                <td class="py-2 px-4">{{ $siswa->full_name }}</td>
                <td class="py-2 px-4">{{ $siswa->gender }}</td>
                <td class="py-2 px-4">{{ $siswa->birth_place }}, {{ \Carbon\Carbon::parse($siswa->birth_date)->isoFormat('D MMMM Y') }}</td>
                <td class="py-2 px-4">{{ $siswa->religion }}</td>
                <td class="py-2 px-4">{{ $siswa->address }}</td>
                <td class="py-2 px-4">{{ $siswa->parent_name }}</td>
                <td class="py-2 px-4">{{ $siswa->parent_phone }}</td>
                <td class="py-2 px-4">{{ $siswa->full_name }}</td>
                <td class="py-2 px-4">{{ $siswa->user->email }}</td>
                <td class="py-2 px-4">
                    {{ $siswa->classrooms->first()->name ?? '-' }}
                </td>
                <td class="py-2 px-4">{{ $siswa->phone_number }}</td>
                <td class="py-2 px-4 flex gap-2">
                    <a href="{{ route('siswa.edit', $siswa) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</a>
                    <form action="{{ route('siswa.destroy', $siswa) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-4 text-center text-gray-500">Belum ada data siswa.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
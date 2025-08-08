@extends('layouts.dashboard')
@section('title', 'Manajemen Kepala Sekolah')
@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Kepala Sekolah</h1>
                <p class="text-gray-600 mt-1">Hanya boleh ada 1 akun Kepala Sekolah pada sistem.</p>
            </div>
            @if(!$kepala)
            <a href="{{ route('admin.kepsek.create') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium">Tambah</a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendidikan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TTL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if($kepala)
                    <tr>
                        <td class="px-6 py-4">{{ $kepala->name }}</td>
                        <td class="px-6 py-4">{{ $kepala->email }}</td>
                        <td class="px-6 py-4">{{ $kepala->kepalaSekolah->nip ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $kepala->kepalaSekolah->phone_number ?? '-' }}</td>
                        <td class="px-6 py-4">{{ trim(($kepala->kepalaSekolah->degree ?? '').' '.($kepala->kepalaSekolah->major ?? '').' '.($kepala->kepalaSekolah->university ?? '' )) ?: ($kepala->kepalaSekolah->last_education ?? '-') }}</td>
                        <td class="px-6 py-4">{{ ($kepala->kepalaSekolah->birth_place ?? '-') }}, {{ optional($kepala->kepalaSekolah->birth_date)->format('d/m/Y') ?? '-' }}</td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admin.kepsek.edit', $kepala->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-md bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium">Edit</a>
                            <form action="{{ route('admin.kepsek.destroy', $kepala->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus akun Kepala Sekolah?');">
                                @csrf
                                @method('DELETE')
                                <button class="inline-flex items-center px-3 py-1.5 rounded-md bg-red-600 hover:bg-red-700 text-white text-xs font-medium">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada Kepala Sekolah</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

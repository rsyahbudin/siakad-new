@extends('layouts.dashboard')

@section('title', 'Manajemen Ekstrakurikuler')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Manajemen Ekstrakurikuler</h1>
        <a href="{{ route('extracurricular.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Tambah Ekskul Baru
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Ekskul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembina</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($extracurriculars as $extracurricular)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $extracurricular->name }}</div>
                            @if($extracurricular->description)
                            <div class="text-sm text-gray-500">{{ Str::limit($extracurricular->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($extracurricular->category === 'Olahraga') bg-red-100 text-red-800
                                    @elseif($extracurricular->category === 'Seni') bg-purple-100 text-purple-800
                                    @elseif($extracurricular->category === 'Akademik') bg-blue-100 text-blue-800
                                    @elseif($extracurricular->category === 'Keagamaan') bg-green-100 text-green-800
                                    @elseif($extracurricular->category === 'Teknologi') bg-yellow-100 text-yellow-800
                                    @elseif($extracurricular->category === 'Bahasa') bg-indigo-100 text-indigo-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                {{ $extracurricular->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $extracurricular->teacher->full_name ?? 'Belum ditentukan' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $extracurricular->getScheduleText() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($extracurricular->max_participants)
                            {{ $extracurricular->getActiveStudentsCount() }} / {{ $extracurricular->max_participants }}
                            @if($extracurricular->isFull())
                            <span class="text-red-600 font-semibold">(Penuh)</span>
                            @endif
                            @else
                            {{ $extracurricular->getActiveStudentsCount() }} / ∞
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($extracurricular->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ $extracurricular->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('extracurricular.show', $extracurricular) }}"
                                    class="text-blue-600 hover:text-blue-900">Detail</a>
                                <a href="{{ route('extracurricular.edit', $extracurricular) }}"
                                    class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('extracurricular.destroy', $extracurricular) }}"
                                    method="POST" class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus ekskul ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Belum ada data ekstrakurikuler
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

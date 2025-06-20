@extends('layouts.dashboard')
@section('title', isset($tahunAjaran) ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran')
@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-xl font-bold mb-6">{{ isset($tahunAjaran) ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran' }}</h2>
    <form method="POST" action="{{ isset($tahunAjaran) ? route('tahun-ajaran.update', $tahunAjaran) : route('tahun-ajaran.store') }}">
        @csrf
        @if(isset($tahunAjaran))
        @method('PUT')
        @endif
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Tahun Ajaran <span class="text-red-500">*</span></label>
            <input type="text" name="year" value="{{ old('year', $tahunAjaran->year ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('year') border-red-500 @enderror" placeholder="2024/2025">
            @error('year')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Tanggal Mulai <span class="text-red-500">*</span></label>
            <input type="date" name="start_date" value="{{ old('start_date', isset($tahunAjaran) ? $tahunAjaran->start_date->format('Y-m-d') : '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('start_date') border-red-500 @enderror">
            @error('start_date')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Tanggal Selesai <span class="text-red-500">*</span></label>
            <input type="date" name="end_date" value="{{ old('end_date', isset($tahunAjaran) ? $tahunAjaran->end_date->format('Y-m-d') : '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('end_date') border-red-500 @enderror">
            @error('end_date')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4 flex items-center">
            <input type="checkbox" name="is_active" id="is_active" class="mr-2" {{ old('is_active', $tahunAjaran->is_active ?? false) ? 'checked' : '' }}>
            <label for="is_active" class="font-semibold">Tahun Ajaran Aktif</label>
        </div>
        <div class="flex gap-2 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">Simpan</button>
            <a href="{{ route('tahun-ajaran.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded font-semibold">Kembali</a>
        </div>
    </form>
</div>
@endsection
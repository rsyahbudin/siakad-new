@extends('layouts.dashboard')
@section('title', isset($mapel) ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran')
@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-xl font-bold mb-6">{{ isset($mapel) ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran' }}</h2>
    <form method="POST" action="{{ isset($mapel) ? route('mapel.update', $mapel) : route('mapel.store') }}">
        @csrf
        @if(isset($mapel))
        @method('PUT')
        @endif
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Kode Mapel <span class="text-red-500">*</span></label>
            <input type="text" name="code" value="{{ old('code', $mapel->code ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('code') border-red-500 @enderror" placeholder="Contoh: MAT">
            @error('code')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama Mapel <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $mapel->name ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('name') border-red-500 @enderror" placeholder="Contoh: Matematika">
            @error('name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Jurusan (Opsional)</label>
            <select name="major_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('major_id') border-red-500 @enderror">
                <option value="">- Semua Jurusan -</option>
                @foreach($majors as $major)
                <option value="{{ $major->id }}" {{ old('major_id', $mapel->major_id ?? '') == $major->id ? 'selected' : '' }}>{{ $major->short_name }} - {{ $major->name }}</option>
                @endforeach
            </select>
            @error('major_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex gap-2 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">Simpan</button>
            <a href="{{ route('mapel.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded font-semibold">Kembali</a>
        </div>
    </form>
</div>
@endsection
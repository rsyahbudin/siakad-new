@extends('layouts.dashboard')
@section('title', isset($jurusan) ? 'Edit Jurusan' : 'Tambah Jurusan')
@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-xl font-bold mb-6">{{ isset($jurusan) ? 'Edit Jurusan' : 'Tambah Jurusan' }}</h2>
    <form method="POST" action="{{ isset($jurusan) ? route('jurusan.update', $jurusan) : route('jurusan.store') }}">
        @csrf
        @if(isset($jurusan))
        @method('PUT')
        @endif
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama Jurusan <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $jurusan->name ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('name') border-red-500 @enderror" placeholder="Contoh: Ilmu Pengetahuan Alam">
            @error('name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Singkatan <span class="text-red-500">*</span></label>
            <input type="text" name="short_name" value="{{ old('short_name', $jurusan->short_name ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('short_name') border-red-500 @enderror" placeholder="Contoh: IPA">
            @error('short_name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex gap-2 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">Simpan</button>
            <a href="{{ route('jurusan.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded font-semibold">Kembali</a>
        </div>
    </form>
</div>
@endsection
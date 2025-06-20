@extends('layouts.dashboard')
@section('title', isset($guru) ? 'Edit Guru' : 'Tambah Guru')
@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-xl font-bold mb-6">{{ isset($guru) ? 'Edit Guru' : 'Tambah Guru' }}</h2>
    <form method="POST" action="{{ isset($guru) ? route('guru.update', $guru) : route('guru.store') }}">
        @csrf
        @if(isset($guru))
        @method('PUT')
        @endif
        <div class="mb-4">
            <label class="block mb-1 font-semibold">NIP <span class="text-red-500">*</span></label>
            <input type="text" name="nip" value="{{ old('nip', $guru->nip ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('nip') border-red-500 @enderror" placeholder="Contoh: 1987654321">
            @error('nip')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama <span class="text-red-500">*</span></label>
            <input type="text" name="full_name" value="{{ old('full_name', $guru->full_name ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('full_name') border-red-500 @enderror" placeholder="Contoh: Budi Santoso">
            @error('full_name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email', $guru->user->email ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('email') border-red-500 @enderror" placeholder="Contoh: guru@email.com">
            @error('email')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">No HP</label>
            <input type="text" name="phone" value="{{ old('phone', $guru->phone_number ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('phone') border-red-500 @enderror" placeholder="Contoh: 08123456789">
            @error('phone')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Alamat</label>
            <textarea name="address" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('address') border-red-500 @enderror" placeholder="Contoh: Jl. Melati No. 10, Jakarta">{{ old('address', $guru->address ?? '') }}</textarea>
            @error('address')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Mata Pelajaran Diampu <span class="text-red-500">*</span></label>
            <select name="subject_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('subject_id') border-red-500 @enderror">
                <option value="">- Pilih Mapel -</option>
                @foreach($subjects as $mapel)
                <option value="{{ $mapel->id }}" {{ old('subject_id', $guru->subject_id ?? '') == $mapel->id ? 'selected' : '' }}>{{ $mapel->name }}</option>
                @endforeach
            </select>
            @error('subject_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex gap-2 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">Simpan</button>
            <a href="{{ route('guru.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded font-semibold">Kembali</a>
        </div>
    </form>
</div>
@endsection
@extends('layouts.dashboard')
@section('title', isset($kelas) ? 'Edit Kelas' : 'Tambah Kelas')
@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-xl font-bold mb-6">{{ isset($kelas) ? 'Edit Kelas' : 'Tambah Kelas' }}</h2>
    <form method="POST" action="{{ isset($kelas) ? route('kelas.update', $kelas) : route('kelas.store') }}">
        @csrf
        @if(isset($kelas))
        @method('PUT')
        @endif
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama Kelas <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $kelas->name ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('name') border-red-500 @enderror" placeholder="Contoh: X IPA 1">
            @error('name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Wali Kelas <span class="text-red-500">*</span></label>
            <select name="homeroom_teacher_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('homeroom_teacher_id') border-red-500 @enderror">
                <option value="">- Pilih Wali Kelas -</option>
                @foreach($teachers as $guru)
                <option value="{{ $guru->id }}" {{ old('homeroom_teacher_id', $kelas->homeroom_teacher_id ?? '') == $guru->id ? 'selected' : '' }}>{{ $guru->name ?? $guru->user->name ?? '-' }}</option>
                @endforeach
            </select>
            @error('homeroom_teacher_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Jurusan <span class="text-red-500">*</span></label>
            <select name="major_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('major_id') border-red-500 @enderror">
                <option value="">- Pilih Jurusan -</option>
                @foreach($majors as $jurusan)
                <option value="{{ $jurusan->id }}" {{ old('major_id', $kelas->major_id ?? '') == $jurusan->id ? 'selected' : '' }}>{{ $jurusan->short_name }} - {{ $jurusan->name }}</option>
                @endforeach
            </select>
            @error('major_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Kapasitas <span class="text-red-500">*</span></label>
            <input type="number" name="capacity" value="{{ old('capacity', $kelas->capacity ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('capacity') border-red-500 @enderror" placeholder="Contoh: 30">
            @error('capacity')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <div class="flex gap-2 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">Simpan</button>
                <a href="{{ route('kelas.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded font-semibold">Kembali</a>
            </div>
    </form>
</div>
@endsection
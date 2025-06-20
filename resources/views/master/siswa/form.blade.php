@extends('layouts.dashboard')
@section('title', isset($siswa) ? 'Edit Siswa' : 'Tambah Siswa')
@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-xl font-bold mb-6">{{ isset($siswa) ? 'Edit Siswa' : 'Tambah Siswa' }}</h2>
    <form method="POST" action="{{ isset($siswa) ? route('siswa.update', $siswa) : route('siswa.store') }}">
        @csrf
        @if(isset($siswa))
        @method('PUT')
        @endif
        <div class="mb-4">
            <label class="block mb-1 font-semibold">NIS <span class="text-red-500">*</span></label>
            <input type="text" name="nis" value="{{ old('nis', $siswa->nis ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('nis') border-red-500 @enderror" placeholder="Contoh: 20230001">
            @error('nis')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">NISN <span class="text-red-500">*</span></label>
            <input type="text" name="nisn" value="{{ old('nisn', $siswa->nisn ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('nisn') border-red-500 @enderror" placeholder="Contoh: 1234567890">
            @error('nisn')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $siswa->full_name ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('name') border-red-500 @enderror" placeholder="Contoh: Andi Saputra">
            @error('name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email', $siswa->user->email ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('email') border-red-500 @enderror" placeholder="Contoh: siswa@email.com">
            @error('email')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Kelas <span class="text-red-500">*</span></label>
            <select name="classroom_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('classroom_id') border-red-500 @enderror">
                <option value="">- Pilih Kelas -</option>
                @foreach($classrooms as $kelas)
                <option value="{{ $kelas->id }}" {{ old('classroom_id', $siswa->classroom_id ?? '') == $kelas->id ? 'selected' : '' }}>{{ $kelas->name }}</option>
                @endforeach
            </select>
            @error('classroom_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Jenis Kelamin <span class="text-red-500">*</span></label>
            <select name="gender" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('gender') border-red-500 @enderror">
                <option value="">- Pilih -</option>
                <option value="L" {{ old('gender', $siswa->gender ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('gender', $siswa->gender ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('gender')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Tempat Lahir <span class="text-red-500">*</span></label>
            <input type="text" name="birth_place" value="{{ old('birth_place', $siswa->birth_place ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('birth_place') border-red-500 @enderror" placeholder="Contoh: Jakarta">
            @error('birth_place')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Tanggal Lahir <span class="text-red-500">*</span></label>
            <input type="date" name="birth_date" value="{{ old('birth_date', $siswa->birth_date ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('birth_date') border-red-500 @enderror">
            @error('birth_date')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Agama <span class="text-red-500">*</span></label>
            <input type="text" name="religion" value="{{ old('religion', $siswa->religion ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('religion') border-red-500 @enderror" placeholder="Contoh: Islam">
            @error('religion')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Alamat</label>
            <input type="text" name="address" value="{{ old('address', $siswa->address ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('address') border-red-500 @enderror" placeholder="Contoh: Jl. Merdeka No. 1">
            @error('address')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama Orang Tua</label>
            <input type="text" name="parent_name" value="{{ old('parent_name', $siswa->parent_name ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('parent_name') border-red-500 @enderror" placeholder="Contoh: Budi Santoso">
            @error('parent_name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">No HP Orang Tua</label>
            <input type="text" name="parent_phone" value="{{ old('parent_phone', $siswa->parent_phone ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('parent_phone') border-red-500 @enderror" placeholder="Contoh: 08123456789">
            @error('parent_phone')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">No HP</label>
            <input type="text" name="phone" value="{{ old('phone', $siswa->phone_number ?? '') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('phone') border-red-500 @enderror" placeholder="Contoh: 08123456789">
            @error('phone')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Status Siswa <span class="text-red-500">*</span></label>
            <select name="status" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('status') border-red-500 @enderror">
                <option value="Aktif" {{ old('status', $siswa->status ?? 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Pindahan" {{ old('status', $siswa->status ?? '') == 'Pindahan' ? 'selected' : '' }}>Pindahan</option>
            </select>
            @error('status')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex gap-2 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">Simpan</button>
            <a href="{{ route('siswa.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded font-semibold">Kembali</a>
        </div>
    </form>
</div>
@endsection
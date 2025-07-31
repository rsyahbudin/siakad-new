@extends('layouts.dashboard')
@section('title', 'Profil Siswa')
@section('content')

<div class="max-w-xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-xl font-bold mb-6">Profil Siswa</h2>
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('profil.siswa.update') }}">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold">NIS</label>
            <input type="text" value="{{ $siswa->nis }}" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">NISN</label>
            <input type="text" value="{{ $siswa->nisn }}" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="full_name" value="{{ old('full_name', $siswa->full_name) }}" class="w-full border rounded px-3 py-2 @error('full_name') border-red-500 @enderror bg-gray-100" readonly>
            @error('full_name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Jenis Kelamin <span class="text-red-500">*</span></label>
            <select name="gender" class="w-full border rounded px-3 py-2 @error('gender') border-red-500 @enderror bg-gray-100" disabled>
                <option value="L" {{ old('gender', $siswa->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('gender', $siswa->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('gender')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Tempat Lahir <span class="text-red-500">*</span></label>
            <input type="text" name="birth_place" value="{{ old('birth_place', $siswa->birth_place) }}" class="w-full border rounded px-3 py-2 @error('birth_place') border-red-500 @enderror bg-gray-100" readonly>
            @error('birth_place')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Tanggal Lahir <span class="text-red-500">*</span></label>
            <input type="date" name="birth_date" value="{{ old('birth_date', $siswa->birth_date ? \Carbon\Carbon::parse($siswa->birth_date)->format('Y-m-d') : '') }}" class="w-full border rounded px-3 py-2 @error('birth_date') border-red-500 @enderror bg-gray-100" readonly>
            @error('birth_date')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Agama <span class="text-red-500">*</span></label>
            <input type="text" name="religion" value="{{ old('religion', $siswa->religion) }}" class="w-full border rounded px-3 py-2 @error('religion') border-red-500 @enderror">
            @error('religion')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Alamat</label>
            <input type="text" name="address" value="{{ old('address', $siswa->address) }}" class="w-full border rounded px-3 py-2 @error('address') border-red-500 @enderror">
            @error('address')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama Orang Tua</label>
            <input type="text" name="parent_name" value="{{ old('parent_name', $siswa->parent_name) }}" class="w-full border rounded px-3 py-2 @error('parent_name') border-red-500 @enderror">
            @error('parent_name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">No HP Orang Tua</label>
            <input type="text" name="parent_phone" value="{{ old('parent_phone', $siswa->parent_phone) }}" class="w-full border rounded px-3 py-2 @error('parent_phone') border-red-500 @enderror">
            @error('parent_phone')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">No HP Siswa</label>
            <input type="text" name="phone_number" value="{{ old('phone_number', $siswa->phone_number) }}" class="w-full border rounded px-3 py-2 @error('phone_number') border-red-500 @enderror">
            @error('phone_number')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex gap-2 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">Simpan Perubahan</button>
        </div>
    </form>
</div>

<!-- Informasi Wali Murid -->
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow mt-8">
    <h2 class="text-xl font-bold mb-6">Informasi Wali Murid</h2>
    @if($siswa->waliMurids->count() > 0)
    @foreach($siswa->waliMurids as $wali)
    <div class="border rounded p-4 mb-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-1 font-semibold text-gray-600">Nama Wali</label>
                <p class="font-medium">{{ $wali->full_name }}</p>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-600">Hubungan</label>
                <p class="font-medium">{{ $wali->relationship }}</p>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-600">No. Telepon</label>
                <p class="font-medium">{{ $wali->phone_number ?? 'Tidak ada' }}</p>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-600">Alamat</label>
                <p class="font-medium">{{ $wali->address ?? 'Tidak ada' }}</p>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <div class="text-center py-8">
        <p class="text-gray-500">Belum ada data wali murid yang terdaftar</p>
    </div>
    @endif
</div>
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow mt-8">
    <h2 class="text-xl font-bold mb-6">Ganti Password</h2>
    @if(session('password_success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">{{ session('password_success') }}</div>
    @endif
    @if(session('password_error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">{{ session('password_error') }}</div>
    @endif
    <form method="POST" action="{{ route('profil.siswa.password') }}">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Password Lama <span class="text-red-500">*</span></label>
            <input type="password" name="current_password" class="w-full border rounded px-3 py-2 @error('current_password') border-red-500 @enderror">
            @error('current_password')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Password Baru <span class="text-red-500">*</span></label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2 @error('password') border-red-500 @enderror">
            @error('password')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2 @error('password_confirmation') border-red-500 @enderror">
            @error('password_confirmation')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex gap-2 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">Simpan Password</button>
        </div>
    </form>
</div>
@endsection
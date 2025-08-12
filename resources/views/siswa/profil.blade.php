@extends('layouts.dashboard')
@section('title', 'Profil Siswa')
@section('content')

<!-- Header Section -->
<div class="bg-white shadow-sm border-b border-gray-200 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Profil Siswa</h1>
                <p class="mt-1 text-sm text-gray-600">Kelola informasi profil dan akun Anda</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="bg-blue-50 px-4 py-2 rounded-lg">
                    <p class="text-sm font-medium text-blue-700">
                        Status: {{ $siswa->status ?? 'Aktif' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Success Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Profile Information Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Informasi Pribadi</h2>
            <p class="text-sm text-gray-600 mt-1">Data pribadi dan informasi kontak</p>
        </div>

        <form method="POST" action="{{ route('profil.siswa.update') }}" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- NIS -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIS</label>
                    <input type="text" value="{{ $siswa->nis }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600" readonly>
                </div>

                <!-- NISN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                    <input type="text" value="{{ $siswa->nisn }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600" readonly>
                </div>

                <!-- Nama Lengkap -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name', $siswa->full_name) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 @error('full_name') border-red-500 @enderror" readonly>
                    @error('full_name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="gender" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 @error('gender') border-red-500 @enderror" disabled>
                        <option value="L" {{ old('gender', $siswa->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $siswa->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Tempat Lahir -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir <span class="text-red-500">*</span></label>
                    <input type="text" name="birth_place" value="{{ old('birth_place', $siswa->birth_place) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 @error('birth_place') border-red-500 @enderror" readonly>
                    @error('birth_place')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $siswa->birth_date ? \Carbon\Carbon::parse($siswa->birth_date)->format('Y-m-d') : '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 @error('birth_date') border-red-500 @enderror" readonly>
                    @error('birth_date')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Agama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Agama <span class="text-red-500">*</span></label>
                    <input type="text" name="religion" value="{{ old('religion', $siswa->religion) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('religion') border-red-500 @enderror">
                    @error('religion')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <input type="text" name="address" value="{{ old('address', $siswa->address) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror">
                    @error('address')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- No HP Siswa -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">No HP Siswa</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $siswa->phone_number) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone_number') border-red-500 @enderror">
                    @error('phone_number')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Wali Murid Information Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Informasi Wali Murid</h2>
            <p class="text-sm text-gray-600 mt-1">Data wali murid yang terdaftar</p>
        </div>

        <div class="p-6">
            @if($siswa->waliMurids->count() > 0)
            <div class="space-y-4">
                @foreach($siswa->waliMurids as $wali)
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Wali</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $wali->full_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Hubungan</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $wali->relationship }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $wali->user->email ?? 'Tidak ada' }}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">No. Telepon</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $wali->phone_number ?? 'Tidak ada' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Alamat</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $wali->address ?? 'Tidak ada' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data wali murid</h3>
                <p class="text-gray-500">Data wali murid belum terdaftar dalam sistem</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Change Password Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Ganti Password</h2>
            <p class="text-sm text-gray-600 mt-1">Perbarui password akun Anda</p>
        </div>

        <div class="p-6">
            @if(session('password_success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('password_success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if(session('password_error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('password_error') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('profil.siswa.password') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Password Lama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Lama <span class="text-red-500">*</span></label>
                        <input type="password" name="current_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('current_password') border-red-500 @enderror">
                        @error('current_password')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru <span class="text-red-500">*</span></label>
                        <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                        @error('password')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password_confirmation') border-red-500 @enderror">
                        @error('password_confirmation')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
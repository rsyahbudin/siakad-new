@extends('layouts.dashboard')
@section('title', isset($siswa) ? 'Edit Siswa' : 'Tambah Siswa')
@section('content')

{{-- Header --}}
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('siswa.index') }}" class="text-indigo-600 hover:text-indigo-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">{{ isset($siswa) ? 'Edit Siswa' : 'Tambah Siswa' }}</h1>
            </div>
            <p class="mt-2 text-sm text-gray-600">
                {{ isset($siswa) ? 'Edit data siswa ' . $siswa->full_name : 'Tambah data siswa baru' }}
            </p>
        </div>
    </div>
</div>

{{-- Check if assignments are available --}}
@if($assignments->isEmpty())
<div class="mb-6 rounded-lg border border-yellow-200 bg-yellow-50 p-4">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-yellow-800">Tidak ada kelas yang tersedia</h3>
            <div class="mt-2 text-sm text-yellow-700">
                <p>Untuk menambahkan siswa, Anda perlu terlebih dahulu:</p>
                <ul class="list-disc list-inside mt-1 space-y-1">
                    <li>Membuat tahun ajaran aktif</li>
                    <li>Membuat kelas</li>
                    <li>Membagi guru ke kelas (pembagian kelas)</li>
                </ul>
            </div>
            <div class="mt-4">
                <a href="{{ route('kelas.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-yellow-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-yellow-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Kelola Kelas
                </a>
            </div>
        </div>
    </div>
</div>
@else

{{-- Student Form --}}
<div class="max-w-4xl mx-auto">
    {{-- Validation Errors --}}
    @if ($errors->any())
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Ada kesalahan dalam form</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">Form Data Siswa</h3>
        </div>

        <form method="POST" action="{{ isset($siswa) ? route('siswa.update', $siswa) : route('siswa.store') }}" class="p-6">
            @csrf
            @if(isset($siswa))
            @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                {{-- Personal Information --}}
                <div class="space-y-4">
                    <h4 class="text-md font-semibold text-gray-900 border-b border-gray-200 pb-2">Informasi Pribadi</h4>

                    <div>
                        <label for="nis" class="block text-sm font-medium text-gray-700 mb-1">NIS <span class="text-red-500">*</span></label>
                        <input type="text" name="nis" id="nis" value="{{ old('nis', $siswa->nis ?? '') }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nis') border-red-500 @enderror"
                            placeholder="Contoh: 20230001">
                        @error('nis')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="nisn" class="block text-sm font-medium text-gray-700 mb-1">NISN <span class="text-red-500">*</span></label>
                        <input type="text" name="nisn" id="nisn" value="{{ old('nisn', $siswa->nisn ?? '') }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nisn') border-red-500 @enderror"
                            placeholder="Contoh: 1234567890">
                        @error('nisn')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $siswa->full_name ?? '') }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                            placeholder="Contoh: Andi Saputra">
                        @error('name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $siswa->user->email ?? '') }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror"
                            placeholder="Contoh: siswa@email.com">
                        @error('email')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="gender" id="gender" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('gender') border-red-500 @enderror">
                            <option value="">- Pilih Jenis Kelamin -</option>
                            <option value="L" {{ old('gender', $siswa->gender ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender', $siswa->gender ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place', $siswa->birth_place ?? '') }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('birth_place') border-red-500 @enderror"
                            placeholder="Contoh: Jakarta">
                        @error('birth_place')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', isset($siswa->birth_date) ? (is_object($siswa->birth_date) ? $siswa->birth_date->format('Y-m-d') : $siswa->birth_date) : '') }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('birth_date') border-red-500 @enderror">
                        @error('birth_date')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="religion" class="block text-sm font-medium text-gray-700 mb-1">Agama <span class="text-red-500">*</span></label>
                        <input type="text" name="religion" id="religion" value="{{ old('religion', $siswa->religion ?? '') }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('religion') border-red-500 @enderror"
                            placeholder="Contoh: Islam">
                        @error('religion')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Academic & Contact Information --}}
                <div class="space-y-4">
                    <h4 class="text-md font-semibold text-gray-900 border-b border-gray-200 pb-2">Informasi Akademik & Kontak</h4>

                    <div>
                        <label for="assignment_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas & Tahun Ajaran <span class="text-red-500">*</span></label>
                        <select name="assignment_id" id="assignment_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('assignment_id') border-red-500 @enderror">
                            <option value="">- Pilih Kelas & Tahun Ajaran -</option>
                            @foreach($assignments as $assignment)
                            <option value="{{ $assignment->id }}" {{ old('assignment_id', (isset($siswa) && $siswa->classStudents->first() ? $siswa->classStudents->first()->classroom_assignment_id : '')) == $assignment->id ? 'selected' : '' }}>
                                {{ $assignment->classroom->name }} ({{ $assignment->academicYear->year ?? '' }})
                            </option>
                            @endforeach
                        </select>
                        @error('assignment_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Siswa <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror">
                            <option value="Aktif" {{ old('status', $siswa->status ?? 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Pindahan" {{ old('status', $siswa->status ?? '') == 'Pindahan' ? 'selected' : '' }}>Pindahan</option>
                        </select>
                        @error('status')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('address') border-red-500 @enderror"
                            placeholder="Contoh: Jl. Merdeka No. 1, Jakarta">{{ old('address', $siswa->address ?? '') }}</textarea>
                        @error('address')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="parent_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Orang Tua</label>
                        <input type="text" name="parent_name" id="parent_name" value="{{ old('parent_name', $siswa->parent_name ?? '') }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('parent_name') border-red-500 @enderror"
                            placeholder="Contoh: Budi Santoso">
                        @error('parent_name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="parent_phone" class="block text-sm font-medium text-gray-700 mb-1">No HP Orang Tua</label>
                        <input type="text" name="parent_phone" id="parent_phone" value="{{ old('parent_phone', $siswa->parent_phone ?? '') }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('parent_phone') border-red-500 @enderror"
                            placeholder="Contoh: 08123456789">
                        @error('parent_phone')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No HP Siswa</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $siswa->phone_number ?? '') }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror"
                            placeholder="Contoh: 08123456789">
                        @error('phone')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('siswa.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ isset($siswa) ? 'Update Siswa' : 'Simpan Siswa' }}
                </button>
            </div>
        </form>
    </div>
</div>

@endif

@endsection
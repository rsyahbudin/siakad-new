@extends('layouts.dashboard')
@section('title', 'Detail Siswa - ' . $siswa->full_name)
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
                <h1 class="text-3xl font-bold text-gray-900">Detail Siswa</h1>
            </div>
            <p class="mt-2 text-sm text-gray-600">
                Informasi lengkap siswa {{ $siswa->full_name }}
            </p>
            @if($userRole === 'teacher')
            <div class="mt-3">
                <div class="inline-flex items-center gap-2 rounded-lg bg-blue-50 border border-blue-200 px-3 py-2">
                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-medium text-blue-800">Mode Lihat Saja - Anda dapat melihat detail siswa tetapi tidak dapat mengedit</span>
                </div>
            </div>
            @endif
        </div>
        <div class="flex items-center gap-3">
            @if($canEdit)
            <a href="{{ route('siswa.edit', $siswa) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Siswa
            </a>
            @endif
        </div>
    </div>
</div>

{{-- Student Profile Card --}}
<div class="mb-8 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-8">
        <div class="flex items-center gap-6">
            <div class="h-24 w-24 flex-shrink-0">
                <div class="h-24 w-24 rounded-full bg-white/20 flex items-center justify-center border-4 border-white/30">
                    <span class="text-2xl font-bold text-white">{{ substr($siswa->full_name, 0, 2) }}</span>
                </div>
            </div>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-white">{{ $siswa->full_name }}</h1>
                <p class="mt-1 text-indigo-100">{{ $siswa->user->email }}</p>
                <div class="mt-3 flex items-center gap-4">
                    <span class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-sm font-medium text-white">
                        {{ $siswa->nis }}
                    </span>
                    <span class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-sm font-medium text-white">
                        {{ $siswa->nisn }}
                    </span>
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $siswa->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $siswa->status }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Student Information Grid --}}
<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    {{-- Personal Information --}}
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Pribadi</h3>
        </div>
        <div class="p-6">
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $siswa->full_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Jenis Kelamin</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $siswa->gender == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                            {{ $siswa->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tempat Lahir</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $siswa->birth_place }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal Lahir</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $siswa->birth_date ? $siswa->birth_date->format('d F Y') : '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Agama</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $siswa->religion }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $siswa->address ?: 'Tidak diisi' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Academic Information --}}
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Akademik</h3>
        </div>
        <div class="p-6">
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">NIS</dt>
                    <dd class="mt-1 font-mono text-sm text-gray-900">{{ $siswa->nis }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">NISN</dt>
                    <dd class="mt-1 font-mono text-sm text-gray-900">{{ $siswa->nisn }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Kelas Saat Ini</dt>
                    <dd class="mt-1">
                        @if($siswa->classStudents->first()?->classroomAssignment?->classroom)
                        <span class="inline-flex rounded-full bg-indigo-100 px-3 py-1 text-sm font-semibold text-indigo-800">
                            {{ $siswa->classStudents->first()->classroomAssignment->classroom->name }}
                        </span>
                        @else
                        <span class="text-sm text-gray-500">Belum ditentukan</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $siswa->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $siswa->status }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Contact Information --}}
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Kontak</h3>
        </div>
        <div class="p-6">
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $siswa->user->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $siswa->phone_number ?: 'Tidak diisi' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nama Orang Tua</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $siswa->parent_name ?: 'Tidak diisi' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Telepon Orang Tua</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $siswa->parent_phone ?: 'Tidak diisi' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Account Information --}}
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Akun</h3>
        </div>
        <div class="p-6">
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Username</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $siswa->user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $siswa->user->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Role</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800">
                            Siswa
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal Registrasi</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $siswa->created_at ? $siswa->created_at->format('d F Y H:i') : '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>

{{-- Wali Murid Information --}}
@if($siswa->waliMurids->count() > 0)
<div class="mt-6 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
        <h3 class="text-lg font-semibold text-gray-900">Informasi Wali Murid</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            @foreach($siswa->waliMurids as $wali)
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div class="mb-3">
                    <h4 class="text-sm font-medium text-gray-500">Wali Murid {{ $loop->iteration }}</h4>
                </div>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $wali->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Hubungan</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                {{ $wali->relationship }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $wali->phone_number ?: 'Tidak diisi' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $wali->address ?: 'Tidak diisi' }}</dd>
                    </div>
                </dl>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Back Button --}}
<div class="mt-8">
    <a href="{{ route('siswa.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Daftar
    </a>
</div>

@endsection
@extends('layouts.dashboard')
@section('title', 'Manajemen Kepala Sekolah')
@section('content')

<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Kepala Sekolah</h1>
                <p class="text-gray-600 mt-1">Hanya boleh ada 1 akun Kepala Sekolah pada sistem</p>
            </div>
            @if(!$kepala)
            <a href="{{ route('admin.kepsek.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Kepala Sekolah
            </a>
            @endif
        </div>
    </div>

    @if($kepala)
    <!-- Statistics Card -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div class="text-white">
                <h2 class="text-xl font-semibold">Kepala Sekolah Aktif</h2>
                <p class="text-blue-100 mt-1">Sistem saat ini dipimpin oleh</p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Profil Kepala Sekolah</h3>
        </div>
        <div class="p-6">
            <div class="flex items-start space-x-6">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <div class="h-20 w-20 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">{{ substr($kepala->name, 0, 2) }}</span>
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="flex-1 min-w-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $kepala->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $kepala->email }}</p>

                            <div class="mt-4 space-y-3">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-600">NIP: {{ $kepala->kepalaSekolah->nip ?? '-' }}</span>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-600">{{ $kepala->kepalaSekolah->phone_number ?? '-' }}</span>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-600">{{ $kepala->kepalaSekolah->birth_place ?? '-' }}</span>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-600">
                                        @if($kepala->kepalaSekolah->birth_date)
                                        {{ \Carbon\Carbon::parse($kepala->kepalaSekolah->birth_date)->format('d/m/Y') }}
                                        @else
                                        -
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h5 class="text-sm font-medium text-gray-900 mb-3">Pendidikan</h5>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-700">
                                    {{ trim(($kepala->kepalaSekolah->degree ?? '').' '.($kepala->kepalaSekolah->major ?? '').' '.($kepala->kepalaSekolah->university ?? '' )) ?: ($kepala->kepalaSekolah->last_education ?? '-') }}
                                </p>
                            </div>

                            <div class="mt-4">
                                <h5 class="text-sm font-medium text-gray-900 mb-3">Alamat</h5>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-700">{{ $kepala->kepalaSekolah->address ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex space-x-3">
                        <a href="{{ route('admin.kepsek.edit', $kepala->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Profil
                        </a>

                        <form action="{{ route('admin.user.reset-password', $kepala->id) }}" method="POST" class="inline" onsubmit="return confirm('Reset password Kepala Sekolah ini ke password123?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Reset Password
                            </button>
                        </form>

                        <form action="{{ route('admin.kepsek.destroy', $kepala->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus akun Kepala Sekolah?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus Akun
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada Kepala Sekolah</h3>
        <p class="text-gray-600 mb-6">Sistem belum memiliki akun Kepala Sekolah. Silakan tambahkan akun Kepala Sekolah untuk mengelola sistem.</p>
        <a href="{{ route('admin.kepsek.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Kepala Sekolah
        </a>
    </div>
    @endif
</div>

@endsection
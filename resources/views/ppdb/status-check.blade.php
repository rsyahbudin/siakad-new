@extends('layouts.app')

@section('title', 'Cek Status Pendaftaran')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Cek Status Pendaftaran</h1>
            <p class="text-gray-600">Masukkan nomor pendaftaran dan NISN untuk melihat status pendaftaran Anda</p>
        </div>

        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Status Check Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Form Cek Status</h2>
                <p class="text-sm text-gray-600">Masukkan data yang diperlukan untuk melihat status pendaftaran</p>
            </div>

            <form method="POST" action="{{ route('ppdb.status-check.post') }}" class="p-6 space-y-6">
                @csrf

                <div>
                    <label for="application_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Pendaftaran</label>
                    <input type="text" id="application_number" name="application_number" value="{{ old('application_number') }}" required
                        placeholder="Contoh: PPDB2025ABCD"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-600 mt-1">Masukkan nomor pendaftaran yang Anda terima saat mendaftar</p>
                </div>

                <div>
                    <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                    <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}" maxlength="10" required
                        placeholder="Contoh: 1234567890"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-600 mt-1">Masukkan NISN yang Anda gunakan saat mendaftar</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        Cek Status
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Section -->
        <div class="mt-8 bg-blue-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-blue-900 mb-4">Informasi Status</h3>
            <div class="space-y-3 text-sm text-blue-800">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span><strong>Menunggu:</strong> Pendaftaran sedang diproses oleh admin</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span><strong>Lulus:</strong> Pendaftaran diterima dan akun akan dibuat otomatis</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span><strong>Ditolak:</strong> Pendaftaran tidak memenuhi persyaratan</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 text-center">
            <a href="{{ route('ppdb.register') }}" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Daftar PPDB
            </a>
        </div>
    </div>
</div>
@endsection
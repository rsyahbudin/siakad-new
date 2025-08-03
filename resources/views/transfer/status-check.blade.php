@extends('layouts.app')

@section('title', 'Cek Status Pendaftaran - Siswa Pindahan')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Cek Status Pendaftaran</h1>
            <p class="text-gray-600">Masukkan nomor registrasi dan NISN untuk melihat status pendaftaran siswa pindahan</p>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <span class="text-red-800 font-medium">Terjadi kesalahan:</span>
            </div>
            <ul class="list-disc list-inside text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Status Check Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Form Cek Status</h2>
            </div>
            <form method="POST" action="{{ route('transfer.status-check.post') }}" class="p-6 space-y-6">
                @csrf

                <div>
                    <label for="registration_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Registrasi *
                    </label>
                    <input type="text"
                        id="registration_number"
                        name="registration_number"
                        value="{{ old('registration_number') }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('registration_number') ? 'border-red-500' : '' }}"
                        placeholder="Contoh: TS202508ABCD">
                    @error('registration_number')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-600 mt-1">Nomor registrasi yang Anda terima setelah mendaftar</p>
                </div>

                <div>
                    <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">
                        NISN *
                    </label>
                    <input type="text"
                        id="nisn"
                        name="nisn"
                        value="{{ old('nisn') }}"
                        required
                        maxlength="10"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('nisn') ? 'border-red-500' : '' }}"
                        placeholder="10 digit NISN">
                    @error('nisn')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-600 mt-1">Nomor Induk Siswa Nasional (10 digit)</p>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Cek Status
                </button>
            </form>
        </div>

        <!-- Help Section -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Butuh Bantuan?</h3>
            <div class="space-y-3 text-sm text-gray-600">
                <p class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span><strong>Lupa nomor registrasi?</strong> Cek email konfirmasi yang dikirim setelah mendaftar</span>
                </p>
                <p class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span><strong>Data tidak ditemukan?</strong> Pastikan nomor registrasi dan NISN sudah benar</span>
                </p>
                <p class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span><strong>Masih bermasalah?</strong> Hubungi admin sekolah di <a href="tel:+62211234567" class="text-blue-600 hover:text-blue-700 font-medium">+62 21 1234 5678</a></span>
                </p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('transfer.register') }}" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Daftar Siswa Pindahan
            </a>
        </div>

        <!-- Contact Info -->
        <div class="text-center mt-8 text-gray-600">
            <p class="text-sm">
                Email: <a href="mailto:admin@sekolah.sch.id" class="text-blue-600 hover:text-blue-700 font-medium">admin@sekolah.sch.id</a>
            </p>
        </div>
    </div>
</div>
@endsection
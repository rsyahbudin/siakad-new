@extends('layouts.dashboard')

@section('title', 'Ganti Password')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Ganti Password</h1>
            <p class="text-gray-600 mt-1">Ubah password akun Anda untuk keamanan yang lebih baik</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-green-800 font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Error Messages -->
        @if(isset($errors) && $errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-red-800 font-medium">Terjadi kesalahan:</span>
            </div>
            <ul class="text-red-700 text-sm space-y-1">
                @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Password Change Form -->
        <form action="{{ route('wali.change-password') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password Saat Ini
                </label>
                <div class="relative">
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @if(isset($errors) && $errors->has('current_password')) border-red-300 focus:ring-red-500 focus:border-red-500 @endif"
                        placeholder="Masukkan password saat ini"
                        required>
                    @if(isset($errors) && $errors->has('current_password'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('current_password') }}</p>
                    @endif
                </div>
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password Baru
                </label>
                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @if(isset($errors) && $errors->has('password')) border-red-300 focus:ring-red-500 focus:border-red-500 @endif"
                        placeholder="Masukkan password baru (min. 8 karakter)"
                        required>
                    @if(isset($errors) && $errors->has('password'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('password') }}</p>
                    @endif
                </div>
                <p class="mt-1 text-xs text-gray-500">Password minimal 8 karakter</p>
            </div>

            <!-- Confirm New Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Password Baru
                </label>
                <div class="relative">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Masukkan ulang password baru"
                        required>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="{{ route('wali.dashboard') }}" class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Ubah Password
                </button>
            </div>
        </form>

        <!-- Security Tips -->
        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="text-sm font-medium text-blue-900 mb-2">Tips Keamanan Password:</h3>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>• Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol</li>
                <li>• Jangan gunakan informasi pribadi seperti tanggal lahir atau nama</li>
                <li>• Jangan bagikan password dengan siapapun</li>
                <li>• Ganti password secara berkala</li>
            </ul>
        </div>
    </div>
</div>
@endsection

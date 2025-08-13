@extends('layouts.dashboard')

@section('title', 'Ganti Password Admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-yellow-600 via-yellow-700 to-orange-800 rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">🔐 Ganti Password</h1>
                    <p class="text-yellow-100">Ubah password untuk admin: {{ $admin->name }}</p>
                </div>
                <a href="{{ route('admin.management.index') }}"
                    class="bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/20 transition-all duration-300 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Admin Info -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-6">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-xl">
                    {{ strtoupper(substr($admin->name, 0, 2)) }}
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $admin->name }}</h3>
                    <p class="text-gray-600">{{ $admin->email }}</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
            <form action="{{ route('admin.management.update-password', $admin) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Current Password (only if not reset) -->
                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password Saat Ini
                        </label>
                        <input type="password" name="current_password" id="current_password"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            placeholder="Masukkan password saat ini (kosongkan untuk reset)">
                        <p class="text-sm text-gray-500 mt-1">Kosongkan field ini jika Anda ingin melakukan reset password</p>
                        @error('current_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="new_password" id="new_password" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            placeholder="Masukkan password baru (minimal 8 karakter)">
                        @error('new_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            Konfirmasi Password Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            placeholder="Konfirmasi password baru">
                    </div>

                    <!-- Hidden field for reset -->
                    <input type="hidden" name="is_reset" value="0" id="is_reset">
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.management.index') }}"
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-semibold">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Warning -->
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-yellow-800">Peringatan</h4>
                    <p class="text-sm text-yellow-700 mt-1">
                        Pastikan admin yang bersangkutan mengetahui perubahan password ini.
                        Jika Anda melakukan reset password, admin akan perlu menggunakan password baru untuk login.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentPasswordField = document.getElementById('current_password');
        const isResetField = document.getElementById('is_reset');

        currentPasswordField.addEventListener('input', function() {
            if (this.value === '') {
                isResetField.value = '1';
            } else {
                isResetField.value = '0';
            }
        });
    });
</script>
@endsection
@extends('layouts.dashboard')

@section('title', 'Raport Anak')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-6">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Data Raport Tidak Tersedia</h2>
            <p class="text-gray-600">{{ $message ?? 'Belum ada data raport yang dapat ditampilkan.' }}</p>
        </div>

        <div class="space-y-3">
            <a href="{{ route('wali.dashboard') }}"
                class="block w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
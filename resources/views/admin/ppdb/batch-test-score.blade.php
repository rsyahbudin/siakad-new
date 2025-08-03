@extends('layouts.dashboard')

@section('title', 'Input Nilai Tes Massal - PPDB')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Input Nilai Tes Massal</h1>
        <p class="text-gray-600">Input nilai tes untuk semua pendaftar jalur tes yang belum memiliki nilai</p>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
        <div class="flex">
            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="flex">
            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <div>
                <span class="text-red-800 font-medium">Terjadi kesalahan:</span>
                <ul class="text-red-700 text-sm mt-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    @if($applications->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Pendaftar Jalur Tes</h2>
                <span class="text-sm text-gray-600">{{ $applications->count() }} pendaftar</span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.ppdb.update-batch-test-score') }}">
            @csrf
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($applications as $application)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <div class="md:col-span-2">
                                <h3 class="font-medium text-gray-900">{{ $application->full_name }}</h3>
                                <p class="text-sm text-gray-600">NISN: {{ $application->nisn }}</p>
                                <p class="text-sm text-gray-600">Jurusan: {{ $application->desired_major }}</p>
                                <p class="text-sm text-gray-600">Tanggal Daftar: {{ $application->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <label for="test_score_{{ $application->id }}" class="block text-sm font-medium text-gray-700 mb-2">Nilai Tes</label>
                                <input type="number"
                                    id="test_score_{{ $application->id }}"
                                    name="test_scores[{{ $application->id }}]"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="0-100">
                                <p class="text-xs text-gray-500 mt-1">Minimal 70 untuk kelulusan</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        Simpan Semua Nilai Tes
                    </button>
                </div>
            </div>
        </form>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-8 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Pendaftar</h3>
            <p class="text-gray-600 mb-4">Tidak ada pendaftar jalur tes yang memerlukan input nilai tes.</p>
            <a href="{{ route('admin.ppdb.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                Kembali ke Daftar PPDB
            </a>
        </div>
    </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('admin.ppdb.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">
            ← Kembali ke Daftar PPDB
        </a>
    </div>
</div>
@endsection
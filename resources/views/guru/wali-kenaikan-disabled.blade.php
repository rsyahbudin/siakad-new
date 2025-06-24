@extends('layouts.dashboard')
@section('title', 'Penilaian Kenaikan Kelas')
@section('content')
<div class="container mx-auto mt-8">
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-r-lg shadow-md">
        <div class="flex">
            <div class="py-1">
                <svg class="h-8 w-8 text-yellow-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-yellow-800 mb-2">Fitur Belum Tersedia</h3>
                <p class="text-yellow-700">{{ $message }}</p>
                @isset($kelas)
                <p class="text-sm text-yellow-600 mt-2">Kelas: {{ $kelas->name }} | Tahun Ajaran: {{ $activeSemester->academicYear->year }} - Semester {{ $activeSemester->name }}</p>
                @endisset
                <div class="mt-4">
                    <a href="{{ url()->previous() }}" class="text-sm font-medium text-yellow-800 hover:text-yellow-900">Kembali ke halaman sebelumnya</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
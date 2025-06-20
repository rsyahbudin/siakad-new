@extends('layouts.dashboard')
@section('title', 'Leger Nilai')
@section('content')
<div class="px-4 py-6">
    <h2 class="text-2xl font-bold mb-4">Leger Nilai</h2>
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
        <div class="flex">
            <div class="py-1">
                <svg class="h-6 w-6 text-blue-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="font-bold">Informasi</p>
                <p class="text-sm">Anda tidak terdaftar sebagai wali kelas untuk tahun ajaran yang aktif saat ini. Halaman ini hanya bisa diakses oleh wali kelas.</p>
            </div>
        </div>
    </div>
</div>
@endsection
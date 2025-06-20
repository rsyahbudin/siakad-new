@extends('layouts.dashboard')
@section('title', 'Raport Digital')
@section('content')
<div class="px-4 py-6">
    <h2 class="text-2xl font-bold mb-4">Raport Digital</h2>
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
        <div class="flex">
            <div class="py-1">
                <svg class="h-6 w-6 text-yellow-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <p class="font-bold">Raport Belum Tersedia</p>
                <p class="text-sm">{{ $message ?? 'Informasi raport tidak dapat ditampilkan saat ini.' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
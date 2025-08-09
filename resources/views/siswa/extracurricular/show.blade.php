@extends('layouts.dashboard')

@section('title', 'Detail Ekstrakurikuler')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('siswa.extracurricular.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                ← Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-900">{{ $extracurricular->name }}</h1>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informasi Ekskul -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $extracurricular->name }}</h2>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                @if($extracurricular->category === 'Olahraga') bg-red-100 text-red-800
                                @elseif($extracurricular->category === 'Seni') bg-purple-100 text-purple-800
                                @elseif($extracurricular->category === 'Akademik') bg-blue-100 text-blue-800
                                @elseif($extracurricular->category === 'Keagamaan') bg-green-100 text-green-800
                                @elseif($extracurricular->category === 'Teknologi') bg-yellow-100 text-yellow-800
                                @elseif($extracurricular->category === 'Bahasa') bg-indigo-100 text-indigo-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $extracurricular->category }}
                            </span>
                        </div>
                        @if($enrollment)
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $enrollment->pivot->position }}
                        </span>
                        @endif
                    </div>

                    @if($extracurricular->description)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $extracurricular->description }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Informasi Jadwal</h3>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-gray-700">{{ $extracurricular->day ?? 'Belum ditentukan' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-700">
                                        @if($extracurricular->time_start && $extracurricular->time_end)
                                        {{ $extracurricular->time_start->format('H:i') }} - {{ $extracurricular->time_end->format('H:i') }}
                                        @else
                                        Belum ditentukan
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-gray-700">{{ $extracurricular->location ?? 'Belum ditentukan' }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Informasi Keanggotaan</h3>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="text-gray-700">Pembina: {{ $extracurricular->teacher->full_name ?? 'Belum ditentukan' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-gray-700">
                                        Kapasitas:
                                        @if($extracurricular->max_participants)
                                        {{ $extracurricular->getActiveStudentsCount() }} / {{ $extracurricular->max_participants }}
                                        @if($extracurricular->isFull())
                                        <span class="text-red-600 font-semibold">(Penuh)</span>
                                        @endif
                                        @else
                                        {{ $extracurricular->getActiveStudentsCount() }} / ∞
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($enrollment)
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Status Keanggotaan Anda</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Posisi</p>
                                <p class="font-semibold text-gray-900">{{ $enrollment->pivot->position }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $enrollment->pivot->status }}
                                </span>
                            </div>
                            @if($enrollment->pivot->achievements)
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-600">Prestasi</p>
                                <p class="text-gray-900">{{ $enrollment->pivot->achievements }}</p>
                            </div>
                            @endif
                            @if($enrollment->pivot->notes)
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-600">Catatan</p>
                                <p class="text-gray-900">{{ $enrollment->pivot->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Aksi -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h3>
                    @if($enrollment)
                    <form action="{{ route('siswa.extracurricular.leave', $extracurricular) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin keluar dari ekskul ini?')">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mb-3">
                            Keluar dari Ekskul
                        </button>
                    </form>
                    @else
                    @if(!$extracurricular->isFull())
                    <form action="{{ route('siswa.extracurricular.enroll', $extracurricular) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mb-3">
                            Daftar ke Ekskul
                        </button>
                    </form>
                    @else
                    <button disabled class="w-full bg-gray-400 text-white font-bold py-2 px-4 rounded mb-3 cursor-not-allowed">
                        Ekskul Penuh
                    </button>
                    @endif
                    @endif
                </div>

                <!-- Anggota Lain -->
                @if($otherStudents->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Anggota Lain</h3>
                    <div class="space-y-3">
                        @foreach($otherStudents as $student)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $student->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $student->pivot->position }}</p>
                            </div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $student->pivot->status }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
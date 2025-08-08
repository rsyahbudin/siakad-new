@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Input Nilai Ekstrakurikuler</h1>
        <p class="text-gray-600">Kelola nilai ekstrakurikuler yang Anda bimbing</p>
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

    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ekstrakurikuler yang Anda Bimbing</h2>

            @if($extracurriculars->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($extracurriculars as $extracurricular)
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $extracurricular->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $extracurricular->category }}</p>
                            <p class="text-sm text-gray-600">{{ $extracurricular->getScheduleText() }}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $extracurricular->students->count() }} Siswa
                        </span>
                    </div>

                    <div class="space-y-2 mb-4">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Lokasi:</span> {{ $extracurricular->location ?? 'Belum ditentukan' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Kapasitas:</span>
                            @if($extracurricular->max_participants)
                            {{ $extracurricular->getActiveStudentsCount() }}/{{ $extracurricular->max_participants }}
                            @else
                            Tidak terbatas
                            @endif
                        </p>
                    </div>

                    <div class="flex space-x-2">
                        <a href="{{ route('teacher.extracurricular-grade.show', $extracurricular) }}"
                            class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors text-center">
                            Input Nilai
                        </a>
                        <a href="{{ route('teacher.extracurricular-grade.template', $extracurricular) }}"
                            class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                            Template
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Ekstrakurikuler</h3>
                <p class="text-gray-600">Anda belum ditugaskan sebagai pembimbing ekstrakurikuler apapun.</p>
                <p class="text-sm text-gray-500 mt-2">Hanya guru yang ditugaskan sebagai pembina ekstrakurikuler yang dapat mengakses fitur ini.</p>
            </div>
            @endif
        </div>
    </div>

    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="text-sm font-medium text-blue-900 mb-2">Informasi Penilaian</h3>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• Nilai ekstrakurikuler menggunakan skala: Sangat Baik, Baik, Cukup, Kurang</li>
            <li>• Anda dapat input nilai secara manual atau menggunakan template Excel</li>
            <li>• Nilai akan ditampilkan di raport siswa</li>
            <li>• Pastikan semua siswa aktif telah dinilai sebelum semester berakhir</li>
            <li>• Hanya pembina ekstrakurikuler yang dapat mengakses dan menginput nilai</li>
        </ul>
    </div>
</div>
@endsection
@extends('layouts.dashboard')

@section('title', 'Ekstrakurikuler')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Ekstrakurikuler</h1>

    <!-- Informasi Aturan Ekstrakurikuler -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <h3 class="text-sm font-medium text-blue-900 mb-2">Aturan Ekstrakurikuler</h3>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• Setiap siswa wajib mengikuti minimal 1 ekstrakurikuler per tahun ajaran</li>
            <li>• Siswa hanya dapat mengikuti 1 ekstrakurikuler pada saat yang sama</li>
            <li>• Untuk keluar dari ekstrakurikuler, siswa harus mendaftar ke ekstrakurikuler lain terlebih dahulu</li>
            <li>• Data ekstrakurikuler akan ditampilkan di raport akhir semester</li>
        </ul>
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

    <!-- Ekskul yang Saya Ikuti -->
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Ekskul yang Saya Ikuti</h2>
        @if($myExtracurriculars->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($myExtracurriculars as $extracurricular)
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $extracurricular->name }}</h3>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        Anggota
                    </span>
                </div>

                @if($extracurricular->description)
                <p class="text-gray-600 text-sm mb-3">{{ $extracurricular->description }}</p>
                @endif

                <div class="space-y-2 text-sm text-gray-600">
                    <div><strong>Kategori:</strong> {{ $extracurricular->category }}</div>
                    <div><strong>Pembina:</strong> {{ $extracurricular->teacher->full_name ?? 'Belum ditentukan' }}</div>
                    <div><strong>Jadwal:</strong> {{ $extracurricular->getScheduleText() }}</div>
                    @if($extracurricular->pivot->achievements)
                    <div><strong>Prestasi:</strong> {{ $extracurricular->pivot->achievements }}</div>
                    @endif
                </div>

                <div class="mt-4 flex space-x-2">
                    <a href="{{ route('siswa.extracurricular.show', $extracurricular) }}"
                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">Detail</a>
                    <form action="{{ route('siswa.extracurricular.leave', $extracurricular) }}"
                        method="POST" class="inline"
                        onsubmit="return confirm('Apakah Anda yakin ingin keluar dari ekskul ini?')">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Keluar</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gray-50 rounded-lg p-6 text-center">
            <p class="text-gray-500">Anda belum mengikuti ekskul apapun.</p>
        </div>
        @endif
    </div>

    <!-- Ekskul yang Tersedia -->
    <div>
        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Ekskul yang Tersedia</h2>
        @if($availableExtracurriculars->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($availableExtracurriculars as $extracurricular)
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $extracurricular->name }}</h3>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
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

                @if($extracurricular->description)
                <p class="text-gray-600 text-sm mb-3">{{ $extracurricular->description }}</p>
                @endif

                <div class="space-y-2 text-sm text-gray-600">
                    <div><strong>Pembina:</strong> {{ $extracurricular->teacher->full_name ?? 'Belum ditentukan' }}</div>
                    <div><strong>Jadwal:</strong> {{ $extracurricular->getScheduleText() }}</div>
                    <div><strong>Kapasitas:</strong>
                        @if($extracurricular->max_participants)
                        {{ $extracurricular->getActiveStudentsCount() }} / {{ $extracurricular->max_participants }}
                        @if($extracurricular->isFull())
                        <span class="text-red-600 font-semibold">(Penuh)</span>
                        @endif
                        @else
                        {{ $extracurricular->getActiveStudentsCount() }} / ∞
                        @endif
                    </div>
                </div>

                <div class="mt-4 flex space-x-2">
                    <a href="{{ route('siswa.extracurricular.show', $extracurricular) }}"
                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">Detail</a>
                    @if(!$extracurricular->isFull())
                    <form action="{{ route('siswa.extracurricular.enroll', $extracurricular) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-medium">Daftar</button>
                    </form>
                    @else
                    <span class="text-red-600 text-sm font-medium">Penuh</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gray-50 rounded-lg p-6 text-center">
            <p class="text-gray-500">Tidak ada ekskul yang tersedia saat ini.</p>
        </div>
        @endif
    </div>
</div>
@endsection
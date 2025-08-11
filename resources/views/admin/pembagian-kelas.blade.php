@extends('layouts.dashboard')
@section('title', 'Pembagian Kelas')
@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold mb-2">Pembagian Kelas</h2>
    <p class="text-gray-600">Tahun Ajaran {{ $activeYear->year ?? '-' }} Semester {{ $activeSemester->name ?? '-' }}</p>
</div>

@if(session('success'))
<div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
    {{ session('error') }}
</div>
@endif

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow border">
        <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Siswa</p>
                <p class="text-2xl font-bold text-gray-900">{{ $placementStats['total_students'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow border">
        <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Sudah Ditempatkan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $placementStats['placed_students'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow border">
        <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Belum Ditempatkan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $placementStats['unplaced_students'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow border">
        <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Progress</p>
                <p class="text-2xl font-bold text-gray-900">{{ $placementStats['placement_percentage'] }}%</p>
            </div>
        </div>
    </div>
</div>

<!-- Class Statistics -->
<div class="bg-white p-6 rounded-lg shadow border mb-6">
    <h3 class="text-lg font-semibold mb-4">Statistik Per Kelas</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($placementStats['class_stats'] as $classStat)
        <div class="border rounded-lg p-4">
            <div class="flex justify-between items-center mb-2">
                <h4 class="font-medium">{{ $classStat['name'] }}</h4>
                <span class="text-sm text-gray-500">{{ $classStat['student_count'] }}/{{ $classStat['capacity'] }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $classStat['percentage'] }}%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-1">{{ $classStat['percentage'] }}% terisi</p>
        </div>
        @endforeach
    </div>
</div>

<!-- Action Buttons -->
<div class="mb-6 flex flex-col sm:flex-row gap-4">
    <form method="POST" action="{{ route('pembagian.kelas.auto-place') }}" class="flex-1">
        @csrf
        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Auto-Placement Siswa
        </button>
    </form>

    <button onclick="document.getElementById('searchForm').classList.toggle('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center justify-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        Filter & Pencarian
    </button>
</div>

<!-- Search and Filter Form -->
<div id="searchForm" class="mb-6 bg-white p-4 rounded-lg shadow border">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Cari Siswa</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Nama/NIS..."
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Filter Kelas</label>
            <select name="kelas_filter" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kelas</option>
                @foreach($classroomAssignments as $assignment)
                <option value="{{ $assignment->id }}" {{ request('kelas_filter') == $assignment->id ? 'selected' : '' }}>
                    {{ $assignment->classroom->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status Penempatan</label>
            <select name="status_filter" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="placed" {{ request('status_filter') == 'placed' ? 'selected' : '' }}>Sudah Ditempatkan</option>
                <option value="not_placed" {{ request('status_filter') == 'not_placed' ? 'selected' : '' }}>Belum Ditempatkan</option>
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">
                Cari
            </button>
        </div>
    </form>
</div>

<!-- Student Assignment Form -->
<form method="POST" action="{{ route('pembagian.kelas.store') }}" class="bg-white rounded-lg shadow border">
    @csrf
    <div class="p-6 border-b">
        <h3 class="text-lg font-semibold">Penempatan Manual Siswa</h3>
        <p class="text-sm text-gray-600 mt-1">Pilih kelas untuk setiap siswa secara manual</p>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas Saat Ini</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pilih Kelas Baru</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($students as $i => $siswa)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $i+1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $siswa->nis ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $siswa->full_name ?? $siswa->user->name ?? '-' }}</div>
                        <div class="text-sm text-gray-500">{{ $siswa->user->email ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($siswa->classStudents->isNotEmpty())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $siswa->classStudents->first()->classroomAssignment->classroom->name }}
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Belum Ditempatkan
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <select name="assignments[{{ $siswa->id }}]" class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">- Pilih Kelas -</option>
                            @foreach($classroomAssignments as $assignment)
                            <option value="{{ $assignment->id }}"
                                {{ (optional($siswa->classStudents->first())->classroom_assignment_id ?? '') == $assignment->id ? 'selected' : '' }}>
                                {{ $assignment->classroom->name }}
                            </option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data siswa yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-6 border-t bg-gray-50">
        <div class="flex justify-between items-center">
            <p class="text-sm text-gray-600">
                Menampilkan {{ $students->count() }} dari {{ $students->total() }} siswa
            </p>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                Simpan Pembagian Kelas
            </button>
        </div>
    </div>
</form>

<!-- Pagination -->
@if($students->hasPages())
<div class="mt-6">
    {{ $students->links() }}
</div>
@endif

<script>
    // Toggle search form visibility
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        if (searchForm) {
            // Hide search form by default on mobile
            if (window.innerWidth < 768) {
                searchForm.classList.add('hidden');
            }
        }
    });
</script>

@endsection
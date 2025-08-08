@extends('layouts.dashboard')

@section('title', 'Dashboard Wali Murid')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Wali Murid</h1>
                <p class="text-gray-600 mt-1">Ringkasan akademik dan kehadiran anak Anda</p>
            </div>
        </div>
    </div>

    <!-- Student Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Anak</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="space-y-2">
                    <div class="flex items-center justify-between"><span class="text-gray-600">Nama Lengkap</span><span class="font-medium text-gray-900">{{ $student->full_name }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-gray-600">NIS</span><span class="font-medium text-gray-900">{{ $student->nis }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-gray-600">NISN</span><span class="font-medium text-gray-900">{{ $student->nisn }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-gray-600">Jenis Kelamin</span><span class="font-medium text-gray-900">{{ $student->gender }}</span></div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between"><span class="text-gray-600">Tempat Lahir</span><span class="font-medium text-gray-900">{{ $student->birth_place }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-gray-600">Tanggal Lahir</span><span class="font-medium text-gray-900">{{ $student->birth_date ? $student->birth_date->format('d/m/Y') : 'N/A' }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-gray-600">Agama</span><span class="font-medium text-gray-900">{{ $student->religion }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-gray-600">Status</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $student->status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ $student->status }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center justify-center">
            <div class="text-center">
                <div class="w-20 h-20 mx-auto mb-3 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-2xl font-bold">
                    {{ substr($student->full_name, 0, 2) }}
                </div>
                <div class="font-semibold text-gray-900">{{ $student->full_name }}</div>
                <div class="text-gray-500 text-sm">NIS {{ $student->nis }}</div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Rata-rata Nilai</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $avgFinalGrade ?? '-' }}</p>
                    <p class="text-xs text-gray-500">Dari nilai terbaru</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Kehadiran</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $attendanceStats['percentage'] ?? 0 }}%</p>
                    <p class="text-xs text-gray-500">{{ $attendanceStats['hadir'] ?? 0 }} dari {{ $attendanceStats['total_days'] ?? 0 }} hari</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Sakit</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $attendanceStats['sakit'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Semester {{ $activeSemester->name ?? '-' }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Alpha</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $attendanceStats['alpha'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Semester {{ $activeSemester->name ?? '-' }}</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Nilai Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UTS</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UAS</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($grades as $grade)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-700 flex items-center justify-center text-xs font-semibold">
                                        {{ substr($grade->subject->name ?? 'N/A', 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $grade->subject->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $grade->classroom->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $grade->classroom->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">{{ $grade->assignment_grade ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">{{ $grade->uts_grade ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">{{ $grade->uas_grade ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold">{{ $grade->final_grade ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">Tidak ada data nilai terbaru</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($grades, 'links'))
            <div class="px-6 py-4 border-t border-gray-200">{{ $grades->links() }}</div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Rekap Absensi Semester</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sakit</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Izin</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Alpha</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($attendance as $att)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $att->semester->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">{{ $att->sakit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">{{ $att->izin }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">{{ $att->alpha }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Tidak ada data absensi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($attendance, 'links'))
            <div class="px-6 py-4 border-t border-gray-200">{{ $attendance->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
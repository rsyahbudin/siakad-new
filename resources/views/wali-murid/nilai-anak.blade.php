@extends('layouts.dashboard')

@section('title', 'Nilai Anak')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nilai Anak</h1>
                <p class="text-gray-600 mt-1">Daftar nilai akademik anak Anda</p>
            </div>
        </div>
    </div>

    <!-- Grades Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Nilai</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UTS</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UAS</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Akhir</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($grades as $grade)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $grade->subject->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $grade->classroom->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $grade->semester->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">{{ $grade->assignment_grade ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">{{ $grade->uts_grade ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">{{ $grade->uas_grade ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold">{{ $grade->final_grade ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">Tidak ada data nilai</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($grades, 'links'))
        <div class="px-6 py-4 border-t border-gray-200">{{ $grades->links() }}</div>
        @endif
    </div>
</div>
@endsection
@extends('layouts.dashboard')
@section('title', 'Proses Kenaikan & Kelulusan')
@section('content')
<h2 class="text-2xl font-bold mb-4">Proses Kenaikan & Kelulusan Massal</h2>
<p class="mb-6">Tahun Ajaran yang akan diproses: <span class="font-semibold">{{ $academicYear->year }}</span></p>

@if (session('success'))
<div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-md shadow-sm">
    <p>{{ session('success') }}</p>
</div>
@endif
@if (session('error'))
<div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-md shadow-sm">
    <p>{{ session('error') }}</p>
</div>
@endif

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-4 border-b">
        <h3 class="text-lg font-semibold">Status Kesiapan Penilaian per Kelas</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 text-left">Kelas</th>
                    <th class="py-2 px-4 text-left">Wali Kelas</th>
                    <th class="py-2 px-4 text-center">Jumlah Siswa</th>
                    <th class="py-2 px-4 text-center">Data Tersimpan</th>
                    <th class="py-2 px-4 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promotionStatus as $status)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4 font-medium">{{ $status->assignment->classroom->name }}</td>
                    <td class="py-2 px-4">{{ $status->assignment->homeroomTeacher->user->name ?? 'N/A' }}</td>
                    <td class="py-2 px-4 text-center">{{ $status->student_count }}</td>
                    <td class="py-2 px-4 text-center">{{ $status->promotion_count }}</td>
                    <td class="py-2 px-4">
                        @if($status->is_ready)
                        <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">{{ $status->status_message }}</span>
                        @else
                        <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full">{{ $status->status_message }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-4 px-4 text-center text-gray-500">Tidak ada data kelas untuk tahun ajaran ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 bg-gray-50 flex justify-end">
        <form action="{{ route('admin.promotions.process') }}" method="POST">
            @csrf
            <button type="submit"
                class="px-6 py-2 text-white font-semibold rounded-lg shadow-md
                           {{ $allReady ? 'bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50' : 'bg-gray-400 cursor-not-allowed' }}"
                {{ !$allReady ? 'disabled' : '' }}
                onclick="return confirm('Apakah Anda yakin ingin menjalankan proses kenaikan dan kelulusan? Tindakan ini tidak dapat diurungkan.')">
                Proses Kenaikan & Kelulusan
            </button>
        </form>
    </div>
</div>
@if(!$allReady)
<div class="mt-4 p-4 text-sm text-yellow-800 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
    <strong>Perhatian:</strong> Tombol proses akan aktif setelah semua wali kelas menyelesaikan pengisian data kenaikan/kelulusan untuk kelas mereka masing-masing.
</div>
@endif

@endsection
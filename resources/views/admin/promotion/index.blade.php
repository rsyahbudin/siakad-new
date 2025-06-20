@extends('layouts.dashboard')
@section('title', 'Proses Kenaikan Kelas & Kelulusan')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-2">Proses Kenaikan Kelas & Kelulusan</h2>
    <p class="mb-4 text-gray-600">Semester yang akan diproses: <span class="font-semibold">{{ $activeSemester->academicYear->year ?? '-' }} (Semester {{ $activeSemester->name ?? '-' }})</span></p>

    <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800">
        <h4 class="font-bold">Perhatian!</h4>
        <ul class="list-disc list-inside text-sm">
            <li>Pastikan semua wali kelas telah menyelesaikan pengisian keputusan kenaikan kelas.</li>
            <li>Proses ini bersifat <strong class="font-bold">final</strong> dan akan memindahkan siswa ke kelas baru pada tahun ajaran berikutnya atau mengubah statusnya menjadi 'Lulus'.</li>
            <li>Pastikan Anda telah membuat data Tahun Ajaran untuk <strong class="font-bold">{{ $nextYear ? $nextYear->getYearString() : 'tahun berikutnya' }}</strong> sebelum melanjutkan.</li>
            <li>Aksi ini tidak dapat dibatalkan.</li>
        </ul>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded shadow-sm">{{ session('error') }}</div>
    @endif
    @if(session('info'))
    <div class="mb-4 p-3 bg-blue-100 text-blue-800 rounded shadow-sm">{{ session('info') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 text-left">Kelas</th>
                    <th class="py-2 px-4 text-left">Wali Kelas</th>
                    <th class="py-2 px-4 text-center">Jml. Siswa</th>
                    <th class="py-2 px-4 text-center">Keputusan (Diisi)</th>
                    <th class="py-2 px-4 text-center">Naik Kelas</th>
                    <th class="py-2 px-4 text-center">Tinggal Kelas</th>
                    <th class="py-2 px-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promotionSummary as $summary)
                <tr class="border-b">
                    <td class="py-2 px-4 font-medium">{{ $summary->classroom->name }}</td>
                    <td class="py-2 px-4">{{ $summary->classroom->homeroomTeacher->user->name ?? 'N/A' }}</td>
                    <td class="py-2 px-4 text-center">{{ $summary->total_students }}</td>
                    <td class="py-2 px-4 text-center">{{ $summary->decided }}</td>
                    <td class="py-2 px-4 text-center text-green-600 font-semibold">{{ $summary->promoted }}</td>
                    <td class="py-2 px-4 text-center text-red-600 font-semibold">{{ $summary->retained }}</td>
                    <td class="py-2 px-4 text-center">
                        @if($summary->total_students > 0 && $summary->decided == $summary->total_students)
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Lengkap</span>
                        @else
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Belum Lengkap</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-4 text-center text-gray-500">Tidak ada data kelas untuk tahun ajaran aktif.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        <form method="POST" action="{{ route('kenaikan-kelas.store') }}" onsubmit="return confirm('Apakah Anda yakin ingin memproses kenaikan kelas? Aksi ini tidak dapat dibatalkan.');">
            @csrf
            @if($nextYear)
            <button type="submit" class="bg-red-600 text-white px-8 py-2 rounded-lg hover:bg-red-700 shadow transition font-bold">
                PROSES KENAIKAN KELAS
            </button>
            @else
            <button type="button" class="bg-gray-400 text-white px-8 py-2 rounded-lg cursor-not-allowed" disabled>
                Tahun Ajaran Berikutnya Belum Dibuat
            </button>
            @endif
        </form>
    </div>
</div>
@endsection
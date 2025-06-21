@extends('layouts.dashboard')
@section('title', 'Finalisasi Raport')
@section('content')
<h2 class="text-2xl font-bold mb-4">Finalisasi Raport untuk Kelas {{ $kelas->name ?? '' }}</h2>
<p class="mb-4">Tahun Ajaran Aktif: <span class="font-semibold">{{ $activeSemester->academicYear->year ?? '-' }} Semester {{ $activeSemester->name ?? '-' }}</span></p>

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 p-3 bg-red-100 text-red-800 rounded shadow-sm">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        @if($isFinalized)
        <div class="p-4 text-center bg-green-50 text-green-800 rounded-lg">
            <p class="font-bold text-lg">Raport telah difinalisasi.</p>
            <p class="text-sm mt-1">Data raport untuk semester ini telah dikunci dan tidak dapat diubah lagi.</p>
        </div>
        @else
        <div class="p-4 mb-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800">
            <h4 class="font-bold">Perhatian!</h4>
            <p>Proses finalisasi akan membuat raport resmi untuk semua siswa di kelas ini. Setelah difinalisasi, data nilai, absensi, dan catatan tidak dapat diubah lagi. Pastikan semua data sudah benar sebelum melanjutkan.</p>
        </div>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-blue-100">
                <tr>
                    <th class="py-2 px-4 text-left">Nama Siswa</th>
                    <th class="py-2 px-4 text-center">Status</th>
                    @if(!$isFinalized)
                    <th class="py-2 px-4 text-center">Catatan Wali Kelas</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(!$isFinalized)
                <form method="POST" action="{{ route('wali.finalisasi.store') }}" onsubmit="return confirm('Apakah Anda yakin ingin memfinalisasi raport? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    @foreach($students->sortBy('student.full_name') as $classStudent)
                    <tr class="border-b hover:bg-blue-50">
                        <td class="py-2 px-4 font-medium">{{ $classStudent->student->full_name }}</td>
                        <td class="py-2 px-4 text-center">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-200 text-blue-700">Siap Finalisasi</span>
                        </td>
                        <td class="py-2 px-4">
                            <textarea name="catatan[{{ $classStudent->student_id }}]" rows="2" class="w-full border rounded px-2 py-1 text-xs resize-none" placeholder="Catatan untuk siswa ini...">{{ old('catatan.' . $classStudent->student_id) }}</textarea>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="py-4 text-center">
                            <button type="submit" class="bg-red-600 text-white px-8 py-3 rounded-lg hover:bg-red-700 shadow-lg transition font-bold text-lg">
                                Finalisasi Raport Kelas {{ $kelas->name }}
                            </button>
                        </td>
                    </tr>
                </form>
                @else
                @foreach($displayData->sortBy('student.full_name') as $raport)
                <tr class="border-b hover:bg-blue-50">
                    <td class="py-2 px-4 font-medium">{{ $raport->student->full_name }}</td>
                    <td class="py-2 px-4 text-center">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Final</span>
                        <br>
                        <span class="text-xs text-gray-500">{{ $raport->finalized_at ? $raport->finalized_at->format('d/m/Y H:i') : '' }}</span>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
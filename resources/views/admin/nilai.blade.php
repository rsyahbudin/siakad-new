@extends('layouts.dashboard')
@section('title', 'Nilai Siswa')
@section('content')
<h2 class="text-2xl font-bold mb-4">Nilai Siswa</h2>
<p class="mb-4">Tahun Ajaran Aktif: <span class="font-semibold">{{ $activeYear->year ?? '-' }} Semester {{ $activeYear->semester ?? '-' }}</span></p>
<div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
    <form method="GET" class="flex gap-2 items-center w-full sm:w-auto">
        <label class="font-semibold text-blue-700 flex items-center gap-1">
            Pilih Kelas:
        </label>
        <select name="kelas_id" onchange="this.form.submit()" class="border-2 border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full sm:w-auto">
            @foreach($classrooms as $kelas)
            <option value="{{ $kelas->id }}" {{ $selectedClass == $kelas->id ? 'selected' : '' }}>{{ $kelas->name }}</option>
            @endforeach
        </select>
    </form>
</div>
<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full text-sm">
        <thead class="bg-blue-100">
            <tr>
                <th class="py-2 px-4">NIS</th>
                <th class="py-2 px-4">Nama</th>
                <th class="py-2 px-4">Mata Pelajaran</th>
                <th class="py-2 px-4">Tugas</th>
                <th class="py-2 px-4">UTS</th>
                <th class="py-2 px-4">UAS</th>
                <th class="py-2 px-4">Nilai Akhir</th>
                <th class="py-2 px-4">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($grades as $nilai)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-2 px-4">{{ $nilai->student->nis ?? '-' }}</td>
                <td class="py-2 px-4">{{ $nilai->student->user->name ?? '-' }}</td>
                <td class="py-2 px-4">{{ $nilai->subject->name ?? '-' }}</td>
                <td class="py-2 px-4 text-center">{{ $nilai->assignment_grade ?? '-' }}</td>
                <td class="py-2 px-4 text-center">{{ $nilai->uts_grade ?? '-' }}</td>
                <td class="py-2 px-4 text-center">{{ $nilai->uas_grade ?? '-' }}</td>
                <td class="py-2 px-4 text-center">{{ $nilai->final_grade ?? '-' }}</td>
                <td class="py-2 px-4 text-center">
                    @if($nilai->is_passed === null)
                    <span class="text-gray-400 italic">-</span>
                    @elseif($nilai->is_passed)
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">Tuntas</span>
                    @else
                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-semibold">Tidak Tuntas</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="py-4 text-center text-gray-500">Belum ada data nilai.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
@extends('layouts.dashboard')
@section('title', 'Detail Nilai Siswa')
@section('content')
<div class="mb-4 p-4 bg-blue-50 rounded">
    <div><span class="font-semibold">Nama:</span> {{ $student->user->name }}</div>
    <div><span class="font-semibold">NIS:</span> {{ $student->nis }}</div>
    @if($kelas)
    <div><span class="font-semibold">Kelas:</span> {{ $kelas->name }}</div>
    @endif
</div>
@php
$tahunAjaranIds = array_keys($rekap);
@endphp
@forelse($rekap as $tahunId => $semesters)
<div class="mb-6">
    <div class="font-bold text-lg text-blue-700 mb-2">Tahun Ajaran: {{ $tahunAjaranMap[$tahunId] ?? $tahunId }}</div>
    @foreach($semesters as $semester => $mapels)
    <div class="font-semibold text-blue-600 mb-1">Semester: {{ $semester }}</div>
    <div class="overflow-x-auto bg-white rounded shadow mb-4">
        <table class="min-w-full text-sm">
            <thead class="bg-blue-100">
                <tr>
                    <th class="py-2 px-4">Mata Pelajaran</th>
                    <th class="py-2 px-4 text-center">Tugas</th>
                    <th class="py-2 px-4 text-center">UTS</th>
                    <th class="py-2 px-4 text-center">UAS</th>
                    <th class="py-2 px-4 text-center">Nilai</th>
                    <th class="py-2 px-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mapels as $mapel => $nilai)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4">{{ $mapel }}</td>
                    <td class="py-2 px-4 text-center">{{ $nilai['tugas'] !== null ? number_format($nilai['tugas'],2) : '-' }}</td>
                    <td class="py-2 px-4 text-center">{{ $nilai['uts'] !== null ? number_format($nilai['uts'],2) : '-' }}</td>
                    <td class="py-2 px-4 text-center">{{ $nilai['uas'] !== null ? number_format($nilai['uas'],2) : '-' }}</td>
                    <td class="py-2 px-4 text-center">{{ $nilai['final_grade'] !== null ? number_format($nilai['final_grade'],2) : '-' }}</td>
                    <td class="py-2 px-4 text-center">
                        @if(is_numeric($nilai['kkm']))
                        @if($nilai['final_grade'] !== null)
                        @if($nilai['final_grade'] >= $nilai['kkm'])
                        <span class="text-green-600 font-semibold">Lulus</span>
                        @else
                        <span class="text-red-600 font-semibold">Tidak Lulus</span>
                        @endif
                        @else
                        -
                        @endif
                        @elseif($nilai['kkm'] === 'Belum diatur')
                        <span class="text-yellow-600 font-semibold">Belum diatur</span>
                        @else
                        -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
</div>
@empty
<div class="text-center text-gray-500 py-8">Belum ada data nilai.</div>
@endforelse
@endsection
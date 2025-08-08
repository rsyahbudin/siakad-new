@extends('layouts.dashboard')
@section('title', 'Nilai Akademik')
@section('content')
<div class="container-fluid">
    <h3>Nilai Akademik</h3>
    <p>Halaman ini untuk melihat nilai akademik siswa.</p>
</div>
<div class="max-w-4xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-xl font-bold mb-6">Nilai Akademik Semester Berjalan</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 border">Mata Pelajaran</th>
                    <th class="py-2 px-4 border">Tugas</th>
                    <th class="py-2 px-4 border">UTS</th>
                    <th class="py-2 px-4 border">UAS</th>
                    <th class="py-2 px-4 border">Nilai Sikap</th>
                    <th class="py-2 px-4 border">Nilai Akhir</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($grades) && count($grades) > 0)
                @foreach($grades as $grade)
                <tr>
                    <td class="py-2 px-4 border">{{ $grade->subject->name ?? '-' }}</td>
                    <td class="py-2 px-4 border text-center">{{ $grade->assignment_grade ?? '-' }}</td>
                    <td class="py-2 px-4 border text-center">{{ $grade->uts_grade ?? '-' }}</td>
                    <td class="py-2 px-4 border text-center">{{ $grade->uas_grade ?? '-' }}</td>
                    <td class="py-2 px-4 border text-center">
                        @if($grade->attitude_grade)
                        <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($grade->attitude_grade === 'Baik') bg-green-100 text-green-800
                                @elseif($grade->attitude_grade === 'Cukup') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                            {{ $grade->attitude_grade }}
                        </span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    @php
                    $nilaiAkhir = null;
                    if (!is_null($grade->final_grade)) {
                    $nilaiAkhir = $grade->final_grade;
                    } elseif (isset($subjectSettings[$grade->subject_id])) {
                    $setting = $subjectSettings[$grade->subject_id];
                    $nilaiAkhir = ($grade->assignment_grade * $setting->assignment_weight +
                    $grade->uts_grade * $setting->uts_weight +
                    $grade->uas_grade * $setting->uas_weight) / 100;
                    }
                    @endphp
                    <td class="py-2 px-4 border text-center font-semibold">{{ $nilaiAkhir !== null ? number_format($nilaiAkhir, 2) : '-' }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td class="py-2 px-4 border text-center text-gray-400" colspan="6">Belum ada data nilai.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
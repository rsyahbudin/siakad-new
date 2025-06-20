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
                    <td class="py-2 px-4 border text-center text-gray-400" colspan="5">Belum ada data nilai.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
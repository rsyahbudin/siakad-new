@extends('layouts.dashboard')
@section('title', 'Leger Nilai Kelas')
@section('content')
<div class="px-4 py-6">
    <h2 class="text-2xl font-bold mb-4">Leger Nilai Kelas {{ $kelas->name ?? '-' }}</h2>
    <p class="mb-4">Tahun Ajaran Aktif: <span class="font-semibold">{{ $activeYear->year ?? '-' }} Semester {{ $activeYear->semester ?? '-' }}</span></p>

    @if($students->count() > 0 && $mapels->count() > 0)
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full text-sm align-middle whitespace-nowrap">
            <thead class="bg-blue-100">
                <tr class="font-semibold">
                    <th class="py-3 px-4 text-left">#</th>
                    <th class="py-3 px-4 text-left">Nama Siswa</th>
                    @foreach($mapels as $mapel)
                    <th class="py-3 px-4 text-center border-l">
                        {{ $mapel->name }}
                        @php $setting = $subjectSettings[$mapel->id] ?? null; @endphp
                        @if($setting)
                        <div class="text-xs text-gray-500 font-normal">
                            (KKM: {{ $setting->kkm }})
                        </div>
                        @endif
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($students as $i => $siswa)
                <tr class="hover:bg-blue-50">
                    <td class="py-3 px-4">{{ $i+1 }}</td>
                    <td class="py-3 px-4 font-medium">{{ $siswa->full_name }}</td>
                    @foreach($mapels as $mapel)
                    @php
                    $grade = $grades->get($siswa->id)?->get($mapel->id)?->first();
                    $setting = $subjectSettings[$mapel->id] ?? null;
                    $nilaiAkhir = null;
                    if ($grade && $setting) {
                    if (!is_null($grade->final_grade)) {
                    $nilaiAkhir = $grade->final_grade;
                    } else {
                    $nilaiAkhir = ($grade->assignment_grade * $setting->assignment_weight +
                    $grade->uts_grade * $setting->uts_weight +
                    $grade->uas_grade * $setting->uas_weight) / 100;
                    }
                    }
                    $isTuntas = $nilaiAkhir !== null && $setting ? $nilaiAkhir >= $setting->kkm : null;
                    @endphp
                    <td class="py-3 px-4 text-center border-l {{ $isTuntas === false ? 'bg-red-50' : '' }}">
                        @if($nilaiAkhir !== null)
                        <span class="font-semibold text-gray-800">{{ number_format($nilaiAkhir, 1) }}</span>
                        <br>
                        @if($isTuntas !== null)
                        <span class="text-xs font-medium {{ $isTuntas ? 'text-green-600' : 'text-red-600' }}">
                            {{ $isTuntas ? 'Tuntas' : 'Tidak Tuntas' }}
                        </span>
                        @endif
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
        <div class="flex">
            <div class="py-1">
                <svg class="h-6 w-6 text-yellow-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <p class="font-bold">Data Belum Lengkap</p>
                <p class="text-sm">Leger nilai belum dapat ditampilkan. Pastikan ada siswa di kelas Anda dan jadwal mata pelajaran telah diatur untuk tahun ajaran ini.</p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
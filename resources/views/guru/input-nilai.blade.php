@extends('layouts.dashboard')
@section('title', 'Input Nilai Siswa')
@section('content')
<h2 class="text-2xl font-bold mb-4">Input Nilai Siswa</h2>
<p class="mb-4">Tahun Ajaran Aktif: <span class="font-semibold">{{ $activeYear->year ?? '-' }} Semester {{ $activeYear->semester ?? '-' }}</span></p>
@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif
<form method="GET" class="mb-6 flex gap-2 items-end">
    <div>
        <label class="block font-semibold mb-1">Kelas</label>
        <select name="kelas_id" class="border rounded px-3 py-2" onchange="this.form.submit()">
            <option value="">- Pilih Kelas -</option>
            @foreach($kelasMapel as $item)
            <option value="{{ $item['classroom_id'] }}" {{ $selectedClass == $item['classroom_id'] ? 'selected' : '' }}>{{ $item['classroom_name'] }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block font-semibold mb-1">Mata Pelajaran</label>
        <select name="mapel_id" class="border rounded px-3 py-2" onchange="this.form.submit()">
            <option value="">- Pilih Mapel -</option>
            @foreach($kelasMapel->where('classroom_id', $selectedClass) as $item)
            <option value="{{ $item['subject_id'] }}" {{ $selectedSubject == $item['subject_id'] ? 'selected' : '' }}>{{ $item['subject_name'] }}</option>
            @endforeach
        </select>
    </div>
</form>
@if($selectedClass && $selectedSubject)
@if($bobot)
<div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded text-sm text-blue-800">
    Bobot: <strong>Tugas {{ $bobot->assignment_weight }}%</strong>, <strong>UTS {{ $bobot->uts_weight }}%</strong>, <strong>UAS {{ $bobot->uas_weight }}%</strong> | KKM: <strong class="font-bold">{{ $bobot->kkm }}</strong>
</div>
@else
<div class="mb-4 p-3 bg-yellow-50 border border-yellow-300 rounded text-sm text-yellow-800">
    Bobot dan KKM untuk mata pelajaran ini belum diatur. Nilai akhir dan status tidak dapat dihitung.
</div>
@endif
<form method="POST" action="{{ route('nilai.input.store') }}">
    @csrf
    <input type="hidden" name="kelas_id" value="{{ $selectedClass }}">
    <input type="hidden" name="mapel_id" value="{{ $selectedSubject }}">
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-blue-100">
                <tr>
                    <th class="py-2 px-4 text-left">#</th>
                    <th class="py-2 px-4 text-left">Nama Siswa</th>
                    <th class="py-2 px-4 text-center">Nilai Tugas</th>
                    <th class="py-2 px-4 text-center">Nilai UTS</th>
                    <th class="py-2 px-4 text-center">Nilai UAS</th>
                    <th class="py-2 px-4 text-center">Nilai Akhir</th>
                    <th class="py-2 px-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $i => $siswa)
                <tr class="border-b hover:bg-blue-50">
                    <td class="py-2 px-4">{{ $i+1 }}</td>
                    <td class="py-2 px-4">{{ $siswa->full_name }}</td>
                    <td class="py-2 px-4"><input type="number" name="nilai[{{ $siswa->id }}][tugas]" value="{{ $grades[$siswa->id]->assignment_grade ?? '' }}" class="border rounded px-2 py-1 w-20 text-center"></td>
                    <td class="py-2 px-4"><input type="number" name="nilai[{{ $siswa->id }}][uts]" value="{{ $grades[$siswa->id]->uts_grade ?? '' }}" class="border rounded px-2 py-1 w-20 text-center"></td>
                    <td class="py-2 px-4"><input type="number" name="nilai[{{ $siswa->id }}][uas]" value="{{ $grades[$siswa->id]->uas_grade ?? '' }}" class="border rounded px-2 py-1 w-20 text-center"></td>
                    @php
                    $nilaiAkhir = 0;
                    $status = null;
                    if($bobot) {
                    $tugas = $grades[$siswa->id]->assignment_grade ?? 0;
                    $uts = $grades[$siswa->id]->uts_grade ?? 0;
                    $uas = $grades[$siswa->id]->uas_grade ?? 0;
                    $bobotTugas = $bobot->assignment_weight ?? 0;
                    $bobotUts = $bobot->uts_weight ?? 0;
                    $bobotUas = $bobot->uas_weight ?? 0;
                    $nilaiAkhir = ($tugas * $bobotTugas + $uts * $bobotUts + $uas * $bobotUas) / 100;
                    $status = $nilaiAkhir >= $bobot->kkm;
                    }
                    @endphp
                    <td class="py-2 px-4 font-semibold text-center">{{ number_format($nilaiAkhir, 2) }}</td>
                    <td class="py-2 px-4 text-center">
                        @if($bobot)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $status ? 'Tuntas' : 'Tidak Tuntas' }}
                        </span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <button type="submit" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan Nilai</button>
</form>
@endif
@endsection
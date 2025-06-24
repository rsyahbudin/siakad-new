@extends('layouts.dashboard')
@section('title', 'Input Nilai Siswa')
@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Input Nilai Siswa</h2>
            <p class="text-sm text-gray-500 mt-1">Pilih kelas dan mata pelajaran untuk menginput atau mengubah nilai.</p>
        </div>
        <a href="{{ route('nilai.import.show') }}" class="mt-4 sm:mt-0 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
            </svg>
            Impor dari Excel
        </a>
    </div>
    <p class="mb-4">Semester Aktif: <span class="font-semibold">{{ $activeSemester->academicYear->year ?? '-' }} (Semester {{ $activeSemester->name ?? '-' }})</span></p>
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">{{ session('success') }}</div>
    @endif
    <form method="GET" class="mb-6 flex gap-2 items-end">
        <div>
            <label class="block font-semibold mb-1">Kelas</label>
            <select name="assignment_id" class="border rounded px-3 py-2" onchange="this.form.submit()">
                <option value="">- Pilih Kelas -</option>
                @foreach($assignments as $assignment)
                <option value="{{ $assignment->id }}" {{ $selectedAssignment == $assignment->id ? 'selected' : '' }}>{{ $assignment->classroom->name }} ({{ $assignment->academicYear->year ?? '' }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block font-semibold mb-1">Mata Pelajaran</label>
            <select name="subject_id" class="border rounded px-3 py-2" onchange="this.form.submit()">
                <option value="">- Pilih Mapel -</option>
                @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" {{ $selectedSubject == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>
    </form>
    @if($selectedAssignment && $selectedSubject)
    @if($bobot)
    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded text-sm text-blue-800">
        Bobot: <strong>Tugas {{ $bobot->assignment_weight }}%</strong>, <strong>UTS {{ $bobot->uts_weight }}%</strong>, <strong>UAS {{ $bobot->uas_weight }}%</strong> | KKM: <strong class="font-bold">{{ $bobot->kkm }}</strong>
    </div>
    @else
    <div class="mb-4 p-3 bg-yellow-50 border border-yellow-300 rounded text-sm text-yellow-800">
        Bobot dan KKM untuk mata pelajaran ini belum diatur. Nilai akhir dan status tidak dapat dihitung.
    </div>
    @endif

    @if($isFinalized)
    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700" role="alert">
        <p class="font-bold">Raport Telah Difinalisasi</p>
        <p>Anda tidak dapat lagi mengubah atau menyimpan nilai untuk kelas ini karena raport semester ini telah difinalisasi oleh wali kelas.</p>
    </div>
    @endif

    <form method="POST" action="{{ route('nilai.input.store') }}">
        @csrf
        <input type="hidden" name="assignment_id" value="{{ $selectedAssignment }}">
        <input type="hidden" name="subject_id" value="{{ $selectedSubject }}">
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
                        <td class="py-2 px-4"><input type="number" name="nilai[{{ $siswa->id }}][tugas]" value="{{ $grades[$siswa->id]->assignment_grade ?? '' }}" class="border rounded px-2 py-1 w-20 text-center" {{ $isFinalized ? 'disabled' : '' }}></td>
                        <td class="py-2 px-4"><input type="number" name="nilai[{{ $siswa->id }}][uts]" value="{{ $grades[$siswa->id]->uts_grade ?? '' }}" class="border rounded px-2 py-1 w-20 text-center" {{ $isFinalized ? 'disabled' : '' }}></td>
                        <td class="py-2 px-4"><input type="number" name="nilai[{{ $siswa->id }}][uas]" value="{{ $grades[$siswa->id]->uas_grade ?? '' }}" class="border rounded px-2 py-1 w-20 text-center" {{ $isFinalized ? 'disabled' : '' }}></td>
                        @php
                        $nilaiAkhir = null;
                        $status = null;
                        if($bobot) {
                        if(isset($grades[$siswa->id]) && !is_null($grades[$siswa->id]->final_grade)) {
                        $nilaiAkhir = $grades[$siswa->id]->final_grade;
                        } else {
                        $tugas = $grades[$siswa->id]->assignment_grade ?? 0;
                        $uts = $grades[$siswa->id]->uts_grade ?? 0;
                        $uas = $grades[$siswa->id]->uas_grade ?? 0;
                        $bobotTugas = $bobot->assignment_weight ?? 0;
                        $bobotUts = $bobot->uts_weight ?? 0;
                        $bobotUas = $bobot->uas_weight ?? 0;
                        $nilaiAkhir = ($tugas * $bobotTugas + $uts * $bobotUts + $uas * $bobotUas) / 100;
                        }
                        $status = $nilaiAkhir >= $bobot->kkm;
                        }
                        @endphp
                        <td class="py-2 px-4 font-semibold text-center">{{ $nilaiAkhir !== null ? number_format($nilaiAkhir, 2) : '-' }}</td>
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
        <button type="submit" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 {{ $isFinalized ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $isFinalized ? 'disabled' : '' }}>Simpan Nilai</button>
    </form>
    @endif
</div>
@endsection
@extends('layouts.dashboard')
@section('title', 'Kenaikan Kelas & Kelulusan')
@section('content')
<h2 class="text-2xl font-bold mb-4">Kenaikan Kelas & Kelulusan</h2>
<p class="mb-4">Semester Aktif: <span class="font-semibold">{{ $activeSemester->academicYear->year ?? '-' }} (Semester {{ $activeSemester->name ?? '-' }})</span></p>
@if($activeSemester->name != 'Genap')
<div class="mb-4 p-3 bg-yellow-100 text-yellow-800 rounded font-semibold">Kenaikan kelas dan kelulusan <b>hanya dapat dilakukan pada semester Genap</b>. Silakan aktifkan semester Genap untuk menggunakan fitur ini.</div>
@endif
@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
@endif

<p class="mb-4">Batas maksimal mapel gagal agar naik/lulus: <span class="font-semibold">{{ config('siakad.max_failed_subjects', 2) }} mapel</span>. <span class="text-xs text-gray-500">(Bisa diubah di file konfigurasi)</span></p>

<form method="GET" class="mb-6 flex flex-col md:flex-row gap-4 items-end">
    <div>
        <label class="block font-semibold mb-1">Filter Kelas 12</label>
        <select name="kelas12_id" class="border rounded px-3 py-2" onchange="this.form.submit()" @if($activeSemester->name != 'Genap') disabled @endif>
            <option value="">- Semua Kelas 12 -</option>
            @foreach($classroomAssignments->where('classroom.grade_level', 12) as $assignment)
            <option value="{{ $assignment->classroom->id }}" {{ request('kelas12_id') == $assignment->classroom->id ? 'selected' : '' }}>{{ $assignment->classroom->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block font-semibold mb-1">Filter Kelas X & XI</label>
        <select name="kelasnon12_id" class="border rounded px-3 py-2" onchange="this.form.submit()" @if($activeSemester->name != 'Genap') disabled @endif>
            <option value="">- Semua Kelas X & XI -</option>
            @foreach($classroomAssignments->whereIn('classroom.grade_level', [10, 11]) as $assignment)
            <option value="{{ $assignment->classroom->id }}" {{ request('kelasnon12_id') == $assignment->classroom->id ? 'selected' : '' }}>{{ $assignment->classroom->name }}</option>
            @endforeach
        </select>
    </div>
</form>

@php
$selectedKelas12 = request('kelas12_id');
$selectedKelasNon12 = request('kelasnon12_id');
$filteredKelasNon12 = $selectedKelasNon12 && $classroomAssignments->has($selectedKelasNon12)
? collect([$selectedKelasNon12 => $classroomAssignments->get($selectedKelasNon12)])
: $classroomAssignments;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Kelas 12 (Kelulusan) -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-2 flex items-center gap-2">
            <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>
            Kelas 12 (Kelulusan)
            @if($selectedKelas12)
            <span class="ml-2 text-xs bg-gray-200 px-2 py-1 rounded">{{ $classroomAssignments->firstWhere('classroom.id', $selectedKelas12)?->classroom->name }}</span>
            @endif
        </h3>
        <form method="POST" action="{{ route('kenaikan-kelas.store') }}">
            @csrf
            <input type="hidden" name="action" value="lulus">
            <div class="overflow-x-auto rounded">
                <table class="min-w-full text-sm border">
                    <thead class="bg-green-100">
                        <tr>
                            <th class="py-2 px-4"><input type="checkbox" onclick="toggleAll(this, 'lulus')"></th>
                            <th class="py-2 px-4">NIS</th>
                            <th class="py-2 px-4">Nama</th>
                            <th class="py-2 px-4">Kelas</th>
                            <th class="py-2 px-4">Nilai Akhir Tahun</th>
                            <th class="py-2 px-4">Keputusan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($selectedKelas12 ? $kelas12->only([$selectedKelas12]) : $kelas12) as $classId => $promotions)
                        @foreach($promotions as $promotion)
                        @if(is_object($promotion) && $promotion instanceof \App\Models\StudentPromotion)
                        <tr class="border-b hover:bg-green-50">
                            <td class="py-2 px-4"><input type="checkbox" name="student_ids[]" value="{{ is_object($promotion->student) ? $promotion->student->id : '' }}" class="cb-lulus"></td>
                            <td class="py-2 px-4">{{ optional($promotion->student)->nis ?? '-' }}</td>
                            <td class="py-2 px-4">{{ optional($promotion->student)->full_name ?? '-' }}</td>
                            <td class="py-2 px-4">{{ $promotion->fromClassroom->name ?? '-' }}</td>
                            <td class="py-2 px-4">{{ $promotion->yearly_grade ?? '-' }}</td>
                            <td class="py-2 px-4">
                                @if($promotion->final_decision == 'Naik Kelas')
                                <span class="bg-green-200 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Lulus</span>
                                @elseif($promotion->final_decision == 'Tidak Naik Kelas')
                                <span class="bg-red-200 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Tidak Lulus</span>
                                @else
                                <span class="bg-gray-200 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded">Belum Diputuskan</span>
                                @endif
                            </td>
                        </tr>
                        @endif
                        @endforeach
                        @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">Tidak ada siswa kelas 12.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <button type="submit" class="mt-4 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-semibold w-full" @if($activeSemester->name != 'Genap') disabled style="opacity:0.5;cursor:not-allowed;" @endif>Eksekusi Kelulusan</button>
        </form>
    </div>

    <!-- Kelas X & XI (Kenaikan Kelas) -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-2 flex items-center gap-2">
            <span class="inline-block w-3 h-3 rounded-full bg-blue-500"></span>
            Kelas X & XI (Kenaikan Kelas)
            @if($selectedKelasNon12)
            <span class="ml-2 text-xs bg-gray-200 px-2 py-1 rounded">{{ $classroomAssignments->firstWhere('classroom.id', $selectedKelasNon12)?->classroom->name }}</span>
            @endif
        </h3>
        <form method="POST" action="{{ route('kenaikan-kelas.store') }}">
            @csrf
            <input type="hidden" name="action" value="naik">
            <div class="overflow-x-auto rounded"></div>
            <table class="min-w-full text-sm border">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="py-2 px-4"><input type="checkbox" onclick="toggleAll(this, 'naik')"></th>
                        <th class="py-2 px-4">NIS</th>
                        <th class="py-2 px-4">Nama</th>
                        <th class="py-2 px-4">Kelas</th>
                        <th class="py-2 px-4">Keputusan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($filteredKelasNon12 as $classId => $promotions)
                    @foreach($promotions as $promotion)
                    @if(is_object($promotion) && $promotion instanceof \App\Models\StudentPromotion)
                    <tr class="border-b hover:bg-blue-50">
                        <td class="py-2 px-4"><input type="checkbox" name="student_ids[]" value="{{ is_object($promotion->student) ? $promotion->student->id : '' }}" class="cb-naik"></td>
                        <td class="py-2 px-4">{{ optional($promotion->student)->nis ?? '-' }}</td>
                        <td class="py-2 px-4">{{ optional($promotion->student)->full_name ?? '-' }}</td>
                        <td class="py-2 px-4">{{ $promotion->fromClassroom->name ?? '-' }}</td>
                        <td class="py-2 px-4">
                            @if($promotion->final_decision == 'Naik Kelas')
                            <span class="bg-blue-200 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Naik Kelas</span>
                            @elseif($promotion->final_decision == 'Tidak Naik Kelas')
                            <span class="bg-red-200 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Tinggal Kelas</span>
                            @else
                            <span class="bg-gray-200 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded">Belum Diputuskan</span>
                            @endif
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">Tidak ada siswa kelas X/XI.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
    </div>
    <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold w-full" @if($activeSemester->name != 'Genap') disabled style="opacity:0.5;cursor:not-allowed;" @endif>Eksekusi Kenaikan Kelas</button>
    </form>
</div>
</div>

<script>
    function toggleAll(source, type) {
        let checkboxes = document.querySelectorAll('.cb-' + type);
        for (let cb of checkboxes) {
            cb.checked = source.checked;
        }
    }
</script>
@endsection
@extends('layouts.dashboard')
@section('title', 'Rekap Absensi Siswa')
@section('content')
<h2 class="text-2xl font-bold mb-4">Absensi & Rekap Siswa Kelas {{ $kelas->name }}</h2>
<p class="mb-4">Tahun Ajaran Aktif: <span class="font-semibold">{{ $activeSemester->academicYear->year ?? '-' }} Semester {{ $activeSemester->name ?? '-' }}</span></p>

<div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-800">
    <h4 class="font-bold">Informasi</h4>
    <p class="text-sm">Halaman ini menampilkan input absensi harian dan rekap semester. Gunakan fitur pencarian dan navigasi halaman untuk memudahkan pengisian dan monitoring absensi siswa.</p>
</div>

{{-- Tab Navigation --}}
<div class="mb-4 flex gap-2 border-b">
    <a href="?tab=input{{ $q ? '&q=' . urlencode($q) : '' }}" class="px-4 py-2 -mb-px border-b-2 {{ $tab == 'input' ? 'border-blue-600 text-blue-700 font-bold' : 'border-transparent text-gray-500' }}">Input Absensi Hari Ini</a>
    <a href="?tab=rekap{{ $q ? '&q=' . urlencode($q) : '' }}" class="px-4 py-2 -mb-px border-b-2 {{ $tab == 'rekap' ? 'border-blue-600 text-blue-700 font-bold' : 'border-transparent text-gray-500' }}">Rekap Absensi Semester</a>
</div>

{{-- Search Bar --}}
<form method="GET" class="mb-4 flex gap-2 items-center">
    <input type="hidden" name="tab" value="{{ $tab }}">
    <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama/NIS/NISN siswa..." class="border rounded px-3 py-1 w-64 text-sm">
    <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">Cari</button>
    @if($q)
    <a href="?tab={{ $tab }}" class="text-xs text-gray-500 ml-2">Reset</a>
    @endif
</form>

@if($tab == 'input')
{{-- Form Input Absensi Harian --}}
<div class="mb-8 p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-800 rounded">
    <h4 class="font-bold mb-2">Input Absensi Hari Ini ({{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }})</h4>
    <form method="POST" action="{{ route('wali.absensi.store') }}?tab=input{{ $q ? '&q=' . urlencode($q) : '' }}&page={{ request('page', 1) }}">
        @csrf
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full text-sm">
                <thead class="bg-blue-100 sticky top-0 z-10">
                    <tr>
                        <th class="py-2 px-4 text-left">Nama Siswa</th>
                        <th class="py-2 px-4 text-center w-48">Status Kehadiran</th>
                        <th class="py-2 px-4 text-left">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $row)
                    @php $student = $row->student; $current_attendance = $absensiHarian->get($student->id); @endphp
                    <tr class="border-b hover:bg-blue-50">
                        <td class="py-2 px-4 font-medium">{{ $student->full_name }}</td>
                        <td class="py-2 px-4">
                            <div class="flex justify-around gap-2">
                                @foreach(['Hadir', 'Sakit', 'Izin', 'Alpha'] as $status)
                                <label class="flex items-center gap-1 cursor-pointer">
                                    <input type="radio" name="attendances[{{ $student->id }}][status]" value="{{ $status }}"
                                        {{ ($current_attendance?->status ?? 'Hadir') == $status ? 'checked' : '' }}
                                        class="form-radio h-4 w-4 text-blue-600">
                                    <span class="px-2 py-1 rounded text-xs {{
                                        $status == 'Hadir' ? 'bg-green-100 text-green-700' :
                                        ($status == 'Sakit' ? 'bg-yellow-100 text-yellow-700' :
                                        ($status == 'Izin' ? 'bg-blue-100 text-blue-700' :
                                        'bg-red-100 text-red-700'))
                                    }}">{{ $status }}</span>
                                </label>
                                @endforeach
                            </div>
                        </td>
                        <td class="py-2 px-4">
                            <input type="text" name="attendances[{{ $student->id }}][notes]" value="{{ $current_attendance?->notes ?? '' }}" class="border rounded px-2 py-1 w-full text-sm" placeholder="Opsional">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-between items-center">
            <span class="text-xs text-gray-500">Total siswa: {{ $students->total() }}</span>
            <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded-lg hover:bg-blue-700 shadow transition">Simpan Absensi Hari Ini</button>
        </div>
    </form>
    <div class="mt-4">{{ $students->links() }}</div>
</div>
@endif

@if($tab == 'rekap')
{{-- Rekap Absensi Semester --}}
<div class="mb-6 p-4 bg-gray-50 border-l-4 border-gray-400 text-gray-800 rounded">
    <h4 class="font-bold mb-2">Rekap Absensi Semester Ini</h4>
    <div class="overflow-x-auto max-h-[400px]">
        <table class="min-w-full text-sm border" id="rekapTable">
            <thead class="bg-blue-100 sticky top-0 z-10">
                <tr>
                    <th class="py-2 px-4 text-left">Nama Siswa</th>
                    <th class="py-2 px-4 text-center w-24">Sakit</th>
                    <th class="py-2 px-4 text-center w-24">Izin</th>
                    <th class="py-2 px-4 text-center w-24">Alpha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $row)
                @php
                $student = $row->student;
                $rekap = $rekapAbsensi->get($student->id);
                $sakit = $rekap ? (optional($rekap->firstWhere('status', 'Sakit'))->total ?? 0) : 0;
                $izin = $rekap ? (optional($rekap->firstWhere('status', 'Izin'))->total ?? 0) : 0;
                $alpha = $rekap ? (optional($rekap->firstWhere('status', 'Alpha'))->total ?? 0) : 0;
                $total = $sakit + $izin + $alpha;
                @endphp
                <tr class="border-b hover:bg-blue-50 {{ $total >= 10 ? 'bg-yellow-100' : '' }}">
                    <td class="py-2 px-4 font-medium">{{ $student->full_name }}</td>
                    <td class="py-2 px-4 text-center">{{ $sakit }}</td>
                    <td class="py-2 px-4 text-center">{{ $izin }}</td>
                    <td class="py-2 px-4 text-center">{{ $alpha }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $students->links() }}</div>
    <span class="text-xs text-gray-500">Total siswa: {{ $students->total() }}</span>
</div>
@endif
@endsection
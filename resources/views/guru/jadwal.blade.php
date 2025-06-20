@extends('layouts.dashboard')
@section('title', 'Jadwal Mengajar')
@section('content')
<h2 class="text-2xl font-bold mb-4">Jadwal Mengajar</h2>
<p class="mb-4">Tahun Ajaran Aktif: <span class="font-semibold">{{ $activeYear->year ?? '-' }} Semester {{ $activeYear->semester ?? '-' }}</span></p>
@if($schedules->isEmpty())
<div class="p-4 bg-yellow-100 text-yellow-800 rounded">Belum ada jadwal mengajar untuk Anda.</div>
@else
<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full text-sm">
        <thead class="bg-blue-100">
            <tr>
                <th class="py-2 px-4 text-left">Hari</th>
                <th class="py-2 px-4 text-left">Jam</th>
                <th class="py-2 px-4 text-left">Kelas</th>
                <th class="py-2 px-4 text-left">Mata Pelajaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $jadwal)
            <tr class="border-b hover:bg-blue-50">
                <td class="py-2 px-4">{{ $jadwal->day }}</td>
                <td class="py-2 px-4">{{ $jadwal->time_start }} - {{ $jadwal->time_end }}</td>
                <td class="py-2 px-4">{{ $jadwal->classroom->name ?? '-' }}</td>
                <td class="py-2 px-4">{{ $jadwal->subject->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
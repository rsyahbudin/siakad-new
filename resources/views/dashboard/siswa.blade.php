@extends('layouts.dashboard')
@section('title', 'Dashboard Siswa')
@section('content')
<div class="container mx-auto max-w-3xl py-8">
    <h2 class="text-2xl font-bold mb-4">Selamat Datang, {{ Auth::user()->name }}!</h2>
    <div class="bg-white rounded shadow p-4 mb-6">
        <h3 class="font-semibold text-lg mb-2">Profil Singkat</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><span class="font-semibold">Nama</span>: {{ Auth::user()->student->full_name ?? '-' }}</div>
            <div><span class="font-semibold">NIS</span>: {{ Auth::user()->student->nis ?? '-' }}</div>
            <div><span class="font-semibold">Kelas</span>: {{ Auth::user()->student->classrooms->last()->name ?? '-' }}</div>
            <div><span class="font-semibold">Email</span>: {{ Auth::user()->email }}</div>
        </div>
    </div>
    <div class="bg-white rounded shadow p-4">
        <h3 class="font-semibold text-lg mb-2">Jadwal Hari Ini</h3>
        @if(isset($todaySchedules) && count($todaySchedules) > 0)
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4">Jam</th>
                    <th class="py-2 px-4">Mata Pelajaran</th>
                    <th class="py-2 px-4">Guru</th>
                </tr>
            </thead>
            <tbody>
                @foreach($todaySchedules as $jadwal)
                <tr>
                    <td class="py-2 px-4">{{ ($jadwal->time_start ?? '-') . ' - ' . ($jadwal->time_end ?? '-') }}</td>
                    <td class="py-2 px-4">{{ $jadwal->subject->name ?? '-' }}</td>
                    <td class="py-2 px-4">{{ $jadwal->teacher->full_name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="text-gray-500">Tidak ada jadwal hari ini.</div>
        @endif
    </div>
</div>
@endsection
@extends('layouts.dashboard')
@section('title', 'Jadwal Siswa')
@section('content')
<div class="container-fluid">
    <h3>Jadwal Siswa</h3>
    <p>Halaman ini untuk melihat jadwal pelajaran siswa.</p>
</div>
<div class="max-w-4xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-xl font-bold mb-6">Jadwal Pelajaran Mingguan</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 border">Hari</th>
                    <th class="py-2 px-4 border">Jam</th>
                    <th class="py-2 px-4 border">Mata Pelajaran</th>
                    <th class="py-2 px-4 border">Guru</th>
                </tr>
            </thead>
            <tbody>
                @php
                $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                @endphp
                @foreach($days as $day)
                @if(isset($weeklySchedules[$day]) && count($weeklySchedules[$day]) > 0)
                @foreach($weeklySchedules[$day] as $jadwal)
                <tr>
                    <td class="py-2 px-4 border">{{ $day }}</td>
                    <td class="py-2 px-4 border">{{ ($jadwal->time_start ?? '-') . ' - ' . ($jadwal->time_end ?? '-') }}</td>
                    <td class="py-2 px-4 border">{{ $jadwal->subject->name ?? '-' }}</td>
                    <td class="py-2 px-4 border">{{ $jadwal->teacher->full_name ?? '-' }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td class="py-2 px-4 border">{{ $day }}</td>
                    <td class="py-2 px-4 border text-gray-400" colspan="3">Tidak ada jadwal</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
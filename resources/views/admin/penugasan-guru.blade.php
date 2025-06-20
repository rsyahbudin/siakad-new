@extends('layouts.dashboard')
@section('title', 'Laporan Penugasan Guru')
@section('content')
<h2 class="text-2xl font-bold mb-4">Laporan Penugasan Guru</h2>
<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full text-sm">
        <thead class="bg-blue-100">
            <tr>
                <th class="py-2 px-4 text-left">Guru</th>
                <th class="py-2 px-4 text-left">Mata Pelajaran</th>
                <th class="py-2 px-4 text-left">Kelas</th>
                <th class="py-2 px-4 text-left">Hari</th>
                <th class="py-2 px-4 text-left">Jam</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assignments as $a)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-2 px-4">{{ $a->teacher->full_name ?? '-' }}</td>
                <td class="py-2 px-4">{{ $a->subject->name ?? '-' }}</td>
                <td class="py-2 px-4">{{ $a->classroom->name ?? '-' }}</td>
                <td class="py-2 px-4">{{ $a->day }}</td>
                <td class="py-2 px-4">{{ $a->time_start }} - {{ $a->time_end }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-4 text-center text-gray-500">Belum ada penugasan guru.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
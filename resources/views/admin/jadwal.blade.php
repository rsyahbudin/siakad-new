@extends('layouts.dashboard')
@section('title', 'Jadwal & Penugasan Mengajar')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-6">
    <div>
        <h2 class="text-3xl font-extrabold text-blue-800 flex items-center gap-2">
            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Jadwal & Penugasan Mengajar
        </h2>
        <div class="mt-1 text-sm text-gray-600">Tahun Ajaran: <span class="font-semibold text-blue-700">{{ $activeSemester->academicYear->year ?? '-' }}</span> | Semester: <span class="font-semibold text-blue-700">{{ $activeSemester->name ?? '-' }}</span></div>
    </div>
    <a href="#" onclick="window.print()" class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-4 py-2 rounded-lg font-semibold text-sm flex items-center gap-2 shadow-sm transition" title="Cetak Jadwal">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-6 0v4m0 0h4m-4 0H8" />
        </svg>
        Cetak Jadwal
    </a>
</div>
@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow">{{ session('success') }}</div>
@endif
<div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
    <form method="GET" class="flex gap-2 items-center w-full sm:w-auto">
        <label class="font-semibold text-blue-700 flex items-center gap-1">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v4a1 1 0 001 1h3m10 0h3a1 1 0 001-1V7a1 1 0 00-1-1h-3m-10 0H4a1 1 0 00-1 1z" />
            </svg>
            Pilih Kelas:
        </label>
        <select name="assignment_id" onchange="this.form.submit()" class="border-2 border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full sm:w-auto">
            @foreach($assignments as $assignment)
            <option value="{{ $assignment->id }}" {{ $selectedAssignment == $assignment->id ? 'selected' : '' }}>
                {{ $assignment->classroom->name }} ({{ $assignment->academicYear->year ?? '' }})
            </option>
            @endforeach
        </select>
    </form>
</div>
<div class="overflow-x-auto bg-white rounded-xl shadow-lg">
    <table class="min-w-full text-sm">
        <thead class="bg-blue-100">
            <tr>
                <th class="py-3 px-4 text-center sticky left-0 bg-blue-100 z-10">Hari/Jam</th>
                @for($i=1; $i<=10; $i++)
                    <th class="py-3 px-4 text-center">Jam {{ $i }}</th>
                    @endfor
            </tr>
        </thead>
        <tbody>
            @php
            $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            $hasAny = false;
            @endphp
            @foreach($days as $day)
            <tr class="hover:bg-blue-50 transition">
                <td class="py-3 px-4 font-semibold text-center sticky left-0 bg-white z-10 border-r">{{ $day }}</td>
                @for($i=1; $i<=10; $i++)
                    <td class="py-3 px-1 text-center">
                    @php
                    $slot = $schedules->first(function($s) use ($day, $i) {
                    return $s->day == $day && intval(substr($s->time_start,0,2)) == ($i+6); // Jam pelajaran mulai dari 07:00
                    });
                    @endphp
                    @if($slot)
                    @php $hasAny = true; @endphp
                    <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-100 rounded-lg p-2 flex flex-col items-center shadow-sm">
                        <div class="font-bold text-blue-700 text-xs mb-1 flex items-center gap-1">
                            <span class="inline-block bg-blue-200 text-blue-800 px-2 py-0.5 rounded-full text-xs">{{ $slot->subject->name }}</span>
                        </div>
                        <div class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804" />
                            </svg>
                            {{ $slot->teacher->full_name }}
                        </div>
                        <div class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                            </svg>
                            {{ $slot->time_start }} - {{ $slot->time_end }}
                        </div>
                        <div class="flex gap-1 justify-center mt-1">
                            <a href="{{ route('jadwal.admin.edit', $slot) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-2 py-1 rounded text-xs flex items-center gap-1 shadow" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13h3l8-8a2.828 2.828 0 10-4-4l-8 8v3z" />
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('jadwal.admin.destroy', $slot) }}" method="POST" onsubmit="return confirm('Hapus slot ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs flex items-center gap-1 shadow" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('jadwal.admin.create', ['assignment_id' => $selectedAssignment, 'day' => $day, 'jam' => $i]) }}" class="bg-gradient-to-br from-green-400 to-green-600 hover:from-green-500 hover:to-green-700 text-white px-3 py-2 rounded-lg text-xs flex items-center gap-1 justify-center shadow font-semibold transition" title="Tambah Jadwal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </a>
                    @endif
                    </td>
                    @endfor
            </tr>
            @endforeach
            @if(!$hasAny)
            <tr>
                <td colspan="11" class="py-12 text-center text-gray-400 text-lg flex flex-col items-center justify-center">
                    <span class="text-5xl mb-2">📅</span>
                    Belum ada jadwal untuk kelas ini.<br>
                    <span class="text-sm text-gray-300">Silakan tambahkan jadwal dengan tombol hijau di atas.</span>
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection
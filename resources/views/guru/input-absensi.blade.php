@extends('layouts.dashboard')
@section('title', 'Input Absensi Siswa')
@section('content')
<h2 class="text-2xl font-bold mb-4">Input Absensi Siswa</h2>
<p class="mb-4">Tahun Ajaran Aktif: <span class="font-semibold">{{ $activeYear->year ?? '-' }} Semester {{ $activeYear->semester ?? '-' }}</span></p>

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif

<form method="GET" class="mb-6 flex gap-4 items-end bg-gray-50 p-4 rounded-lg">
    <div>
        <label class="block font-semibold mb-1">Pilih Kelas & Mata Pelajaran</label>
        <select name="schedule_id" class="border rounded px-3 py-2 w-72" onchange="this.form.submit()">
            <option value="">- Pilih Jadwal Mengajar Anda -</option>
            @foreach($scheduleMap as $item)
            <option value="{{ $item['schedule_id'] }}" {{ $selectedScheduleId == $item['schedule_id'] ? 'selected' : '' }}>
                {{ $item['classroom_name'] }} - {{ $item['subject_name'] }} (Hari: {{ $item['day'] }})
            </option>
            @endforeach
        </select>
    </div>
</form>

@if($selectedScheduleId)
<form method="POST" action="{{ route('absensi.input.store') }}">
    @csrf
    <input type="hidden" name="schedule_id" value="{{ $selectedScheduleId }}">
    <p class="mb-4 text-sm text-gray-600">Absensi untuk tanggal: <strong>{{ now()->isoFormat('dddd, D MMMM YYYY') }}</strong></p>
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-blue-100">
                <tr>
                    <th class="py-2 px-4 text-left">Nama Siswa</th>
                    <th class="py-2 px-4 text-center w-48">Status Kehadiran</th>
                    <th class="py-2 px-4 text-left">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                @php $current_attendance = $attendances->get($student->id); @endphp
                <tr class="border-b hover:bg-blue-50">
                    <td class="py-2 px-4 font-medium">{{ $student->full_name }}</td>
                    <td class="py-2 px-4">
                        <div class="flex justify-around gap-2">
                            @foreach(['Hadir', 'Sakit', 'Izin', 'Alpha'] as $status)
                            <label class="flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="attendances[{{ $student->id }}][status]" value="{{ $status }}"
                                    {{ ($current_attendance?->status ?? 'Hadir') == $status ? 'checked' : '' }}
                                    class="form-radio h-4 w-4 text-blue-600">
                                <span>{{ $status }}</span>
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
    <button type="submit" class="mt-6 bg-blue-600 text-white px-8 py-2 rounded hover:bg-blue-700 shadow">Simpan Absensi</button>
</form>
@endif
@endsection
@extends('layouts.dashboard')
@section('title', isset($jadwal) ? 'Edit Slot Jadwal' : 'Tambah Slot Jadwal')
@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-xl font-bold mb-6">{{ isset($jadwal) ? 'Edit Slot Jadwal' : 'Tambah Slot Jadwal' }}</h2>
    <form method="POST" action="{{ isset($jadwal) ? route('jadwal.admin.update', $jadwal) : route('jadwal.admin.store') }}">
        @csrf
        @if(isset($jadwal)) @method('PUT') @endif
        <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Hari <span class="text-red-500">*</span></label>
            <select name="day" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('day') border-red-500 @enderror">
                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                <option value="{{ $hari }}" {{ old('day', $jadwal->day ?? request('day')) == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                @endforeach
            </select>
            @error('day')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Jam Pelajaran <span class="text-red-500">*</span></label>
            <input type="number" name="jam" min="1" max="10" value="{{ old('jam', request('jam')) }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400">
            <small class="text-gray-500">Jam pelajaran ke-berapa (1-10)</small>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Mata Pelajaran <span class="text-red-500">*</span></label>
            <select name="subject_id" id="subject_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('subject_id') border-red-500 @enderror">
                <option value="">- Pilih Mapel -</option>
                @foreach($subjects as $mapel)
                <option value="{{ $mapel->id }}" {{ old('subject_id', $jadwal->subject_id ?? request('subject_id')) == $mapel->id ? 'selected' : '' }}>{{ $mapel->name }}</option>
                @endforeach
            </select>
            @error('subject_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Guru Pengampu <span class="text-red-500">*</span></label>
            <select name="teacher_id" id="teacher_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('teacher_id') border-red-500 @enderror">
                <option value="">- Pilih Guru -</option>
                @foreach($teachers as $guru)
                <option value="{{ $guru->id }}" data-mapel="{{ $guru->subject_id }}" {{ old('teacher_id', $jadwal->teacher_id ?? '') == $guru->id ? 'selected' : '' }}>{{ $guru->full_name }}</option>
                @endforeach
            </select>
            @error('teacher_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        @php
        $start = strtotime('07:00');
        $end = strtotime('15:00');
        $intervals = [];
        while ($start <= $end) {
            $intervals[]=date('H:i', $start);
            $start=strtotime('+30 minutes', $start);
            }
            @endphp
            <div class="mb-4 flex gap-2">
            <div class="flex-1">
                <label class="block mb-1 font-semibold">Jam Mulai <span class="text-red-500">*</span></label>
                <select name="time_start" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('time_start') border-red-500 @enderror">
                    <option value="">- Pilih Jam Mulai -</option>
                    @foreach($intervals as $time)
                    <option value="{{ $time }}" {{ old('time_start', $jadwal->time_start ?? '') == $time ? 'selected' : '' }}>{{ $time }}</option>
                    @endforeach
                </select>
                @error('time_start')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="flex-1">
                <label class="block mb-1 font-semibold">Jam Selesai <span class="text-red-500">*</span></label>
                <select name="time_end" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400 @error('time_end') border-red-500 @enderror">
                    <option value="">- Pilih Jam Selesai -</option>
                    @foreach($intervals as $time)
                    <option value="{{ $time }}" {{ old('time_end', $jadwal->time_end ?? '') == $time ? 'selected' : '' }}>{{ $time }}</option>
                    @endforeach
                </select>
                @error('time_end')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
            </div>
</div>
<div class="flex gap-2 mt-6">
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">Simpan</button>
    <a href="{{ route('jadwal.admin.index', ['kelas_id' => $classroom->id]) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded font-semibold">Kembali</a>
</div>
</form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subjectSelect = document.getElementById('subject_id');
        const teacherSelect = document.getElementById('teacher_id');

        function filterTeachers() {
            const subjectId = subjectSelect.value;
            Array.from(teacherSelect.options).forEach(opt => {
                if (!opt.value) return opt.style.display = '';
                opt.style.display = (subjectId === '' || opt.getAttribute('data-mapel') === subjectId) ? '' : 'none';
            });
            // Jika guru terpilih tidak sesuai mapel, reset
            if (teacherSelect.selectedIndex > 0 && teacherSelect.options[teacherSelect.selectedIndex].style.display === 'none') {
                teacherSelect.selectedIndex = 0;
            }
        }
        subjectSelect.addEventListener('change', filterTeachers);
        filterTeachers();
    });
</script>
@endsection
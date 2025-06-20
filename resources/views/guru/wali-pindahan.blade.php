@extends('layouts.dashboard')
@section('title', 'Siswa Pindahan')
@section('content')
<h2 class="text-2xl font-bold mb-4">Siswa Pindahan</h2>
<p class="mb-4">Halaman ini untuk mengelola data siswa pindahan.</p>
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
@endif
@if($students->count() > 0 && $subjects->count() > 0)
<form method="POST" action="{{ route('wali.pindahan.store') }}">
    @csrf
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm align-middle mb-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-3">Nama Siswa</th>
                    @foreach($subjects as $subject)
                    <th class="py-2 px-3">{{ $subject->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td class="py-2 px-3 font-semibold">{{ $student->full_name }}</td>
                    @foreach($subjects as $subject)
                    <td class="py-2 px-3">
                        <input type="number" step="0.01" min="0" max="100"
                            name="grades[{{ $student->id }}_{{ $subject->id }}][nilai]"
                            value="{{ $grades[$student->id][$subject->id][0]->final_grade ?? '' }}"
                            class="w-20 border rounded px-2 py-1 focus:outline-none focus:ring focus:border-blue-400 @error('grades.'.$student->id.'_'.$subject->id.'.nilai') border-red-500 @enderror">
                        <input type="hidden" name="grades[{{ $student->id }}_{{ $subject->id }}][student_id]" value="{{ $student->id }}">
                        <input type="hidden" name="grades[{{ $student->id }}_{{ $subject->id }}][subject_id]" value="{{ $subject->id }}">
                        @error('grades.'.$student->id.'_'.$subject->id.'.nilai')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">Simpan Nilai Konversi</button>
</form>
@else
<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
    <p class="font-bold">Tidak ada siswa pindahan atau mapel di kelas ini.</p>
</div>
@endif
@endsection
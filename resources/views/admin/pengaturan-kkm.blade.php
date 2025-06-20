@extends('layouts.dashboard')
@section('title', 'Pengaturan Bobot & KKM')
@section('content')
<h2 class="text-2xl font-bold mb-4">Pengaturan Bobot & KKM</h2>
@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
@endif
@if(session('success_failed_subjects'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success_failed_subjects') }}</div>
@endif
@if(session('error_failed_subjects'))
<div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error_failed_subjects') }}</div>
@endif
<p class="mb-4">Tahun Ajaran Aktif: <span class="font-semibold">{{ $activeYear->year ?? '-' }} Semester {{ $activeYear->semester ?? '-' }}</span></p>
<form method="POST" action="{{ route('pengaturan.kkm.update') }}">
    @csrf
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-blue-100">
                    <th class="py-2 px-4">No</th>
                    <th class="py-2 px-4 text-left">Mata Pelajaran</th>
                    <th class="py-2 px-4">KKM</th>
                    <th class="py-2 px-4">Bobot Tugas (%)</th>
                    <th class="py-2 px-4">Bobot UTS (%)</th>
                    <th class="py-2 px-4">Bobot UAS (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $i => $mapel)
                @php $setting = $settings[$mapel->id] ?? null; @endphp
                <tr class="border-b">
                    <td class="py-2 px-4 text-center">{{ $i+1 }}</td>
                    <td class="py-2 px-4">{{ $mapel->name }}</td>
                    <td class="py-2 px-4"><input type="number" name="settings[{{ $mapel->id }}][kkm]" value="{{ old('settings.'.$mapel->id.'.kkm', $setting->kkm ?? 75) }}" min="1" max="100" class="w-20 border rounded px-2 py-1"></td>
                    <td class="py-2 px-4"><input type="number" name="settings[{{ $mapel->id }}][assignment_weight]" value="{{ old('settings.'.$mapel->id.'.assignment_weight', $setting->assignment_weight ?? 30) }}" min="0" max="100" class="w-20 border rounded px-2 py-1"></td>
                    <td class="py-2 px-4"><input type="number" name="settings[{{ $mapel->id }}][uts_weight]" value="{{ old('settings.'.$mapel->id.'.uts_weight', $setting->uts_weight ?? 30) }}" min="0" max="100" class="w-20 border rounded px-2 py-1"></td>
                    <td class="py-2 px-4"><input type="number" name="settings[{{ $mapel->id }}][uas_weight]" value="{{ old('settings.'.$mapel->id.'.uas_weight', $setting->uas_weight ?? 40) }}" min="0" max="100" class="w-20 border rounded px-2 py-1"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6 flex gap-2">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">Simpan</button>
    </div>
</form>
<form method="POST" action="{{ route('pengaturan.kkm.update-failed-subjects') }}" class="mb-6 flex items-center gap-2">
    @csrf
    <label class="font-semibold">Batas maksimal mapel gagal kenaikan/kelulusan:</label>
    <input type="number" name="max_failed_subjects" value="{{ old('max_failed_subjects', config('siakad.max_failed_subjects', 2)) }}" min="0" max="20" class="w-20 border rounded px-2 py-1">
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">Simpan</button>
</form>
@endsection
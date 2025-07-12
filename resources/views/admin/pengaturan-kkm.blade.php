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
@if(session('success_semester_weights'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success_semester_weights') }}</div>
@endif
@if(session('error_semester_weights'))
<div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error_semester_weights') }}</div>
@endif

<p class="mb-4">Tahun Ajaran Aktif: <span class="font-semibold">{{ $activeYear->year ?? '-' }}</span></p>
<p class="mb-4">Semester Aktif: <span class="font-semibold">{{ $activeSemester->name ?? '-' }}</span></p>

@if($activeSemester->name !== 'Ganjil')
<div class="mb-4 p-3 bg-yellow-100 text-yellow-800 rounded font-semibold">Pengaturan hanya dapat diubah pada semester Ganjil. Untuk semester Genap, pengaturan dikunci dan tidak dapat diubah.</div>
@endif

<!-- Pengaturan Bobot Semester -->
<div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded">
    <h3 class="text-lg font-semibold mb-3">Pengaturan Bobot Semester untuk Nilai Akhir Tahun</h3>
    <form method="POST" action="{{ route('pengaturan.kkm.update-semester-weights') }}" class="flex items-center gap-4">
        @csrf
        <div>
            <label class="block font-semibold mb-1">Bobot Semester Ganjil (%)</label>
            <input type="number" name="ganjil_weight" value="{{ old('ganjil_weight', isset($semesterWeights) ? (int) $semesterWeights->ganjil_weight : 50) }}" min="0" max="100" step="1" class="w-24 border rounded px-2 py-1" placeholder="0-100" @if($activeSemester->name !== 'Ganjil') disabled @endif>
        </div>
        <div>
            <label class="block font-semibold mb-1">Bobot Semester Genap (%)</label>
            <input type="number" name="genap_weight" value="{{ old('genap_weight', isset($semesterWeights) ? (int) $semesterWeights->genap_weight : 50) }}" min="0" max="100" step="1" class="w-24 border rounded px-2 py-1" placeholder="0-100" @if($activeSemester->name !== 'Ganjil') disabled @endif>
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold" @if($activeSemester->name !== 'Ganjil') disabled style="opacity:0.5;cursor:not-allowed;" @endif>Simpan Bobot Semester</button>
        </div>
    </form>
    <p class="text-sm text-gray-600 mt-2">Bobot ini harus bilangan bulat dan totalnya <b>tepat 100%</b> untuk menghitung nilai akhir tahun dalam proses kenaikan kelas.</p>
</div>

<!-- Pengaturan KKM dan Bobot Mapel -->
<div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded">
    <h3 class="text-lg font-semibold mb-3">Pengaturan KKM & Bobot Mapel (Semester {{ $activeSemester->name ?? '-' }})</h3>
    <p class="text-sm text-gray-600 mb-3">Pengaturan ini hanya berlaku untuk semester yang sedang aktif.</p>

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
                        <td class="py-2 px-4"><input type="number" name="settings[{{ $mapel->id }}][kkm]" value="{{ old('settings.'.$mapel->id.'.kkm', $setting->kkm ?? 75) }}" min="1" max="100" class="w-20 border rounded px-2 py-1" @if($activeSemester->name !== 'Ganjil') disabled @endif></td>
                        <td class="py-2 px-4"><input type="number" name="settings[{{ $mapel->id }}][assignment_weight]" value="{{ old('settings.'.$mapel->id.'.assignment_weight', $setting->assignment_weight ?? 30) }}" min="0" max="100" class="w-20 border rounded px-2 py-1" @if($activeSemester->name !== 'Ganjil') disabled @endif></td>
                        <td class="py-2 px-4"><input type="number" name="settings[{{ $mapel->id }}][uts_weight]" value="{{ old('settings.'.$mapel->id.'.uts_weight', $setting->uts_weight ?? 30) }}" min="0" max="100" class="w-20 border rounded px-2 py-1" @if($activeSemester->name !== 'Ganjil') disabled @endif></td>
                        <td class="py-2 px-4"><input type="number" name="settings[{{ $mapel->id }}][uas_weight]" value="{{ old('settings.'.$mapel->id.'.uas_weight', $setting->uas_weight ?? 40) }}" min="0" max="100" class="w-20 border rounded px-2 py-1" @if($activeSemester->name !== 'Ganjil') disabled @endif></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold" @if($activeSemester->name !== 'Ganjil') disabled style="opacity:0.5;cursor:not-allowed;" @endif>Simpan Pengaturan Mapel</button>
        </div>
    </form>
</div>

<!-- Pengaturan Batas Mapel Gagal -->
@php
use App\Models\AppSetting;
$maxFailedSubjects = old('max_failed_subjects', AppSetting::getValue('max_failed_subjects', 2));
@endphp
<div class="mb-6 p-4 bg-green-50 border border-green-200 rounded">
    <h3 class="text-lg font-semibold mb-3">Pengaturan Batas Mapel Gagal</h3>
    <form method="POST" action="{{ route('pengaturan.kkm.update-failed-subjects') }}" class="flex items-center gap-4">
        @csrf
        <label class="font-semibold">Batas maksimal mapel gagal kenaikan/kelulusan:</label>
        <input type="number" name="max_failed_subjects" value="{{ $maxFailedSubjects }}" min="0" max="20" class="w-20 border rounded px-2 py-1" @if($activeSemester->name !== 'Ganjil') disabled @endif>
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold" @if($activeSemester->name !== 'Ganjil') disabled style="opacity:0.5;cursor:not-allowed;" @endif>Simpan</button>
    </form>
    <p class="text-sm text-gray-600 mt-2">Pengaturan ini berlaku untuk semua semester dalam tahun ajaran aktif.</p>
</div>
@endsection
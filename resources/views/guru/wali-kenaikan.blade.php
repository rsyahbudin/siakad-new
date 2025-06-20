@extends('layouts.dashboard')
@section('title', 'Kenaikan Kelas')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-2">Proses Kenaikan Kelas untuk Kelas {{ $kelas->name }}</h2>
    <p class="mb-4 text-gray-600">Tahun Ajaran Aktif: <span class="font-semibold">{{ $activeYear->year ?? '-' }} Semester {{ $activeYear->semester ?? '-' }}</span></p>

    <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-800">
        <h4 class="font-bold">Informasi</h4>
        <p class="text-sm">Halaman ini digunakan untuk menentukan status kenaikan kelas bagi setiap siswa. Sistem memberikan rekomendasi berdasarkan jumlah mata pelajaran yang tidak tuntas (di bawah KKM). Keputusan akhir tetap berada di tangan Anda sebagai wali kelas.</p>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow-sm">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('wali.kenaikan.store') }}">
        @csrf
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="py-2 px-4 text-left">Nama Siswa</th>
                        <th class="py-2 px-4 text-center">Gagal Mapel</th>
                        <th class="py-2 px-4 text-left">Rekomendasi Sistem</th>
                        <th class="py-2 px-4 text-left w-64">Keputusan Wali Kelas</th>
                        <th class="py-2 px-4 text-left">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotionData as $data)
                    <tr class="border-b hover:bg-blue-50">
                        <td class="py-2 px-4 font-medium">{{ $data->student->full_name }}</td>
                        <td class="py-2 px-4 text-center">{{ $data->failed_subjects }}</td>
                        <td class="py-2 px-4">
                            @if($data->system_recommendation == 'Layak Naik')
                            <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">{{ $data->system_recommendation }}</span>
                            @else
                            <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">{{ $data->system_recommendation }}</span>
                            @endif
                        </td>
                        <td class="py-2 px-4">
                            <select name="promotions[{{ $data->student->id }}][final_decision]" class="w-full border rounded px-2 py-1">
                                <option value="">- Pilih Keputusan -</option>
                                <option value="Naik Kelas" {{ $data->final_decision == 'Naik Kelas' ? 'selected' : '' }}>Naik Kelas</option>
                                <option value="Tidak Naik Kelas" {{ $data->final_decision == 'Tidak Naik Kelas' ? 'selected' : '' }}>Tidak Naik Kelas</option>
                            </select>
                        </td>
                        <td class="py-2 px-4">
                            <input type="text" name="promotions[{{ $data->student->id }}][notes]" class="w-full border rounded px-2 py-1 text-sm" placeholder="Opsional">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">Tidak ada siswa di kelas ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded-lg hover:bg-blue-700 shadow transition">Simpan Keputusan</button>
        </div>
    </form>
</div>
@endsection
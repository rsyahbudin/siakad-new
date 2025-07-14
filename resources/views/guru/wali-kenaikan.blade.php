@extends('layouts.dashboard')
@section('title', 'Kenaikan Kelas')

@section('content')
@php
// Pastikan variabel $isLastGrade tersedia
if (!isset($isLastGrade)) {
$isLastGrade = isset($kelas) && str_starts_with($kelas->name, 'XII');
}
@endphp
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-2">Proses Kenaikan Kelas untuk Kelas {{ $kelas->name }}</h2>
    <p class="mb-4 text-gray-600">Tahun Ajaran Aktif: <span class="font-semibold">{{ $activeSemester->academicYear->year ?? '-' }} Semester {{ $activeSemester->name ?? '-' }}</span></p>

    <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-800">
        <h4 class="font-bold">Informasi</h4>
        <p class="text-sm">Halaman ini digunakan untuk menentukan status kenaikan kelas bagi setiap siswa. Sistem memberikan rekomendasi berdasarkan jumlah mata pelajaran yang tidak tuntas (di bawah KKM). Keputusan akhir tetap berada di tangan Anda sebagai wali kelas.</p>
        <p class="text-sm mt-2"><strong>Batas maksimal mata pelajaran yang boleh tidak tuntas:</strong> {{ $maxFailedSubjects }} mata pelajaran</p>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow-sm">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded shadow-sm">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('wali.kenaikan.store') }}">
        @csrf

        <!-- Ringkasan Statistik Kenaikan -->
        <div class="flex flex-wrap gap-4 mb-6">
            <div class="flex items-center bg-green-100 text-green-800 rounded-lg px-4 py-2 shadow min-w-[180px]">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <div>
                    <div class="text-lg font-bold">{{ $countNaik }}</div>
                    <div class="text-xs">{{ isset($isLastGrade) && $isLastGrade ? 'Lulus' : 'Naik Kelas' }}</div>
                </div>
            </div>
            <div class="flex items-center bg-red-100 text-red-800 rounded-lg px-4 py-2 shadow min-w-[180px]">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <div>
                    <div class="text-lg font-bold">{{ $countTidakNaik }}</div>
                    <div class="text-xs">{{ isset($isLastGrade) && $isLastGrade ? 'Tidak Lulus' : 'Tinggal Kelas' }}</div>
                </div>
            </div>
            <div class="flex items-center bg-gray-100 text-gray-800 rounded-lg px-4 py-2 shadow min-w-[180px]">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                </svg>
                <div>
                    <div class="text-lg font-bold">{{ $countBelum }}</div>
                    <div class="text-xs">Belum Diputuskan</div>
                </div>
            </div>
        </div>
        <!-- Tabel Ringkasan -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Ringkasan Siswa</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left font-semibold">Nama Siswa</th>
                            <th class="py-3 px-4 text-center font-semibold">Gagal Mapel</th>
                            <th class="py-3 px-4 text-center font-semibold">Rekomendasi</th>
                            <th class="py-3 px-4 text-center font-semibold">Keputusan</th>
                            <th class="py-3 px-4 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promotionData as $index => $data)
                        <tr class="border-b hover:bg-gray-50 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                            <td class="py-3 px-4">
                                <div>
                                    <div class="font-medium">{{ $data->student->full_name }}</div>
                                    <div class="text-xs text-gray-500">NIS: {{ $data->student->nis ?? '-' }}</div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $data->failed_subjects > $data->max_failed_subjects ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $data->failed_subjects }}/{{ $data->max_failed_subjects }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if(str_contains($data->system_recommendation, 'Layak'))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $data->system_recommendation }}
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $data->system_recommendation }}
                                </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <select name="promotions[{{ $data->student->id }}][final_decision]" class="w-full border rounded px-2 py-1 text-sm">
                                    <option value="">- Pilih -</option>
                                    @if($data->is_last_grade)
                                    <option value="Lulus" {{ $data->final_decision == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                    <option value="Tidak Lulus" {{ $data->final_decision == 'Tidak Lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                                    @else
                                    <option value="Naik Kelas" {{ $data->final_decision == 'Naik Kelas' ? 'selected' : '' }}>Naik Kelas</option>
                                    <option value="Tidak Naik Kelas" {{ $data->final_decision == 'Tidak Naik Kelas' ? 'selected' : '' }}>Tidak Naik Kelas</option>
                                    @endif
                                </select>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button type="button" onclick="toggleDetails('{{ $data->student->id }}')" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    <span id="btn-{{ $data->student->id }}">Lihat Detail</span>
                                </button>
                            </td>
                        </tr>

                        <!-- Detail Nilai (Collapsible) -->
                        <tr id="details-{{ $data->student->id }}" class="hidden">
                            <td colspan="5" class="p-0">
                                <div class="bg-gray-50 p-4">
                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="font-semibold text-gray-700">Detail Nilai {{ $data->student->full_name }}</h4>
                                        <div class="flex gap-2">
                                            <input type="text" name="promotions[{{ $data->student->id }}][notes]"
                                                value="{{ old("promotions.{$data->student->id}.notes") }}"
                                                class="border rounded px-3 py-1 text-sm w-64"
                                                placeholder="Catatan (opsional)">
                                        </div>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-xs border">
                                            <thead class="bg-white">
                                                <tr>
                                                    <th class="py-2 px-2 text-left border font-medium">Mapel</th>
                                                    <th class="py-2 px-2 text-center border font-medium">Ganjil</th>
                                                    <th class="py-2 px-2 text-center border font-medium">Genap</th>
                                                    <th class="py-2 px-2 text-center border font-medium">Akhir Tahun</th>
                                                    <th class="py-2 px-2 text-center border font-medium">KKM</th>
                                                    <th class="py-2 px-2 text-center border font-medium">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data->subject_details as $detail)
                                                @php
                                                $status = '-';
                                                $statusClass = 'text-gray-500';
                                                $rowClass = 'border-b';

                                                if ($detail['has_complete_grades']) {
                                                if ($detail['yearly_grade'] !== null && $detail['kkm'] !== null) {
                                                if ($detail['yearly_grade'] >= $detail['kkm']) {
                                                $status = '✓';
                                                $statusClass = 'text-green-600 font-bold';
                                                } else {
                                                $status = '✗';
                                                $statusClass = 'text-red-600 font-bold';
                                                }
                                                }

                                                if ($detail['is_failed']) {
                                                $rowClass .= ' bg-red-50';
                                                }
                                                } else {
                                                $status = '!';
                                                $statusClass = 'text-orange-600 font-bold';
                                                $rowClass .= ' bg-orange-50';
                                                }
                                                @endphp
                                                <tr class="{{ $rowClass }}">
                                                    <td class="py-1 px-2 border font-medium">{{ $detail['subject']->name }}</td>
                                                    <td class="py-1 px-2 text-center border">{{ $detail['ganjil_grade'] !== null ? number_format($detail['ganjil_grade'], 1) : '-' }}</td>
                                                    <td class="py-1 px-2 text-center border">{{ $detail['genap_grade'] !== null ? number_format($detail['genap_grade'], 1) : '-' }}</td>
                                                    <td class="py-1 px-2 text-center border font-bold {{ $detail['yearly_grade'] !== null ? 'bg-yellow-100' : '' }}">
                                                        {{ $detail['yearly_grade'] !== null ? number_format($detail['yearly_grade'], 1) : '-' }}
                                                    </td>
                                                    <td class="py-1 px-2 text-center border">{{ $detail['kkm'] !== null ? $detail['kkm'] : '-' }}</td>
                                                    <td class="py-1 px-2 text-center border {{ $statusClass }}">{{ $status }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-3 text-xs text-gray-600">
                                        <span class="inline-block mr-4"><span class="text-green-600 font-bold">✓</span> Lulus</span>
                                        <span class="inline-block mr-4"><span class="text-red-600 font-bold">✗</span> Tidak Lulus</span>
                                        <span class="inline-block"><span class="text-orange-600 font-bold">!</span> Data Tidak Lengkap</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="text-lg font-medium mb-2">Tidak ada siswa di kelas ini</h3>
                                    <p class="text-sm">Belum ada data siswa yang dapat diproses untuk kenaikan kelas.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($promotionData->count() > 0)
        <div class="flex justify-between items-center">
            <div class="text-sm text-gray-600">
                Total siswa: <span class="font-semibold">{{ $promotionData->count() }}</span>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 shadow transition">
                Simpan Semua Keputusan
            </button>
        </div>
        @endif
    </form>
</div>

<script>
    function toggleDetails(studentId) {
        const details = document.getElementById('details-' + studentId);
        const btn = document.getElementById('btn-' + studentId);

        if (details.classList.contains('hidden')) {
            details.classList.remove('hidden');
            btn.textContent = 'Sembunyikan Detail';
        } else {
            details.classList.add('hidden');
            btn.textContent = 'Lihat Detail';
        }
    }
</script>
@endsection
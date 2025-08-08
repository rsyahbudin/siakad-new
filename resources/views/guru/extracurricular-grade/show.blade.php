@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Input Nilai Ekstrakurikuler</h1>
                <p class="text-gray-600">{{ $extracurricular->name }} - {{ $extracurricular->category }}</p>
            </div>
            <a href="{{ route('teacher.extracurricular-grade.index') }}"
                class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Ekstrakurikuler</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600"><span class="font-medium">Nama:</span> {{ $extracurricular->name }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Kategori:</span> {{ $extracurricular->category }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Jadwal:</span> {{ $extracurricular->getScheduleText() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600"><span class="font-medium">Lokasi:</span> {{ $extracurricular->location ?? 'Belum ditentukan' }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Kapasitas:</span>
                        @if($extracurricular->max_participants)
                        {{ $extracurricular->getActiveStudentsCount() }}/{{ $extracurricular->max_participants }}
                        @else
                        Tidak terbatas
                        @endif
                    </p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Status:</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Aktif
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Siswa Aktif</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('teacher.extracurricular-grade.template', $extracurricular) }}"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                        Download Template
                    </a>
                    <button onclick="document.getElementById('importForm').classList.toggle('hidden')"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        Import Excel
                    </button>
                </div>
            </div>

            <!-- Import Form -->
            <div id="importForm" class="hidden mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-md font-medium text-gray-900 mb-3">Import Nilai dari Excel</h3>
                <form action="{{ route('teacher.extracurricular-grade.import', $extracurricular) }}" method="POST" enctype="multipart/form-data" class="flex space-x-4">
                    @csrf
                    <input type="file" name="file" accept=".xlsx,.xls" required
                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        Import
                    </button>
                </form>
                <p class="text-xs text-gray-600 mt-2">
                    Format: Kolom A (No), Kolom B (NIS), Kolom C (Nama Siswa), Kolom D (Posisi), Kolom E (Nilai), Kolom F (Prestasi), Kolom G (Catatan)
                </p>
            </div>

            @if($students->count() > 0)
            <form action="{{ route('teacher.extracurricular-grade.store', $extracurricular) }}" method="POST">
                @csrf
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Nama Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Posisi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Nilai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Prestasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($students as $index => $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->nis }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->full_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select name="positions[{{ $student->id }}]"
                                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="Anggota" {{ ($student->pivot->position ?? '') === 'Anggota' ? 'selected' : '' }}>Anggota</option>
                                        <option value="Ketua" {{ ($student->pivot->position ?? '') === 'Ketua' ? 'selected' : '' }}>Ketua</option>
                                        <option value="Wakil Ketua" {{ ($student->pivot->position ?? '') === 'Wakil Ketua' ? 'selected' : '' }}>Wakil Ketua</option>
                                        <option value="Sekretaris" {{ ($student->pivot->position ?? '') === 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                                        <option value="Bendahara" {{ ($student->pivot->position ?? '') === 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                                    </select>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select name="grades[{{ $student->id }}]"
                                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">- Pilih Nilai -</option>
                                        <option value="Sangat Baik" {{ ($student->pivot->grade ?? '') === 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                                        <option value="Baik" {{ ($student->pivot->grade ?? '') === 'Baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="Cukup" {{ ($student->pivot->grade ?? '') === 'Cukup' ? 'selected' : '' }}>Cukup</option>
                                        <option value="Kurang" {{ ($student->pivot->grade ?? '') === 'Kurang' ? 'selected' : '' }}>Kurang</option>
                                    </select>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="text" name="achievements[{{ $student->id }}]"
                                        value="{{ $student->pivot->achievements ?? '' }}"
                                        placeholder="Prestasi yang diraih"
                                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <textarea name="notes[{{ $student->id }}]"
                                        placeholder="Catatan khusus"
                                        rows="2"
                                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $student->pivot->notes ?? '' }}</textarea>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        Simpan Nilai
                    </button>
                </div>
            </form>
            @else
            <div class="text-center py-8">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Siswa Aktif</h3>
                <p class="text-gray-600">Tidak ada siswa yang aktif mengikuti ekstrakurikuler ini.</p>
            </div>
            @endif
        </div>
    </div>

    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <h3 class="text-sm font-medium text-yellow-900 mb-2">Panduan Penilaian</h3>
        <ul class="text-sm text-yellow-800 space-y-1">
            <li>• <strong>Sangat Baik:</strong> Siswa sangat aktif, berprestasi tinggi, dan menjadi teladan</li>
            <li>• <strong>Baik:</strong> Siswa aktif mengikuti kegiatan dan menunjukkan kemajuan</li>
            <li>• <strong>Cukup:</strong> Siswa mengikuti kegiatan dengan baik namun masih perlu peningkatan</li>
            <li>• <strong>Kurang:</strong> Siswa kurang aktif atau belum menunjukkan kemajuan yang signifikan</li>
        </ul>
    </div>
</div>
@endsection
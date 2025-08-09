@extends('layouts.dashboard')

@section('title', 'Detail Ekstrakurikuler')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Detail Ekstrakurikuler</h1>
        <div class="flex space-x-2">
            <a href="{{ route('extracurricular.edit', $extracurricular) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit Ekskul
            </a>
            <a href="{{ route('extracurricular.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Ekstrakurikuler -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold mb-4 text-gray-900">Informasi Ekstrakurikuler</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ekstrakurikuler</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $extracurricular->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                @if($extracurricular->category === 'Olahraga') bg-red-100 text-red-800
                                @elseif($extracurricular->category === 'Seni') bg-purple-100 text-purple-800
                                @elseif($extracurricular->category === 'Akademik') bg-blue-100 text-blue-800
                                @elseif($extracurricular->category === 'Keagamaan') bg-green-100 text-green-800
                                @elseif($extracurricular->category === 'Teknologi') bg-yellow-100 text-yellow-800
                                @elseif($extracurricular->category === 'Bahasa') bg-indigo-100 text-indigo-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                            {{ $extracurricular->category }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pembina</label>
                        <p class="text-gray-900">{{ $extracurricular->teacher->full_name ?? 'Belum ditentukan' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                @if($extracurricular->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                            {{ $extracurricular->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <p class="text-gray-900">{{ $extracurricular->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                </div>
            </div>

            <!-- Jadwal dan Lokasi -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold mb-4 text-gray-900">Jadwal & Lokasi</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                        <p class="text-gray-900">{{ $extracurricular->day ?? 'Belum ditentukan' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                        <p class="text-gray-900">
                            @if($extracurricular->time_start && $extracurricular->time_end)
                            {{ $extracurricular->time_start }} - {{ $extracurricular->time_end }}
                            @else
                            Belum ditentukan
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                        <p class="text-gray-900">{{ $extracurricular->location ?? 'Belum ditentukan' }}</p>
                    </div>
                </div>
            </div>

            <!-- Daftar Siswa -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-900">Daftar Siswa</h2>
                    <button onclick="showAddStudentModal()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                        Tambah Siswa
                    </button>
                </div>

                @if($extracurricular->students->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($extracurricular->students as $index => $student)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->nis }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->full_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($student->pivot->status === 'Aktif') bg-green-100 text-green-800 
                                            @else bg-red-100 text-red-800 @endif">
                                        {{ $student->pivot->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $student->pivot->join_date ? \Carbon\Carbon::parse($student->pivot->join_date)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-900 edit-student-btn"
                                            data-student-id="{{ $student->id }}"
                                            data-status="{{ $student->pivot->status }}">Edit</button>
                                        <form action="{{ route('extracurricular.remove-student', [$extracurricular, $student]) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin mengeluarkan siswa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Keluarkan</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center text-gray-500 py-8">Belum ada siswa yang terdaftar</p>
                @endif
            </div>
        </div>

        <!-- Statistik -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold mb-4 text-gray-900">Statistik</h2>

                <div class="space-y-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-blue-900">{{ $extracurricular->getActiveStudentsCount() }}</div>
                        <div class="text-sm text-blue-700">Total Anggota Aktif</div>
                    </div>

                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-green-900">
                            @if($extracurricular->max_participants)
                            {{ $extracurricular->max_participants - $extracurricular->getActiveStudentsCount() }}
                            @else
                            ∞
                            @endif
                        </div>
                        <div class="text-sm text-green-700">Sisa Kapasitas</div>
                    </div>

                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-900">{{ $extracurricular->students->where('pivot.status', 'Tidak Aktif')->count() }}</div>
                        <div class="text-sm text-yellow-700">Anggota Tidak Aktif</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Siswa -->
<div id="addStudentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Siswa ke Ekstrakurikuler</h3>
                <form action="{{ route('extracurricular.add-student', $extracurricular) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Siswa</label>
                        <select name="student_id" id="student_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Pilih siswa...</option>
                            @foreach($availableStudents as $student)
                            <option value="{{ $student->id }}">{{ $student->nis }} - {{ $student->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="hideAddStudentModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Siswa -->
<div id="editStudentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Data Siswa</h3>
                <form id="editStudentForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="edit_status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="hideEditStudentModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function showAddStudentModal() {
        document.getElementById('addStudentModal').classList.remove('hidden');
    }

    function hideAddStudentModal() {
        document.getElementById('addStudentModal').classList.add('hidden');
    }

    // Event listeners for edit buttons
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.edit-student-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const studentId = this.getAttribute('data-student-id');
                const status = this.getAttribute('data-status');

                document.getElementById('editStudentForm').action = `{{ route('extracurricular.update-student', [$extracurricular, 'STUDENT_ID']) }}`.replace('STUDENT_ID', studentId);
                document.getElementById('edit_status').value = status;
                document.getElementById('editStudentModal').classList.remove('hidden');
            });
        });
    });

    function hideEditStudentModal() {
        document.getElementById('editStudentModal').classList.add('hidden');
    }
</script>
@endsection
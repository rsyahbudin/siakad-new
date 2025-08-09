@extends('layouts.dashboard')

@section('title', 'Edit Ekstrakurikuler')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Ekstrakurikuler</h1>
        <a href="{{ route('extracurricular.show', $extracurricular) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Kembali
        </a>
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

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('extracurricular.update', $extracurricular) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informasi Dasar -->
                <div class="lg:col-span-2">
                    <h2 class="text-xl font-bold mb-4 text-gray-900">Informasi Dasar</h2>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Ekstrakurikuler <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $extracurricular->name) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                        required>
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select id="category" name="category"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror"
                        required>
                        <option value="">Pilih kategori...</option>
                        <option value="Olahraga" {{ old('category', $extracurricular->category) === 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                        <option value="Seni" {{ old('category', $extracurricular->category) === 'Seni' ? 'selected' : '' }}>Seni</option>
                        <option value="Akademik" {{ old('category', $extracurricular->category) === 'Akademik' ? 'selected' : '' }}>Akademik</option>
                        <option value="Keagamaan" {{ old('category', $extracurricular->category) === 'Keagamaan' ? 'selected' : '' }}>Keagamaan</option>
                        <option value="Teknologi" {{ old('category', $extracurricular->category) === 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                        <option value="Bahasa" {{ old('category', $extracurricular->category) === 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                        <option value="Lainnya" {{ old('category', $extracurricular->category) === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                        placeholder="Deskripsi singkat tentang ekstrakurikuler...">{{ old('description', $extracurricular->description) }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pembina dan Kapasitas -->
                <div class="lg:col-span-2">
                    <h2 class="text-xl font-bold mb-4 text-gray-900 mt-6">Pembina & Kapasitas</h2>
                </div>

                <div>
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">Pembina <span class="text-red-500">*</span></label>
                    <select id="teacher_id" name="teacher_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('teacher_id') border-red-500 @enderror"
                        required>
                        <option value="">Pilih pembina...</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $extracurricular->teacher_id) == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->full_name }}
                        </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-2">Maksimal Peserta</label>
                    <input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants', $extracurricular->max_participants) }}"
                        min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('max_participants') border-red-500 @enderror"
                        placeholder="Kosongkan untuk tanpa batas">
                    @error('max_participants')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Kosongkan jika tidak ada batasan peserta</p>
                </div>

                <!-- Jadwal -->
                <div class="lg:col-span-2">
                    <h2 class="text-xl font-bold mb-4 text-gray-900 mt-6">Jadwal & Lokasi</h2>
                </div>

                <div>
                    <label for="day" class="block text-sm font-medium text-gray-700 mb-2">Hari</label>
                    <select id="day" name="day"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('day') border-red-500 @enderror">
                        <option value="">Pilih hari...</option>
                        <option value="Senin" {{ old('day', $extracurricular->day) === 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ old('day', $extracurricular->day) === 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ old('day', $extracurricular->day) === 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ old('day', $extracurricular->day) === 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ old('day', $extracurricular->day) === 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="Sabtu" {{ old('day', $extracurricular->day) === 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                        <option value="Minggu" {{ old('day', $extracurricular->day) === 'Minggu' ? 'selected' : '' }}>Minggu</option>
                    </select>
                    @error('day')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                    <input type="text" id="location" name="location" value="{{ old('location', $extracurricular->location) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location') border-red-500 @enderror"
                        placeholder="Contoh: Aula, Lapangan, Lab Komputer">
                    @error('location')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="time_start" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai</label>
                    <input type="time" id="time_start" name="time_start" value="{{ old('time_start', $extracurricular->time_start) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('time_start') border-red-500 @enderror">
                    @error('time_start')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="time_end" class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai</label>
                    <input type="time" id="time_end" name="time_end" value="{{ old('time_end', $extracurricular->time_end) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('time_end') border-red-500 @enderror">
                    @error('time_end')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="lg:col-span-2">
                    <h2 class="text-xl font-bold mb-4 text-gray-900 mt-6">Status</h2>
                </div>

                <div class="lg:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $extracurricular->is_active) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Aktif (siswa dapat mendaftar)</span>
                    </label>
                    @error('is_active')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('extracurricular.show', $extracurricular) }}"
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                    Update Ekstrakurikuler
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

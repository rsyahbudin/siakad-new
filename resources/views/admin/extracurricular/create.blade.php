@extends('layouts.dashboard')

@section('title', 'Tambah Ekstrakurikuler Baru')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('extracurricular.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                ← Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Tambah Ekstrakurikuler Baru</h1>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('extracurricular.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Ekskul -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Ekstrakurikuler *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                        <select name="category" id="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                            @endforeach
                        </select>
                        @error('category')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pembina -->
                    <div>
                        <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">Pembina</label>
                        <select name="teacher_id" id="teacher_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Pembina</option>
                            @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->full_name }}
                            </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hari -->
                    <div>
                        <label for="day" class="block text-sm font-medium text-gray-700 mb-2">Hari</label>
                        <select name="day" id="day"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Hari</option>
                            @foreach($days as $day)
                            <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                            @endforeach
                        </select>
                        @error('day')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('location')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Waktu Mulai -->
                    <div>
                        <label for="time_start" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai</label>
                        <input type="time" name="time_start" id="time_start" value="{{ old('time_start') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('time_start')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Waktu Selesai -->
                    <div>
                        <label for="time_end" class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai</label>
                        <input type="time" name="time_end" id="time_end" value="{{ old('time_end') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('time_end')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Maksimal Peserta -->
                    <div>
                        <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-2">Maksimal Peserta</label>
                        <input type="number" name="max_participants" id="max_participants" value="{{ old('max_participants') }}"
                            min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Kosongkan jika tidak terbatas">
                        @error('max_participants')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Aktif</label>
                        </div>
                        @error('is_active')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Simpan Ekstrakurikuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
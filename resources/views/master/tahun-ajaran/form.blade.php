@extends('layouts.dashboard')
@section('title', isset($tahunAjaran) ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ isset($tahunAjaran) ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran' }}</h1>
                <p class="text-gray-600">{{ isset($tahunAjaran) ? 'Perbarui informasi tahun ajaran' : 'Tambahkan tahun ajaran baru ke sistem' }}</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form method="POST" action="{{ isset($tahunAjaran) ? route('tahun-ajaran.update', $tahunAjaran) : route('tahun-ajaran.store') }}" class="p-6 space-y-6">
            @csrf
            @if(isset($tahunAjaran))
            @method('PUT')
            @endif

            <!-- Academic Year Info -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-2 border-b border-gray-200">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Tahun Ajaran</h2>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tahun Ajaran <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input type="text" name="year" value="{{ old('year', $tahunAjaran->year ?? '') }}"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('year') border-red-500 @enderror"
                            placeholder="Contoh: 2024/2025">
                    </div>
                    @error('year')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    <p class="text-gray-500 text-sm mt-1">Format: 2024/2025 atau 2024-2025</p>
                </div>
            </div>

            <!-- Date Range -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-2 border-b border-gray-200">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Periode Tahun Ajaran</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input type="date" name="start_date" value="{{ old('start_date', isset($tahunAjaran) ? $tahunAjaran->start_date->format('Y-m-d') : '') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror">
                        </div>
                        @error('start_date')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                        <p class="text-gray-500 text-sm mt-1">Tanggal dimulainya tahun ajaran</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input type="date" name="end_date" value="{{ old('end_date', isset($tahunAjaran) ? $tahunAjaran->end_date->format('Y-m-d') : '') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror">
                        </div>
                        @error('end_date')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                        <p class="text-gray-500 text-sm mt-1">Tanggal berakhirnya tahun ajaran</p>
                    </div>
                </div>

                <!-- Duration Preview -->
                @if(old('start_date') && old('end_date') || (isset($tahunAjaran) && $tahunAjaran->start_date && $tahunAjaran->end_date))
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <span class="text-sm font-medium text-blue-800">Durasi Tahun Ajaran:</span>
                            <span class="text-sm text-blue-700 ml-2">
                                @php
                                $startDate = old('start_date') ? \Carbon\Carbon::parse(old('start_date')) : $tahunAjaran->start_date ?? null;
                                $endDate = old('end_date') ? \Carbon\Carbon::parse(old('end_date')) : $tahunAjaran->end_date ?? null;
                                if ($startDate && $endDate) {
                                echo $startDate->diffInDays($endDate) . ' hari';
                                }
                                @endphp
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Status -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-2 border-b border-gray-200">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Status Tahun Ajaran</h2>
                </div>

                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                    <input type="checkbox" name="is_active" id="is_active" class="mt-1 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" {{ old('is_active', $tahunAjaran->is_active ?? false) ? 'checked' : '' }}>
                    <div>
                        <label for="is_active" class="text-sm font-medium text-gray-700">Tahun Ajaran Aktif</label>
                        <p class="text-sm text-gray-500 mt-1">Centang jika tahun ajaran ini akan menjadi tahun ajaran aktif. Hanya satu tahun ajaran yang dapat aktif pada satu waktu.</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('tahun-ajaran.index') }}" class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ isset($tahunAjaran) ? 'Update Tahun Ajaran' : 'Simpan Tahun Ajaran' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-calculate duration when dates change
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.querySelector('input[name="start_date"]');
        const endDateInput = document.querySelector('input[name="end_date"]');

        function updateDuration() {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                // Update or create duration preview
                let durationPreview = document.querySelector('.bg-blue-50');
                if (!durationPreview) {
                    durationPreview = document.createElement('div');
                    durationPreview.className = 'bg-blue-50 border border-blue-200 rounded-lg p-4';
                    durationPreview.innerHTML = `
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <span class="text-sm font-medium text-blue-800">Durasi Tahun Ajaran:</span>
                            <span class="text-sm text-blue-700 ml-2">${diffDays} hari</span>
                        </div>
                    </div>
                `;
                    endDateInput.parentElement.parentElement.parentElement.appendChild(durationPreview);
                } else {
                    const durationText = durationPreview.querySelector('.text-blue-700');
                    durationText.textContent = `${diffDays} hari`;
                }
            }
        }

        startDateInput.addEventListener('change', updateDuration);
        endDateInput.addEventListener('change', updateDuration);
    });
</script>
@endsection
@extends('layouts.dashboard')
@section('title', isset($jurusan) ? 'Edit Jurusan' : 'Tambah Jurusan')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ isset($jurusan) ? 'Edit Jurusan' : 'Tambah Jurusan' }}</h1>
                <p class="text-gray-600">{{ isset($jurusan) ? 'Perbarui informasi jurusan' : 'Tambahkan jurusan baru ke sistem' }}</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form method="POST" action="{{ isset($jurusan) ? route('jurusan.update', $jurusan) : route('jurusan.store') }}" class="p-6 space-y-6">
            @csrf
            @if(isset($jurusan))
            @method('PUT')
            @endif

            <!-- Major Information -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-2 border-b border-gray-200">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Jurusan</h2>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Jurusan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <input type="text" name="name" value="{{ old('name', $jurusan->name ?? '') }}"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                            placeholder="Contoh: Ilmu Pengetahuan Alam">
                    </div>
                    @error('name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    <p class="text-gray-500 text-sm mt-1">Masukkan nama lengkap jurusan (contoh: Ilmu Pengetahuan Alam, Ilmu Pengetahuan Sosial)</p>
                </div>
            </div>

            <!-- Abbreviation -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-2 border-b border-gray-200">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Singkatan Jurusan</h2>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Singkatan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <input type="text" name="short_name" value="{{ old('short_name', $jurusan->short_name ?? '') }}"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('short_name') border-red-500 @enderror"
                            placeholder="Contoh: IPA" maxlength="10">
                    </div>
                    @error('short_name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    <p class="text-gray-500 text-sm mt-1">Masukkan singkatan jurusan (maksimal 10 karakter, contoh: IPA, IPS, TKJ)</p>
                </div>
            </div>

            <!-- Preview Section -->
            @if(old('name') || old('short_name') || (isset($jurusan) && ($jurusan->name || $jurusan->short_name)))
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-2 border-b border-gray-200">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Preview</h2>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">
                                {{ old('name', $jurusan->name ?? 'Nama Jurusan') }}
                            </h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ old('short_name', $jurusan->short_name ?? 'SINGKATAN') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('jurusan.index') }}" class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ isset($jurusan) ? 'Update Jurusan' : 'Simpan Jurusan' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-update preview when inputs change
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.querySelector('input[name="name"]');
        const shortNameInput = document.querySelector('input[name="short_name"]');

        function updatePreview() {
            const name = nameInput.value || 'Nama Jurusan';
            const shortName = shortNameInput.value || 'SINGKATAN';

            // Update preview if it exists
            const previewName = document.querySelector('.bg-gray-50 .font-semibold');
            const previewShortName = document.querySelector('.bg-gray-50 .text-blue-800');

            if (previewName) {
                previewName.textContent = name;
            }
            if (previewShortName) {
                previewShortName.textContent = shortName;
            }

            // Create preview if it doesn't exist and we have values
            if (!document.querySelector('.bg-gray-50') && (nameInput.value || shortNameInput.value)) {
                const previewSection = document.createElement('div');
                previewSection.className = 'space-y-6';
                previewSection.innerHTML = `
                <div class="flex items-center gap-3 pb-2 border-b border-gray-200">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Preview</h2>
                </div>
                
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">${name}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    ${shortName}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                // Insert before action buttons
                const actionButtons = document.querySelector('.flex.items-center.justify-between');
                actionButtons.parentElement.insertBefore(previewSection, actionButtons);
            }
        }

        nameInput.addEventListener('input', updatePreview);
        shortNameInput.addEventListener('input', updatePreview);
    });
</script>
@endsection
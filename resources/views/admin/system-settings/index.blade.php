@extends('layouts.dashboard')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pengaturan Sistem</h1>
                <p class="text-gray-600 mt-1">Kelola pengaturan sistem PPDB, Siswa Pindahan, dan Informasi Sekolah</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-green-800 font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- System Settings -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- PPDB System -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Sistem PPDB</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $ppdbEnabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $ppdbEnabled ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Status Sistem</h3>
                        <p class="text-sm text-gray-600">
                            @if($ppdbEnabled)
                            Sistem PPDB saat ini aktif dan dapat diakses oleh calon siswa untuk mendaftar.
                            @else
                            Sistem PPDB saat ini nonaktif dan tidak dapat diakses oleh calon siswa.
                            @endif
                        </p>
                    </div>

                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Fitur yang Terpengaruh</h3>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Form pendaftaran PPDB publik</li>
                            <li>• Cek status pendaftaran</li>
                            <li>• Upload dokumen pendaftar</li>
                            <li>• Manajemen pendaftar oleh admin</li>
                        </ul>
                    </div>

                    <div class="pt-4">
                        <form method="POST" action="{{ route('admin.system-settings.toggle-ppdb') }}">
                            @csrf
                            <button type="submit"
                                class="w-full {{ $ppdbEnabled ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                {{ $ppdbEnabled ? 'Nonaktifkan Sistem PPDB' : 'Aktifkan Sistem PPDB' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transfer Student System -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Sistem Siswa Pindahan</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $transferStudentEnabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $transferStudentEnabled ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Status Sistem</h3>
                        <p class="text-sm text-gray-600">
                            @if($transferStudentEnabled)
                            Sistem Siswa Pindahan saat ini aktif dan dapat diakses oleh calon siswa pindahan.
                            @else
                            Sistem Siswa Pindahan saat ini nonaktif dan tidak dapat diakses oleh calon siswa pindahan.
                            @endif
                        </p>
                    </div>

                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Fitur yang Terpengaruh</h3>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Form pendaftaran siswa pindahan</li>
                            <li>• Cek status pendaftaran</li>
                            <li>• Upload dokumen siswa pindahan</li>
                            <li>• Manajemen siswa pindahan oleh admin</li>
                            <li>• Konversi nilai siswa pindahan</li>
                        </ul>
                    </div>

                    <div class="pt-4">
                        <form method="POST" action="{{ route('admin.system-settings.toggle-transfer-student') }}">
                            @csrf
                            <button type="submit"
                                class="w-full {{ $transferStudentEnabled ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                {{ $transferStudentEnabled ? 'Nonaktifkan Sistem Siswa Pindahan' : 'Aktifkan Sistem Siswa Pindahan' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- School Info (Affects Raport Header) -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Informasi Sekolah (Header Raport)</h2>
            </div>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.system-settings.index') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sekolah</label>
                        <input type="text" name="school_name" value="{{ old('school_name', $school['name']) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NPSN</label>
                        <input type="text" name="school_npsn" value="{{ old('school_npsn', $school['npsn']) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="school_address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('school_address', $school['address']) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                        <input type="text" name="school_phone" value="{{ old('school_phone', $school['phone']) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="school_email" value="{{ old('school_email', $school['email']) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                        <input type="text" name="school_website" value="{{ old('school_website', $school['website']) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" />
                    </div>
                </div>
                <div class="pt-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">Simpan Informasi Sekolah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Information Section -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="font-medium text-blue-900 mb-2">Informasi Penting</h3>
                <div class="text-sm text-blue-800 space-y-2">
                    <p><strong>Perhatian:</strong> Ketika sistem dinonaktifkan:</p>
                    <ul class="list-disc list-inside space-y-1 ml-4">
                        <li>Form pendaftaran publik tidak akan dapat diakses</li>
                        <li>Menu di sidebar admin tetap tersedia untuk manajemen data yang sudah ada</li>
                        <li>Data yang sudah ada tidak akan terhapus</li>
                        <li>Admin masih dapat mengakses data pendaftar yang sudah ada</li>
                    </ul>
                    <p class="mt-3"><strong>Tips:</strong> Gunakan fitur ini untuk mengontrol periode pendaftaran atau maintenance sistem. Informasi sekolah yang disimpan akan tampil di beberapa halaman dan header raport siswa.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
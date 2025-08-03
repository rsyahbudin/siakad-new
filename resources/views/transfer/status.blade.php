@extends('layouts.app')

@section('title', 'Status Pendaftaran - Siswa Pindahan')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Status Pendaftaran Siswa Pindahan</h1>
            <p class="text-gray-600">Detail status pendaftaran siswa pindahan Anda</p>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Status Pendaftaran</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $transferStudent->status_badge_class }}">
                        @if($transferStudent->status === 'pending')
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        @elseif($transferStudent->status === 'approved')
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        @else
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        @endif
                        {{ $transferStudent->status_label }}
                    </span>
                </div>
            </div>
            <div class="p-6">
                @if($transferStudent->status === 'pending')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-yellow-900 mb-1">Sedang Dalam Proses Verifikasi</h3>
                            <p class="text-yellow-800 text-sm">Aplikasi Anda sedang ditinjau oleh admin sekolah. Proses verifikasi membutuhkan waktu 3-7 hari kerja.</p>
                        </div>
                    </div>
                </div>
                @elseif($transferStudent->status === 'approved')
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-green-900 mb-1">Selamat! Pendaftaran Anda Diterima</h3>
                            <p class="text-green-800 text-sm">Akun siswa dan wali murid telah dibuat. Silakan login menggunakan email yang terdaftar.</p>
                            <div class="mt-3 space-y-1 text-sm">
                                <p><strong>Login Siswa:</strong> {{ $transferStudent->email }} (Password: student123)</p>
                                <p><strong>Login Wali Murid:</strong> {{ $transferStudent->parent_email }} (Password: wali123)</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-red-900 mb-1">Pendaftaran Ditolak</h3>
                            <p class="text-red-800 text-sm">Maaf, pendaftaran Anda tidak dapat diterima. Silakan hubungi admin sekolah untuk informasi lebih lanjut.</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($transferStudent->notes)
                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-medium text-blue-900 mb-2">Catatan Admin:</h4>
                    <p class="text-blue-800 text-sm">{{ $transferStudent->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Student Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Pendaftar</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Registrasi</label>
                        <p class="text-gray-900 font-mono">{{ $transferStudent->registration_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <p class="text-gray-900">{{ $transferStudent->full_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NISN</label>
                        <p class="text-gray-900">{{ $transferStudent->nisn }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Siswa</label>
                        <p class="text-gray-900">{{ $transferStudent->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Orang Tua</label>
                        <p class="text-gray-900">{{ $transferStudent->parent_email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pendaftaran</label>
                        <p class="text-gray-900">{{ $transferStudent->submitted_at->format('d F Y, H:i') }} WIB</p>
                    </div>
                </div>
            </div>

            <!-- Transfer Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Detail Pindahan</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sekolah Asal</label>
                        <p class="text-gray-900">{{ $transferStudent->previous_school_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas & Jurusan Asal</label>
                        <p class="text-gray-900">{{ $transferStudent->previous_grade }} {{ $transferStudent->previous_major }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran Terakhir</label>
                        <p class="text-gray-900">{{ $transferStudent->previous_academic_year }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan Kelas & Jurusan</label>
                        <p class="text-gray-900">{{ $transferStudent->target_grade }} {{ $transferStudent->target_major }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Pindah</label>
                        <p class="text-gray-900 text-sm">{{ $transferStudent->transfer_reason }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grade Conversion (if available) -->
        @if($transferStudent->original_grades || $transferStudent->converted_grades)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Konversi Nilai</h3>
            </div>
            <div class="p-6">
                @if($transferStudent->original_grades && $transferStudent->converted_grades)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2 font-medium text-gray-700">Mata Pelajaran</th>
                                <th class="text-center py-2 font-medium text-gray-700">Nilai Asal</th>
                                <th class="text-center py-2 font-medium text-gray-700">Nilai Konversi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transferStudent->original_grades as $subject => $originalGrade)
                            <tr class="border-b border-gray-100">
                                <td class="py-2 text-gray-900">{{ $subject }}</td>
                                <td class="py-2 text-center text-gray-900">{{ $originalGrade }}</td>
                                <td class="py-2 text-center text-gray-900">
                                    {{ $transferStudent->converted_grades[$subject] ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($transferStudent->conversion_notes)
                <div class="mt-4 bg-gray-50 rounded-lg p-3">
                    <h4 class="font-medium text-gray-900 mb-1">Catatan Konversi:</h4>
                    <p class="text-gray-700 text-sm">{{ $transferStudent->conversion_notes }}</p>
                </div>
                @endif
                @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p>Konversi nilai belum dilakukan oleh admin</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Processing Timeline -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Timeline Proses</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Submitted -->
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Pendaftaran Diterima</h4>
                            <p class="text-sm text-gray-600">{{ $transferStudent->submitted_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                    </div>

                    <!-- Processing -->
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 {{ $transferStudent->status !== 'pending' ? 'bg-green-100' : 'bg-yellow-100' }} rounded-full flex items-center justify-center">
                            @if($transferStudent->status !== 'pending')
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            @else
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Verifikasi & Konversi Nilai</h4>
                            <p class="text-sm text-gray-600">
                                @if($transferStudent->processed_at)
                                Selesai pada {{ $transferStudent->processed_at->format('d F Y, H:i') }} WIB
                                @else
                                Sedang dalam proses
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Final Decision -->
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 {{ $transferStudent->status === 'approved' ? 'bg-green-100' : ($transferStudent->status === 'rejected' ? 'bg-red-100' : 'bg-gray-100') }} rounded-full flex items-center justify-center">
                            @if($transferStudent->status === 'approved')
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            @elseif($transferStudent->status === 'rejected')
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            @else
                            <span class="text-xs font-bold text-gray-400">3</span>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Keputusan Final</h4>
                            <p class="text-sm text-gray-600">
                                @if($transferStudent->processed_at)
                                {{ $transferStudent->status_label }} - {{ $transferStudent->processed_at->format('d F Y, H:i') }} WIB
                                @else
                                Menunggu keputusan
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('transfer.status-check') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors">
                Cek Status Lain
            </a>
            <a href="{{ route('transfer.register') }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors">
                Daftar Siswa Pindahan
            </a>
        </div>

        <!-- Contact Info -->
        <div class="text-center mt-8 text-gray-600">
            <p class="text-sm">
                Butuh bantuan? Hubungi kami di
                <a href="tel:+62211234567" class="text-blue-600 hover:text-blue-700 font-medium">+62 21 1234 5678</a>
                atau email
                <a href="mailto:admin@sekolah.sch.id" class="text-blue-600 hover:text-blue-700 font-medium">admin@sekolah.sch.id</a>
            </p>
        </div>
    </div>
</div>
@endsection
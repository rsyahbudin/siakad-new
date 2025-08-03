@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil - Siswa Pindahan')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pendaftaran Berhasil!</h1>
            <p class="text-gray-600">Terima kasih telah mendaftar sebagai siswa pindahan. Data Anda telah kami terima.</p>
        </div>

        <!-- Registration Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Detail Pendaftaran</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Registrasi</label>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <p class="text-lg font-bold text-blue-900">{{ $transferStudent->registration_number }}</p>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Simpan nomor ini untuk cek status pendaftaran</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pendaftaran</label>
                        <p class="text-gray-900">{{ $transferStudent->submitted_at->format('d F Y, H:i') }} WIB</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <p class="text-gray-900">{{ $transferStudent->full_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                        <p class="text-gray-900">{{ $transferStudent->nisn }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Siswa</label>
                        <p class="text-gray-900">{{ $transferStudent->email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Orang Tua</label>
                        <p class="text-gray-900">{{ $transferStudent->parent_email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sekolah Asal</label>
                        <p class="text-gray-900">{{ $transferStudent->previous_school_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas Asal → Tujuan</label>
                        <p class="text-gray-900">{{ $transferStudent->previous_grade }} {{ $transferStudent->previous_major }} → {{ $transferStudent->target_grade }} {{ $transferStudent->target_major }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Pendaftaran</label>
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Menunggu Verifikasi Admin
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Langkah Selanjutnya</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-bold text-blue-600">1</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Menunggu Verifikasi</h3>
                            <p class="text-gray-600 text-sm">Admin sekolah akan memverifikasi dokumen dan data yang Anda kirimkan.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-bold text-blue-600">2</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Konversi Nilai</h3>
                            <p class="text-gray-600 text-sm">Admin akan melakukan konversi nilai dari sekolah asal ke sistem kurikulum sekolah.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-bold text-blue-600">3</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Keputusan Penerimaan</h3>
                            <p class="text-gray-600 text-sm">Anda akan mendapat notifikasi mengenai keputusan penerimaan.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-bold text-green-600">4</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Akun Otomatis Dibuat</h3>
                            <p class="text-gray-600 text-sm">Jika diterima, akun siswa dan wali murid akan otomatis dibuat untuk akses SIAKAD.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Important Info -->
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="font-semibold text-amber-900 mb-2">Informasi Penting</h3>
                    <ul class="text-amber-800 space-y-1 text-sm">
                        <li>• Simpan nomor registrasi <strong>{{ $transferStudent->registration_number }}</strong> untuk cek status</li>
                        <li>• Proses verifikasi membutuhkan waktu 3-7 hari kerja</li>
                        <li>• Pastikan email Anda aktif untuk menerima notifikasi</li>
                        <li>• Hubungi sekolah jika ada pertanyaan lebih lanjut</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('transfer.status-check') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors">
                Cek Status Pendaftaran
            </a>
            <a href="{{ route('transfer.register') }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors">
                Daftar Siswa Lain
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
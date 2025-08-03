<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIAKAD SMA XYZ')</title>
    @vite('resources/css/app.css')
    <style>
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Smooth animations */
        .nav-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item:hover {
            transform: translateX(4px);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Glass effect */
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-800 via-blue-700 to-indigo-800 text-white flex flex-col relative overflow-hidden shadow-2xl">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 -left-4 w-24 h-24 bg-white rounded-full mix-blend-multiply filter blur-xl"></div>
                <div class="absolute top-20 -right-4 w-32 h-32 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl"></div>
                <div class="absolute -bottom-8 left-20 w-28 h-28 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl"></div>
            </div>

            <!-- Header -->
            <div class="relative z-10 mb-8 p-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-white/20 to-white/10 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/20">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold tracking-wide gradient-text">SIAKAD</h2>
                <p class="text-blue-200 text-sm font-medium">SMA XYZ</p>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 pb-4 custom-scrollbar overflow-y-auto relative z-10">
                @php $role = Auth::user()->role; @endphp
                <ul class="space-y-1">
                    @if($role === 'admin')
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('dashboard') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>

                    <!-- Master Data Section -->
                    <li class="pt-4">
                        <div class="px-4 py-2">
                            <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider">Master Data</p>
                        </div>
                    </li>

                    <li>
                        <a href="{{ route('tahun-ajaran.index') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('tahun-ajaran.index') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium">Tahun Ajaran</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('jurusan.index') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('jurusan.index') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="font-medium">Jurusan</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('mapel.index') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('mapel.index') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span class="font-medium">Mata Pelajaran</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('guru.index') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('guru.index') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium">Guru</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('siswa.index') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('siswa.index') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <span class="font-medium">Siswa</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('kelas.index') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('kelas.index') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium">Kelas</span>
                        </a>
                    </li>

                    <!-- Academic Section -->
                    <li class="pt-4">
                        <div class="px-4 py-2">
                            <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider">Akademik</p>
                        </div>
                    </li>

                    <li>
                        <a href="{{ route('jadwal.admin.index') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('jadwal.admin.*') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Jadwal & Penugasan</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('penugasan.guru') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('penugasan.guru') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Laporan Penugasan</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('pembagian.kelas') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('pembagian.kelas') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                            <span class="font-medium">Pembagian Kelas</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.promotions.index') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('admin.promotions.*') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span class="font-medium text-sm">Kenaikan & Kelulusan</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('nilai.admin') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('nilai.admin') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-medium">Nilai Siswa</span>
                        </a>
                    </li>

                    <!-- System Section -->
                    <li class="pt-4">
                        <div class="px-4 py-2">
                            <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider">Sistem</p>
                        </div>
                    </li>

                    <li>
                        <a href="{{ route('pengaturan.kkm') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('pengaturan.kkm') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Pengaturan KKM</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('manajemen.pengguna') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('manajemen.pengguna') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Manajemen User</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.transfer.index') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('admin.transfer.*') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Siswa Pindahan</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.ppdb.index') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('admin.ppdb.*') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="font-medium text-sm">Manajemen PPDB</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.system-settings.index') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('admin.system-settings.*') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Pengaturan Sistem</span>
                        </a>
                    </li>


                    @elseif($role === 'teacher')
                    <li>
                        <a href="{{ route('dashboard') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('dashboard') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('jadwal.guru') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('jadwal.guru') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">Jadwal Mengajar</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('nilai.input') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('nilai.input') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="font-medium">Input Nilai</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('teacher.attendance.index') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('teacher.attendance.*') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span class="font-medium">Manajemen Absensi</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('siswa.index') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('siswa.index') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <span class="font-medium">Data Siswa</span>
                        </a>
                    </li>

                    @if(Auth::user()->isHomeroomTeacher())
                    <li class="pt-4">
                        <div class="px-4 py-2">
                            <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider">Menu Wali Kelas</p>
                        </div>
                    </li>

                    <li>
                        <a href="{{ route('wali.dashboard') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('wali.dashboard') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Dashboard Wali</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('wali.kelas') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('wali.kelas') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium">Kelas Perwalian</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('wali.leger') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('wali.leger') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Rekap Nilai</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('wali.absensi') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('wali.absensi') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span class="font-medium">Rekap Absensi</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('wali.finalisasi') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('wali.finalisasi') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">Finalisasi Raport</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('wali.kenaikan') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('wali.kenaikan') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span class="font-medium">Kenaikan Kelas</span>
                        </a>
                    </li>


                    @endif

                    @elseif($role === 'student')
                    <li>
                        <a href="{{ route('dashboard') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('dashboard') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('profil.siswa') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('profil.siswa') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium">Profil</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('jadwal.siswa') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('jadwal.siswa') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">Jadwal</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('nilai.siswa') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('nilai.siswa') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-medium">Nilai Akademik</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('siswa.raport') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('siswa.raport') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span class="font-medium">Raport Digital</span>
                        </a>
                    </li>

                    @elseif($role === 'kepala_sekolah')
                    <li>
                        <a href="{{ route('kepala.dashboard') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('kepala.dashboard') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('kepala.laporan.akademik') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('kepala.laporan.akademik') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-medium">Laporan Akademik</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('kepala.laporan.keuangan') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('kepala.laporan.keuangan') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium">Laporan Keuangan</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('kepala.pengaturan.sekolah') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('kepala.pengaturan.sekolah') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="font-medium">Pengaturan Sekolah</span>
                        </a>
                    </li>

                    @elseif($role === 'wali_murid')
                    <li>
                        <a href="{{ route('wali.dashboard') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('wali.dashboard') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('wali.nilai.anak') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('wali.nilai.anak') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-medium">Nilai Anak</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('wali.raport.anak') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('wali.raport.anak') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium">Raport Anak</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('wali.jadwal.anak') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('wali.jadwal.anak') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">Jadwal Anak</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('wali.absensi.anak') }}" class="nav-item flex items-center px-4 py-3 rounded-xl hover:bg-white/10 {{ request()->routeIs('wali.absensi.anak') ? 'bg-white/20 shadow-lg' : '' }} group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span class="font-medium">Absensi Anak</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>

            <!-- User Profile & Logout -->
            <div class="relative z-10 p-4">
                <!-- User Info -->
                <div class="glass-effect rounded-xl p-4 mb-4 border border-white/20">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-white/30 to-white/10 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-medium text-sm truncate">{{ Auth::user()->name }}</p>
                            <p class="text-blue-200 text-xs capitalize">{{ Auth::user()->role }}</p>
                        </div>
                    </div>
                </div>

                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="w-full bg-gradient-to-r from-white/20 to-white/10 backdrop-blur-sm text-white font-semibold py-3 rounded-xl hover:from-white/30 hover:to-white/20 transition-all duration-300 border border-white/20 flex items-center justify-center space-x-2 group">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-h-screen">
            <!-- Top Bar -->
            <header class="bg-white/80 backdrop-blur-sm border-b border-gray-200/50 px-8 py-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">@yield('title', 'Dashboard')</h1>
                        <p class="text-gray-600 text-sm">Selamat datang di Sistem Informasi Akademik</p>
                    </div>
                    <div class="flex items-center space-x-4">


                        <!-- User Menu -->
                        <div class="flex items-center space-x-3 bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-2 rounded-xl">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <div class="hidden sm:block">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="flex-1 p-8 bg-gradient-to-br from-gray-50/50 via-white to-blue-50/30">
                @yield('content')
            </div>
        </main>
    </div>

</body>

</html>
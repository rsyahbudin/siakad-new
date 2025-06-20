<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIAKAD SMA XYZ')</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-700 text-white flex flex-col py-6 px-4">
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-bold tracking-wide">SIAKAD</h2>
            </div>
            <nav class="flex-1">
                @php $role = Auth::user()->role; @endphp
                <ul class="space-y-2">
                    @if($role === 'admin')
                    <li><a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('dashboard') ? 'bg-blue-900' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('tahun-ajaran.index') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('tahun-ajaran.index') ? 'bg-blue-900' : '' }}">Tahun Ajaran</a></li>
                    <li><a href="{{ route('jurusan.index') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('jurusan.index') ? 'bg-blue-900' : '' }}">Jurusan</a></li>
                    <li><a href="{{ route('mapel.index') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('mapel.index') ? 'bg-blue-900' : '' }}">Mata Pelajaran</a></li>
                    <li><a href="{{ route('guru.index') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('guru.index') ? 'bg-blue-900' : '' }}">Guru</a></li>
                    <li><a href="{{ route('siswa.index') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('siswa.index') ? 'bg-blue-900' : '' }}">Siswa</a></li>
                    <li><a href="{{ route('kelas.index') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('kelas.index') ? 'bg-blue-900' : '' }}">Kelas</a></li>
                    <li><a href="{{ route('jadwal.admin.index') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('jadwal.admin.*') ? 'bg-blue-900' : '' }}">Jadwal & Penugasan Mengajar</a></li>
                    <li><a href="{{ route('penugasan.guru') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('penugasan.guru') ? 'bg-blue-900' : '' }}">Laporan Penugasan Guru</a></li>
                    <li><a href="{{ route('pembagian.kelas') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('pembagian.kelas') ? 'bg-blue-900' : '' }}">Pembagian Kelas</a></li>
                    <li><a href="{{ route('kenaikan-kelas.index') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('kenaikan-kelas.*') ? 'bg-blue-900' : '' }}">Kenaikan Kelas & Kelulusan</a></li>
                    <li><a href="{{ route('import.siswa') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('import.siswa') ? 'bg-blue-900' : '' }}">Import Data Siswa</a></li>
                    <li><a href="{{ route('pengaturan.kkm') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('pengaturan.kkm') ? 'bg-blue-900' : '' }}">Pengaturan Bobot & KKM</a></li>
                    <li><a href="{{ route('manajemen.pengguna') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('manajemen.pengguna') ? 'bg-blue-900' : '' }}">Manajemen Pengguna</a></li>
                    <li><a href="{{ route('nilai.admin') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('nilai.admin') ? 'bg-blue-900' : '' }}">Nilai Siswa</a></li>
                    @elseif($role === 'teacher')
                    <li><a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('dashboard') ? 'bg-blue-900' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('jadwal.guru') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('jadwal.guru') ? 'bg-blue-900' : '' }}">Jadwal Mengajar</a></li>
                    <li><a href="{{ route('nilai.input') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('nilai.input') ? 'bg-blue-900' : '' }}">Input Nilai</a></li>
                    <li><a href="{{ route('absensi.input') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('absensi.input') ? 'bg-blue-900' : '' }}">Input Absensi</a></li>
                    @if(Auth::user()->isHomeroomTeacher())
                    <li class="mt-4 font-bold text-xs uppercase tracking-wider text-blue-200">Menu Wali Kelas</li>
                    <li><a href="{{ route('wali.dashboard') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('wali.dashboard') ? 'bg-blue-900' : '' }}">Dashboard Wali Kelas</a></li>
                    <li><a href="{{ route('wali.kelas') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('wali.kelas') ? 'bg-blue-900' : '' }}">Kelas Perwalian</a></li>
                    <li><a href="{{ route('wali.leger') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('wali.leger') ? 'bg-blue-900' : '' }}">Rekap Nilai (Leger)</a></li>
                    <li><a href="{{ route('wali.absensi') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('wali.absensi') ? 'bg-blue-900' : '' }}">Rekap Absensi</a></li>
                    <li><a href="{{ route('wali.catatan') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('wali.catatan') ? 'bg-blue-900' : '' }}">Catatan Raport</a></li>
                    <li><a href="{{ route('wali.finalisasi') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('wali.finalisasi') ? 'bg-blue-900' : '' }}">Finalisasi Raport</a></li>
                    <li><a href="{{ route('wali.kenaikan') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('wali.kenaikan') ? 'bg-blue-900' : '' }}">Kenaikan Kelas</a></li>
                    <li><a href="{{ route('wali.pindahan') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('wali.pindahan') ? 'bg-blue-900' : '' }}">Siswa Pindahan</a></li>
                    @endif
                    @elseif($role === 'student')
                    <li><a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('dashboard') ? 'bg-blue-900' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('profil.siswa') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('profil.siswa') ? 'bg-blue-900' : '' }}">Profil</a></li>
                    <li><a href="{{ route('jadwal.siswa') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('jadwal.siswa') ? 'bg-blue-900' : '' }}">Jadwal</a></li>
                    <li><a href="{{ route('nilai.siswa') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('nilai.siswa') ? 'bg-blue-900' : '' }}">Nilai Akademik</a></li>
                    <li><a href="{{ route('raport.siswa') }}" class="block px-4 py-2 rounded hover:bg-blue-800 {{ request()->routeIs('raport.siswa') ? 'bg-blue-900' : '' }}">Raport Digital</a></li>
                    @endif
                </ul>
            </nav>
            <form action="{{ route('logout') }}" method="POST" class="mt-8">
                @csrf
                <button class="w-full bg-white text-blue-700 font-semibold py-2 rounded hover:bg-gray-200 transition">Keluar</button>
            </form>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>
</body>

</html>
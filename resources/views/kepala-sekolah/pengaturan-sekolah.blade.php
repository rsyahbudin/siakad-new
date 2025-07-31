@extends('layouts.dashboard')

@section('title', 'Pengaturan Sekolah')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('kepala.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pengaturan Sekolah</li>
                    </ol>
                </div>
                <h4 class="page-title">Pengaturan Sekolah</h4>
            </div>
        </div>
    </div>

    <!-- School Information -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Informasi Sekolah</h4>
                    <form method="POST" action="#">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Sekolah</label>
                                    <input type="text" class="form-control" value="SMK Negeri 1 Jakarta" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">NPSN</label>
                                    <input type="text" class="form-control" value="20100101" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea class="form-control" rows="3" readonly>Jl. Pendidikan No. 123, Jakarta Pusat, DKI Jakarta 10110</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Telepon</label>
                                    <input type="text" class="form-control" value="021-1234567" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="info@smkn1jakarta.sch.id" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Website</label>
                                    <input type="url" class="form-control" value="https://smkn1jakarta.sch.id" readonly>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Settings -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Pengaturan Akademik</h4>
                    <form method="POST" action="#">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tahun Ajaran Aktif</label>
                            <select class="form-select" name="active_academic_year">
                                <option value="1" selected>2023/2024</option>
                                <option value="2">2024/2025</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Semester Aktif</label>
                            <select class="form-select" name="active_semester">
                                <option value="1">Ganjil</option>
                                <option value="2" selected>Genap</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">KKM (Kriteria Ketuntasan Minimal)</label>
                            <input type="number" class="form-control" value="75" min="0" max="100">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bobot Nilai</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Tugas</label>
                                    <input type="number" class="form-control" value="30" min="0" max="100">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">UTS</label>
                                    <input type="number" class="form-control" value="30" min="0" max="100">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">UAS</label>
                                    <input type="number" class="form-control" value="40" min="0" max="100">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Pengaturan Sistem</h4>
                    <form method="POST" action="#">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Zona Waktu</label>
                            <select class="form-select" name="timezone">
                                <option value="Asia/Jakarta" selected>Asia/Jakarta (WIB)</option>
                                <option value="Asia/Makassar">Asia/Makassar (WITA)</option>
                                <option value="Asia/Jayapura">Asia/Jayapura (WIT)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Format Tanggal</label>
                            <select class="form-select" name="date_format">
                                <option value="d/m/Y" selected>DD/MM/YYYY</option>
                                <option value="Y-m-d">YYYY-MM-DD</option>
                                <option value="d-m-Y">DD-MM-YYYY</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bahasa</label>
                            <select class="form-select" name="language">
                                <option value="id" selected>Bahasa Indonesia</option>
                                <option value="en">English</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="maintenance_mode" checked>
                                <label class="form-check-label" for="maintenance_mode">
                                    Mode Maintenance
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="email_notifications">
                                <label class="form-check-label" for="email_notifications">
                                    Notifikasi Email
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- User Management -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Manajemen Pengguna</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Terakhir Login</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-light text-center me-2">
                                                <span class="avatar-title text-primary font-weight-bold">A</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Admin</h6>
                                                <small class="text-muted">admin@siakad.test</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>admin@siakad.test</td>
                                    <td><span class="badge bg-danger">Admin</span></td>
                                    <td><span class="badge bg-success">Aktif</span></td>
                                    <td>2 jam yang lalu</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                                        <button class="btn btn-sm btn-outline-danger">Nonaktifkan</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-light text-center me-2">
                                                <span class="avatar-title text-success font-weight-bold">K</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Dr. Ahmad Supriyadi</h6>
                                                <small class="text-muted">kepala@siakad.test</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>kepala@siakad.test</td>
                                    <td><span class="badge bg-warning">Kepala Sekolah</span></td>
                                    <td><span class="badge bg-success">Aktif</span></td>
                                    <td>1 hari yang lalu</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                                        <button class="btn btn-sm btn-outline-danger">Nonaktifkan</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-light text-center me-2">
                                                <span class="avatar-title text-info font-weight-bold">G</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Budi Santoso</h6>
                                                <small class="text-muted">guru@siakad.test</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>guru@siakad.test</td>
                                    <td><span class="badge bg-info">Guru</span></td>
                                    <td><span class="badge bg-success">Aktif</span></td>
                                    <td>3 jam yang lalu</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                                        <button class="btn btn-sm btn-outline-danger">Nonaktifkan</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Logs -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Log Sistem</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>User</th>
                                    <th>Aktivitas</th>
                                    <th>IP Address</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2024-01-15 14:30:25</td>
                                    <td>admin@siakad.test</td>
                                    <td>Login berhasil</td>
                                    <td>192.168.1.100</td>
                                    <td><span class="badge bg-success">Sukses</span></td>
                                </tr>
                                <tr>
                                    <td>2024-01-15 14:25:10</td>
                                    <td>guru@siakad.test</td>
                                    <td>Input nilai siswa</td>
                                    <td>192.168.1.101</td>
                                    <td><span class="badge bg-success">Sukses</span></td>
                                </tr>
                                <tr>
                                    <td>2024-01-15 14:20:45</td>
                                    <td>siswa@siakad.test</td>
                                    <td>Login gagal</td>
                                    <td>192.168.1.102</td>
                                    <td><span class="badge bg-danger">Gagal</span></td>
                                </tr>
                                <tr>
                                    <td>2024-01-15 14:15:30</td>
                                    <td>admin@siakad.test</td>
                                    <td>Update pengaturan sistem</td>
                                    <td>192.168.1.100</td>
                                    <td><span class="badge bg-success">Sukses</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
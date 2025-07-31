@extends('layouts.dashboard')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('kepala.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Laporan Keuangan</li>
                    </ol>
                </div>
                <h4 class="page-title">Laporan Keuangan</h4>
            </div>
        </div>
    </div>

    <!-- Financial Overview -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0">Total Pemasukan</h5>
                            <h3 class="mt-3 mb-3">Rp 125.000.000</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> 12.5%
                                </span>
                                <span class="text-nowrap">Dari bulan lalu</span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded">
                                <i class="mdi mdi-arrow-up font-20 text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0">Total Pengeluaran</h5>
                            <h3 class="mt-3 mb-3">Rp 85.000.000</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-danger me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> 8.2%
                                </span>
                                <span class="text-nowrap">Dari bulan lalu</span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-danger rounded">
                                <i class="mdi mdi-arrow-down font-20 text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0">Saldo</h5>
                            <h3 class="mt-3 mb-3">Rp 40.000.000</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> 15.3%
                                </span>
                                <span class="text-nowrap">Dari bulan lalu</span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded">
                                <i class="mdi mdi-wallet font-20 text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0">Tunggakan</h5>
                            <h3 class="mt-3 mb-3">Rp 5.250.000</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-warning me-2">
                                    <i class="mdi mdi-arrow-down-bold"></i> 2.1%
                                </span>
                                <span class="text-nowrap">Dari bulan lalu</span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded">
                                <i class="mdi mdi-alert font-20 text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Breakdown -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Pemasukan per Bulan</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>SPP</th>
                                    <th>Uang Makan</th>
                                    <th>Seragam</th>
                                    <th>Lainnya</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Januari 2024</td>
                                    <td>Rp 45.000.000</td>
                                    <td>Rp 12.500.000</td>
                                    <td>Rp 8.000.000</td>
                                    <td>Rp 2.500.000</td>
                                    <td><span class="badge bg-success">Rp 68.000.000</span></td>
                                </tr>
                                <tr>
                                    <td>Februari 2024</td>
                                    <td>Rp 42.000.000</td>
                                    <td>Rp 11.000.000</td>
                                    <td>Rp 0</td>
                                    <td>Rp 1.500.000</td>
                                    <td><span class="badge bg-success">Rp 54.500.000</span></td>
                                </tr>
                                <tr>
                                    <td>Maret 2024</td>
                                    <td>Rp 38.000.000</td>
                                    <td>Rp 10.500.000</td>
                                    <td>Rp 0</td>
                                    <td>Rp 1.200.000</td>
                                    <td><span class="badge bg-success">Rp 49.700.000</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Pengeluaran per Kategori</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                    <th>Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Gaji Guru & Staff</td>
                                    <td>Rp 45.000.000</td>
                                    <td><span class="badge bg-primary">53%</span></td>
                                </tr>
                                <tr>
                                    <td>Operasional</td>
                                    <td>Rp 20.000.000</td>
                                    <td><span class="badge bg-info">24%</span></td>
                                </tr>
                                <tr>
                                    <td>Fasilitas</td>
                                    <td>Rp 12.000.000</td>
                                    <td><span class="badge bg-warning">14%</span></td>
                                </tr>
                                <tr>
                                    <td>Lainnya</td>
                                    <td>Rp 8.000.000</td>
                                    <td><span class="badge bg-secondary">9%</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Status -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Status Pembayaran SPP</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Kelas</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Lunas</th>
                                    <th>Belum Lunas</th>
                                    <th>Tunggakan</th>
                                    <th>Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-light text-center me-2">
                                                <span class="avatar-title text-warning font-weight-bold">X</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Kelas X</h6>
                                                <small class="text-muted">Semua Jurusan</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary">120</span></td>
                                    <td><span class="badge bg-success">95</span></td>
                                    <td><span class="badge bg-warning">20</span></td>
                                    <td><span class="badge bg-danger">5</span></td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 79%"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-light text-center me-2">
                                                <span class="avatar-title text-info font-weight-bold">XI</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Kelas XI</h6>
                                                <small class="text-muted">Semua Jurusan</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary">115</span></td>
                                    <td><span class="badge bg-success">88</span></td>
                                    <td><span class="badge bg-warning">22</span></td>
                                    <td><span class="badge bg-danger">5</span></td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 77%"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-light text-center me-2">
                                                <span class="avatar-title text-danger font-weight-bold">XII</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Kelas XII</h6>
                                                <small class="text-muted">Semua Jurusan</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary">110</span></td>
                                    <td><span class="badge bg-success">85</span></td>
                                    <td><span class="badge bg-warning">20</span></td>
                                    <td><span class="badge bg-danger">5</span></td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 77%"></div>
                                        </div>
                                    </td>
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
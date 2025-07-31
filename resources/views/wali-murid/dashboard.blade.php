@extends('layouts.dashboard')

@section('title', 'Dashboard Wali Murid')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Wali Murid</li>
                    </ol>
                </div>
                <h4 class="page-title">Dashboard Wali Murid</h4>
            </div>
        </div>
    </div>

    <!-- Student Information Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="header-title mb-3">Informasi Anak</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="150"><strong>Nama Lengkap:</strong></td>
                                            <td>{{ $student->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>NIS:</strong></td>
                                            <td>{{ $student->nis }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>NISN:</strong></td>
                                            <td>{{ $student->nisn }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jenis Kelamin:</strong></td>
                                            <td>{{ $student->gender }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="150"><strong>Tempat Lahir:</strong></td>
                                            <td>{{ $student->birth_place }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Lahir:</strong></td>
                                            <td>{{ $student->birth_date ? $student->birth_date->format('d/m/Y') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Agama:</strong></td>
                                            <td>{{ $student->religion }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $student->status === 'Aktif' ? 'success' : 'warning' }}">
                                                    {{ $student->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title rounded-circle bg-light text-primary" style="width: 80px; height: 80px; font-size: 2rem;">
                                        {{ substr($student->full_name, 0, 2) }}
                                    </div>
                                </div>
                                <h5 class="mb-1">{{ $student->full_name }}</h5>
                                <p class="text-muted mb-0">{{ $student->nis }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0">Rata-rata Nilai</h5>
                            <h3 class="mt-3 mb-3">85.5</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> 2.5%
                                </span>
                                <span class="text-nowrap">Dari bulan lalu</span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded">
                                <i class="mdi mdi-chart-line font-20 text-primary"></i>
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
                            <h5 class="text-muted fw-normal mt-0">Kehadiran</h5>
                            <h3 class="mt-3 mb-3">92.3%</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> 1.2%
                                </span>
                                <span class="text-nowrap">Dari bulan lalu</span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded">
                                <i class="mdi mdi-account-check font-20 text-success"></i>
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
                            <h5 class="text-muted fw-normal mt-0">Sakit</h5>
                            <h3 class="mt-3 mb-3">3</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-warning me-2">
                                    <i class="mdi mdi-arrow-down-bold"></i> 1
                                </span>
                                <span class="text-nowrap">Dari bulan lalu</span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded">
                                <i class="mdi mdi-hospital font-20 text-warning"></i>
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
                            <h5 class="text-muted fw-normal mt-0">Alpha</h5>
                            <h3 class="mt-3 mb-3">1</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-danger me-2">
                                    <i class="mdi mdi-arrow-down-bold"></i> 0
                                </span>
                                <span class="text-nowrap">Dari bulan lalu</span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-danger rounded">
                                <i class="mdi mdi-close-circle font-20 text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Nilai Terbaru</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Nilai Tugas</th>
                                    <th>Nilai UTS</th>
                                    <th>Nilai UAS</th>
                                    <th>Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($grades as $grade)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-light text-center me-2">
                                                <span class="avatar-title text-info font-weight-bold">
                                                    {{ substr($grade->subject->name ?? 'N/A', 0, 2) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $grade->subject->name ?? 'N/A' }}</h6>
                                                <small class="text-muted">{{ $grade->classroom->name ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $grade->classroom->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $grade->assignment_grade ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $grade->midterm_grade ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $grade->final_grade ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $grade->final_grade ?? 'N/A' }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data nilai terbaru</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Rekap Absensi Semester</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Semester</th>
                                    <th>Sakit</th>
                                    <th>Izin</th>
                                    <th>Alpha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendance as $att)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $att->semester->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $att->sakit }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $att->izin }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">{{ $att->alpha }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data absensi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
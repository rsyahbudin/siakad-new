@extends('layouts.dashboard')

@section('title', 'Absensi Anak')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('wali.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Absensi Anak</li>
                    </ol>
                </div>
                <h4 class="page-title">Absensi Anak</h4>
            </div>
        </div>
    </div>

    <!-- Attendance Summary -->
    <div class="row">
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
                            <h5 class="text-muted fw-normal mt-0">Izin</h5>
                            <h3 class="mt-3 mb-3">2</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-info me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> 0
                                </span>
                                <span class="text-nowrap">Dari bulan lalu</span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded">
                                <i class="mdi mdi-account-clock font-20 text-info"></i>
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

    <!-- Attendance Details -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Rekap Absensi per Semester</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Semester</th>
                                    <th>Kelas</th>
                                    <th>Hadir</th>
                                    <th>Sakit</th>
                                    <th>Izin</th>
                                    <th>Alpha</th>
                                    <th>Total Hari</th>
                                    <th>Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendance as $att)
                                @php
                                $total = ($att->hadir ?? 0) + $att->sakit + $att->izin + $att->alpha;
                                $percentage = $total > 0 ? round((($att->hadir ?? 0) / $total) * 100, 1) : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $att->semester->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $att->classroom->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $att->hadir ?? 0 }}</span>
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
                                    <td>
                                        <span class="badge bg-primary">{{ $total }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="text-muted">{{ $percentage }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data absensi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($attendance->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $attendance->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Attendance Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Grafik Kehadiran Bulanan</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-muted">Januari</h5>
                                <div class="progress mb-2" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 95%">95%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-muted">Februari</h5>
                                <div class="progress mb-2" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 92%">92%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-muted">Maret</h5>
                                <div class="progress mb-2" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 88%">88%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-muted">April</h5>
                                <div class="progress mb-2" style="height: 20px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 85%">85%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
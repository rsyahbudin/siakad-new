@extends('layouts.dashboard')

@section('title', 'Dashboard Kepala Sekolah')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kepala Sekolah</li>
                    </ol>
                </div>
                <h4 class="page-title">Dashboard Kepala Sekolah</h4>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Total Students">Total Siswa</h5>
                            <h3 class="mt-3 mb-3">{{ number_format($totalStudents) }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> {{ $totalActiveStudents }} Aktif
                                </span>
                                <span class="text-warning">
                                    {{ $totalPindahanStudents }} Pindahan
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded">
                                <i class="mdi mdi-account-group font-20 text-primary"></i>
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
                            <h5 class="text-muted fw-normal mt-0" title="Total Teachers">Total Guru</h5>
                            <h3 class="mt-3 mb-3">{{ number_format($totalTeachers) }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> Aktif
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded">
                                <i class="mdi mdi-teach font-20 text-success"></i>
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
                            <h5 class="text-muted fw-normal mt-0" title="Total Classrooms">Total Kelas</h5>
                            <h3 class="mt-3 mb-3">{{ number_format($totalClassrooms) }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-info me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> Aktif
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded">
                                <i class="mdi mdi-school font-20 text-warning"></i>
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
                            <h5 class="text-muted fw-normal mt-0" title="Total Reports">Raport Final</h5>
                            <h3 class="mt-3 mb-3">{{ number_format($totalRaports) }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> Selesai
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded">
                                <i class="mdi mdi-file-document font-20 text-info"></i>
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
                                    <th>Siswa</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Nilai</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentGrades as $grade)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-light text-center me-2">
                                                <span class="avatar-title text-primary font-weight-bold">
                                                    {{ substr($grade->student->full_name ?? 'N/A', 0, 2) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $grade->student->full_name ?? 'N/A' }}</h6>
                                                <small class="text-muted">{{ $grade->student->nis ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $grade->subject->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $grade->classroom->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $grade->final_grade ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ $grade->created_at ? $grade->created_at->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data nilai terbaru</td>
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
                    <h4 class="header-title mb-3">Raport Terbaru</h4>
                    <div class="timeline-alt pb-0">
                        @forelse($recentRaports as $raport)
                        <div class="timeline-item">
                            <i class="mdi mdi-circle bg-info-lighten text-info timeline-icon"></i>
                            <div class="timeline-item-info">
                                <a href="#" class="text-info fw-bold mb-1 d-block">{{ $raport->student->full_name ?? 'N/A' }}</a>
                                <small class="text-muted">{{ $raport->classroom->name ?? 'N/A' }} - {{ $raport->semester->name ?? 'N/A' }}</small>
                                <p class="mb-0 pb-2">
                                    <small class="text-muted">{{ $raport->created_at ? $raport->created_at->format('d/m/Y H:i') : 'N/A' }}</small>
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-3">
                            <p class="text-muted">Tidak ada raport terbaru</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Classroom Statistics -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Statistik per Kelas</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Kelas</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($classroomStats as $classroom)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-light text-center me-2">
                                                <span class="avatar-title text-warning font-weight-bold">
                                                    {{ substr($classroom->name, 0, 2) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $classroom->name }}</h6>
                                                <small class="text-muted">{{ $classroom->major->name ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $classroom->students_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 85%"></div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data kelas</td>
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
@extends('layouts.dashboard')

@section('title', 'Laporan Akademik')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('kepala.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Laporan Akademik</li>
                    </ol>
                </div>
                <h4 class="page-title">Laporan Akademik</h4>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Filter Laporan</h5>
                    <form method="GET" action="{{ route('kepala.laporan.akademik') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Tahun Ajaran</label>
                                <select name="academic_year" class="form-select">
                                    <option value="">Semua Tahun Ajaran</option>
                                    @foreach(\App\Models\AcademicYear::orderBy('name', 'desc')->get() as $year)
                                    <option value="{{ $year->id }}" {{ request('academic_year') == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kelas</label>
                                <select name="classroom" class="form-select">
                                    <option value="">Semua Kelas</option>
                                    @foreach(\App\Models\Classroom::orderBy('name')->get() as $class)
                                    <option value="{{ $class->id }}" {{ request('classroom') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Semester</label>
                                <select name="semester" class="form-select">
                                    <option value="">Semua Semester</option>
                                    @foreach(\App\Models\Semester::orderBy('name')->get() as $semester)
                                    <option value="{{ $semester->id }}" {{ request('semester') == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Statistics -->
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
                            <h5 class="text-muted fw-normal mt-0">Lulus</h5>
                            <h3 class="mt-3 mb-3">98.5%</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> 0.5%
                                </span>
                                <span class="text-nowrap">Dari semester lalu</span>
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
                            <h5 class="text-muted fw-normal mt-0">Raport Final</h5>
                            <h3 class="mt-3 mb-3">156</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> 12
                                </span>
                                <span class="text-nowrap">Dari kemarin</span>
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

    <!-- Class Performance -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Performa Kelas</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Kelas</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Rata-rata Nilai</th>
                                    <th>Kehadiran</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($classrooms as $classroom)
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
                                        <span class="badge bg-primary">{{ $classroom->students->count() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">85.5</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">92.3%</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data kelas</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject Performance -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Performa Mata Pelajaran</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                    <th>Rata-rata Nilai</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Subject::with('teacher')->get() as $subject)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-light text-center me-2">
                                                <span class="avatar-title text-info font-weight-bold">
                                                    {{ substr($subject->name, 0, 2) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $subject->name }}</h6>
                                                <small class="text-muted">{{ $subject->code ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $subject->teacher->full_name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-success">87.2</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">45</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
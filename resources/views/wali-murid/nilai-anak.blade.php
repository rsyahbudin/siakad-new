@extends('layouts.dashboard')

@section('title', 'Nilai Anak')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('wali.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Nilai Anak</li>
                    </ol>
                </div>
                <h4 class="page-title">Nilai Anak</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Daftar Nilai</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Semester</th>
                                    <th>Nilai Tugas</th>
                                    <th>Nilai UTS</th>
                                    <th>Nilai UAS</th>
                                    <th>Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($grades as $grade)
                                <tr>
                                    <td>{{ $grade->subject->name ?? 'N/A' }}</td>
                                    <td>{{ $grade->classroom->name ?? 'N/A' }}</td>
                                    <td>{{ $grade->semester->name ?? 'N/A' }}</td>
                                    <td>{{ $grade->assignment_grade ?? 'N/A' }}</td>
                                    <td>{{ $grade->midterm_grade ?? 'N/A' }}</td>
                                    <td>{{ $grade->final_grade ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ $grade->final_grade ?? 'N/A' }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data nilai</td>
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
@extends('layouts.dashboard')

@section('title', 'Raport Anak')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('wali.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Raport Anak</li>
                    </ol>
                </div>
                <h4 class="page-title">Raport Anak</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Daftar Raport</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Semester</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($raports as $raport)
                                <tr>
                                    <td>{{ $raport->semester->name ?? 'N/A' }}</td>
                                    <td>{{ $raport->classroom->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($raport->is_finalized)
                                        <span class="badge bg-success">Final</span>
                                        @else
                                        <span class="badge bg-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $raport->created_at ? $raport->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('wali.raport.show', $raport->id) }}" class="btn btn-sm btn-primary">
                                            <i class="mdi mdi-eye"></i> Lihat
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data raport</td>
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
@extends('layouts.dashboard')

@section('title', 'Jadwal Anak')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('wali.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Jadwal Anak</li>
                    </ol>
                </div>
                <h4 class="page-title">Jadwal Anak</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Jadwal Pelajaran</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                    <th>Ruang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $schedule)
                                <tr>
                                    <td>
                                        @switch($schedule->day_of_week)
                                        @case(1)
                                        Senin
                                        @break
                                        @case(2)
                                        Selasa
                                        @break
                                        @case(3)
                                        Rabu
                                        @break
                                        @case(4)
                                        Kamis
                                        @break
                                        @case(5)
                                        Jumat
                                        @break
                                        @case(6)
                                        Sabtu
                                        @break
                                        @default
                                        N/A
                                        @endswitch
                                    </td>
                                    <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                                    <td>{{ $schedule->subject->name ?? 'N/A' }}</td>
                                    <td>{{ $schedule->teacher->full_name ?? 'N/A' }}</td>
                                    <td>{{ $schedule->classroom->name ?? 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data jadwal</td>
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
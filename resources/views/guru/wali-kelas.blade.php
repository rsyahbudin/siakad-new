@extends('layouts.dashboard')
@section('title', 'Data Siswa Perwalian')
@section('content')
<div class="px-4 py-6">
    <h2 class="text-2xl font-bold mb-4">Data Siswa Kelas Perwalian: {{ $kelas->name ?? '-' }}</h2>
    <p class="mb-6 text-gray-600">Tahun Ajaran Aktif: <span class="font-semibold">{{ $activeSemester->academicYear->year ?? '-' }} Semester {{ $activeSemester->name ?? '-' }}</span></p>

    @if($students->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm align-middle">
                <thead class="bg-gray-100">
                    <tr class="font-semibold">
                        <th class="py-3 px-4 text-left">#</th>
                        <th class="py-3 px-4 text-left">NIS</th>
                        <th class="py-3 px-4 text-left">NISN</th>
                        <th class="py-3 px-4 text-left">Nama Siswa</th>
                        <th class="py-3 px-4 text-left">L/P</th>
                        <th class="py-3 px-4 text-left">Tempat, Tgl Lahir</th>
                        <th class="py-3 px-4 text-left">No Telp Siswa</th>
                        <th class="py-3 px-4 text-left">Agama</th>
                        <th class="py-3 px-4 text-left">Nama Orang Tua</th>
                        <th class="py-3 px-4 text-left">No Telp Orang Tua</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($students as $i => $student)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $loop->iteration }}</td>
                        <td class="py-3 px-4">{{ $student->student->nis }}</td>
                        <td class="py-3 px-4">{{ $student->student->nisn }}</td>
                        <td class="py-3 px-4 font-medium">{{ $student->student->full_name }}</td>
                        <td class="py-3 px-4">{{ $student->student->gender }}</td>
                        <td class="py-3 px-4">{{ $student->student->birth_place }}, {{ \Carbon\Carbon::parse($student->student->birth_date)->isoFormat('D MMMM Y') }}</td>
                        <td class="py-3 px-4">{{ $student->student->phone_number }}</td>
                        <td class="py-3 px-4">{{ $student->student->religion }}</td>
                        <td class="py-3 px-4">{{ $student->student->waliMurids->first()?->full_name ?? 'Tidak diisi' }}</td>
                        <td class="py-3 px-4">{{ $student->student->waliMurids->first()?->phone_number ?? 'Tidak diisi' }}</td>
                        <td class="py-3 px-4 text-center">
                            <a href="{{ route('wali.raport.show', $student->student->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">Lihat Raport</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">
        {{ $students->links() }}
    </div>
    @else
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
        <p class="font-bold">Tidak ada siswa</p>
        <p class="text-sm">Tidak ada data siswa yang ditemukan di kelas perwalian Anda untuk tahun ajaran ini.</p>
    </div>
    @endif
</div>
@endsection
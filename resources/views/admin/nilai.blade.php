@extends('layouts.dashboard')
@section('title', 'Nilai Siswa')
@section('content')
<h2 class="text-2xl font-bold mb-4">Nilai Siswa</h2>
<p class="mb-4">Semester Aktif: <span class="font-semibold">{{ $activeSemester->academicYear->year ?? '-' }} (Semester {{ $activeSemester->name ?? '-' }})</span></p>
<div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
    <form method="GET" class="flex gap-2 items-center w-full sm:w-auto">
        <label class="font-semibold text-blue-700 flex items-center gap-1">
            Pilih Kelas:
        </label>
        <select name="assignment_id" onchange="this.form.submit()" class="border-2 border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full sm:w-auto">
            @foreach($assignments as $assignment)
            <option value="{{ $assignment->id }}" {{ $selectedAssignment == $assignment->id ? 'selected' : '' }}>
                {{ $assignment->classroom->name }} ({{ $assignment->academicYear->year ?? '' }})
            </option>
            @endforeach
        </select>
    </form>
</div>

@if($allStudents->count() > 0)
<!-- Ringkasan Statistik -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
        <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-blue-600">Total Siswa</p>
                <p class="text-2xl font-bold text-blue-900">{{ $classStatistics['total_students'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
        <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-green-600">Lulus Semua</p>
                <p class="text-2xl font-bold text-green-900">{{ $classStatistics['lulus_semua'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
        <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-yellow-600">Perlu Perhatian</p>
                <p class="text-2xl font-bold text-yellow-900">{{ $classStatistics['perlu_perhatian'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
        <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-purple-600">Total Mapel</p>
                <p class="text-2xl font-bold text-purple-900">{{ $classStatistics['total_mapel'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="space-y-4">
    @foreach($allStudents as $studentGrades)
    @php
    $student = $studentGrades->first()?->student;
    $studentId = $student?->id;
    $subjects = $studentGrades->pluck('subject')->unique('id')->sortBy('name');
    $studentStats = $studentStatistics[$studentId] ?? [];
    @endphp

    <div class="bg-white rounded-lg shadow border">
        <!-- Header Siswa -->
        <div class="bg-blue-50 p-4 rounded-t-lg border-b">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div>
                        <h3 class="font-bold text-lg">{{ $student->user->name ?? '-' }}</h3>
                        <p class="text-sm text-gray-600">NIS: {{ $student->nis ?? '-' }}</p>
                    </div>
                    <div class="flex gap-6 text-sm">
                        <div class="text-center">
                            <span class="block font-semibold text-blue-700">Rata-rata Ganjil</span>
                            <span class="text-lg font-bold">{{ $totalGanjil[$studentId] !== null ? number_format($totalGanjil[$studentId],2) : '-' }}</span>
                        </div>
                        <div class="text-center">
                            <span class="block font-semibold text-blue-700">Rata-rata Genap</span>
                            <span class="text-lg font-bold">{{ $totalGenap[$studentId] !== null ? number_format($totalGenap[$studentId],2) : '-' }}</span>
                        </div>
                        <div class="text-center">
                            <span class="block font-semibold text-blue-700">Rata-rata Akhir Tahun</span>
                            <span class="text-lg font-bold text-yellow-600">{{ $totalYearly[$studentId] !== null ? number_format($totalYearly[$studentId],2) : '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="toggleDetails('{{ $studentId }}')" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 text-xs">
                        <span id="btn-{{ $studentId }}">Tampilkan Detail</span>
                    </button>
                    @if(isset($student))
                    <a href="{{ route('admin.nilai-siswa.show', $student->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs">Detail Lengkap</a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Detail Nilai Per Mapel -->
        <div id="details-{{ $studentId }}" class="hidden">
            <div class="p-4">
                <h4 class="font-semibold mb-3 text-gray-700">Detail Nilai Per Mata Pelajaran:</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-2 px-3 text-left border">Mata Pelajaran</th>
                                <th class="py-2 px-3 text-center border">Nilai Ganjil</th>
                                <th class="py-2 px-3 text-center border">Nilai Genap</th>
                                <th class="py-2 px-3 text-center border">Nilai Akhir Tahun</th>
                                <th class="py-2 px-3 text-center border">KKM</th>
                                <th class="py-2 px-3 text-center border">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $subject)
                            @php
                            $ganjilGrade = $studentGrades->where('subject_id', $subject->id)->where('semester.name', 'Ganjil')->first();
                            $genapGrade = $studentGrades->where('subject_id', $subject->id)->where('semester.name', 'Genap')->first();
                            $yearlyKey = $studentId . '_' . $subject->id;
                            $yearlyGrade = $yearlyGrades[$yearlyKey] ?? null;

                            // Tentukan status berdasarkan nilai akhir tahun
                            $status = '-';
                            $statusClass = 'text-gray-500';
                            if ($yearlyGrade !== null) {
                            $kkm = $ganjilGrade?->getKKM() ?? $genapGrade?->getKKM();
                            if ($kkm !== null) {
                            if ($yearlyGrade >= $kkm) {
                            $status = 'Lulus';
                            $statusClass = 'text-green-600 font-semibold';
                            } else {
                            $status = 'Tidak Lulus';
                            $statusClass = 'text-red-600 font-semibold';
                            }
                            }
                            }
                            @endphp
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2 px-3 border font-medium">{{ $subject->name }}</td>
                                <td class="py-2 px-3 text-center border">{{ $ganjilGrade?->final_grade !== null ? number_format($ganjilGrade->final_grade, 2) : '-' }}</td>
                                <td class="py-2 px-3 text-center border">{{ $genapGrade?->final_grade !== null ? number_format($genapGrade->final_grade, 2) : '-' }}</td>
                                <td class="py-2 px-3 text-center border font-bold {{ $yearlyGrade !== null ? 'bg-yellow-50' : '' }}">
                                    {{ $yearlyGrade !== null ? number_format($yearlyGrade, 2) : '-' }}
                                </td>
                                <td class="py-2 px-3 text-center border">{{ $ganjilGrade?->getKKM() ?? $genapGrade?->getKKM() ?? '-' }}</td>
                                <td class="py-2 px-3 text-center border {{ $statusClass }}">{{ $status }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Ringkasan Statistik Siswa -->
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <h5 class="font-semibold text-gray-700 mb-2">Ringkasan Siswa:</h5>
                    <div class="flex gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                            <span class="text-green-600 font-medium">{{ $studentStats['passed_subjects'] ?? 0 }} Mapel Lulus</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                            <span class="text-red-600 font-medium">{{ $studentStats['failed_subjects'] ?? 0 }} Mapel Tidak Lulus</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                            <span class="text-blue-600 font-medium">{{ $studentStats['completed_subjects'] ?? 0 }}/{{ $studentStats['total_subjects'] ?? 0 }} Mapel Terisi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @if($grades)
    <div class="mt-4">{{ $grades->links() }}</div>
    @endif
</div>
@else
<div class="bg-white rounded-lg shadow p-8 text-center">
    <div class="text-gray-500">
        <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="text-lg font-medium mb-2">Belum ada data nilai</h3>
        <p class="text-sm">Pilih kelas untuk melihat rekap nilai siswa.</p>
    </div>
</div>
@endif

<script>
    function toggleDetails(studentId) {
        const details = document.getElementById('details-' + studentId);
        const btn = document.getElementById('btn-' + studentId);

        if (details.classList.contains('hidden')) {
            details.classList.remove('hidden');
            btn.textContent = 'Sembunyikan Detail';
        } else {
            details.classList.add('hidden');
            btn.textContent = 'Tampilkan Detail';
        }
    }
</script>
@endsection
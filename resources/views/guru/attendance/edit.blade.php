@extends('layouts.dashboard')

@section('title', 'Edit Absensi')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Absensi</h1>
                <p class="text-gray-600 mt-1">Edit data absensi siswa yang sudah diisi</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Info Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $schedule->subject->name }}</h2>
                    <div class="flex items-center gap-4 text-sm text-gray-600 mt-1">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span>{{ $schedule->classroom->name }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($schedule->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->time_end)->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Edit Mode</p>
                            <p class="text-xs text-blue-600">Tanggal: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Edit Data Absensi</h2>
                    <p class="text-sm text-gray-600">Ubah status kehadiran siswa</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="setAllStatus('hadir')" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Semua Hadir
                    </button>
                    <button type="button" onclick="resetAllStatus()" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('teacher.attendance.update', [$schedule->id, $date]) }}" id="editAttendanceForm">
            @csrf
            @method('PUT')

            <div class="p-6">
                @if($students->isEmpty())
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Siswa</h3>
                    <p class="text-gray-600">Tidak ada siswa dalam kelas ini.</p>
                </div>
                @else
                <div class="space-y-4">
                    @foreach($students as $index => $classStudent)
                    @php
                    $student = $classStudent->student;
                    $existing = $existingAttendance->get($student->id);
                    @endphp
                    <div class="bg-gray-50 rounded-lg p-4 {{ $existing ? 'border-l-4 border-green-500' : 'border-l-4 border-gray-300' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-blue-600">{{ substr($student->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $student->user->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $student->nis }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-2">
                                    <label class="text-sm font-medium text-gray-700">Status:</label>
                                    <select name="attendances[{{ $student->id }}][status]"
                                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 status-select">
                                        <option value="hadir" {{ $existing && $existing->status == 'hadir' ? 'selected' : '' }}>✅ Hadir</option>
                                        <option value="izin" {{ $existing && $existing->status == 'izin' ? 'selected' : '' }}>⏰ Izin</option>
                                        <option value="sakit" {{ $existing && $existing->status == 'sakit' ? 'selected' : '' }}>🏥 Sakit</option>
                                        <option value="alpha" {{ $existing && $existing->status == 'alpha' ? 'selected' : '' }}>❌ Alpha</option>
                                    </select>
                                    <input type="hidden" name="attendances[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                                </div>
                                <div class="flex items-center gap-2">
                                    <label class="text-sm font-medium text-gray-700">Catatan:</label>
                                    <input type="text" name="attendances[{{ $student->id }}][notes]"
                                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Opsional"
                                        value="{{ $existing ? $existing->notes : '' }}">
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($existing)
                                    <div class="flex flex-col items-center gap-1">
                                        @php
                                        $statusColors = [
                                        'hadir' => 'green',
                                        'izin' => 'yellow',
                                        'sakit' => 'blue',
                                        'alpha' => 'red'
                                        ];
                                        $statusLabels = [
                                        'hadir' => 'Hadir',
                                        'izin' => 'Izin',
                                        'sakit' => 'Sakit',
                                        'alpha' => 'Alpha'
                                        ];
                                        $statusIcons = [
                                        'hadir' => 'M5 13l4 4L19 7',
                                        'izin' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'sakit' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                                        'alpha' => 'M6 18L18 6M6 6l12 12'
                                        ];
                                        @endphp
                                        <span class="inline-flex items-center gap-1 bg-{{ $statusColors[$existing->status] }}-100 text-{{ $statusColors[$existing->status] }}-800 px-2 py-1 rounded-full text-xs font-medium">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusIcons[$existing->status] }}"></path>
                                            </svg>
                                            {{ $statusLabels[$existing->status] }}
                                        </span>
                                        <div class="flex items-center gap-1 text-xs text-gray-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ \Carbon\Carbon::parse($existing->attendance_time)->format('H:i') }}</span>
                                        </div>
                                    </div>
                                    @else
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Belum Diisi
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Summary of Changes -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Ringkasan Perubahan</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-blue-600" id="totalStudents">0</p>
                            <p class="text-sm text-gray-600">Total Siswa</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-green-600" id="hadirCount">0</p>
                            <p class="text-sm text-gray-600">Hadir</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-yellow-600" id="izinCount">0</p>
                            <p class="text-sm text-gray-600">Izin</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-red-600" id="alphaCount">0</p>
                            <p class="text-sm text-gray-600">Alpha</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <a href="{{ route('teacher.attendance.view', $schedule->id) }}" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="previewChanges()" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Preview Perubahan
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg font-medium transition-colors" id="updateBtn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Absensi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Quick action functions
    function setAllStatus(status) {
        const selects = document.querySelectorAll('.status-select');
        selects.forEach(select => {
            select.value = status;
        });
        updateSummary();
    }

    function resetAllStatus() {
        const selects = document.querySelectorAll('.status-select');
        selects.forEach(select => {
            select.value = 'hadir';
        });
        updateSummary();
    }

    function updateSummary() {
        const selects = document.querySelectorAll('.status-select');
        const statusCounts = {
            'hadir': 0,
            'izin': 0,
            'sakit': 0,
            'alpha': 0
        };

        selects.forEach(select => {
            const status = select.value;
            if (statusCounts.hasOwnProperty(status)) {
                statusCounts[status]++;
            }
        });

        document.getElementById('totalStudents').textContent = selects.length;
        document.getElementById('hadirCount').textContent = statusCounts.hadir;
        document.getElementById('izinCount').textContent = statusCounts.izin;
        document.getElementById('alphaCount').textContent = statusCounts.alpha;
    }

    function previewChanges() {
        const form = document.getElementById('editAttendanceForm');
        const formData = new FormData(form);

        // Create preview modal
        let previewHtml = '<div class="modal fade" id="previewModal" tabindex="-1">';
        previewHtml += '<div class="modal-dialog modal-lg"><div class="modal-content">';
        previewHtml += '<div class="modal-header"><h5 class="modal-title">Preview Perubahan Absensi</h5>';
        previewHtml += '<button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>';
        previewHtml += '<div class="modal-body">';
        previewHtml += '<div class="alert alert-info">';
        previewHtml += '<i class="mdi mdi-information-outline me-2"></i>';
        previewHtml += 'Berikut adalah preview perubahan yang akan disimpan.';
        previewHtml += '</div>';
        previewHtml += '<div class="table-responsive"><table class="table table-sm">';
        previewHtml += '<thead><tr><th>Nama</th><th>Status Baru</th><th>Catatan</th></tr></thead><tbody>';

        // Add preview data here
        previewHtml += '</tbody></table></div></div>';
        previewHtml += '<div class="modal-footer">';
        previewHtml += '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>';
        previewHtml += '<button type="button" class="btn btn-warning" onclick="document.getElementById(\'editAttendanceForm\').submit()">Simpan Perubahan</button>';
        previewHtml += '</div></div></div></div>';

        document.body.insertAdjacentHTML('beforeend', previewHtml);

        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();

        // Remove modal after hidden
        document.getElementById('previewModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }

    // Update summary on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateSummary();

        // Update summary when status changes
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', updateSummary);
        });
    });
</script>
@endpush
@endsection
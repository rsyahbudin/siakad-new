@extends('layouts.dashboard')

@section('title', 'Ambil Absensi')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Ambil Absensi</h1>
                <p class="text-gray-600 mt-1">Isi absensi siswa untuk mata pelajaran yang Anda ampu</p>
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
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <form method="GET" class="flex items-center gap-2">
                    <input type="date" name="date" value="{{ $date }}"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if($existingAttendance->isNotEmpty())
    <!-- Warning Alert -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-yellow-800">Absensi Sudah Diisi!</h3>
                <p class="text-sm text-yellow-700 mt-1">
                    Absensi untuk tanggal {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} sudah diisi.
                    Anda dapat <a href="{{ route('teacher.attendance.edit', [$schedule->id, $date]) }}" class="font-medium underline">mengedit</a> data yang ada.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Attendance Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Data Absensi Siswa</h2>
                    <p class="text-sm text-gray-600">Isi status kehadiran untuk setiap siswa</p>
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

        <form method="POST" action="{{ route('teacher.attendance.store', $schedule->id) }}" id="attendanceForm">
            @csrf
            <input type="hidden" name="attendance_date" value="{{ $date }}">

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
                <!-- Search and Filter Controls -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <!-- Search Box -->
                            <div class="relative">
                                <input type="text" id="searchStudents" placeholder="Cari nama siswa atau NIS..."
                                    class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>

                            <!-- Filter by Status -->
                            <select id="filterStatus" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Status</option>
                                <option value="hadir">Hadir</option>
                                <option value="izin">Izin</option>
                                <option value="sakit">Sakit</option>
                                <option value="alpha">Alpha</option>
                                <option value="belum">Belum Diisi</option>
                            </select>
                        </div>

                        <!-- Student Count -->
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span id="studentCount">{{ $students->count() }}</span> siswa
                        </div>
                    </div>
                </div>

                <!-- Students List -->
                <div class="space-y-3" id="studentsList">
                    @foreach($students as $index => $classStudent)
                    @php
                    $student = $classStudent->student;
                    $existing = $existingAttendance->get($student->id);
                    @endphp
                    <div class="student-item bg-gray-50 rounded-lg p-4 {{ $existing ? 'border-l-4 border-green-500' : '' }}"
                        data-name="{{ strtolower($student->user->name) }}"
                        data-nis="{{ strtolower($student->nis) }}"
                        data-status="{{ $existing ? $existing->status : 'belum' }}">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-sm font-medium text-blue-600">{{ substr($student->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $student->user->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $student->nis }}</p>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full lg:w-auto">
                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                    <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Status:</label>
                                    <select name="attendances[{{ $student->id }}][status]"
                                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 status-select w-full sm:w-auto">
                                        <option value="hadir" {{ $existing && $existing->status == 'hadir' ? 'selected' : '' }}>✅ Hadir</option>
                                        <option value="izin" {{ $existing && $existing->status == 'izin' ? 'selected' : '' }}>⏰ Izin</option>
                                        <option value="sakit" {{ $existing && $existing->status == 'sakit' ? 'selected' : '' }}>🏥 Sakit</option>
                                        <option value="alpha" {{ $existing && $existing->status == 'alpha' ? 'selected' : '' }}>❌ Alpha</option>
                                    </select>
                                    <input type="hidden" name="attendances[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                                </div>

                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                    <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Catatan:</label>
                                    <input type="text" name="attendances[{{ $student->id }}][notes]"
                                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-auto"
                                        placeholder="Opsional"
                                        value="{{ $existing ? $existing->notes : '' }}">
                                </div>

                                <div class="flex items-center gap-2">
                                    @if($existing)
                                    <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Sudah Diisi
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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

                <!-- No Results Message -->
                <div id="noResults" class="hidden text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Hasil</h3>
                    <p class="text-gray-600">Tidak ada siswa yang sesuai dengan pencarian Anda.</p>
                </div>
                @endif
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <a href="{{ route('teacher.attendance.index') }}" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="previewAttendance()" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Preview
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors" id="submitBtn"
                            {{ $existingAttendance->isNotEmpty() ? 'disabled' : '' }}>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Absensi
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
        updateSubmitButton();
    }

    function resetAllStatus() {
        const selects = document.querySelectorAll('.status-select');
        selects.forEach(select => {
            select.value = 'hadir';
        });
        updateSubmitButton();
    }

    function updateSubmitButton() {
        const submitBtn = document.getElementById('submitBtn');
        const filledCount = document.querySelectorAll('.status-select').length;
        const emptyCount = document.querySelectorAll('.status-select:not([value])').length;

        if (emptyCount === 0) {
            submitBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Absensi (' + filledCount + ' siswa)';
        } else {
            submitBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Absensi (' + (filledCount - emptyCount) + '/' + filledCount + ')';
        }
    }

    function previewAttendance() {
        const form = document.getElementById('attendanceForm');
        const formData = new FormData(form);

        // Create preview modal
        let previewHtml = '<div class="modal fade" id="previewModal" tabindex="-1">';
        previewHtml += '<div class="modal-dialog modal-lg"><div class="modal-content">';
        previewHtml += '<div class="modal-header"><h5 class="modal-title">Preview Absensi</h5>';
        previewHtml += '<button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>';
        previewHtml += '<div class="modal-body">';
        previewHtml += '<div class="table-responsive"><table class="table table-sm">';
        previewHtml += '<thead><tr><th>Nama</th><th>Status</th><th>Catatan</th></tr></thead><tbody>';

        // Add preview data here
        previewHtml += '</tbody></table></div></div>';
        previewHtml += '<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>';
        previewHtml += '</div></div></div>';

        document.body.insertAdjacentHTML('beforeend', previewHtml);

        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();

        // Remove modal after hidden
        document.getElementById('previewModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }

    // Search and filter functionality
    function filterStudents() {
        const searchTerm = document.getElementById('searchStudents').value.toLowerCase();
        const filterStatus = document.getElementById('filterStatus').value;
        const studentItems = document.querySelectorAll('.student-item');
        const studentsList = document.getElementById('studentsList');
        const noResults = document.getElementById('noResults');
        const studentCount = document.getElementById('studentCount');

        let visibleCount = 0;

        studentItems.forEach(item => {
            const name = item.dataset.name;
            const nis = item.dataset.nis;
            const status = item.dataset.status;

            const matchesSearch = name.includes(searchTerm) || nis.includes(searchTerm);
            const matchesStatus = !filterStatus || status === filterStatus;

            if (matchesSearch && matchesStatus) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Update student count
        studentCount.textContent = visibleCount;

        // Show/hide no results message
        if (visibleCount === 0) {
            studentsList.style.display = 'none';
            noResults.classList.remove('hidden');
        } else {
            studentsList.style.display = 'block';
            noResults.classList.add('hidden');
        }
    }

    // Update submit button on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateSubmitButton();

        // Update button when status changes
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', updateSubmitButton);
        });

        // Search and filter event listeners
        document.getElementById('searchStudents').addEventListener('input', filterStudents);
        document.getElementById('filterStatus').addEventListener('change', filterStudents);

        // Update status data attributes when status changes
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                const studentItem = this.closest('.student-item');
                studentItem.dataset.status = this.value;
            });
        });
    });
</script>
@endpush
@endsection
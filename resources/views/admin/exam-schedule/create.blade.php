@extends('layouts.dashboard')

@section('title', 'Tambah Jadwal Ujian')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Jadwal Ujian</h1>
                <p class="text-gray-600 mt-1">Buat jadwal ujian baru untuk semua kelas berdasarkan angkatan dan jurusan</p>
                @if($activeSemester && $activeAcademicYear)
                <div class="mt-2 flex items-center gap-2 text-sm text-blue-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Membuat jadwal untuk: <strong>{{ $activeSemester->name }} - {{ $activeAcademicYear->year }}</strong></span>
                </div>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.exam-schedules.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    @if($activeSemester && $activeAcademicYear)
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-blue-900">Informasi Semester Aktif</h3>
                <p class="text-sm text-blue-800 mt-1">
                    Saat ini hanya mengizinkan pembuatan jadwal ujian untuk semester <strong>{{ $activeSemester->name }}</strong>
                    tahun ajaran <strong>{{ $activeAcademicYear->year }}</strong>.
                    Sistem akan otomatis membuat jadwal untuk semua kelas berdasarkan angkatan dan jurusan yang dipilih.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Form Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Form Jadwal Ujian</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.exam-schedules.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Academic Year -->
                    <div>
                        <label for="academic_year_id" class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran</label>
                        <select name="academic_year_id" id="academic_year_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            @foreach($academicYears as $academicYear)
                            <option value="{{ $academicYear->id }}" {{ old('academic_year_id', $activeAcademicYear->id ?? '') == $academicYear->id ? 'selected' : '' }}>
                                {{ $academicYear->year }}
                            </option>
                            @endforeach
                        </select>
                        @error('academic_year_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Semester -->
                    <div>
                        <label for="semester_id" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                        <select name="semester_id" id="semester_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ old('semester_id', $activeSemester->id ?? '') == $semester->id ? 'selected' : '' }}>
                                {{ $semester->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('semester_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grade -->
                    <div>
                        <label for="grade" class="block text-sm font-medium text-gray-700 mb-2">Angkatan</label>
                        <select name="grade" id="grade" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Pilih Angkatan</option>
                            @foreach($grades as $grade)
                            <option value="{{ $grade }}" {{ old('grade') == $grade ? 'selected' : '' }}>
                                Kelas {{ $grade }}
                            </option>
                            @endforeach
                        </select>
                        @error('grade')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Major -->
                    <div>
                        <label for="major_id" class="block text-sm font-medium text-gray-700 mb-2">Jurusan</label>
                        <select name="major_id" id="major_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Mata Pelajaran Umum (Semua Jurusan)</option>
                            @foreach($availableMajorsData as $major)
                            <option value="{{ $major->id }}" {{ old('major_id') == $major->id ? 'selected' : '' }}>
                                {{ $major->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('major_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                        <select name="subject_id" id="subject_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}"
                                data-major="{{ $subject->major_id }}"
                                {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                                @if($subject->major)
                                ({{ $subject->major->name }})
                                @else
                                (Umum)
                                @endif
                            </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Exam Type -->
                    <div>
                        <label for="exam_type" class="block text-sm font-medium text-gray-700 mb-2">Jenis Ujian</label>
                        <select name="exam_type" id="exam_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Pilih Jenis Ujian</option>
                            <option value="uts" {{ old('exam_type') == 'uts' ? 'selected' : '' }}>UTS</option>
                            <option value="uas" {{ old('exam_type') == 'uas' ? 'selected' : '' }}>UAS</option>
                        </select>
                        @error('exam_type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Supervisor -->
                    <div>
                        <label for="supervisor_ids" class="block text-sm font-medium text-gray-700 mb-2">Pengawas (Pilih Multiple)</label>
                        <select name="supervisor_ids[]" id="supervisor_ids" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" multiple required>
                            @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ in_array($teacher->id, old('supervisor_ids', [])) ? 'selected' : '' }}>
                                {{ $teacher->full_name }}
                                @if($teacher->subject)
                                ({{ $teacher->subject->name }})
                                @endif
                            </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Pilih pengawas sesuai jumlah kelas yang akan dibuat. Gunakan Ctrl/Cmd untuk memilih multiple.</p>
                        @error('supervisor_ids')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preview Section -->
                    <div class="md:col-span-2">
                        <div id="preview-section" class="bg-blue-50 border border-blue-200 rounded-lg p-4 hidden">
                            <h4 class="text-sm font-medium text-blue-900 mb-2">Preview Jadwal yang Akan Dibuat:</h4>
                            <div id="preview-content" class="text-sm text-blue-800">
                                <!-- Preview content will be populated by JavaScript -->
                            </div>
                            <div id="classroom-count-info" class="mt-3 p-3 bg-green-100 rounded border border-green-200 hidden">
                                <p class="text-sm font-medium text-green-900">Jumlah kelas yang akan dibuat jadwalnya: <span id="classroom-count">0</span> kelas</p>
                            </div>
                        </div>
                    </div>

                    <!-- Exam Date -->
                    <div>
                        <label for="exam_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Ujian</label>
                        <input type="date" name="exam_date" id="exam_date" value="{{ old('exam_date') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        @error('exam_date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        @error('start_time')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        @error('end_time')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is General Subject -->
                    <div>
                        <label for="is_general_subject" class="block text-sm font-medium text-gray-700 mb-2">Jenis Mata Pelajaran</label>
                        <select name="is_general_subject" id="is_general_subject" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="1" {{ old('is_general_subject', '1') == '1' ? 'selected' : '' }}>Mata Pelajaran Umum</option>
                            <option value="0" {{ old('is_general_subject') == '0' ? 'selected' : '' }}>Mata Pelajaran Jurusan</option>
                        </select>
                        @error('is_general_subject')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.exam-schedules.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Buat Jadwal Ujian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Classroom counts data
    var classroomCounts = @json($classroomCounts);

    document.addEventListener('DOMContentLoaded', function() {
        const majorSelect = document.getElementById('major_id');
        const subjectSelect = document.getElementById('subject_id');
        const isGeneralSubjectSelect = document.getElementById('is_general_subject');
        const previewSection = document.getElementById('preview-section');
        const previewContent = document.getElementById('preview-content');
        const supervisorSelect = document.getElementById('supervisor_ids');
        const classroomCountInfo = document.getElementById('classroom-count-info');
        const classroomCountSpan = document.getElementById('classroom-count');

        // Filter subjects based on major selection
        function filterSubjects() {
            const selectedMajor = majorSelect.value;
            const options = subjectSelect.querySelectorAll('option');

            options.forEach(option => {
                if (option.value === '') return; // Skip placeholder option

                const subjectMajor = option.dataset.major;

                if (selectedMajor === '') {
                    // Show only general subjects when no major is selected
                    option.style.display = subjectMajor === '' ? '' : 'none';
                } else {
                    // Show subjects that match the selected major
                    option.style.display = (subjectMajor === selectedMajor || subjectMajor === '') ? '' : 'none';
                }
            });

            // Reset subject selection if current selection is not available
            const currentSubject = subjectSelect.value;
            const currentOption = subjectSelect.querySelector(`option[value="${currentSubject}"]`);
            if (currentOption && currentOption.style.display === 'none') {
                subjectSelect.value = '';
            }
        }

        // Update is_general_subject based on major selection
        function updateGeneralSubject() {
            if (majorSelect.value === '') {
                isGeneralSubjectSelect.value = '1';
            } else {
                isGeneralSubjectSelect.value = '0';
            }
        }

        // Function to get selected supervisor names
        function getSelectedSupervisorNames() {
            const selectedOptions = Array.from(supervisorSelect.selectedOptions);
            return selectedOptions.map(option => option.text.trim()).join(', ');
        }

        // Function to update preview content
        function updatePreview() {
            const academicYearSelect = document.getElementById('academic_year_id');
            const semesterSelect = document.getElementById('semester_id');
            const gradeSelect = document.getElementById('grade');
            const majorSelect = document.getElementById('major_id');
            const subjectSelect = document.getElementById('subject_id');
            const examTypeSelect = document.getElementById('exam_type');
            const examDateInput = document.getElementById('exam_date');
            const startTimeInput = document.getElementById('start_time');
            const endTimeInput = document.getElementById('end_time');
            const isGeneralSubjectSelect = document.getElementById('is_general_subject');

            // Get selected values
            const academicYear = academicYearSelect.options[academicYearSelect.selectedIndex]?.text || '';
            const semester = semesterSelect.options[semesterSelect.selectedIndex]?.text || '';
            const grade = gradeSelect.value || '';
            const major = majorSelect.value === '' ? 'Semua Jurusan' : majorSelect.options[majorSelect.selectedIndex]?.text || '';
            const subject = subjectSelect.value === '' ? 'Belum dipilih' : subjectSelect.options[subjectSelect.selectedIndex]?.text || '';
            const examType = examTypeSelect.value === '' ? 'Belum dipilih' : examTypeSelect.value.toUpperCase();
            const examDate = examDateInput.value || 'Belum dipilih';
            const startTime = startTimeInput.value || 'Belum dipilih';
            const endTime = endTimeInput.value || 'Belum dipilih';
            const isGeneralSubject = isGeneralSubjectSelect.value === '1' ? 'Mata Pelajaran Umum' : 'Mata Pelajaran Jurusan';
            const supervisorNames = getSelectedSupervisorNames() || 'Belum dipilih';
            const supervisorCount = supervisorSelect.selectedOptions.length;

            // Calculate classroom count
            let classroomCount = 0;
            if (grade && classroomCounts && classroomCounts[grade]) {
                if (majorSelect.value === '') {
                    // General subjects - count all classrooms of the grade
                    classroomCount = classroomCounts[grade]['general'] || 0;
                } else {
                    // Major-specific subjects - count classrooms of that major
                    classroomCount = classroomCounts[grade][majorSelect.value] || 0;
                }
            }

            const previewHtml = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p><strong>Tahun Ajaran:</strong> ${academicYear}</p>
                        <p><strong>Semester:</strong> ${semester}</p>
                        <p><strong>Angkatan:</strong> ${grade}</p>
                        <p><strong>Jurusan:</strong> ${major}</p>
                        <p><strong>Mata Pelajaran:</strong> ${subject}</p>
                    </div>
                    <div>
                        <p><strong>Jenis Ujian:</strong> ${examType}</p>
                        <p><strong>Tanggal:</strong> ${examDate}</p>
                        <p><strong>Waktu:</strong> ${startTime} - ${endTime}</p>
                        <p><strong>Jenis Mapel:</strong> ${isGeneralSubject}</p>
                        <p><strong>Pengawas:</strong> ${supervisorCount} guru (${supervisorNames})</p>
                    </div>
                </div>
                <div class="mt-3 p-3 bg-blue-100 rounded border border-blue-200">
                    <p class="text-sm font-medium text-blue-900">Sistem akan membuat jadwal untuk semua kelas ${grade} ${major} dengan pengawas yang berbeda untuk setiap kelas.</p>
                </div>
            `;

            previewContent.innerHTML = previewHtml;
            previewSection.classList.remove('hidden');

            // Update classroom count info
            if (classroomCount > 0) {
                classroomCountSpan.textContent = classroomCount;
                classroomCountInfo.classList.remove('hidden');

                // Show warning if not enough supervisors
                if (supervisorCount > 0 && supervisorCount < classroomCount) {
                    classroomCountInfo.innerHTML = `
                        <p class="text-sm font-medium text-red-900">⚠️ Peringatan: Jumlah pengawas (${supervisorCount}) kurang dari jumlah kelas (${classroomCount}). Minimal ${classroomCount} pengawas diperlukan.</p>
                    `;
                    classroomCountInfo.className = 'mt-3 p-3 bg-red-100 rounded border border-red-200';
                } else if (supervisorCount >= classroomCount) {
                    classroomCountInfo.innerHTML = `
                        <p class="text-sm font-medium text-green-900">✅ Jumlah kelas yang akan dibuat jadwalnya: ${classroomCount} kelas</p>
                    `;
                    classroomCountInfo.className = 'mt-3 p-3 bg-green-100 rounded border border-green-200';
                }
            } else {
                classroomCountInfo.classList.add('hidden');
            }
        }

        // Event listeners
        majorSelect.addEventListener('change', function() {
            filterSubjects();
            updateGeneralSubject();
            updatePreview();
        });

        // Update preview on any form change
        document.querySelectorAll('form select, form input[type="date"], form input[type="time"]').forEach(input => {
            input.addEventListener('change', updatePreview);
        });

        // Initial setup
        filterSubjects();
        updateGeneralSubject();
        updatePreview();
    });
</script>
@endsection
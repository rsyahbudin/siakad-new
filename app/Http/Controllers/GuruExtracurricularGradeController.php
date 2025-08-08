<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Extracurricular;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Semester;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GuruExtracurricularGradeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses sebagai guru.');
        }

        // Get active academic year and semester
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        if (!$activeYear || !$activeSemester) {
            return redirect()->back()->with('error', 'Tahun ajaran atau semester aktif tidak ditemukan.');
        }

        // Get extracurriculars supervised by this teacher
        $extracurriculars = Extracurricular::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->with(['students' => function ($query) use ($activeYear) {
                $query->wherePivot('academic_year_id', $activeYear->id)
                    ->wherePivot('status', 'Aktif');
            }])
            ->orderBy('name')
            ->get();

        // Additional validation to ensure teacher is the supervisor
        $extracurriculars = $extracurriculars->filter(function ($extracurricular) use ($teacher) {
            return $extracurricular->teacher_id === $teacher->id;
        });

        return view('guru.extracurricular-grade.index', compact('extracurriculars', 'activeYear', 'activeSemester'));
    }

    public function show(Extracurricular $extracurricular)
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        if (!$activeYear || !$activeSemester) {
            return redirect()->back()->with('error', 'Tahun ajaran atau semester aktif tidak ditemukan.');
        }

        // Get active students in this extracurricular
        $students = $extracurricular->students()
            ->wherePivot('academic_year_id', $activeYear->id)
            ->wherePivot('status', 'Aktif')
            ->orderBy('full_name')
            ->get();

        return view('guru.extracurricular-grade.show', compact('extracurricular', 'students', 'activeYear', 'activeSemester'));
    }

    public function store(Request $request, Extracurricular $extracurricular)
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'nullable|in:Sangat Baik,Baik,Cukup,Kurang',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string|max:500',
            'achievements' => 'nullable|array',
            'achievements.*' => 'nullable|string|max:500',
            'positions' => 'required|array',
            'positions.*' => 'required|in:Anggota,Ketua,Wakil Ketua,Sekretaris,Bendahara',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($request->grades as $studentId => $grade) {
            try {
                // Check if student is enrolled in this extracurricular
                $enrollment = $extracurricular->students()
                    ->wherePivot('student_id', $studentId)
                    ->wherePivot('academic_year_id', $activeYear->id)
                    ->wherePivot('status', 'Aktif')
                    ->first();

                if ($enrollment) {
                    $updateData = [
                        'grade' => $grade ?: null,
                        'notes' => $request->notes[$studentId] ?? null,
                        'achievements' => $request->achievements[$studentId] ?? null,
                        'position' => $request->positions[$studentId] ?? 'Anggota',
                    ];

                    $extracurricular->students()->updateExistingPivot($studentId, $updateData, $activeYear->id);
                    $successCount++;
                } else {
                    $errorCount++;
                }
            } catch (\Exception $e) {
                $errorCount++;
            }
        }

        $message = "Berhasil menyimpan nilai untuk {$successCount} siswa.";
        if ($errorCount > 0) {
            $message .= " Gagal menyimpan nilai untuk {$errorCount} siswa.";
        }

        return redirect()->back()->with('success', $message);
    }

    public function downloadTemplate(Extracurricular $extracurricular)
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        // Get active students in this extracurricular
        $students = $extracurricular->students()
            ->wherePivot('academic_year_id', $activeYear->id)
            ->wherePivot('status', 'Aktif')
            ->orderBy('full_name')
            ->get();

        // Create Excel file
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIS');
        $sheet->setCellValue('C1', 'Nama Siswa');
        $sheet->setCellValue('D1', 'Posisi');
        $sheet->setCellValue('E1', 'Nilai Ekstrakurikuler');
        $sheet->setCellValue('F1', 'Prestasi');
        $sheet->setCellValue('G1', 'Catatan');

        // Add data
        $row = 2;
        foreach ($students as $index => $student) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $student->nis);
            $sheet->setCellValue('C' . $row, $student->full_name);
            $sheet->setCellValue('D' . $row, $student->pivot->position ?? 'Anggota');
            $sheet->setCellValue('E' . $row, $student->pivot->grade ?? '');
            $sheet->setCellValue('F' . $row, $student->pivot->achievements ?? '');
            $sheet->setCellValue('G' . $row, $student->pivot->notes ?? '');
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Create the Excel file
        $writer = new Xlsx($spreadsheet);
        $filename = 'template_nilai_ekskul_' . str_replace(' ', '_', $extracurricular->name) . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function import(Request $request, Extracurricular $extracurricular)
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        try {
            $file = $request->file('file');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Remove header row
            array_shift($rows);

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                if (empty($row[0])) continue; // Skip empty rows

                $nis = $row[1];
                $position = $row[3] ?? 'Anggota';
                $grade = $row[4] ?? null;
                $achievements = $row[5] ?? null;
                $notes = $row[6] ?? null;

                if (empty($nis)) continue;

                $student = Student::where('nis', $nis)->first();

                if (!$student) {
                    $errors[] = "Baris " . ($index + 2) . ": Siswa dengan NIS '{$nis}' tidak ditemukan.";
                    $errorCount++;
                    continue;
                }

                // Check if student is enrolled in this extracurricular
                $enrollment = $extracurricular->students()
                    ->wherePivot('student_id', $student->id)
                    ->wherePivot('academic_year_id', $activeYear->id)
                    ->wherePivot('status', 'Aktif')
                    ->first();

                if (!$enrollment) {
                    $errors[] = "Baris " . ($index + 2) . ": Siswa '{$student->full_name}' (NIS: {$nis}) tidak terdaftar di ekstrakurikuler ini.";
                    $errorCount++;
                    continue;
                }

                // Validate position
                $validPositions = ['Anggota', 'Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara'];
                if (!in_array($position, $validPositions)) {
                    $errors[] = "Baris " . ($index + 2) . ": Posisi '{$position}' tidak valid. Posisi yang valid: " . implode(', ', $validPositions);
                    $errorCount++;
                    continue;
                }

                // Validate grade
                $validGrades = ['Sangat Baik', 'Baik', 'Cukup', 'Kurang'];
                if ($grade && !in_array($grade, $validGrades)) {
                    $errors[] = "Baris " . ($index + 2) . ": Nilai '{$grade}' tidak valid. Nilai yang valid: " . implode(', ', $validGrades);
                    $errorCount++;
                    continue;
                }

                try {
                    $extracurricular->students()->updateExistingPivot($student->id, [
                        'grade' => $grade ?: null,
                        'position' => $position,
                        'achievements' => $achievements ?: null,
                        'notes' => $notes ?: null,
                    ], $activeYear->id);
                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Baris " . ($index + 2) . ": Gagal menyimpan data untuk siswa '{$student->full_name}'.";
                    $errorCount++;
                }
            }

            $message = "Berhasil mengimpor nilai untuk {$successCount} siswa.";
            if ($errorCount > 0) {
                $message .= " Gagal mengimpor nilai untuk {$errorCount} siswa.";
            }

            if (!empty($errors)) {
                return redirect()->back()->with('error', implode("\n", $errors));
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }
    }
}

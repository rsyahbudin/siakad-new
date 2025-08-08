<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\ClassroomAssignment;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Semester;
use App\Models\ClassStudent;
use App\Models\Raport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GuruNilaiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $activeYearId = $activeSemester?->academic_year_id;

        $assignment_ids = Schedule::where('teacher_id', $teacher->id)
            ->whereHas('classroomAssignment', function ($query) use ($activeYearId) {
                $query->where('academic_year_id', $activeYearId);
            })
            ->pluck('classroom_assignment_id')->unique();

        $assignments = ClassroomAssignment::with('classroom')->whereIn('id', $assignment_ids)->orderBy('classroom_id')->get();

        $selectedAssignment = $request->assignment_id;
        $selectedSubject = $request->subject_id;
        $students = collect();
        $grades = collect();
        $bobot = null;
        $isFinalized = false;
        if ($selectedAssignment && $selectedSubject) {
            $assignment = ClassroomAssignment::find($selectedAssignment);
            $students = $assignment->classStudents()->with('student.user')->get()->pluck('student');
            $grades = Grade::where('classroom_assignment_id', $selectedAssignment)
                ->where('subject_id', $selectedSubject)
                ->where('semester_id', $activeSemester?->id)
                ->get()->keyBy('student_id');
            $bobot = \App\Models\SubjectSetting::where('subject_id', $selectedSubject)
                ->where('academic_year_id', $activeYearId)
                ->first();

            // Cek apakah raport kelas ini sudah difinalisasi
            $semesterInt = $activeSemester->name === 'Ganjil' ? 1 : 2;
            $isFinalized = Raport::where('classroom_id', $assignment->classroom_id)
                ->where('academic_year_id', $activeSemester->academic_year_id)
                ->where('semester', $semesterInt)
                ->where('is_finalized', true)
                ->exists();
        }
        // Ambil subjects hanya yang diampu guru pada assignment terpilih
        if ($selectedAssignment) {
            $subjects = Schedule::where('classroom_assignment_id', $selectedAssignment)
                ->where('teacher_id', $teacher->id)
                ->with('subject')
                ->get()
                ->pluck('subject')
                ->unique('id')
                ->sortBy('name')
                ->values();
        } else {
            $subjects = collect();
        }
        return view('guru.input-nilai', compact('assignments', 'selectedAssignment', 'selectedSubject', 'students', 'grades', 'activeSemester', 'bobot', 'subjects', 'isFinalized'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $activeYearId = $activeSemester?->academic_year_id;
        $request->validate([
            'assignment_id' => 'required|exists:classroom_assignments,id',
            'subject_id' => 'required|exists:subjects,id',
            'nilai' => 'required|array',
        ]);

        $assignment = \App\Models\ClassroomAssignment::find($request->assignment_id);
        $semesterInt = $activeSemester->name === 'Ganjil' ? 1 : 2;
        $isFinalized = Raport::where('classroom_id', $assignment->classroom_id)
            ->where('academic_year_id', $activeSemester->academic_year_id)
            ->where('semester', $semesterInt)
            ->where('is_finalized', true)
            ->exists();

        if ($isFinalized) {
            return redirect()->back()->with('error', 'Tidak dapat menyimpan nilai karena raport untuk kelas ini sudah difinalisasi.');
        }

        foreach ($request->nilai as $student_id => $nilai) {
            $grade = Grade::updateOrCreate([
                'student_id' => $student_id,
                'classroom_assignment_id' => $request->assignment_id,
                'subject_id' => $request->subject_id,
                'semester_id' => $activeSemester?->id,
            ], [
                'classroom_id' => $assignment?->classroom_id,
                'academic_year_id' => $activeYearId,
                'assignment_grade' => isset($nilai['tugas']) ? ($nilai['tugas'] === null || $nilai['tugas'] === '' ? 0 : $nilai['tugas']) : 0,
                'uts_grade' => isset($nilai['uts']) ? ($nilai['uts'] === null || $nilai['uts'] === '' ? 0 : $nilai['uts']) : 0,
                'uas_grade' => isset($nilai['uas']) ? ($nilai['uas'] === null || $nilai['uas'] === '' ? 0 : $nilai['uas']) : 0,
                'attitude_grade' => isset($nilai['sikap']) ? ($nilai['sikap'] === null || $nilai['sikap'] === '' ? null : $nilai['sikap']) : null,
            ]);
            $grade->calculateFinalGrade();
        }
        return redirect()->route('nilai.input', ['assignment_id' => $request->assignment_id, 'subject_id' => $request->subject_id])->with('success', 'Nilai berhasil disimpan.');
    }

    public function showImportForm(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $activeSemester = Semester::where('is_active', true)->first();
        $activeYearId = $activeSemester?->academic_year_id;

        $assignment_ids = Schedule::where('teacher_id', $teacher->id)
            ->whereHas('classroomAssignment', function ($query) use ($activeYearId) {
                $query->where('academic_year_id', $activeYearId);
            })
            ->pluck('classroom_assignment_id')->unique();

        $assignments = ClassroomAssignment::with('classroom')->whereIn('id', $assignment_ids)->orderBy('classroom_id')->get();

        $selectedAssignment = $request->assignment_id;

        if ($selectedAssignment) {
            $subjects = Schedule::where('classroom_assignment_id', $selectedAssignment)
                ->where('teacher_id', $teacher->id)
                ->with('subject')
                ->get()
                ->pluck('subject')
                ->unique('id')
                ->sortBy('name')
                ->values();
        } else {
            $subjects = collect();
        }

        return view('guru.import-nilai', compact('assignments', 'subjects', 'selectedAssignment'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:classroom_assignments,id',
            'subject_id' => 'required|exists:subjects,id',
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $activeSemester = Semester::where('is_active', true)->first();
        if (!$activeSemester) {
            return back()->with('error', 'Tidak ada semester aktif.');
        }

        // Cek finalisasi sebelum import
        $assignment = ClassroomAssignment::find($request->assignment_id);
        $semesterInt = $activeSemester->name === 'Ganjil' ? 1 : 2;
        $isFinalized = Raport::where('classroom_id', $assignment->classroom_id)
            ->where('academic_year_id', $activeSemester->academic_year_id)
            ->where('semester', $semesterInt)
            ->where('is_finalized', true)
            ->exists();

        if ($isFinalized) {
            return redirect()->back()->with('error', 'Tidak dapat mengimpor nilai karena raport untuk kelas ini sudah difinalisasi.');
        }

        $path = $request->file('file')->getRealPath();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $errors = [];
        $successCount = 0;

        foreach (array_slice($rows, 1) as $index => $row) {
            $nis = $row[0];
            $assignment_grade = $row[2] ?? 0;
            $uts_grade = $row[3] ?? 0;
            $uas_grade = $row[4] ?? 0;
            $attitude_grade = $row[5] ?? null;

            if (empty($nis)) continue;

            $student = Student::where('nis', $nis)->first();

            if (!$student) {
                $errors[] = "Baris " . ($index + 2) . ": Siswa dengan NIS '{$nis}' tidak ditemukan.";
                continue;
            }

            $isStudentInClass = ClassStudent::where('student_id', $student->id)
                ->where('classroom_assignment_id', $request->assignment_id)
                ->exists();

            if (!$isStudentInClass) {
                $errors[] = "Baris " . ($index + 2) . ": Siswa '{$student->full_name}' (NIS: {$nis}) tidak terdaftar di kelas yang dipilih.";
                continue;
            }

            try {
                $grade = Grade::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'classroom_assignment_id' => $request->assignment_id,
                        'subject_id' => $request->subject_id,
                        'semester_id' => $activeSemester->id,
                    ],
                    [
                        'classroom_id' => $assignment->classroom_id,
                        'academic_year_id' => $activeSemester->academic_year_id,
                        'assignment_grade' => $assignment_grade === null || $assignment_grade === '' ? 0 : $assignment_grade,
                        'uts_grade' => $uts_grade === null || $uts_grade === '' ? 0 : $uts_grade,
                        'uas_grade' => $uas_grade === null || $uas_grade === '' ? 0 : $uas_grade,
                        'attitude_grade' => $attitude_grade === null || $attitude_grade === '' ? null : $attitude_grade,
                    ]
                );
                $grade->calculateFinalGrade();
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Baris " . ($index + 2) . ": Terjadi kesalahan saat menyimpan nilai untuk NIS '{$nis}'. " . $e->getMessage();
            }
        }

        $message = "Berhasil mengimpor {$successCount} data nilai.";
        if (count($errors) > 0) {
            return back()->with('error', 'Terjadi beberapa kesalahan:')->withErrors($errors);
        }

        return redirect()->route('nilai.input', [
            'assignment_id' => $request->assignment_id,
            'subject_id' => $request->subject_id
        ])->with('success', $message);
    }

    public function downloadTemplate(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:classroom_assignments,id',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        $assignment = ClassroomAssignment::with('classStudents.student', 'classroom')->findOrFail($request->assignment_id);
        $subjectId = $request->subject_id;
        $grades = collect();

        if ($subjectId) {
            $activeSemester = Semester::where('is_active', true)->first();
            if ($activeSemester) {
                $studentIds = $assignment->classStudents->pluck('student.id')->filter();
                $grades = Grade::where('classroom_assignment_id', $assignment->id)
                    ->where('subject_id', $subjectId)
                    ->where('semester_id', $activeSemester->id)
                    ->whereIn('student_id', $studentIds)
                    ->get()
                    ->keyBy('student_id');
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'NIS');
        $sheet->setCellValue('B1', 'Nama Siswa');
        $sheet->setCellValue('C1', 'Nilai Tugas');
        $sheet->setCellValue('D1', 'Nilai UTS');
        $sheet->setCellValue('E1', 'Nilai UAS');
        $sheet->setCellValue('F1', 'Nilai Sikap');

        $rowNum = 2;
        if ($assignment->classStudents) {
            foreach ($assignment->classStudents as $classStudent) {
                if ($classStudent->student) {
                    $student = $classStudent->student;
                    $sheet->setCellValue('A' . $rowNum, $student->nis);
                    $sheet->setCellValue('B' . $rowNum, $student->full_name);

                    if ($grades->has($student->id)) {
                        $grade = $grades->get($student->id);
                        $sheet->setCellValue('C' . $rowNum, $grade->assignment_grade);
                        $sheet->setCellValue('D' . $rowNum, $grade->uts_grade);
                        $sheet->setCellValue('E' . $rowNum, $grade->uas_grade);
                        $sheet->setCellValue('F' . $rowNum, $grade->attitude_grade);
                    }

                    $rowNum++;
                }
            }
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        $classroomName = $assignment->classroom ? $assignment->classroom->name : 'template_nilai';
        $fileName = 'template_import_nilai_' . str_replace([' ', '/'], '_', $classroomName) . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName);
    }
}

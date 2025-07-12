<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\ClassroomAssignment;
use App\Models\Semester;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activeSemester = Semester::where('is_active', true)->first();
        $activeYearId = $activeSemester?->academic_year_id;
        $assignments = ClassroomAssignment::with('classroom')
            ->where('academic_year_id', $activeYearId)
            ->get();
        $selectedAssignment = $request->assignment_id ?? $assignments->first()?->id;
        $perPage = 30;

        // Initialize default values
        $allStudents = collect();
        $yearlyGrades = [];
        $totalGanjil = [];
        $totalGenap = [];
        $totalYearly = [];
        $classStatistics = [
            'total_students' => 0,
            'lulus_semua' => 0,
            'perlu_perhatian' => 0,
            'total_mapel' => 0,
            'total_completed_subjects' => 0,
            'total_passed_subjects' => 0,
            'overall_pass_rate' => 0
        ];
        $studentStatistics = [];
        $grades = null;

        if ($selectedAssignment) {
            // Ambil semua siswa di kelas ini
            $studentIds = Grade::where('classroom_assignment_id', $selectedAssignment)
                ->where('academic_year_id', $activeYearId)
                ->pluck('student_id')
                ->unique()
                ->values();

            // Paginate siswa
            $paginatedStudentIds = $studentIds->slice((request('page', 1) - 1) * $perPage, $perPage);

            // Ambil semua nilai siswa yang sedang dipaging
            $gradesData = Grade::with(['student.user', 'subject', 'semester'])
                ->where('classroom_assignment_id', $selectedAssignment)
                ->where('academic_year_id', $activeYearId)
                ->whereIn('student_id', $paginatedStudentIds)
                ->get();

            // Group by student
            $gradesByStudent = $gradesData->groupBy('student_id');

            // Hitung nilai akhir tahun dan total semester per siswa
            foreach ($gradesByStudent as $studentId => $studentGrades) {
                $ganjil = $studentGrades->filter(fn($g) => $g->semester->name === 'Ganjil')->pluck('final_grade')->filter()->all();
                $genap = $studentGrades->filter(fn($g) => $g->semester->name === 'Genap')->pluck('final_grade')->filter()->all();
                $yearly = [];

                foreach ($studentGrades->pluck('subject_id')->unique() as $subjectId) {
                    $key = $studentId . '_' . $subjectId;
                    $yearlyGrades[$key] = \App\Models\Grade::calculateYearlyGradeForStudentSubject($studentId, $subjectId, $activeYearId);
                    if ($yearlyGrades[$key] !== null) $yearly[] = $yearlyGrades[$key];
                }

                $totalGanjil[$studentId] = count($ganjil) ? round(array_sum($ganjil) / count($ganjil), 2) : null;
                $totalGenap[$studentId] = count($genap) ? round(array_sum($genap) / count($genap), 2) : null;
                $totalYearly[$studentId] = count($yearly) ? round(array_sum($yearly) / count($yearly), 2) : null;

                // Hitung statistik per siswa
                $studentStatistics[$studentId] = $this->calculateStudentStatistics($studentGrades, $yearlyGrades, $studentId);
            }

            // Hitung statistik kelas
            $classStatistics = $this->calculateClassStatistics($gradesByStudent, $yearlyGrades);

            // Prepare students data for view
            $allStudents = $gradesByStudent->values();

            // Buat paginator manual
            $grades = new \Illuminate\Pagination\LengthAwarePaginator(
                $allStudents,
                $studentIds->count(),
                $perPage,
                request('page', 1),
                ['path' => url()->current(), 'query' => request()->query()]
            );
        }

        return view('admin.nilai', compact(
            'assignments',
            'selectedAssignment',
            'grades',
            'allStudents',
            'activeSemester',
            'yearlyGrades',
            'totalGanjil',
            'totalGenap',
            'totalYearly',
            'classStatistics',
            'studentStatistics'
        ));
    }

    /**
     * Calculate statistics for a single student
     */
    private function calculateStudentStatistics($studentGrades, $yearlyGrades, $studentId)
    {
        $subjects = $studentGrades->pluck('subject')->unique('id');
        $totalSubjects = $subjects->count();
        $completedSubjects = 0;
        $passedSubjects = 0;
        $failedSubjects = 0;

        foreach ($subjects as $subject) {
            $ganjilGrade = $studentGrades->where('subject_id', $subject->id)->where('semester.name', 'Ganjil')->first();
            $genapGrade = $studentGrades->where('subject_id', $subject->id)->where('semester.name', 'Genap')->first();
            $yearlyKey = $studentId . '_' . $subject->id;
            $yearlyGrade = $yearlyGrades[$yearlyKey] ?? null;

            // Hitung statistik
            if ($ganjilGrade?->final_grade !== null || $genapGrade?->final_grade !== null) {
                $completedSubjects++;
            }

            // Tentukan status berdasarkan nilai akhir tahun
            if ($yearlyGrade !== null) {
                $kkm = $ganjilGrade?->getKKM() ?? $genapGrade?->getKKM();
                if ($kkm !== null) {
                    if ($yearlyGrade >= $kkm) {
                        $passedSubjects++;
                    } else {
                        $failedSubjects++;
                    }
                }
            }
        }

        return [
            'total_subjects' => $totalSubjects,
            'completed_subjects' => $completedSubjects,
            'passed_subjects' => $passedSubjects,
            'failed_subjects' => $failedSubjects,
            'completion_rate' => $totalSubjects > 0 ? round(($completedSubjects / $totalSubjects) * 100, 1) : 0,
            'pass_rate' => $completedSubjects > 0 ? round(($passedSubjects / $completedSubjects) * 100, 1) : 0
        ];
    }

    /**
     * Calculate overall class statistics
     */
    private function calculateClassStatistics($gradesByStudent, $yearlyGrades)
    {
        $totalStudents = $gradesByStudent->count();
        $lulusSemua = 0;
        $totalMapel = 0;
        $totalCompletedSubjects = 0;
        $totalPassedSubjects = 0;

        foreach ($gradesByStudent as $studentId => $studentGrades) {
            $subjects = $studentGrades->pluck('subject')->unique('id');
            $totalMapel = max($totalMapel, $subjects->count());
            $semuaLulus = true;
            $adaNilai = false;

            foreach ($subjects as $subject) {
                $yearlyKey = $studentId . '_' . $subject->id;
                $yearlyGrade = $yearlyGrades[$yearlyKey] ?? null;

                if ($yearlyGrade !== null) {
                    $adaNilai = true;
                    $ganjilGrade = $studentGrades->where('subject_id', $subject->id)->where('semester.name', 'Ganjil')->first();
                    $kkm = $ganjilGrade?->getKKM();
                    if ($kkm !== null && $yearlyGrade < $kkm) {
                        $semuaLulus = false;
                    }

                    $totalCompletedSubjects++;
                    if ($yearlyGrade >= $kkm) {
                        $totalPassedSubjects++;
                    }
                }
            }

            if ($adaNilai && $semuaLulus) {
                $lulusSemua++;
            }
        }

        return [
            'total_students' => $totalStudents,
            'lulus_semua' => $lulusSemua,
            'perlu_perhatian' => $totalStudents - $lulusSemua,
            'total_mapel' => $totalMapel,
            'total_completed_subjects' => $totalCompletedSubjects,
            'total_passed_subjects' => $totalPassedSubjects,
            'overall_pass_rate' => $totalCompletedSubjects > 0 ? round(($totalPassedSubjects / $totalCompletedSubjects) * 100, 1) : 0
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        //
    }
}

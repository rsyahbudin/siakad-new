<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Semester;
use App\Models\ClassroomAssignment;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Menampilkan halaman siswa pindahan untuk admin
     */
    public function siswaPindahan(Request $request)
    {
        $activeSemester = Semester::where('is_active', true)->first();

        // Ambil semua kelas yang memiliki siswa pindahan
        $classrooms = Classroom::whereHas('students', function ($query) {
            $query->where('status', 'Pindahan');
        })->with(['students' => function ($query) {
            $query->where('status', 'Pindahan')->with('user');
        }])->get();

        // Filter berdasarkan kelas jika ada
        $selectedClassroomId = $request->get('classroom_id');
        $selectedClassroom = null;
        $students = collect();
        $subjects = collect();
        $grades = collect();

        if ($selectedClassroomId) {
            $selectedClassroom = Classroom::find($selectedClassroomId);
            if ($selectedClassroom) {
                $students = $selectedClassroom->students()
                    ->where('status', 'Pindahan')
                    ->with('user')
                    ->orderBy('full_name')
                    ->get();

                $subjects = Schedule::where('classroom_id', $selectedClassroom->id)
                    ->with('subject')
                    ->get()
                    ->pluck('subject')
                    ->unique('id')
                    ->sortBy('name');

                $grades = Grade::where('classroom_id', $selectedClassroom->id)
                    ->where('semester_id', $activeSemester->id)
                    ->where('source', 'konversi')
                    ->get()
                    ->groupBy(['student_id', 'subject_id']);
            }
        }

        return view('admin.siswa-pindahan', compact(
            'classrooms',
            'selectedClassroom',
            'students',
            'subjects',
            'grades',
            'activeSemester'
        ));
    }

    /**
     * Menyimpan nilai konversi siswa pindahan
     */
    public function storeKonversi(Request $request)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.subject_id' => 'required|exists:subjects,id',
            'grades.*.nilai' => 'required|numeric|min:0|max:100',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        $activeSemester = Semester::where('is_active', true)->first();
        $classroom = Classroom::findOrFail($request->classroom_id);

        DB::transaction(function () use ($request, $activeSemester, $classroom) {
            foreach ($request->grades as $data) {
                Grade::updateOrCreate(
                    [
                        'student_id' => $data['student_id'],
                        'subject_id' => $data['subject_id'],
                        'classroom_id' => $classroom->id,
                        'semester_id' => $activeSemester->id,
                        'source' => 'konversi',
                    ],
                    [
                        'final_grade' => $data['nilai'],
                        'is_passed' => $data['nilai'] >= 75,
                    ]
                );
            }
        });

        return redirect()->route('admin.siswa-pindahan', ['classroom_id' => $classroom->id])
            ->with('success', 'Nilai konversi berhasil disimpan.');
    }

    /**
     * Menampilkan daftar semua siswa pindahan
     */
    public function daftarSiswaPindahan()
    {
        $activeSemester = Semester::where('is_active', true)->first();

        $students = Student::where('status', 'Pindahan')
            ->with(['user', 'classStudents.classroomAssignment.classroom'])
            ->orderBy('full_name')
            ->get();

        // Kelompokkan siswa berdasarkan kelas
        $studentsByClass = $students->groupBy(function ($student) use ($activeSemester) {
            $currentAssignment = $student->classStudents()
                ->where('academic_year_id', $activeSemester->academic_year_id)
                ->first();
            return $currentAssignment ? $currentAssignment->classroomAssignment->classroom->name : 'Tidak Terdaftar';
        });

        return view('admin.daftar-siswa-pindahan', compact('studentsByClass', 'activeSemester'));
    }

    /**
     * Menampilkan detail siswa pindahan
     */
    public function detailSiswaPindahan($id)
    {
        $student = Student::with(['user', 'classStudents.classroomAssignment.classroom'])
            ->findOrFail($id);

        $activeSemester = Semester::where('is_active', true)->first();

        $currentAssignment = $student->classStudents()
            ->where('academic_year_id', $activeSemester->academic_year_id)
            ->first();

        $classroom = $currentAssignment ? $currentAssignment->classroomAssignment->classroom : null;

        $grades = collect();
        if ($classroom) {
            $grades = Grade::where('student_id', $student->id)
                ->where('classroom_id', $classroom->id)
                ->where('semester_id', $activeSemester->id)
                ->where('source', 'konversi')
                ->with('subject')
                ->get();
        }

        return view('admin.detail-siswa-pindahan', compact('student', 'classroom', 'grades', 'activeSemester'));
    }
}

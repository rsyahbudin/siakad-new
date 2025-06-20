<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\SubjectSetting;
use App\Models\Raport;
use App\Models\Student;

class SiswaRaportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;
        if (!$student) {
            // Handle case where user is not a student
            return redirect()->route('dashboard')->with('error', 'Hanya siswa yang dapat mengakses halaman ini.');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return view('siswa.raport-empty', ['message' => 'Tahun ajaran aktif belum ditentukan.']);
        }

        $classroom = $student->classrooms()->where('academic_year_id', $activeYear->id)->first();
        if (!$classroom) {
            return view('siswa.raport-empty', ['message' => 'Anda tidak terdaftar di kelas manapun pada tahun ajaran ini.']);
        }

        // Ambil data raport (jika sudah dibuat oleh wali kelas)
        $raport = Raport::where('student_id', $student->id)
            ->where('academic_year_id', $activeYear->id)
            ->first();

        // Ambil semua nilai dengan prioritas: input_guru > konversi
        $allGrades = Grade::with('subject')
            ->where('student_id', $student->id)
            ->where('academic_year_id', $activeYear->id)
            ->orderByRaw("FIELD(source, 'input_guru', 'konversi')")
            ->get();
        // Ambil satu nilai per mapel (prioritas input_guru)
        $grades = $allGrades->unique('subject_id')->values();

        // Ambil semua pengaturan KKM dan bobot
        $subjectSettings = SubjectSetting::where('academic_year_id', $activeYear->id)
            ->whereIn('subject_id', $grades->pluck('subject_id'))
            ->get()
            ->keyBy('subject_id');

        return view('siswa.raport', compact('student', 'activeYear', 'classroom', 'raport', 'grades', 'subjectSettings'));
    }
}

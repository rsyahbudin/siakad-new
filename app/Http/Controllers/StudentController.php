<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Classroom;
use App\Models\ClassroomAssignment;
use App\Models\ClassStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        $activeYearId = $activeSemester?->academic_year_id;
        $query = Student::with(['user', 'classStudents' => function ($q) use ($activeYearId) {
            $q->where('academic_year_id', $activeYearId)->with('classroomAssignment.classroom');
        }]);
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('full_name', 'like', "%$q%")
                    ->orWhere('nis', 'like', "%$q%")
                    ->orWhere('nisn', 'like', "%$q%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('email', 'like', "%$q%")
                            ->orWhere('name', 'like', "%$q%");
                    });
            });
        }
        $students = $query->orderByDesc('id')->get();
        return view('master.siswa.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classrooms = Classroom::orderBy('name')->get();
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        $activeYearId = $activeSemester?->academic_year_id;
        $assignments = ClassroomAssignment::where('academic_year_id', $activeYearId)->with('classroom')->get();
        return view('master.siswa.form', compact('classrooms', 'assignments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        $activeYearId = $activeSemester?->academic_year_id;
        $request->validate([
            'nis' => 'required|string|max:20|unique:students,nis',
            'nisn' => 'required|string|max:20|unique:students,nisn',
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'assignment_id' => 'required|exists:classroom_assignments,id',
            'status' => 'required|in:Aktif,Pindahan',
            'gender' => 'required|in:L,P',
            'birth_place' => 'required|string',
            'birth_date' => 'required|date',
            'religion' => 'required|string',
        ], [
            'status.required' => 'Status siswa wajib dipilih.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('password'), // Default password
            'role' => User::ROLE_STUDENT,
        ]);
        $student = Student::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'full_name' => $request->name,
            'gender' => $request->gender,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'religion' => $request->religion,
            'address' => $request->address,
            'parent_name' => $request->parent_name,
            'parent_phone' => $request->parent_phone,
            'phone_number' => $request->phone,
            'status' => $request->status,
        ]);
        ClassStudent::create([
            'classroom_assignment_id' => $request->assignment_id,
            'academic_year_id' => $activeYearId,
            'student_id' => $student->id,
        ]);
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $siswa)
    {
        $classrooms = Classroom::orderBy('name')->get();
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        $activeYearId = $activeSemester?->academic_year_id;
        $assignments = ClassroomAssignment::where('academic_year_id', $activeYearId)->with('classroom')->get();
        $siswa->load('user', 'classStudents.classroomAssignment.classroom');
        return view('master.siswa.form', ['siswa' => $siswa, 'classrooms' => $classrooms, 'assignments' => $assignments]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $siswa)
    {
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        $activeYearId = $activeSemester?->academic_year_id;
        $request->validate([
            'nis' => 'required|string|max:20|unique:students,nis,' . $siswa->id,
            'nisn' => 'required|string|max:20|unique:students,nisn,' . $siswa->id,
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $siswa->user_id,
            'assignment_id' => 'required|exists:classroom_assignments,id',
            'status' => 'required|in:Aktif,Pindahan',
            'gender' => 'required|in:L,P',
            'birth_place' => 'required|string',
            'birth_date' => 'required|date',
            'religion' => 'required|string',
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah digunakan.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.unique' => 'NISN sudah digunakan.',
            'name.required' => 'Nama siswa wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'assignment_id.required' => 'Kelas wajib dipilih.',
            'status.required' => 'Status siswa wajib dipilih.',
        ]);

        $siswa->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        $siswa->update([
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'full_name' => $request->name,
            'gender' => $request->gender,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'religion' => $request->religion,
            'address' => $request->address,
            'parent_name' => $request->parent_name,
            'parent_phone' => $request->parent_phone,
            'phone_number' => $request->phone,
            'status' => $request->status,
        ]);
        // Hapus penempatan lama di tahun ajaran aktif
        ClassStudent::where('student_id', $siswa->id)
            ->where('academic_year_id', $activeYearId)
            ->delete();
        // Assign baru
        ClassStudent::create([
            'classroom_assignment_id' => $request->assignment_id,
            'academic_year_id' => $activeYearId,
            'student_id' => $siswa->id,
        ]);
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $siswa)
    {
        $user = $siswa->user;
        ClassStudent::where('student_id', $siswa->id)->delete();
        $siswa->delete();
        if ($user) $user->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }

    /**
     * Show and edit profile for logged-in student
     */
    public function profilSiswa()
    {
        $user = auth()->user();
        $siswa = $user->student;
        return view('siswa.profil', compact('siswa'));
    }

    /**
     * Update profile for logged-in student
     */
    public function updateProfilSiswa(Request $request)
    {
        $user = auth()->user();
        $siswa = $user->student;
        $request->validate([
            'full_name' => 'required|string|max:100',
            'gender' => 'required|in:L,P',
            'birth_place' => 'required|string',
            'birth_date' => 'required|date',
            'religion' => 'required|string',
            'address' => 'nullable|string',
            'parent_name' => 'nullable|string',
            'parent_phone' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ]);
        $siswa->update($request->only([
            'full_name',
            'gender',
            'birth_place',
            'birth_date',
            'religion',
            'address',
            'parent_name',
            'parent_phone',
            'phone_number'
        ]));
        return redirect()->route('profil.siswa')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Change password for logged-in student
     */
    public function changePasswordSiswa(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = auth()->user();
        if (!\Hash::check($request->current_password, $user->password)) {
            return back()->with('password_error', 'Password lama salah.');
        }
        $user->password = bcrypt($request->password);
        $user->save();
        return back()->with('password_success', 'Password berhasil diubah.');
    }

    /**
     * Show weekly schedule for logged-in student
     */
    public function jadwalMingguanSiswa()
    {
        $user = auth()->user();
        $student = $user->student;
        $classroom = $student->classrooms()->latest('id')->first();
        $weeklySchedules = [];
        if ($classroom) {
            $allSchedules = \App\Models\Schedule::with(['subject', 'teacher'])
                ->where('classroom_id', $classroom->id)
                ->orderBy('day')
                ->orderBy('time_start')
                ->get();
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            foreach ($days as $day) {
                $weeklySchedules[$day] = $allSchedules->where('day', $day)->values();
            }
        }
        return view('siswa.jadwal', compact('weeklySchedules'));
    }

    /**
     * Show academic grades for logged-in student (semester berjalan)
     */
    public function nilaiAkademikSiswa()
    {
        $user = auth()->user();
        $student = $user->student;
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $grades = collect();
        $subjectSettings = [];
        if ($activeYear) {
            $grades = \App\Models\Grade::with('subject')
                ->where('student_id', $student->id)
                ->where('academic_year_id', $activeYear->id)
                ->get();
            $subjectSettings = \App\Models\SubjectSetting::where('academic_year_id', $activeYear->id)
                ->get()
                ->keyBy('subject_id');
        }
        return view('siswa.nilai', compact('grades', 'subjectSettings'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $query = \App\Models\Student::with(['user', 'classrooms' => function ($q) use ($activeYear) {
            $q->where('academic_year_id', $activeYear?->id);
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
        return view('master.siswa.form', compact('classrooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|max:20|unique:students,nis',
            'nisn' => 'required|string|max:20|unique:students,nisn',
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'classroom_id' => 'required|exists:classrooms,id',
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
        $student->classrooms()->attach($request->classroom_id);
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
        $siswa->load('user', 'classrooms');
        return view('master.siswa.form', ['siswa' => $siswa, 'classrooms' => $classrooms]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $siswa)
    {
        $request->validate([
            'nis' => 'required|string|max:20|unique:students,nis,' . $siswa->id,
            'nisn' => 'required|string|max:20|unique:students,nisn,' . $siswa->id,
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $siswa->user_id,
            'classroom_id' => 'required|exists:classrooms,id',
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
            'classroom_id.required' => 'Kelas wajib dipilih.',
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
        $siswa->classrooms()->sync([$request->classroom_id]);
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $siswa)
    {
        $user = $siswa->user;
        $siswa->classrooms()->detach();
        $siswa->delete();
        if ($user) $user->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }
}

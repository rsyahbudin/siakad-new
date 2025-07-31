<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Teacher::with(['user', 'subject']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('full_name', 'like', "%$q%")
                    ->orWhere('nip', 'like', "%$q%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('email', 'like', "%$q%")
                            ->orWhere('name', 'like', "%$q%");
                    });
            });
        }

        $teachers = $query->orderBy('full_name')->paginate(12);

        return view('master.guru.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('master.guru.form', compact('subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|max:30|unique:teachers,nip',
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'degree' => 'nullable|string|max:20',
            'major' => 'nullable|string|max:100',
            'university' => 'nullable|string|max:100',
            'graduation_year' => 'nullable|integer|min:1950|max:' . (date('Y') + 5),
            'subject_id' => 'required|exists:subjects,id',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah digunakan.',
            'full_name.required' => 'Nama guru wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'subject_id.exists' => 'Mata pelajaran tidak valid.',
            'graduation_year.integer' => 'Tahun lulus harus berupa angka.',
            'graduation_year.min' => 'Tahun lulus minimal 1950.',
            'graduation_year.max' => 'Tahun lulus tidak boleh lebih dari ' . (date('Y') + 5) . '.',
        ]);

        // Buat user baru untuk guru
        $user = \App\Models\User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'password' => bcrypt('password'), // Default password
            'role' => 'teacher',
        ]);

        // Buat profil guru
        Teacher::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'full_name' => $request->full_name,
            'phone_number' => $request->phone,
            'address' => $request->address,
            'degree' => $request->degree,
            'major' => $request->major,
            'university' => $request->university,
            'graduation_year' => $request->graduation_year,
            'subject_id' => $request->subject_id,
        ]);
        return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $guru)
    {
        $subjects = Subject::orderBy('name')->get();
        return view('master.guru.form', ['guru' => $guru, 'subjects' => $subjects]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $guru)
    {
        $request->validate([
            'nip' => 'required|string|max:30|unique:teachers,nip,' . $guru->id,
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $guru->user_id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'degree' => 'nullable|string|max:20',
            'major' => 'nullable|string|max:100',
            'university' => 'nullable|string|max:100',
            'graduation_year' => 'nullable|integer|min:1950|max:' . (date('Y') + 5),
            'subject_id' => 'required|exists:subjects,id',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah digunakan.',
            'full_name.required' => 'Nama guru wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'subject_id.exists' => 'Mata pelajaran tidak valid.',
            'graduation_year.integer' => 'Tahun lulus harus berupa angka.',
            'graduation_year.min' => 'Tahun lulus minimal 1950.',
            'graduation_year.max' => 'Tahun lulus tidak boleh lebih dari ' . (date('Y') + 5) . '.',
        ]);

        // Update email ke tabel users
        $guru->user->update(['email' => $request->email, 'name' => $request->full_name]);

        // Update profil guru
        $guru->update([
            'nip' => $request->nip,
            'full_name' => $request->full_name,
            'phone_number' => $request->phone,
            'address' => $request->address,
            'degree' => $request->degree,
            'major' => $request->major,
            'university' => $request->university,
            'graduation_year' => $request->graduation_year,
            'subject_id' => $request->subject_id,
        ]);
        return redirect()->route('guru.index')->with('success', 'Guru berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $guru)
    {
        $guru->delete();
        return redirect()->route('guru.index')->with('success', 'Guru berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Major;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subject::with('major');

        // Search functionality
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhereHas('major', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('short_name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by major
        if ($request->filled('major_id')) {
            $query->where('major_id', $request->major_id);
        }

        $subjects = $query->orderBy('name')->paginate(12);
        $majors = Major::orderBy('short_name')->get();

        return view('master.mapel.index', compact('subjects', 'majors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $majors = Major::orderBy('short_name')->get();
        return view('master.mapel.form', compact('majors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:subjects,code',
            'major_id' => 'nullable|exists:majors,id',
        ], [
            'name.required' => 'Nama mata pelajaran wajib diisi.',
            'code.required' => 'Kode mapel wajib diisi.',
            'code.unique' => 'Kode mapel sudah digunakan.',
        ]);

        Subject::create($request->only('name', 'code', 'major_id'));
        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $mapel)
    {
        $majors = Major::orderBy('short_name')->get();
        return view('master.mapel.form', ['mapel' => $mapel, 'majors' => $majors]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $mapel)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:subjects,code,' . $mapel->id,
            'major_id' => 'nullable|exists:majors,id',
        ], [
            'name.required' => 'Nama mata pelajaran wajib diisi.',
            'code.required' => 'Kode mapel wajib diisi.',
            'code.unique' => 'Kode mapel sudah digunakan.',
        ]);

        $mapel->update($request->only('name', 'code', 'major_id'));
        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil diupdate.');
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy(Subject $mapel)
    {
        $mapel->delete();
        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}

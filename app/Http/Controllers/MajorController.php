<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $majors = Major::orderBy('short_name')->get();
        return view('master.jurusan.index', compact('majors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.jurusan.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'short_name' => 'required|string|max:10|unique:majors,short_name',
        ], [
            'name.required' => 'Nama jurusan wajib diisi.',
            'short_name.required' => 'Singkatan jurusan wajib diisi.',
            'short_name.unique' => 'Singkatan jurusan sudah digunakan.',
        ]);

        Major::create($request->only('name', 'short_name'));
        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Major $jurusan)
    {
        return view('master.jurusan.form', ['jurusan' => $jurusan]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Major $jurusan)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'short_name' => 'required|string|max:10|unique:majors,short_name,' . $jurusan->id,
        ], [
            'name.required' => 'Nama jurusan wajib diisi.',
            'short_name.required' => 'Singkatan jurusan wajib diisi.',
            'short_name.unique' => 'Singkatan jurusan sudah digunakan.',
        ]);

        $jurusan->update($request->only('name', 'short_name'));
        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Major $jurusan)
    {
        $jurusan->delete();
        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil dihapus.');
    }
}

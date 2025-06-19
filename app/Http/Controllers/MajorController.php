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
        // Ambil data jurusan dengan paginasi
        $majors = Major::latest()->paginate(10);

        // Kirim data ke komponen React sebagai 'props'
        return Inertia::render('Admin/Majors/Index', [
            'majors' => $majors,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Hanya tampilkan komponen form kosong
        return Inertia::render('Admin/Majors/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:majors,name',
            'short_name' => 'required|string|max:10',
        ]);

        Major::create($validated);

        return redirect()->route('majors.index')->with('success', 'Jurusan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Major $major)
    {
        // Tampilkan komponen form dan kirim data 'major' yang akan diedit
        return Inertia::render('Admin/Majors/Edit', [
            'major' => $major,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Major $major)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:majors,name,' . $major->id,
            'short_name' => 'required|string|max:10',
        ]);

        $major->update($validated);

        return redirect()->route('majors.index')->with('success', 'Jurusan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Major $major)
    {
        $major->delete();
        return redirect()->route('majors.index')->with('success', 'Jurusan berhasil dihapus.');
    }
}

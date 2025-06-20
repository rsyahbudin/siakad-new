<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $years = AcademicYear::orderByDesc('year')->get();
        return view('master.tahun-ajaran.index', compact('years'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.tahun-ajaran.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $academicYear = AcademicYear::create([
            'year' => $request->year,
            'is_active' => $request->has('is_active'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        // Otomatis buat dua semester
        $academicYear->semesters()->createMany([
            [
                'name' => 'Ganjil',
                'is_active' => $request->semester == 1,
                'start_date' => $request->start_date,
                'end_date' => null,
            ],
            [
                'name' => 'Genap',
                'is_active' => $request->semester == 2,
                'start_date' => null,
                'end_date' => $request->end_date,
            ],
        ]);

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicYear $academicYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $tahun_ajaran)
    {
        return view('master.tahun-ajaran.form', ['tahunAjaran' => $tahun_ajaran]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicYear $tahun_ajaran)
    {
        $tahun_ajaran->update([
            'year' => $request->year,
            'is_active' => $request->has('is_active'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $tahun_ajaran)
    {
        $tahun_ajaran->delete();
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    public function setActive(AcademicYear $tahun_ajaran)
    {
        // Nonaktifkan semua tahun ajaran lain
        AcademicYear::where('id', '!=', $tahun_ajaran->id)->update(['is_active' => false]);
        // Aktifkan tahun ajaran ini
        $tahun_ajaran->is_active = true;
        $tahun_ajaran->save();
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil diaktifkan.');
    }
}

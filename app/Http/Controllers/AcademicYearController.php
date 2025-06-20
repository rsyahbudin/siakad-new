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
        $years = AcademicYear::orderByDesc('year')->orderByDesc('semester')->get();
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
        $request->validate([
            'year' => 'required|string|max:9',
            'semester' => 'required|in:1,2',
            'is_active' => 'nullable|boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'year.required' => 'Tahun ajaran wajib diisi.',
            'semester.required' => 'Semester wajib dipilih.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.required' => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ]);

        AcademicYear::create([
            'year' => $request->year,
            'semester' => $request->semester,
            'is_active' => $request->has('is_active'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
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
        $request->validate([
            'year' => 'required|string|max:9',
            'semester' => 'required|in:1,2',
            'is_active' => 'nullable|boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'year.required' => 'Tahun ajaran wajib diisi.',
            'semester.required' => 'Semester wajib dipilih.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.required' => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ]);

        $tahun_ajaran->update([
            'year' => $request->year,
            'semester' => $request->semester,
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

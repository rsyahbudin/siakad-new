<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function setActive(Request $request, Semester $semester)
    {
        // Nonaktifkan semua tahun ajaran lain
        AcademicYear::where('id', '!=', $semester->academic_year_id)->update(['is_active' => false]);
        // Aktifkan tahun ajaran semester yang dipilih
        $semester->academicYear->is_active = true;
        $semester->academicYear->save();
        // Nonaktifkan semua semester di semua tahun ajaran
        Semester::where('id', '!=', $semester->id)->update(['is_active' => false]);
        // Aktifkan semester yang dipilih
        $semester->is_active = true;
        $semester->save();
        return redirect()->route('tahun-ajaran.index')->with('success', 'Semester ' . $semester->name . ' pada tahun ajaran ' . $semester->academicYear->year . ' berhasil diaktifkan.');
    }
}

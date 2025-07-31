<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\Classroom;
use Illuminate\Http\Request;

class PenugasanGuruController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kelas untuk filter
        $classrooms = Classroom::orderBy('name')->get();

        // Query dasar
        $query = Schedule::with(['teacher', 'subject', 'classroom']);

        // Filter berdasarkan kelas jika ada
        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        // Filter berdasarkan guru jika ada
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter berdasarkan mata pelajaran jika ada
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter berdasarkan hari jika ada
        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }

        // Ambil semua penugasan jadwal, join guru, mapel, kelas
        $assignments = $query->orderBy('teacher_id')
            ->orderBy('day')
            ->orderBy('time_start')
            ->get();

        return view('admin.penugasan-guru', compact('assignments', 'classrooms'));
    }
}

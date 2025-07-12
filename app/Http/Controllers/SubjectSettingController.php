<?php

namespace App\Http\Controllers;

use App\Models\SubjectSetting;
use App\Models\SemesterWeight;
use Illuminate\Http\Request;

class SubjectSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        $activeYear = $activeSemester?->academicYear;
        $subjects = \App\Models\Subject::orderBy('name')->get();

        // Get settings for active semester
        $settings = \App\Models\SubjectSetting::where('academic_year_id', $activeYear?->id)
            ->get()
            ->keyBy('subject_id');

        // Get semester weights
        $semesterWeights = SemesterWeight::where('academic_year_id', $activeYear?->id)
            ->where('is_active', true)
            ->first();

        return view('admin.pengaturan-kkm', compact('subjects', 'settings', 'activeYear', 'activeSemester', 'semesterWeights'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SubjectSetting $subjectSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubjectSetting $subjectSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        $activeYear = $activeSemester?->academicYear;

        // Hanya bisa diubah pada semester Ganjil
        if (!$activeSemester || $activeSemester->name !== 'Ganjil') {
            return back()->with('error', 'Pengaturan hanya dapat diubah pada semester Ganjil.');
        }

        // Cek jika sudah pernah disimpan untuk tahun ajaran+semester ganjil
        $existing = \App\Models\SubjectSetting::where('academic_year_id', $activeYear->id)
            ->exists();
        if ($existing) {
            return back()->with('error', 'Pengaturan sudah pernah disimpan untuk tahun ajaran dan semester ini. Tidak dapat diubah lagi.');
        }

        if (!$activeSemester || !$activeYear) {
            return back()->with('error', 'Tidak ada semester atau tahun ajaran aktif.');
        }

        $data = $request->input('settings', []);
        $errors = [];

        foreach ($data as $subject_id => $row) {
            $kkm = (int)($row['kkm'] ?? 0);
            $tugas = (int)($row['assignment_weight'] ?? 0);
            $uts = (int)($row['uts_weight'] ?? 0);
            $uas = (int)($row['uas_weight'] ?? 0);

            if ($kkm < 1) {
                $errors[] = "KKM untuk mapel ID $subject_id wajib diisi.";
            }
            if ($tugas + $uts + $uas !== 100) {
                $errors[] = "Total bobot untuk mapel ID $subject_id harus 100%.";
            }
        }

        if ($errors) {
            return back()->with('error', implode(' ', $errors));
        }

        foreach ($data as $subject_id => $row) {
            \App\Models\SubjectSetting::updateOrCreate(
                [
                    'subject_id' => $subject_id,
                    'academic_year_id' => $activeYear->id,
                ],
                [
                    'kkm' => $row['kkm'],
                    'assignment_weight' => $row['assignment_weight'],
                    'uts_weight' => $row['uts_weight'],
                    'uas_weight' => $row['uas_weight'],
                ]
            );
        }

        return back()->with('success', 'Pengaturan KKM & bobot berhasil disimpan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubjectSetting $subjectSetting)
    {
        //
    }

    public function updateFailedSubjects(Request $request)
    {
        $request->validate([
            'max_failed_subjects' => 'required|integer|min:0|max:20',
        ], [
            'max_failed_subjects.required' => 'Batas maksimal mapel gagal wajib diisi.',
            'max_failed_subjects.integer' => 'Harus berupa angka.',
            'max_failed_subjects.min' => 'Minimal 0.',
            'max_failed_subjects.max' => 'Maksimal 20.',
        ]);
        $value = (int) $request->max_failed_subjects;
        \App\Models\AppSetting::setValue('max_failed_subjects', $value, 'Batas maksimal mapel gagal agar naik/lulus');
        return back()->with('success_failed_subjects', 'Batas maksimal mapel gagal berhasil diubah. Silakan reload halaman jika belum berubah.');
    }

    /**
     * Update semester weights for yearly grade calculation
     */
    public function updateSemesterWeights(Request $request)
    {
        $request->validate([
            'ganjil_weight' => 'required|integer|min:0|max:100',
            'genap_weight' => 'required|integer|min:0|max:100',
        ], [
            'ganjil_weight.required' => 'Bobot semester ganjil wajib diisi.',
            'ganjil_weight.integer' => 'Bobot semester ganjil harus berupa bilangan bulat.',
            'ganjil_weight.min' => 'Bobot semester ganjil minimal 0%.',
            'ganjil_weight.max' => 'Bobot semester ganjil maksimal 100%.',
            'genap_weight.required' => 'Bobot semester genap wajib diisi.',
            'genap_weight.integer' => 'Bobot semester genap harus berupa bilangan bulat.',
            'genap_weight.min' => 'Bobot semester genap minimal 0%.',
            'genap_weight.max' => 'Bobot semester genap maksimal 100%.',
        ]);

        $ganjilWeight = (int) $request->ganjil_weight;
        $genapWeight = (int) $request->genap_weight;

        if (($ganjilWeight + $genapWeight) !== 100) {
            return back()->with('error_semester_weights', 'Total bobot semester ganjil dan genap harus tepat 100%.');
        }

        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        $activeYear = $activeSemester?->academicYear;

        if (!$activeYear) {
            return back()->with('error_semester_weights', 'Tidak ada tahun ajaran aktif.');
        }

        SemesterWeight::updateOrCreate(
            [
                'academic_year_id' => $activeYear->id,
            ],
            [
                'ganjil_weight' => $ganjilWeight,
                'genap_weight' => $genapWeight,
                'is_active' => true,
            ]
        );

        return back()->with('success_semester_weights', 'Bobot semester berhasil disimpan.');
    }
}

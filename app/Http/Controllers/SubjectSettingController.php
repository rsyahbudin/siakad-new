<?php

namespace App\Http\Controllers;

use App\Models\SubjectSetting;
use Illuminate\Http\Request;

class SubjectSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $subjects = \App\Models\Subject::orderBy('name')->get();
        $settings = \App\Models\SubjectSetting::where('academic_year_id', $activeYear?->id)->get()->keyBy('subject_id');
        return view('admin.pengaturan-kkm', compact('subjects', 'settings', 'activeYear'));
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
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
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
                    'academic_year_id' => $activeYear?->id,
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
        // Update config/siakad.php secara dinamis
        $configPath = config_path('siakad.php');
        $config = file_get_contents($configPath);
        $config = preg_replace(
            "/('max_failed_subjects'\s*=>\s*)env\('SIAKAD_MAX_FAILED_SUBJECTS',\s*\d+\)/",
            "'max_failed_subjects' => env('SIAKAD_MAX_FAILED_SUBJECTS', $value)",
            $config
        );
        file_put_contents($configPath, $config);
        // Update juga .env jika ada akses
        // (Abaikan jika tidak bisa tulis .env)
        try {
            $envPath = base_path('.env');
            if (is_writable($envPath)) {
                $env = file_get_contents($envPath);
                if (preg_match('/^SIAKAD_MAX_FAILED_SUBJECTS=.*/m', $env)) {
                    $env = preg_replace('/^SIAKAD_MAX_FAILED_SUBJECTS=.*/m', "SIAKAD_MAX_FAILED_SUBJECTS=$value", $env);
                } else {
                    $env .= "\nSIAKAD_MAX_FAILED_SUBJECTS=$value\n";
                }
                file_put_contents($envPath, $env);
            }
        } catch (\Exception $e) {
        }
        return back()->with('success_failed_subjects', 'Batas maksimal mapel gagal berhasil diubah. Silakan reload halaman jika belum berubah.');
    }
}

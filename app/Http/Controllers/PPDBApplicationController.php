<?php

namespace App\Http\Controllers;

use App\Models\PPDBApplication;
use App\Models\User;
use App\Models\Student;
use App\Models\WaliMurid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PPDBApplicationController extends Controller
{
    /**
     * Show the public PPDB registration form
     */
    public function showRegistrationForm()
    {
        return view('ppdb.registration');
    }

    /**
     * Handle PPDB registration submission
     */
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'nisn' => 'required|string|unique:ppdb_applications,nisn|size:10|regex:/^[0-9]+$/',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'religion' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'required|email|max:255',
            'parent_occupation' => 'nullable|string|max:255',
            'parent_address' => 'nullable|string',
            'entry_path' => 'required|in:tes,prestasi,afirmasi',
            'desired_major' => 'required|in:IPA,IPS',
            'raport_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo_file' => 'required|file|mimes:jpg,jpeg,png|max:1024',
            'family_card_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'achievement_certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'financial_document_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'average_raport_score' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            // Handle file uploads
            $raportFile = $request->file('raport_file')->store('ppdb/raport', 'public');
            $photoFile = $request->file('photo_file')->store('ppdb/photo', 'public');
            $familyCardFile = $request->file('family_card_file')->store('ppdb/family_card', 'public');

            $achievementCertificateFile = null;
            if ($request->hasFile('achievement_certificate_file')) {
                $achievementCertificateFile = $request->file('achievement_certificate_file')->store('ppdb/achievement', 'public');
            }

            $financialDocumentFile = null;
            if ($request->hasFile('financial_document_file')) {
                $financialDocumentFile = $request->file('financial_document_file')->store('ppdb/financial', 'public');
            }

            // Create PPDB application
            $application = PPDBApplication::create([
                'full_name' => $request->full_name,
                'nisn' => $request->nisn,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'religion' => $request->religion,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'address' => $request->address,
                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_phone,
                'parent_email' => $request->parent_email,
                'parent_occupation' => $request->parent_occupation,
                'parent_address' => $request->parent_address,
                'entry_path' => $request->entry_path,
                'desired_major' => $request->desired_major,
                'raport_file' => $raportFile,
                'photo_file' => $photoFile,
                'family_card_file' => $familyCardFile,
                'achievement_certificate_file' => $achievementCertificateFile,
                'financial_document_file' => $financialDocumentFile,
                'average_raport_score' => $request->average_raport_score,
                'submitted_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('ppdb.success', ['application_number' => $application->application_number])
                ->with('success', 'Pendaftaran PPDB berhasil! Nomor pendaftaran Anda: ' . $application->application_number);
        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded files if any
            if (isset($raportFile)) Storage::disk('public')->delete($raportFile);
            if (isset($photoFile)) Storage::disk('public')->delete($photoFile);
            if (isset($familyCardFile)) Storage::disk('public')->delete($familyCardFile);
            if (isset($achievementCertificateFile)) Storage::disk('public')->delete($achievementCertificateFile);
            if (isset($financialDocumentFile)) Storage::disk('public')->delete($financialDocumentFile);

            return back()->withErrors(['error' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.']);
        }
    }

    /**
     * Show success page after registration
     */
    public function showSuccess(Request $request)
    {
        $applicationNumber = $request->application_number;
        $application = PPDBApplication::where('application_number', $applicationNumber)->first();

        if (!$application) {
            return redirect()->route('ppdb.register');
        }

        return view('ppdb.success', compact('application'));
    }

    /**
     * Show admin dashboard for PPDB management
     */
    public function adminIndex(Request $request)
    {
        $query = PPDBApplication::with('processedBy');

        // Filter by entry path
        if ($request->filled('entry_path')) {
            $query->byEntryPath($request->entry_path);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by desired major
        if ($request->filled('desired_major')) {
            $query->byDesiredMajor($request->desired_major);
        }

        // Search by name or NISN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('application_number', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->paginate(15);

        return view('admin.ppdb.index', compact('applications'));
    }

    /**
     * Show application detail for admin
     */
    public function adminShow(PPDBApplication $application)
    {
        return view('admin.ppdb.show', compact('application'));
    }

    /**
     * Update application status and test score
     */
    /**
     * Update test score only
     */
    public function updateTestScore(Request $request, PPDBApplication $application)
    {
        $request->validate([
            'test_score' => 'required|numeric|min:0|max:100',
        ]);

        try {
            $application->update([
                'test_score' => $request->test_score,
            ]);

            return back()->with('success', 'Nilai tes berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui nilai tes.']);
        }
    }

    /**
     * Show batch test score input form
     */
    public function showBatchTestScore()
    {
        $applications = PPDBApplication::where('entry_path', 'tes')
            ->where('status', 'pending')
            ->whereNull('test_score')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.ppdb.batch-test-score', compact('applications'));
    }

    /**
     * Update batch test scores
     */
    public function updateBatchTestScore(Request $request)
    {
        $request->validate([
            'test_scores' => 'required|array',
            'test_scores.*' => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->test_scores as $applicationId => $testScore) {
                $application = PPDBApplication::find($applicationId);
                if ($application && $application->entry_path === 'tes') {
                    $application->update([
                        'test_score' => $testScore,
                    ]);
                }
            }

            DB::commit();

            return back()->with('success', 'Nilai tes berhasil diperbarui untuk ' . count($request->test_scores) . ' pendaftar.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui nilai tes.']);
        }
    }

    /**
     * Update application status
     */
    public function adminUpdate(Request $request, PPDBApplication $application)
    {
        $request->validate([
            'status' => 'required|in:pending,lulus,ditolak',
            'notes' => 'nullable|string',
        ]);

        // Check if test score is required for tes entry path
        if ($application->entry_path === 'tes' && !$application->test_score) {
            return back()->withErrors(['error' => 'Untuk jalur tes, nilai tes harus diinput terlebih dahulu sebelum mengubah status.']);
        }

        try {
            DB::beginTransaction();

            $application->update([
                'status' => $request->status,
                'notes' => $request->notes,
                'processed_at' => now(),
                'processed_by' => auth()->id(),
            ]);

            // If status is "lulus", create user account and student record
            if ($request->status === 'lulus') {
                try {
                    $this->createStudentAccount($application);
                    \Log::info('Account creation successful for application: ' . $application->id);
                } catch (\Exception $e) {
                    \Log::error('Account creation failed for application: ' . $application->id . ' - ' . $e->getMessage());
                    throw $e;
                }
            }

            DB::commit();

            return back()->with('success', 'Status pendaftaran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui status.']);
        }
    }

    /**
     * Create student account and related records when application is approved
     */
    private function createStudentAccount(PPDBApplication $application)
    {
        Log::info('Starting account creation for application: ' . $application->id);

        // Check if user already exists
        $existingUser = User::where('email', $application->email)->first();
        if ($existingUser) {
            Log::info('User already exists for email: ' . $application->email);
            return; // User already exists
        }

        // Create user account
        $user = User::create([
            'name' => $application->full_name,
            'email' => $application->email,
            'password' => Hash::make('student123'), // Default password
            'role' => User::ROLE_STUDENT,
        ]);

        // Create student record
        $student = Student::create([
            'user_id' => $user->id,
            'nis' => $application->nisn, // Using NISN as NIS for now
            'nisn' => $application->nisn,
            'full_name' => $application->full_name,
            'gender' => $application->gender,
            'birth_place' => $application->birth_place,
            'birth_date' => $application->birth_date,
            'religion' => $application->religion,
            'phone_number' => $application->phone_number,
            'address' => $application->address,
            'parent_name' => $application->parent_name,
            'parent_phone' => $application->parent_phone,
            'status' => 'Aktif',
        ]);

        // Create wali murid account
        $waliMuridUser = User::create([
            'name' => $application->parent_name,
            'email' => $application->parent_email,
            'password' => Hash::make('wali123'), // Default password
            'role' => User::ROLE_WALI_MURID,
        ]);

        // Create wali murid record
        WaliMurid::create([
            'user_id' => $waliMuridUser->id,
            'student_id' => $student->id,
            'full_name' => $application->parent_name,
            'phone_number' => $application->parent_phone,
            'address' => $application->parent_address ?? $application->address,
            'relationship' => 'Orang Tua',
        ]);
    }

    /**
     * Download application document
     */
    public function downloadDocument(PPDBApplication $application, $documentType)
    {
        $documentField = $documentType . '_file';

        if (!isset($application->$documentField)) {
            return back()->withErrors(['error' => 'Dokumen tidak ditemukan.']);
        }

        $filePath = $application->$documentField;

        if (!Storage::disk('public')->exists($filePath)) {
            return back()->withErrors(['error' => 'File tidak ditemukan.']);
        }

        // Get file extension to determine if it's an image
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);

        if ($isImage) {
            // For images, return the file for preview/download
            return response()->file(storage_path('app/public/' . $filePath));
        } else {
            // For other files, force download
            return Storage::disk('public')->download($filePath);
        }
    }

    /**
     * Check application status (public)
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'application_number' => 'required|string',
            'nisn' => 'required|string',
        ]);

        $application = PPDBApplication::where('application_number', $request->application_number)
            ->where('nisn', $request->nisn)
            ->first();

        if (!$application) {
            return back()->withErrors(['error' => 'Data pendaftaran tidak ditemukan.']);
        }

        return view('ppdb.status', compact('application'));
    }

    /**
     * Show status check form
     */
    public function showStatusCheck()
    {
        return view('ppdb.status-check');
    }
}

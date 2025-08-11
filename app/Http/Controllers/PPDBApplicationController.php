<?php

namespace App\Http\Controllers;

use App\Models\PPDBApplication;
use App\Models\User;
use App\Models\Student;
use App\Models\WaliMurid;
use App\Services\NISGeneratorService;
use App\Services\ClassPlacementService;
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
        // Log the request for debugging
        Log::info('PPDB Registration Request', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'has_files' => $request->hasFile('raport_file') && $request->hasFile('photo_file') && $request->hasFile('family_card_file'),
            'entry_path' => $request->entry_path,
            'desired_major' => $request->desired_major,
        ]);

        try {
            $request->validate([
                'full_name' => 'required|string|max:100',
                'nisn' => 'required|string|unique:ppdb_applications,nisn|size:10|regex:/^[0-9]+$/',
                'birth_place' => 'required|string|max:50',
                'birth_date' => 'required|date|before:today',
                'gender' => 'required|in:L,P',
                'religion' => 'required|string|max:20|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
                'phone_number' => 'required|string|max:20',
                'email' => 'required|email|max:100|unique:ppdb_applications,email',
                'address' => 'required|string|max:200',
                'parent_name' => 'required|string|max:100',
                'parent_phone' => 'required|string|max:20',
                'parent_email' => 'required|email|max:100|unique:ppdb_applications,parent_email',
                'parent_occupation' => 'nullable|string|max:50',
                'parent_address' => 'nullable|string|max:200',
                'entry_path' => 'required|in:tes,prestasi,afirmasi',
                'desired_major' => 'required|in:IPA,IPS',
                'raport_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'photo_file' => 'required|file|mimes:jpg,jpeg,png|max:1024',
                'family_card_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'achievement_certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'financial_document_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'average_raport_score' => 'nullable|numeric|min:0|max:100',
            ], [
                'full_name.required' => 'Nama lengkap wajib diisi',
                'full_name.max' => 'Nama lengkap maksimal 100 karakter',
                'nisn.required' => 'NISN wajib diisi',
                'nisn.size' => 'NISN harus tepat 10 digit',
                'nisn.regex' => 'NISN hanya boleh berisi angka',
                'nisn.unique' => 'NISN sudah terdaftar sebelumnya',
                'birth_place.required' => 'Tempat lahir wajib diisi',
                'birth_place.max' => 'Tempat lahir maksimal 50 karakter',
                'birth_date.required' => 'Tanggal lahir wajib diisi',
                'birth_date.before' => 'Tanggal lahir tidak boleh di masa depan',
                'gender.required' => 'Jenis kelamin wajib dipilih',
                'gender.in' => 'Jenis kelamin tidak valid',
                'religion.required' => 'Agama wajib diisi',
                'religion.max' => 'Agama maksimal 20 karakter',
                'religion.in' => 'Agama tidak valid',
                'phone_number.required' => 'Nomor telepon wajib diisi',
                'phone_number.max' => 'Nomor telepon maksimal 20 karakter',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.max' => 'Email maksimal 100 karakter',
                'email.unique' => 'Email sudah terdaftar sebelumnya',
                'address.required' => 'Alamat wajib diisi',
                'address.max' => 'Alamat maksimal 200 karakter',
                'parent_name.required' => 'Nama orang tua wajib diisi',
                'parent_name.max' => 'Nama orang tua maksimal 100 karakter',
                'parent_phone.required' => 'Nomor telepon orang tua wajib diisi',
                'parent_phone.max' => 'Nomor telepon orang tua maksimal 20 karakter',
                'parent_email.required' => 'Email orang tua wajib diisi',
                'parent_email.email' => 'Format email orang tua tidak valid',
                'parent_email.max' => 'Email orang tua maksimal 100 karakter',
                'parent_email.unique' => 'Email orang tua sudah terdaftar sebelumnya',
                'parent_occupation.max' => 'Pekerjaan orang tua maksimal 50 karakter',
                'parent_address.max' => 'Alamat orang tua maksimal 200 karakter',
                'entry_path.required' => 'Jalur pendaftaran wajib dipilih',
                'entry_path.in' => 'Jalur pendaftaran tidak valid',
                'desired_major.required' => 'Jurusan wajib dipilih',
                'desired_major.in' => 'Jurusan tidak valid',
                'raport_file.required' => 'File rapor wajib diupload',
                'raport_file.file' => 'File rapor tidak valid',
                'raport_file.mimes' => 'File rapor harus berformat PDF, JPG, JPEG, atau PNG',
                'raport_file.max' => 'File rapor maksimal 2MB',
                'photo_file.required' => 'Pas foto wajib diupload',
                'photo_file.file' => 'Pas foto tidak valid',
                'photo_file.mimes' => 'Pas foto harus berformat JPG, JPEG, atau PNG',
                'photo_file.max' => 'Pas foto maksimal 1MB',
                'family_card_file.required' => 'File kartu keluarga wajib diupload',
                'family_card_file.file' => 'File kartu keluarga tidak valid',
                'family_card_file.mimes' => 'File kartu keluarga harus berformat PDF, JPG, JPEG, atau PNG',
                'family_card_file.max' => 'File kartu keluarga maksimal 2MB',
                'achievement_certificate_file.file' => 'File piagam prestasi tidak valid',
                'achievement_certificate_file.mimes' => 'File piagam prestasi harus berformat PDF, JPG, JPEG, atau PNG',
                'achievement_certificate_file.max' => 'File piagam prestasi maksimal 2MB',
                'financial_document_file.file' => 'File dokumen keuangan tidak valid',
                'financial_document_file.mimes' => 'File dokumen keuangan harus berformat PDF, JPG, JPEG, atau PNG',
                'financial_document_file.max' => 'File dokumen keuangan maksimal 2MB',
                'average_raport_score.numeric' => 'Rata-rata nilai rapor harus berupa angka',
                'average_raport_score.min' => 'Rata-rata nilai rapor minimal 0',
                'average_raport_score.max' => 'Rata-rata nilai rapor maksimal 100',
            ]);

            // Validasi tambahan berdasarkan jalur pendaftaran
            if ($request->entry_path === 'prestasi') {
                $request->validate([
                    'average_raport_score' => 'required|numeric|min:85|max:100',
                    'achievement_certificate_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                ], [
                    'average_raport_score.required' => 'Rata-rata nilai rapor wajib diisi untuk jalur prestasi',
                    'average_raport_score.min' => 'Rata-rata nilai rapor minimal 85 untuk jalur prestasi',
                    'average_raport_score.max' => 'Rata-rata nilai rapor maksimal 100',
                    'achievement_certificate_file.required' => 'Piagam prestasi wajib diupload untuk jalur prestasi',
                ]);
            }

            if ($request->entry_path === 'afirmasi') {
                $request->validate([
                    'financial_document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                ], [
                    'financial_document_file.required' => 'Dokumen keuangan wajib diupload untuk jalur afirmasi',
                ]);
            }

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

            // Prepare application data
            $applicationData = [
                'full_name' => trim($request->full_name),
                'nisn' => trim($request->nisn),
                'birth_place' => trim($request->birth_place),
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'religion' => trim($request->religion),
                'phone_number' => trim($request->phone_number),
                'email' => trim($request->email),
                'address' => trim($request->address),
                'parent_name' => trim($request->parent_name),
                'parent_phone' => trim($request->parent_phone),
                'parent_email' => trim($request->parent_email),
                'parent_occupation' => $request->parent_occupation ? trim($request->parent_occupation) : null,
                'parent_address' => $request->parent_address ? trim($request->parent_address) : null,
                'entry_path' => $request->entry_path,
                'desired_major' => $request->desired_major,
                'raport_file' => $raportFile,
                'photo_file' => $photoFile,
                'family_card_file' => $familyCardFile,
                'achievement_certificate_file' => $achievementCertificateFile,
                'financial_document_file' => $financialDocumentFile,
                'average_raport_score' => $request->average_raport_score ? floatval($request->average_raport_score) : null,
                'test_score' => null,
                'status' => 'pending',
                'notes' => null,
                'submitted_at' => now(),
            ];

            // Log the data being inserted for debugging
            Log::info('PPDB Application Data to be inserted', [
                'data' => array_merge($applicationData, [
                    'raport_file' => '***hidden***',
                    'photo_file' => '***hidden***',
                    'family_card_file' => '***hidden***',
                    'achievement_certificate_file' => $achievementCertificateFile ? '***hidden***' : null,
                    'financial_document_file' => $financialDocumentFile ? '***hidden***' : null,
                ])
            ]);

            // Create PPDB application
            $application = PPDBApplication::create($applicationData);

            DB::commit();

            Log::info('PPDB Registration Success', [
                'application_id' => $application->id,
                'application_number' => $application->application_number,
                'full_name' => $application->full_name,
            ]);

            return redirect()->route('ppdb.success', ['application_number' => $application->application_number])
                ->with('success', 'Pendaftaran PPDB berhasil! Nomor pendaftaran Anda: ' . $application->application_number);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('PPDB Registration Validation Error', [
                'errors' => $e->errors(),
                'request_data' => $request->except(['raport_file', 'photo_file', 'family_card_file', 'achievement_certificate_file', 'financial_document_file'])
            ]);

            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded files if any
            if (isset($raportFile)) Storage::disk('public')->delete($raportFile);
            if (isset($photoFile)) Storage::disk('public')->delete($photoFile);
            if (isset($familyCardFile)) Storage::disk('public')->delete($familyCardFile);
            if (isset($achievementCertificateFile)) Storage::disk('public')->delete($achievementCertificateFile);
            if (isset($financialDocumentFile)) Storage::disk('public')->delete($financialDocumentFile);

            // Log the error for debugging
            Log::error('PPDB Registration Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['raport_file', 'photo_file', 'family_card_file', 'achievement_certificate_file', 'financial_document_file'])
            ]);

            return back()->withErrors(['error' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi atau hubungi admin.'])->withInput();
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
     * Show admin index page for PPDB applications
     */
    public function adminIndex(Request $request)
    {
        $query = PPDBApplication::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by entry path
        if ($request->filled('entry_path')) {
            $query->where('entry_path', $request->entry_path);
        }

        // Filter by desired major
        if ($request->filled('desired_major')) {
            $query->where('desired_major', $request->desired_major);
        }

        // Search by name, NISN, or application number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('application_number', 'like', "%{$search}%");
            });
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.ppdb.index', compact('applications'));
    }

    /**
     * Show admin detail page for PPDB application
     */
    public function adminShow(PPDBApplication $application)
    {
        return view('admin.ppdb.show', compact('application'));
    }

    /**
     * Update test score for PPDB application
     */
    public function updateTestScore(Request $request, PPDBApplication $application)
    {
        $request->validate([
            'test_score' => 'required|numeric|min:0|max:100',
        ]);

        $application->update([
            'test_score' => $request->test_score,
        ]);

        return back()->with('success', 'Nilai tes berhasil diperbarui.');
    }

    /**
     * Show batch test score update page
     */
    public function showBatchTestScore()
    {
        $applications = PPDBApplication::where('entry_path', 'tes')
            ->where('status', 'pending')
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
            'scores' => 'required|array',
            'scores.*' => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($request->scores as $applicationId => $score) {
            if ($score !== null) {
                PPDBApplication::where('id', $applicationId)->update([
                    'test_score' => $score,
                ]);
            }
        }

        return back()->with('success', 'Nilai tes berhasil diperbarui.');
    }

    /**
     * Admin update PPDB application status
     */
    public function adminUpdate(Request $request, PPDBApplication $application)
    {
        $request->validate([
            'status' => 'required|in:lulus,ditolak',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if test score is required but not provided for tes entry path
        if ($application->entry_path === 'tes' && !$application->test_score) {
            return back()->withErrors(['error' => 'Untuk jalur tes, nilai tes harus diinput terlebih dahulu sebelum mengubah status.']);
        }

        // Check if average raport score is required but not provided for prestasi entry path
        if ($application->entry_path === 'prestasi' && !$application->average_raport_score) {
            return back()->withErrors(['error' => 'Untuk jalur prestasi, rata-rata nilai rapor harus diinput terlebih dahulu sebelum mengubah status.']);
        }

        $application->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'processed_at' => now(),
            'processed_by' => auth()->id(),
        ]);

        // If approved, create student account
        if ($request->status === 'lulus') {
            $this->createStudentAccount($application);
        }

        return back()->with('success', 'Status aplikasi berhasil diperbarui.');
    }

    /**
     * Create student account from approved PPDB application
     */
    private function createStudentAccount(PPDBApplication $application)
    {
        DB::beginTransaction();

        try {
            // Create user account for student
            $studentUser = User::create([
                'name' => $application->full_name,
                'email' => $application->email,
                'password' => Hash::make('siswa123'),
                'role' => 'student',
            ]);

            // Create student record
            $student = Student::create([
                'user_id' => $studentUser->id,
                'ppdb_application_id' => $application->id, // Link ke PPDB application
                'nis' => NISGeneratorService::generateNIS(),
                'nisn' => $application->nisn,
                'full_name' => substr($application->full_name, 0, 100), // Batasi panjang nama
                'gender' => $application->gender,
                'birth_place' => substr($application->birth_place, 0, 50), // Batasi panjang tempat lahir
                'birth_date' => $application->birth_date,
                'religion' => substr($application->religion, 0, 20), // Batasi panjang agama
                'address' => $application->address, // Bisa text, tidak perlu dibatasi
                'phone_number' => substr($application->phone_number, 0, 20), // Batasi panjang nomor telepon
                'status' => 'Aktif',
            ]);

            // Create parent account
            $parentUser = User::create([
                'name' => $application->parent_name,
                'email' => $application->parent_email,
                'password' => Hash::make('wali123'),
                'role' => 'wali_murid',
            ]);

            // Create wali murid record
            WaliMurid::create([
                'user_id' => $parentUser->id,
                'full_name' => substr($application->parent_name, 0, 100), // Batasi panjang nama
                'phone_number' => substr($application->parent_phone, 0, 20), // Batasi panjang nomor telepon
                'address' => $application->parent_address ?? $application->address, // Bisa text
                'relationship' => 'Orang Tua',
                'student_id' => $student->id,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Download document from PPDB application
     */
    public function downloadDocument(PPDBApplication $application, $documentType)
    {
        $filePath = null;

        switch ($documentType) {
            case 'raport':
                $filePath = $application->raport_file;
                break;
            case 'photo':
                $filePath = $application->photo_file;
                break;
            case 'family_card':
                $filePath = $application->family_card_file;
                break;
            case 'achievement_certificate':
                $filePath = $application->achievement_certificate_file;
                break;
            case 'financial_document':
                $filePath = $application->financial_document_file;
                break;
            default:
                abort(404);
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        return Storage::disk('public')->download($filePath);
    }

    /**
     * Check PPDB application status
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'application_number' => 'required|string',
        ]);

        $application = PPDBApplication::where('application_number', $request->application_number)->first();

        if (!$application) {
            return back()->withErrors(['application_number' => 'Nomor pendaftaran tidak ditemukan.']);
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

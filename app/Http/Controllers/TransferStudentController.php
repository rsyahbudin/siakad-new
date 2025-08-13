<?php

namespace App\Http\Controllers;

use App\Models\TransferStudent;
use App\Models\User;
use App\Models\Student;
use App\Models\WaliMurid;
use App\Models\Subject;
use App\Services\NISGeneratorService;
use App\Services\ClassPlacementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TransferStudentController extends Controller
{
    /**
     * Show transfer student registration form (public)
     */
    public function showRegistrationForm()
    {
        return view('transfer.registration');
    }

    /**
     * Handle transfer student registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'nisn' => 'required|string|unique:transfer_students,nisn|size:10|regex:/^[0-9]+$/',
            'nis_previous' => 'nullable|string|max:255',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'religion' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:transfer_students,email',
            'address' => 'required|string',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'required|email|max:255|unique:transfer_students,parent_email',
            'parent_occupation' => 'nullable|string|max:255',
            'parent_address' => 'nullable|string',
            'previous_school_name' => 'required|string|max:255',
            'previous_school_address' => 'required|string',
            'previous_school_npsn' => 'nullable|string|max:20',
            'previous_grade' => 'required|in:X,XI,XII',
            'previous_major' => 'required|in:IPA,IPS,Bahasa,Lainnya',
            'previous_academic_year' => 'required|string|max:20',
            'transfer_reason' => 'required|string',
            'desired_grade' => 'required|in:X,XI,XII',
            'desired_major' => 'required|in:IPA,IPS',
            'grade_scale' => 'required|in:0-100,0-4,A-F,Predikat',
            'raport_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo_file' => 'required|file|mimes:jpg,jpeg,png|max:1024',
            'family_card_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'transfer_certificate_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'birth_certificate_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'health_certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Handle file uploads
            $raportFile = $request->file('raport_file')->store('transfer/raport', 'public');
            $photoFile = $request->file('photo_file')->store('transfer/photo', 'public');
            $familyCardFile = $request->file('family_card_file')->store('transfer/family_card', 'public');
            $transferCertificateFile = $request->file('transfer_certificate_file')->store('transfer/certificate', 'public');
            $birthCertificateFile = $request->file('birth_certificate_file')->store('transfer/birth_certificate', 'public');

            $healthCertificateFile = null;
            if ($request->hasFile('health_certificate_file')) {
                $healthCertificateFile = $request->file('health_certificate_file')->store('transfer/health_certificate', 'public');
            }

            // Create transfer student application
            $transferStudent = TransferStudent::create([
                'full_name' => $request->full_name,
                'nisn' => $request->nisn,
                'nis_previous' => $request->nis_previous,
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
                'previous_school_name' => $request->previous_school_name,
                'previous_school_address' => $request->previous_school_address,
                'previous_school_npsn' => $request->previous_school_npsn,
                'previous_grade' => $request->previous_grade,
                'previous_major' => $request->previous_major,
                'previous_academic_year' => $request->previous_academic_year,
                'transfer_reason' => $request->transfer_reason,
                'desired_grade' => $request->desired_grade,
                'desired_major' => $request->desired_major,
                'grade_scale' => $request->grade_scale,
                'raport_file' => $raportFile,
                'photo_file' => $photoFile,
                'family_card_file' => $familyCardFile,
                'transfer_certificate_file' => $transferCertificateFile,
                'birth_certificate_file' => $birthCertificateFile,
                'health_certificate_file' => $healthCertificateFile,
                'submitted_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('transfer.success', ['registration_number' => $transferStudent->registration_number])
                ->with('success', 'Pendaftaran siswa pindahan berhasil! Nomor pendaftaran Anda: ' . $transferStudent->registration_number);
        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded files if any
            if (isset($raportFile)) Storage::disk('public')->delete($raportFile);
            if (isset($photoFile)) Storage::disk('public')->delete($photoFile);
            if (isset($familyCardFile)) Storage::disk('public')->delete($familyCardFile);
            if (isset($transferCertificateFile)) Storage::disk('public')->delete($transferCertificateFile);
            if (isset($birthCertificateFile)) Storage::disk('public')->delete($birthCertificateFile);
            if (isset($healthCertificateFile)) Storage::disk('public')->delete($healthCertificateFile);

            return back()->withErrors(['error' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.']);
        }
    }

    /**
     * Show success page after registration
     */
    public function showSuccess(Request $request)
    {
        $registrationNumber = $request->registration_number;
        $transferStudent = TransferStudent::where('registration_number', $registrationNumber)->first();

        if (!$transferStudent) {
            return redirect()->route('transfer.register');
        }

        return view('transfer.success', compact('transferStudent'));
    }

    /**
     * Show admin dashboard for transfer student management
     */
    public function adminIndex(Request $request)
    {
        $query = TransferStudent::with('processedBy');

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by target grade
        if ($request->filled('target_grade')) {
            $query->byTargetGrade($request->target_grade);
        }

        // Filter by target major
        if ($request->filled('target_major')) {
            $query->byTargetMajor($request->target_major);
        }

        // Search by name or registration number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('registration_number', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $transferStudents = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get statistics
        $totalApplications = TransferStudent::count();
        $pendingCount = TransferStudent::pending()->count();
        $approvedCount = TransferStudent::approved()->count();
        $rejectedCount = TransferStudent::rejected()->count();

        return view('admin.transfer.index', compact(
            'transferStudents',
            'totalApplications',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Show detailed view of transfer student application
     */
    public function adminShow(TransferStudent $transferStudent)
    {
        $subjects = $this->getSubjectsByMajor($transferStudent->desired_major);
        return view('admin.transfer.show', compact('transferStudent', 'subjects'));
    }

    /**
     * Update transfer student status and grade conversion
     */
    public function adminUpdate(Request $request, TransferStudent $transferStudent)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Check if student is eligible for approval
            if ($request->status === 'approved' && !$transferStudent->isEligibleForApproval()) {
                return back()->withErrors(['error' => 'Siswa belum memenuhi syarat untuk disetujui. Pastikan dokumen lengkap dan konversi nilai sudah dilakukan.']);
            }

            $transferStudent->update([
                'status' => $request->status,
                'notes' => $request->notes,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            // If approved, create student account
            if ($request->status === 'approved') {
                $this->createStudentAccount($transferStudent);
            }

            DB::commit();

            return back()->with('success', 'Status siswa pindahan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating transfer student: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui status.']);
        }
    }

    /**
     * Auto convert grades from original scale to 0-100
     */
    public function autoConvertGrades(Request $request, TransferStudent $transferStudent)
    {
        $request->validate([
            'original_grades' => 'required|array',
            'original_grades.*' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // Update original grades
            $transferStudent->update([
                'original_grades' => $request->original_grades,
            ]);

            // Auto convert grades
            $success = $transferStudent->autoConvertGrades();

            if (!$success) {
                return back()->withErrors(['error' => 'Gagal melakukan konversi nilai. Pastikan skala nilai sudah dipilih.']);
            }

            DB::commit();

            return back()->with('success', 'Konversi nilai berhasil dilakukan secara otomatis.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error auto converting grades: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat konversi nilai.']);
        }
    }

    /**
     * Create student account when approved
     */
    private function createStudentAccount(TransferStudent $transferStudent)
    {
        // Check if user already exists
        $existingUser = User::where('email', $transferStudent->email)->first();
        if ($existingUser) {
            Log::info('User already exists for email: ' . $transferStudent->email);
            return; // User already exists
        }

        // Create user account
        $user = User::create([
            'name' => $transferStudent->full_name,
            'email' => $transferStudent->email,
            'password' => Hash::make('student123'), // Default password
            'role' => User::ROLE_STUDENT,
        ]);

        // Generate unique NIS for transfer student (different from previous NIS)
        $nis = NISGeneratorService::generateNISForTransferStudent($transferStudent->nis_previous);

        // Create student record
        $student = Student::create([
            'user_id' => $user->id,
            'nis' => $nis, // Generated unique NIS
            'nisn' => $transferStudent->nisn,
            'full_name' => $transferStudent->full_name,
            'gender' => $transferStudent->gender,
            'birth_place' => $transferStudent->birth_place,
            'birth_date' => $transferStudent->birth_date,
            'religion' => $transferStudent->religion,
            'phone_number' => $transferStudent->phone_number,
            'address' => $transferStudent->address,
            'parent_name' => $transferStudent->parent_name,
            'parent_phone' => $transferStudent->parent_phone,
            'major_interest' => $transferStudent->desired_major, // Set major interest dari desired_major
            'status' => 'Pindahan', // Status siswa pindahan, bukan aktif
        ]);

        // Create wali murid account
        $waliMuridUser = User::create([
            'name' => $transferStudent->parent_name,
            'email' => $transferStudent->parent_email,
            'password' => Hash::make('wali123'), // Default password
            'role' => User::ROLE_WALI_MURID,
        ]);

        // Create wali murid record
        WaliMurid::create([
            'user_id' => $waliMuridUser->id,
            'student_id' => $student->id,
            'full_name' => $transferStudent->parent_name,
            'phone_number' => $transferStudent->parent_phone,
            'address' => $transferStudent->parent_address ?? $transferStudent->address,
            'relationship' => 'Orang Tua',
        ]);

        // Place student in appropriate class based on target grade and major
        $placementSuccess = ClassPlacementService::placeTransferStudent($student, $transferStudent->desired_grade, $transferStudent->desired_major);

        if ($placementSuccess) {
            Log::info("Transfer student {$student->full_name} successfully placed in class");
        } else {
            Log::warning("Failed to place transfer student {$student->full_name} in class");
        }

        Log::info('Student account created successfully for transfer student: ' . $transferStudent->registration_number);
    }

    /**
     * Download transfer student document
     */
    public function downloadDocument(TransferStudent $transferStudent, $documentType)
    {
        $documentField = $documentType . '_file';

        if (!isset($transferStudent->$documentField)) {
            return back()->withErrors(['error' => 'Dokumen tidak ditemukan.']);
        }

        $filePath = $transferStudent->$documentField;

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
     * Check transfer student status (public)
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'registration_number' => 'required|string',
            'nisn' => 'required|string',
        ]);

        $transferStudent = TransferStudent::where('registration_number', $request->registration_number)
            ->where('nisn', $request->nisn)
            ->first();

        if (!$transferStudent) {
            return back()->withErrors(['error' => 'Data pendaftaran tidak ditemukan.']);
        }

        return view('transfer.status', compact('transferStudent'));
    }

    /**
     * Show status check form
     */
    public function showStatusCheck()
    {
        return view('transfer.status-check');
    }

    /**
     * Show grade conversion form for admin
     */
    public function showGradeConversion(TransferStudent $transferStudent)
    {
        $subjects = $this->getSubjectsByMajor($transferStudent->desired_major);
        return view('admin.transfer.grade-conversion', compact('transferStudent', 'subjects'));
    }

    /**
     * Save grade conversion (manual)
     */
    public function saveGradeConversion(Request $request, TransferStudent $transferStudent)
    {
        $request->validate([
            'original_grades' => 'required|array',
            'original_grades.*' => 'required',
            'converted_grades' => 'required|array',
            'converted_grades.*' => 'required|numeric|min:0|max:100',
            'conversion_notes' => 'nullable|string',
        ]);

        // Validate original grades based on grade scale
        $gradeScale = $transferStudent->grade_scale;
        $originalGrades = $request->original_grades;

        foreach ($originalGrades as $subject => $grade) {
            if (empty($grade)) continue;

            switch ($gradeScale) {
                case '0-4':
                    if (!is_numeric($grade) || $grade < 0 || $grade > 4) {
                        return back()->withErrors(['error' => "Nilai {$subject} harus antara 0-4 untuk skala 0-4"]);
                    }
                    break;
                case 'A-F':
                    $validGrades = ['A', 'A-', 'A+', 'B', 'B-', 'B+', 'C', 'C-', 'C+', 'D', 'D-', 'D+', 'E', 'F'];
                    if (!in_array($grade, $validGrades)) {
                        return back()->withErrors(['error' => "Nilai {$subject} harus berupa huruf A-F untuk skala A-F"]);
                    }
                    break;
                case 'Predikat':
                    $validPredikats = ['Sangat Baik', 'Baik', 'Cukup', 'Kurang', 'Sangat Kurang'];
                    if (!in_array($grade, $validPredikats)) {
                        return back()->withErrors(['error' => "Nilai {$subject} harus berupa predikat yang valid"]);
                    }
                    break;
                case '0-100':
                default:
                    if (!is_numeric($grade) || $grade < 0 || $grade > 100) {
                        return back()->withErrors(['error' => "Nilai {$subject} harus antara 0-100"]);
                    }
                    break;
            }
        }

        $transferStudent->update([
            'original_grades' => $request->original_grades,
            'converted_grades' => $request->converted_grades,
            'conversion_notes' => $request->conversion_notes,
        ]);

        return back()->with('success', 'Konversi nilai berhasil disimpan.');
    }

    /**
     * Get subjects based on major
     */
    private function getSubjectsByMajor($major)
    {
        switch ($major) {
            case 'IPA':
                return Subject::whereIn('name', [
                    'Matematika',
                    'Fisika',
                    'Kimia',
                    'Biologi',
                    'Bahasa Indonesia',
                    'Bahasa Inggris',
                    'Pendidikan Agama',
                    'PPKN',
                    'Sejarah Indonesia',
                    'Seni Budaya',
                    'PJOK',
                    'Prakarya'
                ])->orderBy('name')->get();

            case 'IPS':
                return Subject::whereIn('name', [
                    'Matematika',
                    'Ekonomi',
                    'Geografi',
                    'Sejarah',
                    'Sosiologi',
                    'Bahasa Indonesia',
                    'Bahasa Inggris',
                    'Pendidikan Agama',
                    'PPKN',
                    'Seni Budaya',
                    'PJOK',
                    'Prakarya'
                ])->orderBy('name')->get();

            default:
                return Subject::orderBy('name')->get();
        }
    }
}

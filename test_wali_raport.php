<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Student;
use App\Models\WaliMurid;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Grade;
use App\Models\Raport;
use App\Models\SubjectSetting;
use App\Services\AttendanceService;

echo "=== TEST WALI MURID RAPORT ===\n\n";

// Test 1: Check if there are wali murid users
echo "1. CHECKING WALI MURID USERS:\n";
$waliMuridUsers = User::where('role', 'wali_murid')->with('waliMurid.student')->get();
echo "Total wali murid users: " . $waliMuridUsers->count() . "\n";

foreach ($waliMuridUsers as $user) {
    echo "   - User: {$user->name} (ID: {$user->id})\n";
    if ($user->waliMurid && $user->waliMurid->student) {
        echo "     ✅ Has wali murid record and student\n";
        echo "     - Student: {$user->waliMurid->student->full_name} (ID: {$user->waliMurid->student->id})\n";
    } else {
        echo "     ❌ Missing wali murid record or student\n";
    }
}

// Test 2: Check academic years
echo "\n2. CHECKING ACADEMIC YEARS:\n";
$academicYears = AcademicYear::orderBy('year', 'desc')->get();
echo "Total academic years: " . $academicYears->count() . "\n";
foreach ($academicYears as $year) {
    echo "   - {$year->year} (ID: {$year->id})\n";
}

// Test 3: Check semesters
echo "\n3. CHECKING SEMESTERS:\n";
$semesters = Semester::with('academicYear')->get();
echo "Total semesters: " . $semesters->count() . "\n";
foreach ($semesters as $semester) {
    echo "   - {$semester->academicYear->year} {$semester->name} (ID: {$semester->id})\n";
}

// Test 4: Check if there are grades for students
echo "\n4. CHECKING GRADES:\n";
$students = Student::whereHas('waliMurid')->get();
echo "Total students with wali murid: " . $students->count() . "\n";

foreach ($students as $student) {
    $grades = Grade::where('student_id', $student->id)->get();
    echo "   - {$student->full_name}: {$grades->count()} grades\n";

    if ($grades->count() > 0) {
        echo "     ✅ Has grades\n";
    } else {
        echo "     ❌ No grades\n";
    }
}

// Test 5: Check if there are raports
echo "\n5. CHECKING RAPORTS:\n";
$raports = Raport::with(['student', 'academicYear'])->get();
echo "Total raports: " . $raports->count() . "\n";

foreach ($raports as $raport) {
    echo "   - Student: {$raport->student->full_name}\n";
    echo "     - Year: {$raport->academicYear->year}\n";
    echo "     - Semester: " . ($raport->semester == 1 ? 'Ganjil' : 'Genap') . "\n";
    echo "     - Finalized: " . ($raport->is_finalized ? 'Yes' : 'No') . "\n";
}

// Test 6: Check subject settings
echo "\n6. CHECKING SUBJECT SETTINGS:\n";
$subjectSettings = SubjectSetting::with(['academicYear', 'subject'])->get();
echo "Total subject settings: " . $subjectSettings->count() . "\n";

foreach ($subjectSettings as $setting) {
    echo "   - {$setting->academicYear->year} - {$setting->subject->name} (KKM: {$setting->kkm})\n";
}

// Test 7: Test with a specific wali murid
echo "\n7. TESTING WITH SPECIFIC WALI MURID:\n";
$waliMuridUser = User::where('role', 'wali_murid')->whereHas('waliMurid.student')->first();

if ($waliMuridUser) {
    echo "Testing with wali murid: {$waliMuridUser->name}\n";
    $student = $waliMuridUser->waliMurid->student;
    echo "Student: {$student->full_name}\n";

    // Check academic years for this student
    $studentAcademicYears = AcademicYear::whereHas('classroomAssignments.classStudents', function ($query) use ($student) {
        $query->where('student_id', $student->id);
    })->orderBy('year', 'desc')->get();

    echo "Academic years for student: " . $studentAcademicYears->count() . "\n";
    foreach ($studentAcademicYears as $year) {
        echo "   - {$year->year} (ID: {$year->id})\n";
    }

    // Check grades for this student
    $studentGrades = Grade::where('student_id', $student->id)->with(['subject', 'semester'])->get();
    echo "Grades for student: " . $studentGrades->count() . "\n";

    // Check raports for this student
    $studentRaports = Raport::where('student_id', $student->id)->with(['academicYear'])->get();
    echo "Raports for student: " . $studentRaports->count() . "\n";

    if ($studentRaports->count() > 0) {
        echo "   ✅ Student has raports\n";
    } else {
        echo "   ❌ Student has no raports\n";
    }
} else {
    echo "❌ No wali murid with student found\n";
}

echo "\n=== TEST COMPLETED ===\n";

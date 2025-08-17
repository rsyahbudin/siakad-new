<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\User;

echo "=== CHECK SCHEDULES ===\n\n";

// Check all schedules
$schedules = Schedule::with(['teacher', 'subject', 'classroom'])->get();
echo "Total schedules: " . $schedules->count() . "\n\n";

foreach ($schedules as $schedule) {
    echo "Schedule ID {$schedule->id}:\n";
    echo "   - Subject: {$schedule->subject->name}\n";
    echo "   - Classroom: {$schedule->classroom->name}\n";
    echo "   - Teacher ID: {$schedule->teacher_id}\n";
    
    if ($schedule->teacher) {
        echo "   - Teacher: {$schedule->teacher->full_name}\n";
        if ($schedule->teacher->user) {
            echo "   - User: {$schedule->teacher->user->name} (Role: {$schedule->teacher->user->role})\n";
        } else {
            echo "   - User: ❌ No user record\n";
        }
    } else {
        echo "   - Teacher: ❌ No teacher record\n";
    }
    echo "\n";
}

// Check all teachers
echo "=== ALL TEACHERS ===\n";
$teachers = Teacher::with('user')->get();
echo "Total teachers: " . $teachers->count() . "\n\n";

foreach ($teachers as $teacher) {
    echo "Teacher ID {$teacher->id}: {$teacher->full_name}\n";
    echo "   - User ID: " . ($teacher->user ? $teacher->user->id : 'None') . "\n";
    echo "   - User Name: " . ($teacher->user ? $teacher->user->name : 'None') . "\n";
    echo "   - User Role: " . ($teacher->user ? $teacher->user->role : 'None') . "\n";
    
    // Check schedules for this teacher
    $teacherSchedules = Schedule::where('teacher_id', $teacher->id)->count();
    echo "   - Schedules: {$teacherSchedules}\n";
    echo "\n";
}

echo "=== CHECK COMPLETED ===\n";

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubjectSetting;
use App\Models\Subject;
use App\Models\AcademicYear;

class SubjectSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get current academic year
        $academicYear = AcademicYear::where('is_active', true)->first();

        // Get all subjects
        $subjects = Subject::all();

        // Default settings for all subjects
        $defaultSettings = [
            'kkm' => 75.00,
            'assignment_weight' => 30.00,
            'uts_weight' => 30.00,
            'uas_weight' => 40.00,
            'allow_remedial' => true,
            'remedial_max_grade' => 75.00,
            'is_active' => true,
        ];

        // Create settings for each subject
        foreach ($subjects as $subject) {
            // Customize settings based on subject if needed
            $settings = $defaultSettings;

            // For example, set different KKM for certain subjects
            if (in_array($subject->code, ['MTK', 'FIS', 'KIM'])) {
                $settings['kkm'] = 70.00;
            }

            // Create the settings
            SubjectSetting::create([
                'subject_id' => $subject->id,
                'academic_year_id' => $academicYear->id,
                ...$settings
            ]);
        }
    }
}

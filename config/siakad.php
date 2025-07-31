<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SIAKAD Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration settings for the SIAKAD application.
    |
    */

    // Maximum number of failed subjects allowed for promotion
    'max_failed_subjects' => env('SIAKAD_MAX_FAILED_SUBJECTS', 2),

    // Time slots configuration for school schedule
    'time_slots' => [
        1 => ['start' => '07:00', 'end' => '07:45', 'name' => 'Jam 1'],
        2 => ['start' => '07:45', 'end' => '08:30', 'name' => 'Jam 2'],
        3 => ['start' => '08:30', 'end' => '09:15', 'name' => 'Jam 3'],
        4 => ['start' => '09:15', 'end' => '10:00', 'name' => 'Jam 4'],
        // Istirahat 1: 10:00 - 10:30
        5 => ['start' => '10:30', 'end' => '11:15', 'name' => 'Jam 5'],
        6 => ['start' => '11:15', 'end' => '12:00', 'name' => 'Jam 6'],
        // Istirahat 2: 12:00 - 13:00
        7 => ['start' => '13:00', 'end' => '13:45', 'name' => 'Jam 7'],
        8 => ['start' => '13:45', 'end' => '14:30', 'name' => 'Jam 8'],
        9 => ['start' => '14:30', 'end' => '15:15', 'name' => 'Jam 9'],
        10 => ['start' => '15:15', 'end' => '16:00', 'name' => 'Jam 10'],
    ],

    // Break times configuration
    'break_times' => [
        'break_1' => ['start' => '10:00', 'end' => '10:30', 'name' => 'Istirahat 1'],
        'break_2' => ['start' => '12:00', 'end' => '13:00', 'name' => 'Istirahat 2'],
    ],

    // School days
    'school_days' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],

    // Default academic settings
    'default_kkm' => 75,
    'default_assignment_weight' => 30,
    'default_uts_weight' => 30,
    'default_uas_weight' => 40,
];

<?php

namespace App\Services;

use App\Models\Student;
use Carbon\Carbon;

class NISGeneratorService
{
    /**
     * Generate unique NIS for new student
     */
    public static function generateNIS(): string
    {
        $currentYear = Carbon::now()->year;
        $yearPrefix = substr($currentYear, -2); // Get last 2 digits of year

        // Get the latest NIS for this year
        $latestNIS = Student::where('nis', 'like', $yearPrefix . '%')
            ->orderBy('nis', 'desc')
            ->first();

        if ($latestNIS) {
            // Extract the sequence number from the latest NIS
            $sequence = (int) substr($latestNIS->nis, 2); // Remove year prefix
            $newSequence = $sequence + 1;
        } else {
            $newSequence = 1;
        }

        // Format: YY + 6 digit sequence (e.g., 25000001 for year 2025, student #1)
        $nis = $yearPrefix . str_pad($newSequence, 6, '0', STR_PAD_LEFT);

        // Ensure NIS is exactly 8 digits
        if (strlen($nis) > 8) {
            throw new \Exception('NIS sequence exceeded maximum limit for this year');
        }

        return $nis;
    }

    /**
     * Generate NIS for transfer student (different from previous NIS)
     */
    public static function generateNISForTransferStudent(string $previousNIS = null): string
    {
        $currentYear = Carbon::now()->year;
        $yearPrefix = substr($currentYear, -2);

        // Get the latest NIS for this year
        $latestNIS = Student::where('nis', 'like', $yearPrefix . '%')
            ->orderBy('nis', 'desc')
            ->first();

        if ($latestNIS) {
            $sequence = (int) substr($latestNIS->nis, 2);
            $newSequence = $sequence + 1;
        } else {
            $newSequence = 1;
        }

        $nis = $yearPrefix . str_pad($newSequence, 6, '0', STR_PAD_LEFT);

        // Ensure NIS is exactly 8 digits
        if (strlen($nis) > 8) {
            throw new \Exception('NIS sequence exceeded maximum limit for this year');
        }

        // If this NIS is the same as previous NIS, generate next one
        if ($previousNIS && $nis === $previousNIS) {
            $newSequence++;
            $nis = $yearPrefix . str_pad($newSequence, 6, '0', STR_PAD_LEFT);
        }

        return $nis;
    }

    /**
     * Validate NIS format
     */
    public static function validateNIS(string $nis): bool
    {
        // NIS should be exactly 8 digits and start with current year
        $currentYear = substr(Carbon::now()->year, -2);

        return preg_match('/^' . $currentYear . '\d{6}$/', $nis) === 1;
    }

    /**
     * Get NIS format example
     */
    public static function getNISFormatExample(): string
    {
        $currentYear = substr(Carbon::now()->year, -2);
        return $currentYear . '000001'; // Example: 25000001 for year 2025
    }
}

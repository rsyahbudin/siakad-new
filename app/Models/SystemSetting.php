<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    /**
     * Get a setting value by key
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key
     */
    public static function setValue($key, $value, $description = null)
    {
        $setting = self::where('key', $key)->first();

        if ($setting) {
            $setting->update([
                'value' => $value,
                'description' => $description ?? $setting->description,
            ]);
        } else {
            self::create([
                'key' => $key,
                'value' => $value,
                'description' => $description,
            ]);
        }
    }

    /**
     * Check if PPDB system is enabled
     */
    public static function isPPDBEnabled()
    {
        return self::getValue('ppdb_enabled', 'true') === 'true';
    }

    /**
     * Check if Transfer Student system is enabled
     */
    public static function isTransferStudentEnabled()
    {
        return self::getValue('transfer_student_enabled', 'true') === 'true';
    }

    /**
     * Enable PPDB system
     */
    public static function enablePPDB()
    {
        self::setValue('ppdb_enabled', 'true', 'Enable/disable PPDB system');
    }

    /**
     * Disable PPDB system
     */
    public static function disablePPDB()
    {
        self::setValue('ppdb_enabled', 'false', 'Enable/disable PPDB system');
    }

    /**
     * Enable Transfer Student system
     */
    public static function enableTransferStudent()
    {
        self::setValue('transfer_student_enabled', 'true', 'Enable/disable Transfer Student system');
    }

    /**
     * Disable Transfer Student system
     */
    public static function disableTransferStudent()
    {
        self::setValue('transfer_student_enabled', 'false', 'Enable/disable Transfer Student system');
    }
}

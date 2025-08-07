<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;

class SystemSettingController extends Controller
{
    public function index()
    {
        $ppdbEnabled = (bool) AppSetting::getValue('system_ppdb_enabled', true);
        $transferStudentEnabled = (bool) AppSetting::getValue('system_transfer_enabled', true);

        // School info values
        $school = [
            'name' => AppSetting::getValue('school_name', ''),
            'npsn' => AppSetting::getValue('school_npsn', ''),
            'address' => AppSetting::getValue('school_address', ''),
            'phone' => AppSetting::getValue('school_phone', ''),
            'email' => AppSetting::getValue('school_email', ''),
            'website' => AppSetting::getValue('school_website', ''),
        ];

        return view('admin.system-settings.index', compact('ppdbEnabled', 'transferStudentEnabled', 'school'));
    }

    public function togglePPDB()
    {
        $current = (bool) AppSetting::getValue('system_ppdb_enabled', true);
        AppSetting::setValue('system_ppdb_enabled', !$current);
        return back()->with('success', 'Status sistem PPDB diperbarui.');
    }

    public function toggleTransferStudent()
    {
        $current = (bool) AppSetting::getValue('system_transfer_enabled', true);
        AppSetting::setValue('system_transfer_enabled', !$current);
        return back()->with('success', 'Status sistem Siswa Pindahan diperbarui.');
    }

    public function updateSchoolInfo(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'nullable|string|max:255',
            'school_npsn' => 'nullable|string|max:50',
            'school_address' => 'nullable|string|max:1000',
            'school_phone' => 'nullable|string|max:50',
            'school_email' => 'nullable|email|max:255',
            'school_website' => 'nullable|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            AppSetting::setValue($key, $value);
        }

        return back()->with('success', 'Informasi sekolah berhasil disimpan.');
    }
}

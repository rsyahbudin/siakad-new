<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    /**
     * Show system settings page
     */
    public function index()
    {
        $ppdbEnabled = SystemSetting::isPPDBEnabled();
        $transferStudentEnabled = SystemSetting::isTransferStudentEnabled();

        return view('admin.system-settings.index', compact('ppdbEnabled', 'transferStudentEnabled'));
    }

    /**
     * Toggle PPDB system
     */
    public function togglePPDB()
    {
        $currentStatus = SystemSetting::isPPDBEnabled();

        if ($currentStatus) {
            SystemSetting::disablePPDB();
            $message = 'Sistem PPDB berhasil dinonaktifkan.';
        } else {
            SystemSetting::enablePPDB();
            $message = 'Sistem PPDB berhasil diaktifkan.';
        }

        return back()->with('success', $message);
    }

    /**
     * Toggle Transfer Student system
     */
    public function toggleTransferStudent()
    {
        $currentStatus = SystemSetting::isTransferStudentEnabled();

        if ($currentStatus) {
            SystemSetting::disableTransferStudent();
            $message = 'Sistem Siswa Pindahan berhasil dinonaktifkan.';
        } else {
            SystemSetting::enableTransferStudent();
            $message = 'Sistem Siswa Pindahan berhasil diaktifkan.';
        }

        return back()->with('success', $message);
    }
}

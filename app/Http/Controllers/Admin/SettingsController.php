<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $setting = AttendanceSetting::getSetting();
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'check_in_start' => ['required', 'date_format:H:i'],
            'check_in_late_time' => ['required', 'date_format:H:i'],
            'check_out_time' => ['required', 'date_format:H:i'],
            'check_out_early_time' => ['required', 'date_format:H:i'],
        ]);

        $setting = AttendanceSetting::getSetting();

        $setting->update([
            'check_in_start' => $request->check_in_start . ':00',
            'check_in_late_time' => $request->check_in_late_time . ':00',
            'check_out_time' => $request->check_out_time . ':00',
            'check_out_early_time' => $request->check_out_early_time . ':00',
        ]);

        return back()->with('success', 'Pengaturan jam presensi dan batas toleransi berhasil diperbarui.');
    }
}

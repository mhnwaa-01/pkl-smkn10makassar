<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        try {
            if (Auth::check()) {
                return redirect()->route('dashboard');
            }
        } catch (\Throwable $e) {
            // Database might not be connected yet
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        try {
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();
                $user = Auth::user();

                // Special check for Siswa role
                if ($user->isSiswa()) {
                    $student = $user->student;
                    $today = date('Y-m-d');
                    $hasCheckedIn = $student ? Attendance::where('student_id', $student->id)
                        ->where('date', $today)
                        ->whereNotNull('check_in_time')
                        ->exists() : false;

                    if (!$hasCheckedIn) {
                        session()->flash('show_checkin_popup', true);
                        return redirect()->route('attendance.index')
                            ->with('warning', 'Anda belum melakukan presensi datang hari ini! Silakan lakukan presensi sekarang.');
                    } else {
                        return redirect()->route('journals.index')
                            ->with('info', 'Presensi hari ini sudah tercatat. Silakan isi jurnal harian Anda.');
                    }
                }

                return redirect()->route('dashboard')->with('success', 'Selamat datang kembali, ' . $user->name . '!');
            }
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal terhubung ke database: ' . $e->getMessage())->withInput();
        }

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    public function switchRole(Request $request, string $role)
    {
        $validRoles = ['admin', 'guru', 'industri', 'siswa'];
        if (!in_array($role, $validRoles)) {
            return redirect()->back()->with('error', 'Role tidak valid.');
        }

        try {
            $user = \App\Models\User::where('role', $role)->first();
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Gagal membaca database: ' . $e->getMessage() . '. Pastikan Supabase sudah di-import.');
        }

        if (!$user) {
            return redirect()->route('login')->with('error', 'Akun untuk peran ' . $role . ' tidak ditemukan di database. Pastikan dump SQL Supabase sudah dijalankan.');
        }

        Auth::login($user);
        $request->session()->regenerate();

        $roleNames = [
            'admin' => 'Administrator',
            'guru' => 'Guru Pembimbing',
            'industri' => 'Pembimbing Industri',
            'siswa' => 'Siswa PKL',
        ];

        return redirect()->route('dashboard')->with('success', 'Berhasil beralih akses ke: ' . ($roleNames[$role] ?? ucfirst($role)) . ' (' . $user->name . ')');
    }
}

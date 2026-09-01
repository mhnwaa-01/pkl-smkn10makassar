@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div x-data="{
    fillCredentials(user, pass) {
        document.getElementById('username').value = user;
        document.getElementById('password').value = pass;
    }
}" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm">
    
    <!-- Logo & Header -->
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-50 dark:bg-slate-800/80 p-3 mb-3 border border-slate-200 dark:border-slate-700 shadow-sm">
            <img src="{{ asset('logo-sekolah.png') }}" class="w-full h-full object-contain" width="96" height="96" loading="eager" decoding="sync" alt="Logo SMKN 10 Makassar">
        </div>
        <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">SMKN 10 Makassar</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mt-0.5 uppercase tracking-wider">Sistem Monitoring Presensi & Jurnal PKL</p>
    </div>

    <!-- Login Form -->
    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="username" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 px-2">Username</label>
            <div class="relative">
                <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                    placeholder="Masukkan username Anda"
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2.5 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-500 transition-colors text-xs font-medium">
            </div>
        </div>

        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 px-2">Password</label>
            <div class="relative">
                <input type="password" name="password" id="password" required
                    placeholder="••••••••"
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2.5 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-500 transition-colors text-xs font-medium">
            </div>
        </div>

        <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-full shadow-sm transition-colors text-xs inline-flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            <span>Masuk ke Akun</span>
        </button>
    </form>

    <!-- Quick Role Switcher / Demo Accounts selector -->
    <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800 text-center">
        <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2.5 uppercase tracking-wider">Pilih Akses Masuk Instan (Tanpa Ketik Password):</p>
        <div class="grid grid-cols-2 gap-2 text-xs">
            <!-- Admin -->
            <a href="{{ route('switch-role', 'admin') }}" class="p-3 bg-slate-50 hover:bg-blue-50 dark:bg-slate-950 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-2xl border border-slate-200 dark:border-slate-800 text-left transition-colors block">
                <div class="flex items-center gap-1.5 font-bold text-blue-600 dark:text-blue-400">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                    <span>👑 Admin</span>
                </div>
                <span class="block text-[10px] text-slate-500 dark:text-slate-400 font-mono mt-0.5">user: admin</span>
            </a>
            
            <!-- Guru -->
            <a href="{{ route('switch-role', 'guru') }}" class="p-3 bg-slate-50 hover:bg-emerald-50 dark:bg-slate-950 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-2xl border border-slate-200 dark:border-slate-800 text-left transition-colors block">
                <div class="flex items-center gap-1.5 font-bold text-emerald-600 dark:text-emerald-400">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                    <span>👨‍🏫 Guru</span>
                </div>
                <span class="block text-[10px] text-slate-500 dark:text-slate-400 font-mono mt-0.5">user: guru_budi</span>
            </a>
            
            <!-- Industri -->
            <a href="{{ route('switch-role', 'industri') }}" class="p-3 bg-slate-50 hover:bg-amber-50 dark:bg-slate-950 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-2xl border border-slate-200 dark:border-slate-800 text-left transition-colors block">
                <div class="flex items-center gap-1.5 font-bold text-amber-600 dark:text-amber-400">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span>
                    <span>🏢 Industri</span>
                </div>
                <span class="block text-[10px] text-slate-500 dark:text-slate-400 font-mono mt-0.5">user: pt_telkom</span>
            </a>
            
            <!-- Siswa -->
            <a href="{{ route('switch-role', 'siswa') }}" class="p-3 bg-slate-50 hover:bg-purple-50 dark:bg-slate-950 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-2xl border border-slate-200 dark:border-slate-800 text-left transition-colors block">
                <div class="flex items-center gap-1.5 font-bold text-purple-600 dark:text-purple-400">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                    <span>🎓 Siswa</span>
                </div>
                <span class="block text-[10px] text-slate-500 dark:text-slate-400 font-mono mt-0.5">user: siswa_andi</span>
            </a>
        </div>
    </div>

    <!-- Mobile Android APK Download Banner -->
    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
        <a href="{{ route('download.apk') }}" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-full shadow-sm transition-all text-xs inline-flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.551 0 .9993.4482.9993.9993.0001.5511-.4483.9997-.9993.9997m-11.046 0c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993 0 .5511-.4482.9997-.9993.9997m11.4045-6.02l1.9973-3.4592a.416.416 0 00-.1521-.5676.416.416 0 00-.5676.1521l-2.0223 3.503C15.5902 8.411 13.8559 8.1 12 8.1s-3.5902.311-5.1368.8497L4.8409 5.4467a.4161.4161 0 00-.5677-.1521.4157.4157 0 00-.1521.5676l1.9973 3.4592C2.6889 11.1867.3432 14.6589 0 18.761h24c-.3432-4.1021-2.6889-7.5743-6.1185-9.4396"/></svg>
            <span>Unduh Aplikasi Mobile Siswa (.APK)</span>
        </a>
    </div>
</div>
@endsection

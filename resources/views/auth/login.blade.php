@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-3xl sm:rounded-[32px] shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[580px]">
    
    <!-- Kolom Kiri: Gambar Gedung Sekolah & Branding SMKN 10 Makassar -->
    <div class="lg:col-span-7 relative flex flex-col justify-between p-8 sm:p-10 lg:p-12 text-white bg-slate-950 overflow-hidden min-h-[380px] lg:min-h-[620px]">
        <!-- School Photo Background with Fallback & Rich Gradient Overlay -->
        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 hover:scale-105"
             style="background-image: url('https://rakyatsulsel.fajar.co.id/wp-content/uploads/2026/07/IMG-20260718-WA0006-scaled-e1784335208513.jpg');">
        </div>
        
        <!-- Multi-layer Gradient Overlay for Optimal Text Readability -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/75 to-slate-950/40 backdrop-blur-[1px]"></div>
        <div class="absolute inset-0 bg-blue-950/20 mix-blend-multiply"></div>

        <!-- Header Content: Badge & Logo -->
        <div class="relative z-10">
            <div class="flex items-center gap-3.5">
                <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md p-2.5 border border-white/20 shadow-xl flex items-center justify-center flex-shrink-0">
                    <img src="{{ asset('logo-sekolah.png') }}" class="w-full h-full object-contain drop-shadow-md" width="56" height="56" alt="Logo SMKN 10 Makassar">
                </div>
                <div>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold tracking-wider text-emerald-300 uppercase bg-emerald-500/20 border border-emerald-400/30 px-3 py-0.5 rounded-full mb-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        PORTAL RESMI PKL
                    </span>
                    <h1 class="text-xl sm:text-2xl font-black text-white leading-tight drop-shadow-sm">SMK Negeri 10 Makassar</h1>
                </div>
            </div>
        </div>

        <!-- Middle Body Content -->
        <div class="relative z-10 my-auto py-6 sm:py-8 space-y-4">
            <div class="inline-block">
                <p class="text-xs font-bold uppercase tracking-widest text-blue-400 mb-1">Sistem Terpadu PKL</p>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white leading-tight tracking-tight drop-shadow-sm">
                    Monitoring & Evaluasi Praktik Kerja Lapangan
                </h2>
            </div>
            
            <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal max-w-lg">
                Platform digital presensi harian kamera geospasial (WITA), jurnal kegiatan magang siswa, serta instrumen penilaian kemitraan Dunia Usaha dan Dunia Industri (DUDI).
            </p>

            <!-- Feature Badges -->
            <div class="grid grid-cols-2 gap-2.5 pt-2">
                <div class="flex items-center gap-2.5 bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/15 text-xs text-white font-semibold">
                    <span class="text-base">📍</span>
                    <span class="leading-tight">Geofence & Geotag</span>
                </div>
                <div class="flex items-center gap-2.5 bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/15 text-xs text-white font-semibold">
                    <span class="text-base">📸</span>
                    <span class="leading-tight">Presensi Kamera Live</span>
                </div>
                <div class="flex items-center gap-2.5 bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/15 text-xs text-white font-semibold">
                    <span class="text-base">📝</span>
                    <span class="leading-tight">Jurnal Kegiatan Harian</span>
                </div>
                <div class="flex items-center gap-2.5 bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/15 text-xs text-white font-semibold">
                    <span class="text-base">⭐</span>
                    <span class="leading-tight">Penilaian Mitra DUDI</span>
                </div>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="relative z-10 pt-4 border-t border-white/15 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-300">
            <span class="inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Jl. Bontomanai No. 14, Kota Makassar
            </span>
            <span class="text-slate-400 font-mono text-[11px]">Zona Waktu WITA (UTC+8)</span>
        </div>
    </div>

    <!-- Kolom Kanan: Form Login & Unduh APK Mobile -->
    <div class="lg:col-span-5 p-8 sm:p-10 lg:p-12 flex flex-col justify-between h-full bg-white dark:bg-slate-900">
        <div>
            <!-- Form Title -->
            <div class="mb-8">
                <!-- Mobile only logo display -->
                <div class="inline-flex lg:hidden items-center justify-center w-16 h-16 rounded-2xl bg-slate-50 dark:bg-slate-800 p-2.5 mb-4 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <img src="{{ asset('logo-sekolah.png') }}" class="w-full h-full object-contain" width="64" height="64" alt="Logo SMKN 10 Makassar">
                </div>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Masuk ke Akun</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 font-medium">Gunakan username dan password yang telah didaftarkan untuk masuk ke sistem.</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="username" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                            placeholder="Masukkan username Anda"
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl pl-11 pr-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-xs font-medium">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative" x-data="{ showPass: false }">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input :type="showPass ? 'text' : 'password'" name="password" id="password" required
                            placeholder="••••••••"
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl pl-11 pr-11 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-xs font-medium">
                        <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none">
                            <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 px-5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/30 transition-all text-xs inline-flex items-center justify-center gap-2 group">
                        <span>Masuk ke Dashboard</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>

            <!-- Download Mobile APK Banner for Students -->
            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
                <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2.5 text-center">Khusus Pengguna Siswa (Android)</p>
                <a href="{{ asset('downloads/PKL-SMKN10-Siswa.apk') }}" download="PKL-SMKN10-Siswa.apk" class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-md shadow-emerald-500/10 hover:shadow-emerald-500/20 transition-all text-xs inline-flex items-center justify-center gap-2.5 group">
                    <svg class="w-4 h-4 text-emerald-100 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.551 0 .9993.4482.9993.9993.0001.5511-.4483.9997-.9993.9997m-11.046 0c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993 0 .5511-.4482.9997-.9993.9997m11.4045-6.02l1.9973-3.4592a.416.416 0 00-.1521-.5676.416.416 0 00-.5676.1521l-2.0223 3.503C15.5902 8.411 13.8559 8.1 12 8.1s-3.5902.311-5.1368.8497L4.8409 5.4467a.4161.4161 0 00-.5677-.1521.4157.4157 0 00-.1521.5676l1.9973 3.4592C2.6889 11.1867.3432 14.6589 0 18.761h24c-.3432-4.1021-2.6889-7.5743-6.1185-9.4396"/></svg>
                    <span>Unduh Aplikasi Mobile Siswa (.APK)</span>
                </a>
            </div>
        </div>

        <!-- Footer Copyright -->
        <div class="mt-8 pt-4 border-t border-slate-100 dark:border-slate-800 text-center">
            <p class="text-[11px] text-slate-400 dark:text-slate-500">
                &copy; {{ date('Y') }} SMK Negeri 10 Makassar • Hak Cipta Dilindungi
            </p>
        </div>
    </div>
</div>
@endsection

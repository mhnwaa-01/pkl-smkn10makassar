@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')
<div class="space-y-8">

    <!-- Welcome Hero Banner -->
    <div class="bg-slate-900 text-white border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm relative overflow-hidden">
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 bg-blue-500/20 text-blue-300 border border-blue-500/30 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
                <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Pusat Kendali Administrator</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                Selamat Datang, {{ Auth::user()->name }}
            </h2>
            <p class="text-sm text-slate-300 mt-2 max-w-2xl font-normal leading-relaxed">
                Sistem Terpadu Monitoring Presensi Harian Kamera, Jurnal Lapangan, dan Penilaian Praktik Kerja Lapangan (PKL) SMKN 10 Makassar (Zona Waktu WITA).
            </p>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Siswa -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Siswa PKL</p>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-1.5">{{ $stats['total_students'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <span class="text-xs text-slate-500 font-medium">Siswa Terdaftar</span>
                <span class="text-xs font-bold text-blue-600 dark:text-blue-400">Aktif</span>
            </div>
        </div>

        <!-- Guru Pembimbing -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Guru Pembimbing</p>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-1.5">{{ $stats['total_teachers'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <span class="text-xs text-slate-500 font-medium">Pembimbing Sekolah</span>
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Siap Bimbing</span>
            </div>
        </div>

        <!-- Mitra Industri -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Mitra Industri (DUDI)</p>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-1.5">{{ $stats['total_industries'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-full bg-amber-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M9 16h.01M9 12h.01M9 8h.01M15 16h.01M15 12h.01M15 8h.01"/></svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <span class="text-xs text-slate-500 font-medium">Perusahaan Rekanan</span>
                <span class="text-xs font-bold text-amber-600 dark:text-amber-400">Kerja Sama</span>
            </div>
        </div>

        <!-- Presensi Masuk Hari Ini -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Presensi Masuk Hari Ini</p>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-1.5">{{ $stats['today_attendance'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-full bg-cyan-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <span class="text-xs text-slate-500 font-medium">Hadir Tepat & Terlambat</span>
                <span class="text-xs font-bold text-cyan-600 dark:text-cyan-400">Real-time</span>
            </div>
        </div>

        <!-- Jurnal Menunggu -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jurnal Menunggu Verifikasi</p>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-1.5">{{ $stats['pending_journals'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-full bg-orange-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <span class="text-xs text-slate-500 font-medium">Perlu Review Industri</span>
                <span class="text-xs font-bold text-orange-600 dark:text-orange-400">Pending</span>
            </div>
        </div>

        <!-- Penilaian Selesai -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Penilaian Selesai</p>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-1.5">{{ $stats['total_evaluations'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-full bg-purple-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <span class="text-xs text-slate-500 font-medium">Siswa Dinilai Lengkap</span>
                <span class="text-xs font-bold text-purple-600 dark:text-purple-400">Tuntas</span>
            </div>
        </div>
    </div>

    <!-- Recent Activity Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Journals -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Jurnal Harian Terbaru</h3>
                    </div>
                    <a href="{{ route('journals.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-bold inline-flex items-center gap-1">
                        Lihat Semua
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($recent_journals as $journal)
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200 dark:border-slate-700 flex items-center justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $journal->student->name ?? 'Siswa' }}</p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 truncate font-medium">{{ $journal->activity_title }} (@formatdate($journal->date))</p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button type="button" @click="openJournalModal(@js($journal->activity_title), @js(\Carbon\Carbon::parse($journal->date)->setTimezone('Asia/Makassar')->locale('id')->translatedFormat('l, d F Y')), @js($journal->student->name ?? 'Siswa'), @js($journal->student->class_name ?? '-'), @js($journal->activity_description), @js($journal->photo ? asset('storage/' . $journal->photo) : ''), @js($journal->status), @js($journal->verification_notes ?? ''))"
                                    class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-full transition-colors inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Lihat</span>
                                </button>
                                <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full uppercase tracking-wider
                                    @if($journal->status === 'approved') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300
                                    @elseif($journal->status === 'rejected') bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300
                                    @else bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 @endif">
                                    {{ $journal->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-6 text-center">Belum ada jurnal harian yang diisi.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Attendances -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-cyan-600 text-white flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Presensi Masuk Hari Ini</h3>
                    </div>
                    <a href="{{ route('attendance.index') }}" class="text-xs text-cyan-600 dark:text-cyan-400 hover:underline font-bold inline-flex items-center gap-1">
                        Lihat Rekap
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($recent_attendances as $attendance)
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200 dark:border-slate-700 flex items-center justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $attendance->student->name ?? 'Siswa' }}</p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Jam Masuk: <strong class="text-slate-900 dark:text-white">{{ $attendance->check_in_time }} WITA</strong></p>
                            </div>
                            <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full uppercase tracking-wider flex-shrink-0
                                @if($attendance->check_in_status === 'Tepat Waktu') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300
                                @else bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300 @endif">
                                {{ $attendance->check_in_status }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-6 text-center">Belum ada presensi masuk hari ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

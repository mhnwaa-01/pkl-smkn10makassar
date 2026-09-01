@extends('layouts.app')

@section('title', 'Dashboard Guru Pembimbing')

@section('content')
<div class="space-y-8">
    <!-- Welcome Hero Banner -->
    <div class="bg-slate-900 text-white border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm relative overflow-hidden">
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                <span>Panel Guru Pembimbing Lapangan</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                Selamat Datang, {{ Auth::user()->name }}
            </h2>
            <p class="text-sm text-slate-300 mt-2 max-w-2xl font-normal leading-relaxed">
                Pantau presensi harian siswa bimbingan, verifikasi pencatatan jurnal, dan catat kunjungan monitoring PKL langsung di industri (Waktu WITA).
            </p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Siswa Bimbingan -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Siswa Bimbingan</p>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-1.5">{{ $stats['my_students'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                <span class="text-xs font-bold text-blue-600 dark:text-blue-400">Peserta Didik</span>
            </div>
        </div>

        <!-- Presensi Hari Ini -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Presensi Hari Ini</p>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-1.5">{{ $stats['today_attendance'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-full bg-cyan-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                <span class="text-xs font-bold text-cyan-600 dark:text-cyan-400">Kehadiran Siswa</span>
            </div>
        </div>

        <!-- Jurnal Menunggu -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jurnal Menunggu</p>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-1.5">{{ $stats['pending_journals'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-full bg-amber-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                <span class="text-xs font-bold text-amber-600 dark:text-amber-400">Menunggu Review</span>
            </div>
        </div>

        <!-- Total Kunjungan Monitoring -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kunjungan Monitoring</p>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-1.5">{{ $stats['total_monitorings'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Catatan Kunjungan</span>
            </div>
        </div>
    </div>

    <!-- Monitoring & Journal Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Jurnal Siswa Bimbingan -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Jurnal Siswa Bimbingan</h3>
                    </div>
                    <a href="{{ route('journals.index') }}" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline font-bold inline-flex items-center gap-1">
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
                        <p class="text-xs text-slate-500 py-6 text-center">Belum ada jurnal dari siswa bimbingan.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Histori Kunjungan Monitoring -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-amber-600 text-white flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Kunjungan Monitoring</h3>
                    </div>
                    <a href="{{ route('monitoring.create') }}" class="inline-flex items-center gap-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-full transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Catatan</span>
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($recent_monitorings as $monitoring)
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200 dark:border-slate-700">
                            <div class="flex items-center justify-between">
                                <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $monitoring->industry->name ?? 'Industri' }}</p>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 font-semibold">@formatdate($monitoring->visit_date)</span>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 line-clamp-2 leading-relaxed">{{ $monitoring->notes }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-6 text-center">Belum ada catatan kunjungan monitoring.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

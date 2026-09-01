@extends('layouts.app')

@section('title', 'Dashboard Pembimbing Industri')

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner -->
    <div class="bg-slate-900 text-white border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm relative overflow-hidden">
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 bg-amber-500/20 text-amber-300 border border-amber-500/30 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M9 16h.01M9 12h.01M9 8h.01M15 16h.01M15 12h.01M15 8h.01"/></svg>
                <span>Panel Pembimbing Industri & Perusahaan</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                Selamat Datang, {{ Auth::user()->name }}
            </h2>
            <p class="text-sm text-slate-300 mt-2 max-w-2xl font-normal leading-relaxed">
                Kelola presensi harian, verifikasi jurnal kegiatan siswa magang, serta berikan penilaian instrumen PKL secara terpadu dan real-time (WITA).
            </p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Siswa Magang -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Siswa Magang</p>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-1.5">{{ $stats['intern_students'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                <span class="text-xs font-bold text-blue-600 dark:text-blue-400">Siswa Aktif PKL</span>
            </div>
        </div>

        <!-- Card 2: Presensi Masuk Hari Ini -->
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
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                <span class="text-xs font-bold text-cyan-600 dark:text-cyan-400">Kehadiran Hari Ini</span>
            </div>
        </div>

        <!-- Card 3: Jurnal Perlu Verifikasi -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jurnal Perlu Verifikasi</p>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-1.5">{{ $stats['pending_journals'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-full bg-amber-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                <span class="text-xs font-bold text-amber-600 dark:text-amber-400">Menunggu Verifikasi</span>
            </div>
        </div>

        <!-- Card 4: Siswa Sudah Dinilai -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Siswa Sudah Dinilai</p>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-1.5">{{ $stats['evaluated_students'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Evaluasi Selesai</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Pending Journals -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Jurnal Harian Siswa Magang</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Verifikasi aktivitas dan pencatatan kerja lapangan siswa</p>
                </div>
            </div>
            <a href="{{ route('journals.index') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-full text-xs font-semibold transition-colors inline-flex items-center gap-1 shadow-sm">
                <span>Halaman Verifikasi</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <div class="space-y-3">
            @forelse($recent_journals as $journal)
                <div class="p-4 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-sm flex-shrink-0 shadow-sm">
                            {{ strtoupper(substr($journal->student->name ?? 'S', 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $journal->student->name ?? 'Siswa' }}</span>
                                <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 bg-slate-200 dark:bg-slate-700 px-2.5 py-0.5 rounded-full">
                                    {{ $journal->student->class_name ?? '-' }}
                                </span>
                                <span class="text-xs text-slate-500 dark:text-slate-400">
                                    • @formatdate($journal->date)
                                </span>
                            </div>
                            <p class="text-xs font-semibold text-slate-900 dark:text-white mt-1">{{ $journal->activity_title }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5 line-clamp-1 leading-relaxed">{{ $journal->activity_description }}</p>
                        </div>
                    </div>

                    @if($journal->status === 'pending')
                        <div class="flex items-center gap-2 flex-shrink-0 w-full sm:w-auto justify-end">
                            <button type="button" @click="openJournalModal(@js($journal->activity_title), @js(\Carbon\Carbon::parse($journal->date)->setTimezone('Asia/Makassar')->locale('id')->translatedFormat('l, d F Y')), @js($journal->student->name ?? 'Siswa'), @js($journal->student->class_name ?? '-'), @js($journal->activity_description), @js($journal->photo ? asset('storage/' . $journal->photo) : ''), @js($journal->status), @js($journal->verification_notes ?? ''))"
                                class="px-3.5 py-1.5 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-full transition-colors inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Lihat</span>
                            </button>
                            <form action="{{ route('journals.verify', $journal) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-full transition-colors inline-flex items-center gap-1 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Setujui</span>
                                </button>
                            </form>
                            <form action="{{ route('journals.verify', $journal) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-full transition-colors inline-flex items-center gap-1 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    <span>Tolak</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center gap-2 flex-shrink-0 w-full sm:w-auto justify-end">
                            <button type="button" @click="openJournalModal(@js($journal->activity_title), @js(\Carbon\Carbon::parse($journal->date)->setTimezone('Asia/Makassar')->locale('id')->translatedFormat('l, d F Y')), @js($journal->student->name ?? 'Siswa'), @js($journal->student->class_name ?? '-'), @js($journal->activity_description), @js($journal->photo ? asset('storage/' . $journal->photo) : ''), @js($journal->status), @js($journal->verification_notes ?? ''))"
                                class="px-3.5 py-1.5 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-full transition-colors inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Lihat</span>
                            </button>
                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full uppercase
                                @if($journal->status === 'approved') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300
                                @else bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300 @endif">
                                {{ $journal->status }}
                            </span>
                        </div>
                    @endif
                </div>
            @empty
                <div class="py-10 text-center">
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum Ada Jurnal Menunggu</p>
                    <p class="text-xs text-slate-500 mt-0.5">Semua jurnal harian siswa telah diverifikasi.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

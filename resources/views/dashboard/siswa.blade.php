@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="space-y-8">

    <!-- Welcome Hero Banner -->
    <div class="bg-slate-900 text-white border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm relative overflow-hidden">
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 bg-purple-500/20 text-purple-300 border border-purple-500/30 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
                <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                <span>Portal Siswa Praktik Kerja Lapangan</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                Selamat Datang, {{ Auth::user()->name }}
            </h2>
            <div class="flex flex-wrap items-center justify-between gap-3 mt-4 text-xs text-slate-300 font-medium">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 bg-slate-800 px-3.5 py-1.5 rounded-full border border-slate-700">
                        <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M9 16h.01M9 12h.01M9 8h.01M15 16h.01M15 12h.01M15 8h.01"/></svg>
                        Industri: <strong class="text-white ml-1">{{ Auth::user()->student->industry->name ?? 'Belum Ditentukan' }}</strong>
                    </span>
                    <span class="inline-flex items-center gap-1.5 bg-slate-800 px-3.5 py-1.5 rounded-full border border-slate-700">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Guru Pembimbing: <strong class="text-white ml-1">{{ Auth::user()->student->teacher->name ?? 'Belum Ditentukan' }}</strong>
                    </span>
                </div>
                <div>
                    <a href="{{ route('download.apk') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full text-xs font-bold transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.551 0 .9993.4482.9993.9993.0001.5511-.4483.9997-.9993.9997m-11.046 0c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993 0 .5511-.4482.9997-.9993.9997m11.4045-6.02l1.9973-3.4592a.416.416 0 00-.1521-.5676.416.416 0 00-.5676.1521l-2.0223 3.503C15.5902 8.411 13.8559 8.1 12 8.1s-3.5902.311-5.1368.8497L4.8409 5.4467a.4161.4161 0 00-.5677-.1521.4157.4157 0 00-.1521.5676l1.9973 3.4592C2.6889 11.1867.3432 14.6589 0 18.761h24c-.3432-4.1021-2.6889-7.5743-6.1185-9.4396"/></svg>
                        <span>Unduh APK Android</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Status Card for Today -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status Presensi Hari Ini (@formatdate(today()))</span>
                </div>
                
                @if(!$todayAttendance)
                    <div class="flex items-center gap-3 mt-3">
                        <span class="inline-flex items-center gap-2 px-3.5 py-1 bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 rounded-full text-xs font-bold">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Belum Presensi Datang
                        </span>
                    </div>
                @else
                    <div class="flex flex-wrap items-center gap-3 mt-3">
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 rounded-full text-xs font-bold">
                            <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Datang: {{ $todayAttendance->check_in_time }} WITA ({{ $todayAttendance->check_in_status }})
                        </span>
                        @if($todayAttendance->check_out_time)
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300 rounded-full text-xs font-bold">
                                <svg class="w-3.5 h-3.5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Pulang: {{ $todayAttendance->check_out_time }} WITA ({{ $todayAttendance->check_out_status }})
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 rounded-full text-xs font-bold">
                                Lama Kerja: {{ $todayAttendance->work_duration }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-xs font-semibold">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Belum Presensi Pulang
                            </span>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('attendance.index') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-full transition-colors inline-flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Buka Kamera Presensi</span>
                </a>
                <a href="{{ route('journals.create') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-full transition-colors inline-flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Isi Jurnal Hari Ini</span>
                </a>
                <a href="{{ route('journals.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-xs rounded-full border border-slate-200 dark:border-slate-700 transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Riwayat Jurnal</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats & Grade Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Total Jurnal -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Jurnal Dikirim</p>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-1.5">{{ $totalJournals }}</h3>
                </div>
                <div class="w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                <span class="text-xs font-bold text-blue-600 dark:text-blue-400">Aktivitas Harian Siswa</span>
            </div>
        </div>

        <!-- Jurnal Disetujui -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jurnal Terverifikasi</p>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-1.5">{{ $approvedJournals }}</h3>
                </div>
                <div class="w-14 h-14 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Disetujui Pembimbing</span>
            </div>
        </div>

        <!-- Nilai Akhir -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nilai Akhir PKL</p>
                    <div class="flex items-center gap-2 mt-1.5">
                        <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white">{{ ($evaluation && $evaluation->final_score > 0) ? $evaluation->final_score : '-' }}</h3>
                        @if($evaluation && $evaluation->final_score > 0)
                            <span class="px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 text-xs font-bold rounded-full">
                                Predikat: {{ $evaluation->predicate }}
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 text-xs font-bold rounded-full">
                                Belum Lengkap
                            </span>
                        @endif
                    </div>
                </div>
                <div class="w-14 h-14 rounded-full bg-purple-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('evaluations.index') }}" class="text-xs font-bold text-purple-600 dark:text-purple-400 hover:underline">Lihat Rapor Evaluasi →</a>
            </div>
        </div>
    </div>

    <!-- Recent Journals Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Jurnal Kegiatan Terakhir Anda</h3>
            </div>
            <a href="{{ route('journals.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-bold inline-flex items-center gap-1">
                Semua Jurnal
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="space-y-3">
            @forelse($recent_journals as $journal)
                <div class="p-3.5 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200 dark:border-slate-700 flex items-center justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $journal->activity_title }}</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">@formatdate($journal->date)</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button type="button" @click="openJournalModal(@js($journal->activity_title), @js(\Carbon\Carbon::parse($journal->date)->setTimezone('Asia/Makassar')->locale('id')->translatedFormat('l, d F Y')), @js(Auth::user()->name), @js(Auth::user()->student->class_name ?? '-'), @js($journal->activity_description), @js($journal->photo ? asset('storage/' . $journal->photo) : ''), @js($journal->status), @js($journal->verification_notes ?? ''))"
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
                <div class="py-8 text-center">
                    <p class="text-xs text-slate-500">Belum ada jurnal yang diisi. Klik "+ Isi Jurnal Hari Ini" untuk menambah kegiatan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

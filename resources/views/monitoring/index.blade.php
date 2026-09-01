@extends('layouts.app')

@section('title', 'Monitoring PKL')

@section('content')
<div class="space-y-8">

    <!-- Header -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-full bg-amber-600 text-white flex items-center justify-center shadow-sm flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Catatan Monitoring & Kunjungan PKL</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">Log Bimbingan Lapangan Guru Pembimbing ke Industri Mitra SMKN 10.</p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('monitoring.export.excel') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Download Excel</span>
            </a>
            <a href="{{ route('monitoring.export.pdf') }}" target="_blank" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Download PDF</span>
            </a>
            @if(Auth::user()->isGuru() || Auth::user()->isAdmin())
                <a href="{{ route('monitoring.create') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Catat Monitoring</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Monitoring Cards List -->
    <div class="space-y-4">
        @forelse($monitorings as $item)
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M9 16h.01M9 12h.01M9 8h.01M15 16h.01M15 12h.01M15 8h.01"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ $item->industry->name ?? '-' }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Guru Pembimbing: <strong class="text-slate-800 dark:text-slate-200 font-semibold">{{ $item->teacher->name ?? '-' }}</strong>
                            </p>
                        </div>
                    </div>
                    <span class="px-3.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-mono font-bold rounded-full">
                        @formatdate($item->visit_date)
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                    <!-- Catatan Bimbingan -->
                    <div class="p-3.5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800">
                        <span class="text-[11px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider block mb-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Catatan Bimbingan</span>
                        </span>
                        <p class="text-slate-700 dark:text-slate-300 leading-relaxed">{{ $item->notes }}</p>
                    </div>

                    <!-- Kendala Lapangan -->
                    <div class="p-3.5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800">
                        <span class="text-[11px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider block mb-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Kendala Lapangan</span>
                        </span>
                        <p class="text-slate-700 dark:text-slate-300 leading-relaxed">{{ $item->obstacles ?? 'Tidak ada kendala' }}</p>
                    </div>

                    <!-- Rekomendasi / Tindak Lanjut -->
                    <div class="p-3.5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800">
                        <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block mb-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Rekomendasi Tindak Lanjut</span>
                        </span>
                        <p class="text-slate-700 dark:text-slate-300 leading-relaxed">{{ $item->recommendations ?? 'Tidak ada rekomendasi khusus' }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-10 text-center text-slate-500 font-medium">
                Belum ada catatan kunjungan monitoring PKL.
            </div>
        @endforelse
    </div>

    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
        {{ $monitorings->links() }}
    </div>
</div>
@endsection

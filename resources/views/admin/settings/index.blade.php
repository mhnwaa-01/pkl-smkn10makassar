@extends('layouts.app')

@section('title', 'Pengaturan Jam Presensi')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">

    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-sm flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Pengaturan Jam Datang & Jam Pulang</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">Atur batasan jam presensi serta acuan waktu terlambat dan pulang normal untuk siswa PKL.</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Jam Datang Section -->
            <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                <h3 class="text-xs font-bold text-slate-900 dark:text-white flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Presensi Datang (Pagi)</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="check_in_start" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Jam Buka Presensi Datang</label>
                        <input type="time" name="check_in_start" id="check_in_start" value="{{ old('check_in_start', substr($setting->check_in_start, 0, 5)) }}" required
                            class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-slate-100 font-mono font-semibold focus:outline-none focus:border-blue-500 transition-colors">
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 px-2">Siswa dapat mulai absen sejak jam ini (Default: 06:00)</p>
                    </div>

                    <div>
                        <label for="check_in_late_time" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Batas Toleransi / Terlambat</label>
                        <input type="time" name="check_in_late_time" id="check_in_late_time" value="{{ old('check_in_late_time', substr($setting->check_in_late_time, 0, 5)) }}" required
                            class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-slate-100 font-mono font-semibold focus:outline-none focus:border-blue-500 transition-colors">
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 px-2">Absen setelah jam ini tercatat <strong>TERLAMBAT</strong></p>
                    </div>
                </div>
            </div>

            <!-- Jam Pulang Section -->
            <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                <h3 class="text-xs font-bold text-slate-900 dark:text-white flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <span>Presensi Pulang (Sore)</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="check_out_early_time" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Batas Buka Absen Pulang</label>
                        <input type="time" name="check_out_early_time" id="check_out_early_time" value="{{ old('check_out_early_time', substr($setting->check_out_early_time, 0, 5)) }}" required
                            class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-slate-100 font-mono font-semibold focus:outline-none focus:border-blue-500 transition-colors">
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 px-2">Siswa mulai bisa absen pulang sejak jam ini</p>
                    </div>

                    <div>
                        <label for="check_out_time" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Jam Pulang Normal (Tepat Waktu)</label>
                        <input type="time" name="check_out_time" id="check_out_time" value="{{ old('check_out_time', substr($setting->check_out_time, 0, 5)) }}" required
                            class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-slate-100 font-mono font-semibold focus:outline-none focus:border-blue-500 transition-colors">
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 px-2">Absen sebelum jam ini tercatat <strong>PULANG CEPAT</strong></p>
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Pengaturan Jam Presensi</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

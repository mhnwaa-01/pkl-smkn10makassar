@extends('layouts.app')

@section('title', 'Laporan Penilaian PKL')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Header Banner -->
    <div class="bg-slate-900 text-white border border-slate-800 rounded-3xl p-6 sm:p-8 text-center shadow-sm relative overflow-hidden">
        <div class="relative z-10">
            <div class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-3 text-white shadow-md">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight">Rapor & Sertifikat Evaluasi PKL</h2>
            <p class="text-xs text-slate-300 font-medium mt-1">SMKN 10 Makassar — Instrumen Terpadu Kemitraan Industri</p>
        </div>
    </div>

    @if(!$evaluation)
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-10 text-center shadow-sm">
            <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-base font-bold text-slate-900 dark:text-white">Penilaian Belum Tersedia</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-md mx-auto">Penilaian PKL Anda sedang dalam proses pengisian oleh Pembimbing Industri dan Guru Pembimbing.</p>
        </div>
    @else
        <!-- Report Card Details -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 space-y-6 shadow-sm">
            
            <!-- Student Header Info -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800 text-xs">
                <div>
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold tracking-wider block">Nama Siswa</span>
                    <strong class="text-slate-900 dark:text-white font-bold text-sm mt-0.5 block">{{ $student->name }}</strong>
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold tracking-wider block">NISN</span>
                    <strong class="text-slate-900 dark:text-white font-bold text-sm mt-0.5 block">{{ $student->nisn }}</strong>
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold tracking-wider block">Kelas & Jurusan</span>
                    <strong class="text-blue-600 dark:text-blue-400 font-bold text-sm mt-0.5 block">{{ $student->class_name }} ({{ $student->major->name ?? '-' }})</strong>
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold tracking-wider block">Lokasi PKL</span>
                    <strong class="text-emerald-600 dark:text-emerald-400 font-bold text-sm mt-0.5 block truncate">{{ $student->industry->name ?? '-' }}</strong>
                </div>
            </div>

            <!-- Grade Aspects Table -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3">Rincian Capaian Aspek Penilaian:</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 uppercase tracking-wider font-bold border-b border-slate-200 dark:border-slate-800">
                            <tr>
                                <th class="p-3">No</th>
                                <th class="p-3">Kompetensi / Aspek Evaluasi</th>
                                <th class="p-3 text-right">Nilai Angka (0-100)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="p-3 font-bold text-slate-500">1</td>
                                <td class="p-3 font-semibold text-slate-900 dark:text-slate-100">
                                    Aspek Sikap & Etika Kerja (Attitude & K3LH - Form 1)
                                </td>
                                <td class="p-3 text-right font-mono font-bold text-sm text-slate-900 dark:text-white">{{ $evaluation->aspect_attitude }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="p-3 font-bold text-slate-500">2</td>
                                <td class="p-3 font-semibold text-slate-900 dark:text-slate-100">
                                    Aspek Keahlian Teknis (Technical Hard Skills - Form 2 & 3)
                                </td>
                                <td class="p-3 text-right font-mono font-bold text-sm text-slate-900 dark:text-white">{{ $evaluation->aspect_technical }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="p-3 font-bold text-slate-500">3</td>
                                <td class="p-3 font-semibold text-slate-900 dark:text-slate-100">
                                    Aspek Kedisiplinan & Kemandirian Wirausaha (Form 4)
                                </td>
                                <td class="p-3 text-right font-mono font-bold text-sm text-slate-900 dark:text-white">{{ $evaluation->aspect_managerial }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="p-3 font-bold text-slate-500">4</td>
                                <td class="p-3 font-semibold text-slate-900 dark:text-slate-100">
                                    Laporan Akhir PKL (Report - Form 5)
                                </td>
                                <td class="p-3 text-right font-mono font-bold text-sm text-slate-900 dark:text-white">{{ $evaluation->aspect_report }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="p-3 font-bold text-slate-500">5</td>
                                <td class="p-3 font-semibold text-slate-900 dark:text-slate-100">
                                    Presentasi Hasil Praktik Lapangan (Presentation - Form 6)
                                </td>
                                <td class="p-3 text-right font-mono font-bold text-sm text-slate-900 dark:text-white">{{ $evaluation->aspect_presentation }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Final Result & Predicate Card -->
            @if($evaluation->aspect_attitude > 0 && $evaluation->aspect_technical > 0 && $evaluation->aspect_managerial > 0 && $evaluation->aspect_report > 0 && $evaluation->aspect_presentation > 0)
                <div class="p-5 bg-slate-900 text-white rounded-2xl border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <span class="text-xs text-slate-300 font-semibold uppercase tracking-wider block">Nilai Akhir Rata-Rata PKL</span>
                        <h3 class="text-3xl font-extrabold text-emerald-400 font-mono mt-1">{{ $evaluation->final_score }}</h3>
                    </div>
                    <div class="text-center sm:text-right">
                        <span class="text-xs text-slate-300 font-semibold uppercase tracking-wider block">Predikat Kualifikasi</span>
                        <span class="inline-block mt-1 px-4 py-1.5 bg-emerald-600 text-white text-sm font-bold rounded-full shadow-sm">
                            Predikat: {{ $evaluation->predicate }}
                        </span>
                    </div>
                </div>
            @else
                <div class="p-5 bg-amber-50 dark:bg-slate-950 rounded-2xl border border-amber-200 dark:border-amber-900/40 text-center">
                    <span class="text-xs text-amber-800 dark:text-amber-300 font-bold uppercase tracking-wider block">Status Penilaian</span>
                    <p class="text-slate-600 dark:text-slate-400 text-xs mt-1 font-medium">Nilai akhir dan predikat kelulusan akan muncul setelah semua formulir penilaian diisi lengkap oleh industri dan guru pembimbing.</p>
                </div>
            @endif

            @if($evaluation->notes)
                <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800 text-xs">
                    <span class="text-slate-500 dark:text-slate-400 font-bold block mb-1">Catatan Umpan Balik Pembimbing:</span>
                    <p class="text-slate-800 dark:text-slate-200 italic font-medium">"{{ $evaluation->notes }}"</p>
                </div>
            @endif

        </div>
    @endif
</div>
@endsection

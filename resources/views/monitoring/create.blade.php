@extends('layouts.app')

@section('title', 'Tambah Catatan Monitoring')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">

    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-full bg-amber-600 text-white flex items-center justify-center shadow-sm flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Catat Kunjungan Monitoring PKL</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">Masukkan hasil observasi dan bimbingan lapangan ke industri mitra SMKN 10.</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 shadow-sm">
        <form action="{{ route('monitoring.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="industry_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 px-2">Pilih Industri Mitra</label>
                <select name="industry_id" id="industry_id" required
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2.5 text-xs text-slate-900 dark:text-slate-100 font-semibold focus:outline-none focus:border-blue-500 transition-colors">
                    <option value="">-- Pilih Industri --</option>
                    @foreach($industries as $ind)
                        <option value="{{ $ind->id }}">{{ $ind->name }} (Penanggung Jawab: {{ $ind->contact_person }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="visit_date" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 px-2">Tanggal Kunjungan</label>
                <input type="date" name="visit_date" id="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" required
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2.5 text-xs text-slate-900 dark:text-slate-100 font-semibold focus:outline-none focus:border-blue-500 transition-colors">
            </div>

            <div>
                <label for="notes" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 px-2">Catatan Bimbingan & Progress Siswa</label>
                <textarea name="notes" id="notes" rows="4" required
                    placeholder="Masukkan rincian perkembangan keterampilan, kedisiplinan, dan kondisi siswa magang..."
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl px-4 py-2.5 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-500 transition-colors leading-relaxed">{{ old('notes') }}</textarea>
            </div>

            <div>
                <label for="obstacles" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 px-2">Kendala Lapangan (Jika Ada)</label>
                <textarea name="obstacles" id="obstacles" rows="2"
                    placeholder="Masukkan kendala teknis atau masalah lain yang disampaikan industri/siswa..."
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl px-4 py-2.5 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-500 transition-colors">{{ old('obstacles') }}</textarea>
            </div>

            <div>
                <label for="recommendations" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 px-2">Rekomendasi / Solusi</label>
                <textarea name="recommendations" id="recommendations" rows="2"
                    placeholder="Masukkan langkah perbaikan atau saran untuk siswa dan industri..."
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl px-4 py-2.5 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-500 transition-colors">{{ old('recommendations') }}</textarea>
            </div>

            <div>
                <label for="photo" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 px-2">Foto Dokumentasi Kunjungan (Opsional)</label>
                <input type="file" name="photo" id="photo" accept="image/*"
                    class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900/30 dark:file:text-blue-300 hover:file:bg-blue-100 transition-colors">
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    <span>Simpan Catatan Monitoring</span>
                </button>
                <a href="{{ route('monitoring.index') }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs rounded-full transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

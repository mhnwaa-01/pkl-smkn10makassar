@extends('layouts.app')

@section('title', 'Tambah Jurnal Harian')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">

    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-sm flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Form Pengisian Jurnal Harian PKL</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">Isi rincian pekerjaan dan kegiatan yang Anda lakukan hari ini secara lengkap.</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 shadow-sm">
        <form action="{{ route('journals.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="date" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 px-2">Tanggal Kegiatan</label>
                <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2.5 text-xs text-slate-900 dark:text-slate-100 font-semibold focus:outline-none focus:border-blue-500 transition-colors">
            </div>

            <div>
                <label for="activity_title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 px-2">Judul / Rangkuman Kegiatan</label>
                <input type="text" name="activity_title" id="activity_title" value="{{ old('activity_title') }}" required
                    placeholder="Contoh: Perbaikan Jaringan LAN & Konfigurasi Router MikroTik"
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2.5 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-500 transition-colors">
            </div>

            <div>
                <label for="activity_description" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 px-2">Deskripsi Rincian Pekerjaan</label>
                <textarea name="activity_description" id="activity_description" rows="5" required
                    placeholder="Jelaskan langkah-langkah kerja, alat/perangkat yang digunakan, kendala yang dihadapi, serta solusinya..."
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl px-4 py-2.5 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-500 transition-colors leading-relaxed">{{ old('activity_description') }}</textarea>
            </div>

            <div x-data="{ mode: 'gallery' }">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 px-2">Foto Bukti Kegiatan (Kamera Live / Unggah Galeri)</label>
                
                <!-- Choose Source Switcher -->
                <div class="flex gap-2 mb-3">
                    <button type="button" @click="mode = 'gallery'" 
                        :class="mode === 'gallery' ? 'bg-blue-600 text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold'" 
                        class="flex-1 py-2 rounded-full text-xs transition-colors inline-flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Dari Galeri / File</span>
                    </button>
                    <button type="button" @click="mode = 'camera'" 
                        :class="mode === 'camera' ? 'bg-blue-600 text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold'" 
                        class="flex-1 py-2 rounded-full text-xs transition-colors inline-flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span>Gunakan Kamera Live</span>
                    </button>
                </div>

                <!-- File input for gallery selection -->
                <div :class="mode === 'gallery' ? 'block' : 'hidden'">
                    <input type="file" name="photo" id="photo" accept="image/*" required
                        class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900/30 dark:file:text-blue-300 hover:file:bg-blue-100 transition-colors">
                </div>
                
                <!-- Camera capturing widget -->
                <div x-show="mode === 'camera'" class="mt-2 p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl space-y-3" style="display: none;">
                    <div x-data="cameraWidget('photo')" class="space-y-3">
                        <div class="relative bg-slate-950 rounded-xl overflow-hidden aspect-video flex items-center justify-center">
                            <video x-ref="video" autoplay playsinline class="w-full h-full object-cover" x-show="streamActive" style="transform: scaleX(-1);"></video>
                            <canvas x-ref="canvas" class="hidden"></canvas>
                            <img :src="capturedPhoto" class="w-full h-full object-cover" x-show="capturedPhoto" alt="Pratinjau Foto">
                            <div class="absolute inset-0 flex flex-col items-center justify-center p-4 text-center text-slate-400" x-show="!streamActive && !capturedPhoto">
                                <div class="w-12 h-12 rounded-full bg-slate-900 text-slate-600 flex items-center justify-center mb-1.5 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <span class="text-xs font-medium text-slate-500">Kamera belum aktif. Tekan "Aktifkan Kamera" untuk mengambil foto langsung.</span>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center bg-slate-950/80 text-white text-xs font-bold" x-show="loading">
                                <span class="animate-pulse">Mengaktifkan kamera...</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="startCamera()" class="flex-1 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-full text-xs font-semibold flex items-center justify-center gap-2 transition-colors" x-show="!streamActive">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Aktifkan Kamera</span>
                            </button>
                            <button type="button" @click="capturePhoto()" class="flex-1 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-full text-xs font-semibold flex items-center justify-center gap-2 transition-colors shadow-sm" x-show="streamActive">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                                <span>Ambil Foto</span>
                            </button>
                            <button type="button" @click="resetPhoto()" class="py-2 px-4 bg-rose-100 hover:bg-rose-200 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300 rounded-full text-xs font-semibold flex items-center justify-center gap-1 transition-colors" x-show="capturedPhoto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Ulangi</span>
                            </button>
                        </div>
                    </div>
                </div>

                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1.5 font-medium px-2">Format: JPG, PNG, WEBP (Maksimal 5MB)</p>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span>Kirim Jurnal Harian</span>
                </button>
                <a href="{{ route('journals.index') }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs rounded-full transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

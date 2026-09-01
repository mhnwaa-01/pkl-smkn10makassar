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

            <!-- Upload Foto Bukti Kegiatan (Galeri / Kamera HP) -->
            <div x-data="{ previewUrl: null }">
                <label for="photo" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 px-2">Foto Bukti Kegiatan</label>
                
                <div class="p-5 bg-slate-50 dark:bg-slate-950 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-3xl text-center hover:border-blue-500 transition-colors">
                    <!-- Image Preview if file selected -->
                    <template x-if="previewUrl">
                        <div class="mb-4 relative inline-block">
                            <img :src="previewUrl" class="max-h-60 max-w-full rounded-2xl object-cover shadow-lg mx-auto border border-slate-200 dark:border-slate-700" alt="Pratinjau Foto Kegiatan">
                            <button type="button" @click="previewUrl = null; document.getElementById('photo').value = ''" 
                                class="absolute -top-2 -right-2 w-7 h-7 bg-rose-600 hover:bg-rose-700 text-white rounded-full flex items-center justify-center text-xs font-bold shadow-md transition-colors"
                                title="Hapus Foto">
                                ✕
                            </button>
                        </div>
                    </template>

                    <div x-show="!previewUrl" class="py-3">
                        <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-2 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-700 dark:text-slate-200">Unggah Foto Bukti Kegiatan</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Bisa pilih dari galeri atau ambil foto langsung lewat kamera HP</p>
                    </div>

                    <input type="file" name="photo" id="photo" accept="image/*" required
                        @change="const f = $event.target.files && $event.target.files[0]; if (f) { const r = new FileReader(); r.onload = (e) => previewUrl = e.target.result; r.readAsDataURL(f); }"
                        class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-colors cursor-pointer mt-2">
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1.5 font-medium px-2">Format: JPG, JPEG, PNG, WEBP (Maksimal 5MB)</p>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-full shadow-md transition-all inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span>Kirim Jurnal Harian</span>
                </button>
                <a href="{{ route('journals.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-full transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

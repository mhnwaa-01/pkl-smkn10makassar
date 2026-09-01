@extends('layouts.app')

@section('title', 'Jurnal Harian PKL')

@section('content')
<div class="space-y-8" x-data="{
    verifyModalOpen: false,
    selectedJournalId: null,
    selectedStatus: 'approved',
    verificationNotes: '',
    openVerifyModal(id, status) {
        this.selectedJournalId = id;
        this.selectedStatus = status;
        this.verificationNotes = '';
        this.verifyModalOpen = true;
    }
}">

    <!-- Top Header -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-sm flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Jurnal Harian Siswa PKL</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">Daftar rekaman kegiatan dan laporan harian siswa di industri.</p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @if(!Auth::user()->isSiswa())
                <a href="{{ route('journals.export.excel', request()->all()) }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Download Excel</span>
                </a>
                <a href="{{ route('journals.export.pdf', request()->all()) }}" target="_blank" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Download PDF</span>
                </a>
            @endif
            @if(Auth::user()->isSiswa())
                <a href="{{ route('journals.create') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Jurnal</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm">
        <form action="{{ route('journals.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <select name="status" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-slate-200 font-semibold focus:outline-none focus:border-blue-500 transition-colors">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved (Disetujui)</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                </select>
            </div>
            <div>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-slate-200 font-semibold focus:outline-none focus:border-blue-500 transition-colors">
            </div>
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa / NISN..."
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:border-blue-500 transition-colors">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filter</span>
                </button>
                <a href="{{ route('journals.index') }}" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs rounded-full border border-slate-200 dark:border-slate-700 transition-colors inline-flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Journals Grid / List -->
    <div class="space-y-4">
        @forelse($journals as $journal)
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col md:flex-row gap-6">
                <!-- Journal Photo / Placeholder -->
                <div class="w-full md:w-48 h-36 bg-slate-100 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800 flex-shrink-0 overflow-hidden flex items-center justify-center relative">
                    @if($journal->photo_url)
                        <img src="{{ $journal->photo_url }}" alt="Foto Kegiatan" class="w-full h-full object-cover cursor-zoom-in" @click.prevent="openImagePreview('{{ $journal->photo_url }}')">
                        <div class="absolute bottom-2 right-2 px-2.5 py-0.5 bg-slate-900/80 rounded-full text-[10px] text-white font-semibold flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Zoom</span>
                        </div>
                    @else
                        <div class="text-center text-slate-400 p-4">
                            <svg class="w-8 h-8 mx-auto mb-1 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-[11px] font-medium">Tanpa Foto</span>
                        </div>
                    @endif
                </div>

                <!-- Journal Content Details -->
                <div class="flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-bold rounded-full">
                                    @formatdate($journal->date)
                                </span>
                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $journal->student->name ?? '-' }}</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">({{ $journal->student->class_name ?? '-' }})</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="openJournalModal(@js($journal->activity_title), @js(\Carbon\Carbon::parse($journal->date)->setTimezone('Asia/Makassar')->locale('id')->translatedFormat('l, d F Y')), @js($journal->student->name ?? 'Siswa'), @js($journal->student->class_name ?? '-'), @js($journal->activity_description), @js($journal->photo_url ?? ''), @js($journal->status), @js($journal->verification_notes ?? ''))"
                                    class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-full transition-colors inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Lihat Jurnal</span>
                                </button>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase
                                    @if($journal->status === 'approved') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300
                                    @elseif($journal->status === 'rejected') bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300
                                    @else bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 @endif">
                                    {{ $journal->status }}
                                </span>
                            </div>
                        </div>

                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1">{{ $journal->activity_title }}</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 whitespace-pre-line leading-relaxed">{{ $journal->activity_description }}</p>

                        @if($journal->verification_notes)
                            <div class="mt-3 p-3.5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800 text-xs">
                                <span class="text-slate-500 dark:text-slate-400 font-semibold block mb-0.5">Catatan Verifikasi Pembimbing:</span>
                                <p class="text-slate-700 dark:text-slate-300 italic">"{{ $journal->verification_notes }}"</p>
                            </div>
                        @endif
                    </div>

                    <!-- Action Bar for Industri / Admin -->
                    @if((Auth::user()->isIndustri() || Auth::user()->isAdmin()) && $journal->status === 'pending')
                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2">
                            <button @click="openVerifyModal('{{ $journal->id }}', 'approved')"
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-full shadow-sm transition-colors inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Setujui Jurnal</span>
                            </button>
                            <button @click="openVerifyModal('{{ $journal->id }}', 'rejected')"
                                class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-full shadow-sm transition-colors inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>Tolak dengan Catatan</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-10 text-center text-slate-500 font-medium shadow-sm">
                Belum ada jurnal harian yang sesuai filter.
            </div>
        @endforelse
    </div>

    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
        {{ $journals->links() }}
    </div>

    <!-- VERIFICATION MODAL FOR INDUSTRY/ADMIN -->
    <div x-show="verifyModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click="verifyModalOpen = false" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
        
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 max-w-md w-full z-10 shadow-2xl relative">
            <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1">Verifikasi Jurnal Harian</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                Pilih status verifikasi dan berikan catatan evaluasi jika diperlukan.
            </p>

            <form x-bind:action="'/journals/' + selectedJournalId + '/verify'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 px-2">Status Verifikasi</label>
                    <select name="status" x-model="selectedStatus" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2.5 text-xs text-slate-900 dark:text-slate-200 font-semibold focus:outline-none focus:border-blue-500">
                        <option value="approved">Disetujui (Approved)</option>
                        <option value="rejected">Ditolak / Perlu Perbaikan (Rejected)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 px-2">Catatan Pembimbing (Opsional)</label>
                    <textarea name="verification_notes" x-model="verificationNotes" rows="3" placeholder="Tuliskan catatan perbaikan atau feedback untuk siswa..."
                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl px-4 py-2.5 text-xs text-slate-900 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:border-blue-500"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors">
                        Simpan Verifikasi
                    </button>
                    <button type="button" @click="verifyModalOpen = false" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-full transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

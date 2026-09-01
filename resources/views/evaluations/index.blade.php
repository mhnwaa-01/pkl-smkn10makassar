@extends('layouts.app')

@section('title', 'Penilaian PKL')

@section('content')
<div class="space-y-8" x-data="{
    scoreModalOpen: false,
    selectedStudentId: null,
    selectedStudentName: '',
    form1Items: Array(9).fill(false),
    form2Items: Array(8).fill(false),
    form3Items: Array(7).fill(false),
    form4Items: Array(5).fill(false),
    form5Items: Array(4).fill(false),
    form6Items: Array(4).fill(false),
    notes: '',
    get aspectAttitude() {
        const count = this.form1Items.filter(Boolean).length;
        return Math.round((count / 9) * 100);
    },
    get aspectTechnical() {
        const f2Count = this.form2Items.filter(Boolean).length;
        const f3Count = this.form3Items.filter(Boolean).length;
        const f2Score = (f2Count / 8) * 100;
        const f3Score = (f3Count / 7) * 100;
        return Math.round((f2Score + f3Score) / 2);
    },
    get aspectManagerial() {
        const count = this.form4Items.filter(Boolean).length;
        return Math.round((count / 5) * 100);
    },
    get aspectReport() {
        const count = this.form5Items.filter(Boolean).length;
        return Math.round((count / 4) * 100);
    },
    get aspectPresentation() {
        const count = this.form6Items.filter(Boolean).length;
        return Math.round((count / 4) * 100);
    },
    get finalScore() {
        const att = this.aspectAttitude;
        const tech = this.aspectTechnical;
        const man = this.aspectManagerial;
        const rep = this.aspectReport;
        const pres = this.aspectPresentation;
        
        // Nilai akhir hanya muncul jika seluruh form terisi
        if (att === 0 || tech === 0 || man === 0 || rep === 0 || pres === 0) {
            return 'Belum Lengkap';
        }
        
        const sum = att + tech + man + rep + pres;
        return (sum / 5).toFixed(2);
    },
    get predicate() {
        const scoreStr = this.finalScore;
        if (scoreStr === 'Belum Lengkap') return 'Menunggu Semua Form';
        const score = parseFloat(scoreStr);
        if (score >= 85) return 'A (Sangat Baik)';
        if (score >= 75) return 'B (Baik)';
        if (score >= 65) return 'C (Cukup)';
        return 'D (Kurang)';
    },
    openScoreModal(studentId, studentName, att, tech, man, rep, pres, notesStr) {
        this.selectedStudentId = studentId;
        this.selectedStudentName = studentName;
        
        const f1Count = Math.round((att / 100) * 9);
        this.form1Items = Array(9).fill(false).map((v, i) => i < f1Count);
        
        const f2Count = Math.round((tech / 100) * 8);
        this.form2Items = Array(8).fill(false).map((v, i) => i < f2Count);
        
        const f3Count = Math.round((tech / 100) * 7);
        this.form3Items = Array(7).fill(false).map((v, i) => i < f3Count);
        
        const f4Count = Math.round((man / 100) * 5);
        this.form4Items = Array(5).fill(false).map((v, i) => i < f4Count);
        
        const f5Count = Math.round((rep / 100) * 4);
        this.form5Items = Array(4).fill(false).map((v, i) => i < f5Count);
        
        const f6Count = Math.round((pres / 100) * 4);
        this.form6Items = Array(4).fill(false).map((v, i) => i < f6Count);
        
        this.notes = notesStr || '';
        this.scoreModalOpen = true;
    }
}">

    <!-- Header -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-full bg-purple-600 text-white flex items-center justify-center shadow-sm flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Penilaian Praktik Kerja Lapangan (PKL)</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">
                    Instrumen Terpadu: Form 1, 2, 3, 4 diisi oleh <strong class="text-slate-900 dark:text-white font-semibold">Mitra Industri</strong> • Form 5, 6 diisi oleh <strong class="text-slate-900 dark:text-white font-semibold">Guru Pembimbing</strong>.
                </p>
            </div>
        </div>
        @if(!Auth::user()->isSiswa())
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('evaluations.export.excel') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Download Excel</span>
            </a>
            <a href="{{ route('evaluations.export.pdf') }}" target="_blank" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Download PDF</span>
            </a>
        </div>
        @endif
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 uppercase tracking-wider font-bold border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="p-3">Siswa</th>
                        <th class="p-3">Kelas & Jurusan</th>
                        <th class="p-3">Industri</th>
                        <th class="p-3 text-center">Sikap (F1)</th>
                        <th class="p-3 text-center">Teknis (F2&3)</th>
                        <th class="p-3 text-center">Manajerial (F4)</th>
                        <th class="p-3 text-center">Laporan (F5)</th>
                        <th class="p-3 text-center">Presentasi (F6)</th>
                        <th class="p-3 text-center">Nilai Akhir</th>
                        <th class="p-3 text-center">Predikat</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($students as $st)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="p-3 whitespace-nowrap">
                                <p class="font-bold text-slate-900 dark:text-white">{{ $st->name }}</p>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400">NISN: {{ $st->nisn }}</span>
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                <p class="text-blue-600 dark:text-blue-400 font-semibold">{{ $st->class_name }}</p>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400">{{ $st->major->name ?? '-' }}</span>
                            </td>
                            <td class="p-3 whitespace-nowrap text-slate-700 dark:text-slate-300 font-medium">
                                {{ $st->industry->name ?? '-' }}
                            </td>
                            <td class="p-3 text-center font-mono font-semibold text-slate-900 dark:text-white">
                                {{ ($st->evaluation && $st->evaluation->aspect_attitude > 0) ? $st->evaluation->aspect_attitude : '-' }}
                            </td>
                            <td class="p-3 text-center font-mono font-semibold text-slate-900 dark:text-white">
                                {{ ($st->evaluation && $st->evaluation->aspect_technical > 0) ? $st->evaluation->aspect_technical : '-' }}
                            </td>
                            <td class="p-3 text-center font-mono font-semibold text-slate-900 dark:text-white">
                                {{ ($st->evaluation && $st->evaluation->aspect_managerial > 0) ? $st->evaluation->aspect_managerial : '-' }}
                            </td>
                            <td class="p-3 text-center font-mono font-semibold text-slate-900 dark:text-white">
                                {{ ($st->evaluation && $st->evaluation->aspect_report > 0) ? $st->evaluation->aspect_report : '-' }}
                            </td>
                            <td class="p-3 text-center font-mono font-semibold text-slate-900 dark:text-white">
                                {{ ($st->evaluation && $st->evaluation->aspect_presentation > 0) ? $st->evaluation->aspect_presentation : '-' }}
                            </td>
                            <td class="p-3 text-center font-mono font-bold text-sm">
                                @if($st->evaluation && $st->evaluation->aspect_attitude > 0 && $st->evaluation->aspect_technical > 0 && $st->evaluation->aspect_managerial > 0 && $st->evaluation->aspect_report > 0 && $st->evaluation->aspect_presentation > 0)
                                    <span class="text-blue-600 dark:text-blue-400 font-extrabold">{{ $st->evaluation->final_score }}</span>
                                @else
                                    <span class="text-slate-400 font-normal">-</span>
                                @endif
                            </td>
                            <td class="p-3 text-center whitespace-nowrap">
                                @if($st->evaluation && $st->evaluation->aspect_attitude > 0 && $st->evaluation->aspect_technical > 0 && $st->evaluation->aspect_managerial > 0 && $st->evaluation->aspect_report > 0 && $st->evaluation->aspect_presentation > 0)
                                    <span class="px-2.5 py-0.5 rounded-full font-bold text-xs uppercase
                                        @if($st->evaluation->predicate === 'A') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300
                                        @elseif($st->evaluation->predicate === 'B') bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300
                                        @elseif($st->evaluation->predicate === 'C') bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300
                                        @else bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300 @endif">
                                        {{ $st->evaluation->predicate }}
                                    </span>
                                @else
                                    <span class="text-[10px] text-amber-800 dark:text-amber-300 font-bold bg-amber-100 dark:bg-amber-900/40 px-2.5 py-0.5 rounded-full">Belum Lengkap</span>
                                @endif
                            </td>
                            <td class="p-3 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if(Auth::user()->isIndustri() || Auth::user()->isGuru() || Auth::user()->isAdmin())
                                        <button type="button" @click="openScoreModal('{{ $st->id }}', '{{ $st->name }}', {{ $st->evaluation->aspect_attitude ?? 0 }}, {{ $st->evaluation->aspect_technical ?? 0 }}, {{ $st->evaluation->aspect_managerial ?? 0 }}, {{ $st->evaluation->aspect_report ?? 0 }}, {{ $st->evaluation->aspect_presentation ?? 0 }}, '{{ addslashes($st->evaluation->notes ?? '') }}')"
                                            class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-full shadow-sm transition-colors inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>{{ $st->evaluation ? 'Edit Nilai' : 'Input Nilai' }}</span>
                                        </button>
                                        @if($st->evaluation)
                                            <form action="/evaluations/{{ $st->id }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus/mereset semua nilai siswa ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 bg-rose-100 hover:bg-rose-200 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300 text-xs font-semibold rounded-full transition-colors" title="Reset Nilai">
                                                    Reset
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="p-10 text-center text-slate-500 font-medium">Belum ada siswa PKL yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
            {{ $students->links() }}
        </div>
    </div>

    <!-- EVALUATION INPUT MODAL -->
    <div x-show="scoreModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click="scoreModalOpen = false" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"></div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 max-w-4xl w-full z-10 shadow-2xl relative flex flex-col max-h-[90vh] overflow-hidden text-left" x-data="{ currentPage: 1 }" x-init="$watch('scoreModalOpen', value => { if(value) { currentPage = @js(Auth::user()->isGuru()) ? 5 : 1; } })">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">RUBRIK PENILAIAN PKL</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Siswa: <strong class="text-slate-900 dark:text-white" x-text="selectedStudentName"></strong></p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs bg-slate-100 dark:bg-slate-800 px-3.5 py-1 text-slate-700 dark:text-slate-300 font-bold rounded-full" x-text="'Halaman ' + currentPage + ' dari 6'"></span>
                </div>
            </div>

            <!-- Tab Headers -->
            <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 my-3">
                <button type="button" @click="currentPage = 1" :disabled="@js(Auth::user()->isGuru())"
                    :class="currentPage === 1 ? 'bg-blue-600 text-white font-bold shadow-sm' : (@js(Auth::user()->isGuru()) ? 'bg-slate-100 dark:bg-slate-950 text-slate-400 cursor-not-allowed' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold')"
                    class="py-2 px-1 rounded-2xl text-xs text-center transition-colors">
                    Form 1<br><span class="text-[10px] font-normal opacity-90">Soft Skills</span>
                </button>
                <button type="button" @click="currentPage = 2" :disabled="@js(Auth::user()->isGuru())"
                    :class="currentPage === 2 ? 'bg-blue-600 text-white font-bold shadow-sm' : (@js(Auth::user()->isGuru()) ? 'bg-slate-100 dark:bg-slate-950 text-slate-400 cursor-not-allowed' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold')"
                    class="py-2 px-1 rounded-2xl text-xs text-center transition-colors">
                    Form 2<br><span class="text-[10px] font-normal opacity-90">Penerapan HS</span>
                </button>
                <button type="button" @click="currentPage = 3" :disabled="@js(Auth::user()->isGuru())"
                    :class="currentPage === 3 ? 'bg-blue-600 text-white font-bold shadow-sm' : (@js(Auth::user()->isGuru()) ? 'bg-slate-100 dark:bg-slate-950 text-slate-400 cursor-not-allowed' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold')"
                    class="py-2 px-1 rounded-2xl text-xs text-center transition-colors">
                    Form 3<br><span class="text-[10px] font-normal opacity-90">Pengemb. HS</span>
                </button>
                <button type="button" @click="currentPage = 4" :disabled="@js(Auth::user()->isGuru())"
                    :class="currentPage === 4 ? 'bg-blue-600 text-white font-bold shadow-sm' : (@js(Auth::user()->isGuru()) ? 'bg-slate-100 dark:bg-slate-950 text-slate-400 cursor-not-allowed' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold')"
                    class="py-2 px-1 rounded-2xl text-xs text-center transition-colors">
                    Form 4<br><span class="text-[10px] font-normal opacity-90">Wirausaha</span>
                </button>
                <button type="button" @click="currentPage = 5" :disabled="@js(Auth::user()->isIndustri())"
                    :class="currentPage === 5 ? 'bg-blue-600 text-white font-bold shadow-sm' : (@js(Auth::user()->isIndustri()) ? 'bg-slate-100 dark:bg-slate-950 text-slate-400 cursor-not-allowed' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold')"
                    class="py-2 px-1 rounded-2xl text-xs text-center transition-colors">
                    Form 5<br><span class="text-[10px] font-normal opacity-90">Laporan PKL</span>
                </button>
                <button type="button" @click="currentPage = 6" :disabled="@js(Auth::user()->isIndustri())"
                    :class="currentPage === 6 ? 'bg-blue-600 text-white font-bold shadow-sm' : (@js(Auth::user()->isIndustri()) ? 'bg-slate-100 dark:bg-slate-950 text-slate-400 cursor-not-allowed' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold')"
                    class="py-2 px-1 rounded-2xl text-xs text-center transition-colors">
                    Form 6<br><span class="text-[10px] font-normal opacity-90">Presentasi</span>
                </button>
            </div>

            <!-- Form Content Wrapper -->
            <form x-bind:action="'/evaluations/' + selectedStudentId" method="POST" class="flex-1 overflow-hidden flex flex-col justify-between space-y-3">
                @csrf
                
                <!-- Hidden inputs carrying the calculated scores to backend -->
                <input type="hidden" name="aspect_attitude" :value="aspectAttitude">
                <input type="hidden" name="aspect_technical" :value="aspectTechnical">
                <input type="hidden" name="aspect_managerial" :value="aspectManagerial">
                <input type="hidden" name="aspect_report" :value="aspectReport">
                <input type="hidden" name="aspect_presentation" :value="aspectPresentation">

                <!-- Items list scrollbox -->
                <div class="flex-1 overflow-y-auto pr-1 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl p-3.5">
                    
                    <!-- FORM 1: Soft Skills & K3LH -->
                    <div x-show="currentPage === 1" class="space-y-3">
                        <div class="p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase">FORM RUBRIK PENILAIAN : MENERAPKAN SOFT SKILLS, NORMA, POS DAN K3LH</h4>
                            <span class="text-xs text-slate-700 dark:text-slate-300 font-mono font-bold">Skor: <span class="text-blue-600 dark:text-blue-400" x-text="aspectAttitude"></span> / 100</span>
                        </div>
                        <table class="w-full text-left text-xs border-collapse">
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                <template x-for="(q, index) in [
                                    'Menunjukkan sikap disiplin dan tanggung jawab dalam melaksanakan tugas sesuai aturan perusahaan.',
                                    'Berkomunikasi dengan baik, efektif, dan sopan kepada atasan, rekan kerja, maupun pelanggan.',
                                    'Mematuhi prosedur operasional standar (POS) yang berlaku di tempat kerja.',
                                    'Menunjukkan kemampuan kerja sama tim dalam menyelesaikan tugas yang diberikan.',
                                    'Menerapkan prinsip keselamatan dan kesehatan kerja (K3) sesuai aturan perusahaan.',
                                    'Menunjukkan kepedulian terhadap lingkungan kerja dengan menjaga kebersihan, ketertiban, dan pengelolaan limbah sesuai prinsip K3LH.',
                                    'Menumbuhkan sikap saling menghargai dan toleransi antar rekan kerja.',
                                    'Menerapkan etika kerja yang baik dalam setiap aktivitas pekerjaan.',
                                    'Menunjukkan kejujuran dan integritas dalam setiap pelaksanaan tugas.'
                               ]" :key="index">
                                    <tr class="hover:bg-white dark:hover:bg-slate-900/50">
                                        <td class="p-3 text-slate-800 dark:text-slate-200 font-medium" x-text="(index + 1) + '. ' + q"></td>
                                        <td class="p-3 text-right whitespace-nowrap">
                                            <div class="inline-flex gap-1 bg-slate-200 dark:bg-slate-900 p-1 rounded-full">
                                                <button type="button" @click="if(!@js(Auth::user()->isGuru())) form1Items[index] = true" :class="form1Items[index] ? 'bg-emerald-600 text-white font-bold shadow-sm' : 'text-slate-600 dark:text-slate-400 font-medium'" class="px-3.5 py-1 text-xs rounded-full transition-colors">
                                                    Tercapai
                                                </button>
                                                <button type="button" @click="if(!@js(Auth::user()->isGuru())) form1Items[index] = false" :class="!form1Items[index] ? 'bg-rose-600 text-white font-bold shadow-sm' : 'text-slate-600 dark:text-slate-400 font-medium'" class="px-3.5 py-1 text-xs rounded-full transition-colors">
                                                    Tidak
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- FORM 2: Penerapan Hard Skills -->
                    <div x-show="currentPage === 2" class="space-y-3">
                        <div class="p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase">FORM RUBRIK PENILAIAN : MENERAPKAN HARDSKILLS DI TEMPAT KERJA</h4>
                            <span class="text-xs text-slate-700 dark:text-slate-300 font-mono font-bold">Capaian: <span class="text-blue-600 dark:text-blue-400" x-text="Math.round((form2Items.filter(Boolean).length / 8) * 100)"></span> / 100</span>
                        </div>
                        <table class="w-full text-left text-xs border-collapse">
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                <template x-for="(q, index) in [
                                    'Membaca, memahami, dan menerapkan gambar kerja atau instruksi teknis dari instruktur.',
                                    'Mengoperasikan/menggunakan alat ukur, peralatan, dan mesin kerja sesuai kebutuhan dan prosedur yang berlaku.',
                                    'Melaksanakan pekerjaan sesuai spesifikasi teknis yang ditetapkan perusahaan.',
                                    'Menyesuaikan metode kerja sesuai kondisi lapangan dan instruksi atasan.',
                                    'Melakukan pengukuran, pengujian, atau pemeriksaan kualitas hasil kerja.',
                                    'Melaksanakan pekerjaan perawatan dan perbaikan sesuai prosedur.',
                                    'Menjamin dan memastikan hasil kerja sesuai standar mutu yang berlaku, dapat digunakan dan berfungsi sesuai kebutuhan.',
                                    'Melakukan inovasi sederhana untuk mempermudah pekerjaan.'
                                ]" :key="index">
                                    <tr class="hover:bg-white dark:hover:bg-slate-900/50">
                                        <td class="p-3 text-slate-800 dark:text-slate-200 font-medium" x-text="(index + 1) + '. ' + q"></td>
                                        <td class="p-3 text-right whitespace-nowrap">
                                            <div class="inline-flex gap-1 bg-slate-200 dark:bg-slate-900 p-1 rounded-full">
                                                <button type="button" @click="if(!@js(Auth::user()->isGuru())) form2Items[index] = true" :class="form2Items[index] ? 'bg-emerald-600 text-white font-bold shadow-sm' : 'text-slate-600 dark:text-slate-400 font-medium'" class="px-3.5 py-1 text-xs rounded-full transition-colors">
                                                    Tercapai
                                                </button>
                                                <button type="button" @click="if(!@js(Auth::user()->isGuru())) form2Items[index] = false" :class="!form2Items[index] ? 'bg-rose-600 text-white font-bold shadow-sm' : 'text-slate-600 dark:text-slate-400 font-medium'" class="px-3.5 py-1 text-xs rounded-full transition-colors">
                                                    Tidak
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- FORM 3: Pengembangan Hard Skills -->
                    <div x-show="currentPage === 3" class="space-y-3">
                        <div class="p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase">FORM RUBRIK PENILAIAN : PENINGKATAN DAN PENGEMBANGAN HARD SKILLS</h4>
                            <span class="text-xs text-slate-700 dark:text-slate-300 font-mono font-bold">Capaian: <span class="text-blue-600 dark:text-blue-400" x-text="Math.round((form3Items.filter(Boolean).length / 7) * 100)"></span> / 100</span>
                        </div>
                        <table class="w-full text-left text-xs border-collapse">
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                <template x-for="(q, index) in [
                                    'Mengikuti arahan dan bimbingan dari instruktur atau pembimbing di tempat kerja.',
                                    'Mengasah keterampilan teknis melalui pengalaman langsung di lapangan.',
                                    'Menguasai penggunaan teknologi baru atau peralatan modern di bidang kerja.',
                                    'Beradaptasi dengan metode kerja baru yang lebih efektif.',
                                    'Mengoptimalkan penggunaan sumber daya untuk meningkatkan produktivitas kerja.',
                                    'Mencari solusi kreatif untuk meningkatkan efisiensi kerja.',
                                    'Mengikuti perkembangan teknologi dan tren terbaru di industri.'
                                ]" :key="index">
                                    <tr class="hover:bg-white dark:hover:bg-slate-900/50">
                                        <td class="p-3 text-slate-800 dark:text-slate-200 font-medium" x-text="(index + 1) + '. ' + q"></td>
                                        <td class="p-3 text-right whitespace-nowrap">
                                            <div class="inline-flex gap-1 bg-slate-200 dark:bg-slate-900 p-1 rounded-full">
                                                <button type="button" @click="if(!@js(Auth::user()->isGuru())) form3Items[index] = true" :class="form3Items[index] ? 'bg-emerald-600 text-white font-bold shadow-sm' : 'text-slate-600 dark:text-slate-400 font-medium'" class="px-3.5 py-1 text-xs rounded-full transition-colors">
                                                    Tercapai
                                                </button>
                                                <button type="button" @click="if(!@js(Auth::user()->isGuru())) form3Items[index] = false" :class="!form3Items[index] ? 'bg-rose-600 text-white font-bold shadow-sm' : 'text-slate-600 dark:text-slate-400 font-medium'" class="px-3.5 py-1 text-xs rounded-full transition-colors">
                                                    Tidak
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- FORM 4: Kemandirian Wirausaha -->
                    <div x-show="currentPage === 4" class="space-y-3">
                        <div class="p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase">FORM RUBRIK PENILAIAN : MEMAHAMI KEMANDIRIAN BERWIRAUSAHA</h4>
                            <span class="text-xs text-slate-700 dark:text-slate-300 font-mono font-bold">Skor: <span class="text-blue-600 dark:text-blue-400" x-text="aspectManagerial"></span> / 100</span>
                        </div>
                        <table class="w-full text-left text-xs border-collapse">
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                <template x-for="(q, index) in [
                                    'Menumbuhkan sikap percaya diri dan mandiri untuk memulai usaha.',
                                    'Menyusun rencana usaha sederhana sesuai peluang yang ada.',
                                    'Mengembangkan kreativitas and inovasi dalam produk/jasa yang ditawarkan.',
                                    'Memanfaatkan teknologi digital untuk promosi dan penjualan.',
                                    'Membangun relasi dan jejaring kerja untuk pengembangan usaha.'
                                ]" :key="index">
                                    <tr class="hover:bg-white dark:hover:bg-slate-900/50">
                                        <td class="p-3 text-slate-800 dark:text-slate-200 font-medium" x-text="(index + 1) + '. ' + q"></td>
                                        <td class="p-3 text-right whitespace-nowrap">
                                            <div class="inline-flex gap-1 bg-slate-200 dark:bg-slate-900 p-1 rounded-full">
                                                <button type="button" @click="if(!@js(Auth::user()->isGuru())) form4Items[index] = true" :class="form4Items[index] ? 'bg-emerald-600 text-white font-bold shadow-sm' : 'text-slate-600 dark:text-slate-400 font-medium'" class="px-3.5 py-1 text-xs rounded-full transition-colors">
                                                    Tercapai
                                                </button>
                                                <button type="button" @click="if(!@js(Auth::user()->isGuru())) form4Items[index] = false" :class="!form4Items[index] ? 'bg-rose-600 text-white font-bold shadow-sm' : 'text-slate-600 dark:text-slate-400 font-medium'" class="px-3.5 py-1 text-xs rounded-full transition-colors">
                                                    Tidak
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- FORM 5: Laporan PKL -->
                    <div x-show="currentPage === 5" class="space-y-3">
                        <div class="p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase">FORM RUBRIK PENILAIAN : LAPORAN PKL</h4>
                            <span class="text-xs text-slate-700 dark:text-slate-300 font-mono font-bold">Skor: <span class="text-blue-600 dark:text-blue-400" x-text="aspectReport"></span> / 100</span>
                        </div>
                        <table class="w-full text-left text-xs border-collapse">
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                <template x-for="(q, index) in [
                                    'Sistematika penulisan laporan sesuai dengan format panduan sekolah.',
                                    'Kelengkapan dan relevansi isi laporan dengan aktivitas PKL di lapangan.',
                                    'Ketepatan waktu dalam penyelesaian dan pengumpulan dokumen laporan.',
                                    'Ketepatan tata bahasa, istilah teknis, dan kerapihan penyusunan laporan.'
                                ]" :key="index">
                                    <tr class="hover:bg-white dark:hover:bg-slate-900/50">
                                        <td class="p-3 text-slate-800 dark:text-slate-200 font-medium" x-text="(index + 1) + '. ' + q"></td>
                                        <td class="p-3 text-right whitespace-nowrap">
                                            <div class="inline-flex gap-1 bg-slate-200 dark:bg-slate-900 p-1 rounded-full">
                                                <button type="button" @click="if(!@js(Auth::user()->isIndustri())) form5Items[index] = true" :class="form5Items[index] ? 'bg-emerald-600 text-white font-bold shadow-sm' : 'text-slate-600 dark:text-slate-400 font-medium'" class="px-3.5 py-1 text-xs rounded-full transition-colors">
                                                    Tercapai
                                                </button>
                                                <button type="button" @click="if(!@js(Auth::user()->isIndustri())) form5Items[index] = false" :class="!form5Items[index] ? 'bg-rose-600 text-white font-bold shadow-sm' : 'text-slate-600 dark:text-slate-400 font-medium'" class="px-3.5 py-1 text-xs rounded-full transition-colors">
                                                    Tidak
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- FORM 6: Presentasi Hasil -->
                    <div x-show="currentPage === 6" class="space-y-3">
                        <div class="p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase">FORM RUBRIK PENILAIAN : PRESENTASI HASIL</h4>
                            <span class="text-xs text-slate-700 dark:text-slate-300 font-mono font-bold">Skor: <span class="text-blue-600 dark:text-blue-400" x-text="aspectPresentation"></span> / 100</span>
                        </div>
                        <table class="w-full text-left text-xs border-collapse">
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                <template x-for="(q, index) in [
                                    'Sistematika dan alur pemaparan presentasi hasil kerja PKL.',
                                    'Penguasaan materi, kelancaran bicara, dan ketepatan menjawab pertanyaan.',
                                    'Kualitas dan daya tarik media presentasi (slide/infografis/demo produk).',
                                    'Sikap, kerapian berpakaian, dan etika komunikasi selama presentasi.'
                                ]" :key="index">
                                    <tr class="hover:bg-white dark:hover:bg-slate-900/50">
                                        <td class="p-3 text-slate-800 dark:text-slate-200 font-medium" x-text="(index + 1) + '. ' + q"></td>
                                        <td class="p-3 text-right whitespace-nowrap">
                                            <div class="inline-flex gap-1 bg-slate-200 dark:bg-slate-900 p-1 rounded-full">
                                                <button type="button" @click="if(!@js(Auth::user()->isIndustri())) form6Items[index] = true" :class="form6Items[index] ? 'bg-emerald-600 text-white font-bold shadow-sm' : 'text-slate-600 dark:text-slate-400 font-medium'" class="px-3.5 py-1 text-xs rounded-full transition-colors">
                                                    Tercapai
                                                </button>
                                                <button type="button" @click="if(!@js(Auth::user()->isIndustri())) form6Items[index] = false" :class="!form6Items[index] ? 'bg-rose-600 text-white font-bold shadow-sm' : 'text-slate-600 dark:text-slate-400 font-medium'" class="px-3.5 py-1 text-xs rounded-full transition-colors">
                                                    Tidak
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- Live Score Preview & Notes Card -->
                <div class="space-y-3 pt-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-wider block font-bold">Kalkulasi Nilai Akhir</span>
                                <span :class="finalScore === 'Belum Lengkap' ? 'text-xs text-amber-700 dark:text-amber-300 font-bold' : 'text-xl font-bold text-emerald-600 dark:text-emerald-400 font-mono'" x-text="finalScore"></span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-wider block font-bold">Predikat</span>
                                <span :class="finalScore === 'Belum Lengkap' ? 'text-xs text-slate-500 font-medium' : 'text-xs font-bold text-blue-600 dark:text-blue-400'" x-text="predicate"></span>
                            </div>
                        </div>
                        <div>
                            <input type="text" name="notes" x-model="notes" placeholder="Catatan evaluasi pembimbing..."
                                class="w-full h-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2.5 text-xs text-slate-900 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:border-blue-500 transition-colors">
                        </div>
                    </div>

                    <!-- Bottom Nav Buttons -->
                    <div class="flex items-center justify-between pt-2 border-t border-slate-200 dark:border-slate-800">
                        <div class="flex gap-2">
                            <button type="button" @click="scoreModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-full text-xs transition-colors">
                                Batal
                            </button>
                            @if(Auth::user()->isIndustri() || Auth::user()->isGuru() || Auth::user()->isAdmin())
                                <button type="button" x-show="selectedStudentId" @click="if(confirm('Apakah Anda yakin ingin menghapus/mereset semua nilai siswa ini?')) { $refs.deleteForm.action = '/evaluations/' + selectedStudentId; $refs.deleteForm.submit(); }"
                                    class="px-4 py-2 bg-rose-100 hover:bg-rose-200 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300 font-semibold rounded-full text-xs transition-colors">
                                    Reset Nilai
                                </button>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <button type="button" x-show="currentPage > 1" @click="currentPage--" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-full text-xs font-semibold transition-colors" style="display: none;">
                                &larr; Sebelumnya
                            </button>
                            <button type="button" x-show="currentPage < 6 && !(@js(Auth::user()->isIndustri()) && currentPage === 4)" @click="currentPage++" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-full text-xs transition-colors shadow-sm">
                                Selanjutnya &rarr;
                            </button>
                            <button type="submit" x-show="@js(Auth::user()->isIndustri()) ? currentPage === 4 : currentPage === 6" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-full text-xs shadow-sm transition-colors">
                                Simpan Penilaian
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
    
    <!-- Global hidden delete form -->
    <form x-ref="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection

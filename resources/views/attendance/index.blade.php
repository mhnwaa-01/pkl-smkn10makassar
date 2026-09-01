@extends('layouts.app')

@section('title', 'Rekap Presensi Harian Siswa')

@section('content')
<div class="space-y-8">

    <!-- Header & Search Filter -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5 pb-4 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-sm flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Rekapitulasi Presensi Siswa</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">
                        Batas Jam Masuk: <strong class="text-slate-900 dark:text-white">{{ substr($setting->check_in_late_time, 0, 5) }} WITA</strong> • Jam Pulang Normal: <strong class="text-slate-900 dark:text-white">{{ substr($setting->check_out_time, 0, 5) }} WITA</strong>
                    </p>
                </div>
            </div>
            @if(!Auth::user()->isSiswa())
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('attendance.export.excel', request()->all()) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Download Excel</span>
                </a>
                <a href="{{ route('attendance.export.pdf', request()->all()) }}" target="_blank" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Download PDF</span>
                </a>
            </div>
            @endif
        </div>

        <form action="{{ route('attendance.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-slate-200 font-semibold focus:outline-none focus:border-blue-500 transition-colors">
            </div>
            <div>
                <select name="status" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-slate-200 font-semibold focus:outline-none focus:border-blue-500 transition-colors">
                    <option value="">-- Semua Status Datang --</option>
                    <option value="Tepat Waktu" {{ request('status') === 'Tepat Waktu' ? 'selected' : '' }}>Tepat Waktu</option>
                    <option value="Terlambat" {{ request('status') === 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>
            </div>
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / NISN / kelas..."
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:border-blue-500 transition-colors">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filter</span>
                </button>
                <a href="{{ route('attendance.index') }}" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs rounded-full border border-slate-200 dark:border-slate-700 transition-colors inline-flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Attendance List Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 uppercase tracking-wider font-bold border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Siswa</th>
                        <th class="p-3">Kelas & Jurusan</th>
                        <th class="p-3">Industri</th>
                        <th class="p-3">Jam Masuk</th>
                        <th class="p-3">Foto Masuk</th>
                        <th class="p-3">Jam Pulang</th>
                        <th class="p-3">Foto Pulang</th>
                        <th class="p-3">Lama Kerja</th>
                        <th class="p-3">Lokasi / Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($attendances as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="p-3 font-semibold text-slate-900 dark:text-white whitespace-nowrap">@formatdate($item->date)</td>
                            <td class="p-3 whitespace-nowrap">
                                <p class="font-bold text-slate-900 dark:text-white">{{ $item->student->name ?? 'Siswa Tidak Ditemukan' }}</p>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400">NISN: {{ $item->student->nisn ?? '-' }}</span>
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                <p class="text-blue-600 dark:text-blue-400 font-semibold">{{ $item->student->class_name ?? '-' }}</p>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400">{{ $item->student->major->name ?? '-' }}</span>
                            </td>
                            <td class="p-3 whitespace-nowrap text-slate-700 dark:text-slate-300 font-medium">
                                {{ $item->student->industry->name ?? '-' }}
                            </td>
                            <td class="p-3 font-mono text-slate-900 dark:text-white font-semibold whitespace-nowrap">
                                <div>{{ $item->check_in_time ? $item->check_in_time . ' WITA' : '-' }}</div>
                                @if($item->check_in_status)
                                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full font-bold uppercase text-[10px]
                                        @if($item->check_in_status === 'Tepat Waktu') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300
                                        @else bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300 @endif">
                                        {{ $item->check_in_status }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($item->check_in_photo)
                                    <a href="#" @click.prevent="openImagePreview('{{ asset('storage/' . $item->check_in_photo) }}')" class="block w-10 h-10 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700 cursor-zoom-in shadow-sm">
                                        <img src="{{ asset('storage/' . $item->check_in_photo) }}" class="w-full h-full object-cover" alt="Foto Masuk">
                                    </a>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="p-3 font-mono text-slate-900 dark:text-white font-semibold whitespace-nowrap">
                                <div>{{ $item->check_out_time ? $item->check_out_time . ' WITA' : '-' }}</div>
                                @if($item->check_out_status)
                                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full font-bold uppercase text-[10px]
                                        @if($item->check_out_status === 'Tepat Waktu') bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300
                                        @else bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 @endif">
                                        {{ $item->check_out_status }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($item->check_out_photo)
                                    <a href="#" @click.prevent="openImagePreview('{{ asset('storage/' . $item->check_out_photo) }}')" class="block w-10 h-10 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700 cursor-zoom-in shadow-sm">
                                        <img src="{{ asset('storage/' . $item->check_out_photo) }}" class="w-full h-full object-cover" alt="Foto Pulang">
                                    </a>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold
                                    @if($item->work_duration === '-') bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400
                                    @elseif($item->work_duration === 'Sedang Berlangsung') bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 animate-pulse
                                    @else bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 @endif">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ $item->work_duration }}</span>
                                </span>
                            </td>
                            <td class="p-3 text-xs max-w-xs">
                                @if($item->location)
                                    <div class="flex items-center gap-1 text-slate-700 dark:text-slate-300 font-semibold mb-0.5">
                                        <svg class="w-3.5 h-3.5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <a href="https://www.google.com/maps?q={{ urlencode($item->location) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline truncate" title="Lihat di Google Maps">
                                            {{ $item->location }}
                                        </a>
                                    </div>
                                @endif
                                @if($item->check_in_notes || $item->check_out_notes)
                                    <p class="text-slate-500 dark:text-slate-400 italic truncate">
                                        {{ $item->check_in_notes ?? $item->check_out_notes }}
                                    </p>
                                @elseif(!$item->location)
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-10 text-center text-slate-500 font-medium">Tidak ada data presensi yang sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
            {{ $attendances->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

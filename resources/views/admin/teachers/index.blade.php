@extends('layouts.app')

@section('title', 'Kelola Data Guru Pembimbing')

@section('content')
<div class="space-y-8" x-data="{
    addModalOpen: false,
    editModalOpen: false,
    editTeacherId: null,
    editName: '',
    editNip: '',
    editPhone: '',
    openEditModal(tc) {
        this.editTeacherId = tc.id;
        this.editName = tc.name;
        this.editNip = tc.nip || '';
        this.editPhone = tc.phone || '';
        this.editModalOpen = true;
    }
}">

    <!-- Header -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-sm flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Kelola Data Guru Pembimbing PKL</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">Tambah data pembimbing sekolah dan buatkan akun login untuk monitoring siswa.</p>
            </div>
        </div>

        <button type="button" @click="addModalOpen = true" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Guru Baru</span>
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 uppercase tracking-wider font-bold border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="p-3">NIP / Akun</th>
                        <th class="p-3">Nama Lengkap Guru</th>
                        <th class="p-3">No. Telepon / WA</th>
                        <th class="p-3">Siswa Bimbingan</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
                    @forelse($teachers as $tc)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="p-3 font-mono whitespace-nowrap">
                                <p class="text-slate-900 dark:text-white font-bold">{{ $tc->nip ?? '-' }}</p>
                                <span class="text-[10px] text-blue-600 dark:text-blue-400">@ {{ $tc->user->username ?? '-' }}</span>
                            </td>
                            <td class="p-3 font-bold text-slate-900 dark:text-white whitespace-nowrap">{{ $tc->name }}</td>
                            <td class="p-3 text-slate-700 dark:text-slate-300 font-medium whitespace-nowrap">{{ $tc->phone ?? '-' }}</td>
                            <td class="p-3 font-bold whitespace-nowrap">
                                <span class="text-blue-600 dark:text-blue-400 font-bold text-sm">{{ $tc->students->count() }}</span> Siswa
                            </td>
                            <td class="p-3 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" @click="openEditModal({{ json_encode($tc) }})" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-full text-xs font-semibold transition-colors inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </button>
                                    <form action="{{ route('admin.teachers.destroy', $tc) }}" method="POST" onsubmit="return confirm('Hapus guru dan akun login ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-rose-100 hover:bg-rose-200 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300 rounded-full text-xs font-semibold transition-colors inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center text-slate-500 font-medium">Belum ada data guru pembimbing.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
            {{ $teachers->links() }}
        </div>
    </div>

    <!-- ADD MODAL -->
    <div x-show="addModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click="addModalOpen = false" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 max-w-lg w-full z-10 shadow-2xl relative text-left">
            <h3 class="text-base font-bold text-slate-900 dark:text-white mb-3">Tambah Guru & Akun Login</h3>

            <form action="{{ route('admin.teachers.store') }}" method="POST" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Nama Lengkap Guru & Gelar</label>
                    <input type="text" name="name" required placeholder="Drs. Budi Santoso, M.Pd." class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">NIP (Opsional)</label>
                        <input type="text" name="nip" placeholder="197508122000031001" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white font-mono focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">No. Telepon / WA</label>
                        <input type="text" name="phone" placeholder="081234567890" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Username Login</label>
                        <input type="text" name="username" required placeholder="guru_budi" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Password Login</label>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors">Simpan & Buat Akun</button>
                    <button type="button" @click="addModalOpen = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-full transition-colors">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click="editModalOpen = false" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 max-w-lg w-full z-10 shadow-2xl relative text-left">
            <h3 class="text-base font-bold text-slate-900 dark:text-white mb-3">Edit Data Guru</h3>

            <form x-bind:action="'/admin/teachers/' + editTeacherId" method="POST" class="space-y-3.5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Nama Lengkap Guru</label>
                    <input type="text" name="name" x-model="editName" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">NIP</label>
                        <input type="text" name="nip" x-model="editNip" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white font-mono focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">No. Telepon</label>
                        <input type="text" name="phone" x-model="editPhone" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Password Baru (Biarkan kosong jika tidak diubah)</label>
                    <input type="password" name="password" placeholder="••••••••" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500">
                </div>

                <div class="flex items-center gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors">Simpan Perubahan</button>
                    <button type="button" @click="editModalOpen = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-full transition-colors">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Kelola Data Industri')

@section('content')
<div class="space-y-8" x-data="{
    addModalOpen: false,
    editModalOpen: false,
    editIndustryId: null,
    editName: '',
    editContact: '',
    editAddress: '',
    editPhone: '',
    openEditModal(ind) {
        this.editIndustryId = ind.id;
        this.editName = ind.name;
        this.editContact = ind.contact_person;
        this.editAddress = ind.address || '';
        this.editPhone = ind.phone || '';
        this.editModalOpen = true;
    }
}">

    <!-- Header -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-sm flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M9 16h.01M9 12h.01M9 8h.01M15 16h.01M15 12h.01M15 8h.01"/></svg>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Kelola Data Industri & Perusahaan Mitra</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">Tambah data mitra DUDI, penanggung jawab, dan akun login pembimbing industri.</p>
            </div>
        </div>

        <button type="button" @click="addModalOpen = true" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Industri Baru</span>
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 uppercase tracking-wider font-bold border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="p-3">Nama Industri / Perusahaan</th>
                        <th class="p-3">Penanggung Jawab</th>
                        <th class="p-3">Username Login</th>
                        <th class="p-3">Kontak & Alamat</th>
                        <th class="p-3">Siswa Magang</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
                    @forelse($industries as $ind)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="p-3 font-bold text-slate-900 dark:text-white whitespace-nowrap">{{ $ind->name }}</td>
                            <td class="p-3 text-slate-800 dark:text-slate-200 font-semibold whitespace-nowrap">{{ $ind->contact_person }}</td>
                            <td class="p-3 font-mono text-blue-600 dark:text-blue-400 font-bold whitespace-nowrap">@ {{ $ind->user->username ?? '-' }}</td>
                            <td class="p-3 text-slate-600 dark:text-slate-400">
                                <p class="font-bold text-slate-700 dark:text-slate-300">{{ $ind->phone ?? '-' }}</p>
                                <span class="text-[10px] text-slate-400 truncate block max-w-xs">{{ $ind->address ?? '-' }}</span>
                            </td>
                            <td class="p-3 font-bold whitespace-nowrap">
                                <span class="text-blue-600 dark:text-blue-400 font-bold text-sm">{{ $ind->students->count() }}</span> Siswa
                            </td>
                            <td class="p-3 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" @click="openEditModal({{ json_encode($ind) }})" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-full text-xs font-semibold transition-colors inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </button>
                                    <form action="{{ route('admin.industries.destroy', $ind) }}" method="POST" onsubmit="return confirm('Hapus industri dan akun login ini?')">
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
                            <td colspan="6" class="p-10 text-center text-slate-500 font-medium">Belum ada data industri mitra.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
            {{ $industries->links() }}
        </div>
    </div>

    <!-- ADD MODAL -->
    <div x-show="addModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click="addModalOpen = false" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 max-w-lg w-full z-10 shadow-2xl relative text-left">
            <h3 class="text-base font-bold text-slate-900 dark:text-white mb-3">Tambah Industri & Akun Login</h3>

            <form action="{{ route('admin.industries.store') }}" method="POST" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Nama Industri / Perusahaan</label>
                    <input type="text" name="name" required placeholder="PT Telkom Indonesia Witel Makassar" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Nama Penanggung Jawab (PJ)</label>
                        <input type="text" name="contact_person" required placeholder="Rahmat Hidayat, S.T." class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">No. Telepon / WA</label>
                        <input type="text" name="phone" placeholder="085299887766" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Username Login</label>
                        <input type="text" name="username" required placeholder="pt_telkom" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Password Login</label>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Alamat Kantor / Perusahaan</label>
                    <textarea name="address" rows="2" placeholder="Jl. AP Pettarani No. 2, Makassar" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500"></textarea>
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
            <h3 class="text-base font-bold text-slate-900 dark:text-white mb-3">Edit Data Industri</h3>

            <form x-bind:action="'/admin/industries/' + editIndustryId" method="POST" class="space-y-3.5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Nama Industri / Perusahaan</label>
                    <input type="text" name="name" x-model="editName" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Nama Penanggung Jawab</label>
                        <input type="text" name="contact_person" x-model="editContact" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">No. Telepon</label>
                        <input type="text" name="phone" x-model="editPhone" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Alamat Kantor</label>
                    <textarea name="address" x-model="editAddress" rows="2" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl px-4 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-blue-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 px-2">Password Baru (Kosongkan jika tidak diubah)</label>
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

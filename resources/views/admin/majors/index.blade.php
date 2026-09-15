@extends('layouts.app')

@section('title', 'Manajemen Jurusan')

@section('content')
<div x-data="majorModal()" class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Form Column -->
    <div class="md:col-span-1">
        <form action="{{ route('admin.majors.store') }}" method="POST" class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm space-y-4">
            @csrf
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">Tambah Jurusan Baru</h3>

            <div>
                <label for="code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kode Jurusan *</label>
                <input type="text" id="code" name="code" value="{{ old('code') }}" required placeholder="Contoh: RPL, DKV, AK" class="w-full text-xs p-2.5 border border-slate-300 rounded font-mono font-bold uppercase">
                @error('code') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap Jurusan *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Rekayasa Perangkat Lunak" class="w-full text-xs p-2.5 border border-slate-300 rounded">
                @error('name') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs py-2.5 px-4 rounded shadow-sm">
                + Simpan Jurusan
            </button>
        </form>
    </div>

    <!-- Table Column -->
    <div class="md:col-span-2">
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
            <div class="p-4 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-900">Daftar Jurusan Keahlian</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Nama Jurusan</th>
                            <th class="px-4 py-3">Rombel Kelas</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($majors as $m)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3 font-mono font-bold text-blue-900">{{ $m->code }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-900">{{ $m->name }}</td>
                                <td class="px-4 py-3 font-mono font-bold text-slate-700">{{ $m->school_classes_count }} Kelas</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                        <button type="button" @click="openEdit(@js($m))" class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 hover:bg-amber-100 hover:border-amber-300 px-2 py-1 rounded border border-amber-200 font-medium text-[11px]">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.majors.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus jurusan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 hover:bg-rose-100 hover:border-rose-300 px-2 py-1 rounded border border-rose-200 font-medium text-[11px]">
                                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-500">Belum ada jurusan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</div>

<!-- Edit Jurusan Modal -->
<div x-show="editing" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="editing = false">
    <div class="absolute inset-0 bg-slate-900/40" @click="editing = false"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md p-5 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900">Edit Jurusan</h3>
            <button type="button" @click="editing = false" class="text-slate-400 hover:text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form :action="`{{ route('admin.majors.update', '') }}/${id}`" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kode Jurusan *</label>
                <input type="text" id="edit_code" name="code" x-model="code" required placeholder="Contoh: RPL, DKV, AK" class="w-full text-xs p-2.5 border border-slate-300 rounded font-mono font-bold uppercase">
            </div>
            <div>
                <label for="edit_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap Jurusan *</label>
                <input type="text" id="edit_name" name="name" x-model="name" required placeholder="Contoh: Rekayasa Perangkat Lunak" class="w-full text-xs p-2.5 border border-slate-300 rounded">
            </div>
            <div class="flex gap-2 justify-end pt-1">
                <button type="button" @click="editing = false" class="px-3 py-2 text-xs font-semibold text-slate-600 border border-slate-200 rounded hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-3 py-2 text-xs font-bold text-white bg-blue-700 rounded hover:bg-blue-800">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function majorModal() {
        return {
            editing: false,
            id: null,
            code: '',
            name: '',
            openEdit(m) {
                this.id = m.id;
                this.code = m.code;
                this.name = m.name;
                this.editing = true;
            }
        };
    }
</script>
@endsection

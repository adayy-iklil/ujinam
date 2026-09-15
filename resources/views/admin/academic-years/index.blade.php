@extends('layouts.app')

@section('title', 'Tahun Ajaran')

@section('content')
<div x-data="ayModal()" class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Form -->
    <div class="md:col-span-1">
        <form action="{{ route('admin.academic-years.store') }}" method="POST" class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm space-y-4">
            @csrf
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">Tambah Tahun Ajaran Baru</h3>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Tahun Ajaran *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: 2026/2027" class="w-full text-xs p-2.5 border border-slate-300 rounded font-bold">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="start_year" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tahun Mulai *</label>
                    <input type="number" id="start_year" name="start_year" value="{{ old('start_year', date('Y')) }}" required class="w-full text-xs p-2.5 border border-slate-300 rounded font-mono">
                </div>

                <div>
                    <label for="end_year" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tahun Selesai *</label>
                    <input type="number" id="end_year" name="end_year" value="{{ old('end_year', date('Y')+1) }}" required class="w-full text-xs p-2.5 border border-slate-300 rounded font-mono">
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs py-2.5 px-4 rounded shadow-sm">
                + Simpan Tahun Ajaran
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="md:col-span-2">
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
            <div class="p-4 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-900">Daftar Tahun Ajaran Sekolah</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3">Tahun Ajaran</th>
                            <th class="px-4 py-3">Periode</th>
                            <th class="px-4 py-3">Jumlah Kelas</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($academicYears as $ay)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3 font-bold text-slate-900">{{ $ay->name }}</td>
                                <td class="px-4 py-3 font-mono text-slate-600">{{ $ay->start_year }} - {{ $ay->end_year }}</td>
                                <td class="px-4 py-3 font-mono text-slate-700 font-semibold">{{ $ay->school_classes_count }} Kelas</td>
                                <td class="px-4 py-3">
                                    @if($ay->is_active)
                                        <span class="bg-emerald-50 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded border border-emerald-200">AKTIF (CURRENT)</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200">NONAKTIF</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                        @if(!$ay->is_active)
                                            <form action="{{ route('admin.academic-years.toggleActive', $ay->id) }}" method="POST" onsubmit="return confirm('Aktifkan tahun ajaran {{ $ay->name }}?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 hover:border-emerald-300 px-2 py-1 rounded border border-emerald-200 font-medium text-[11px]">
                                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Set Aktif
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button" @click="openEdit(@js($ay))" class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 hover:bg-amber-100 hover:border-amber-300 px-2 py-1 rounded border border-amber-200 font-medium text-[11px]">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.academic-years.destroy', $ay->id) }}" method="POST" onsubmit="return confirm('Hapus tahun ajaran {{ $ay->name }}?')">
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
                                <td colspan="5" class="px-4 py-6 text-center text-slate-500">Belum ada tahun ajaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</div>

<!-- Edit Tahun Ajaran Modal -->
<div x-show="editing" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="editing = false">
    <div class="absolute inset-0 bg-slate-900/40" @click="editing = false"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md p-5 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900">Edit Tahun Ajaran</h3>
            <button type="button" @click="editing = false" class="text-slate-400 hover:text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form :action="`{{ route('admin.academic-years.update', '') }}/${id}`" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Tahun Ajaran *</label>
                <input type="text" id="edit_name" name="name" x-model="name" required placeholder="Contoh: 2026/2027" class="w-full text-xs p-2.5 border border-slate-300 rounded font-bold">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_start_year" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tahun Mulai *</label>
                    <input type="number" id="edit_start_year" name="start_year" x-model="start_year" required class="w-full text-xs p-2.5 border border-slate-300 rounded font-mono">
                </div>
                <div>
                    <label for="edit_end_year" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tahun Selesai *</label>
                    <input type="number" id="edit_end_year" name="end_year" x-model="end_year" required class="w-full text-xs p-2.5 border border-slate-300 rounded font-mono">
                </div>
            </div>
            <div class="flex gap-2 justify-end pt-1">
                <button type="button" @click="editing = false" class="px-3 py-2 text-xs font-semibold text-slate-600 border border-slate-200 rounded hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-3 py-2 text-xs font-bold text-white bg-blue-700 rounded hover:bg-blue-800">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function ayModal() {
        return {
            editing: false,
            id: null,
            name: '',
            start_year: null,
            end_year: null,
            openEdit(ay) {
                this.id = ay.id;
                this.name = ay.name;
                this.start_year = ay.start_year;
                this.end_year = ay.end_year;
                this.editing = true;
            }
        };
    }
</script>
@endsection

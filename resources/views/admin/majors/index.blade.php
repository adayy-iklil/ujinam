@extends('layouts.app')

@section('title', 'Manajemen Jurusan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

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
                                    <form action="{{ route('admin.majors.destroy', $m->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus jurusan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:underline font-medium">Hapus</button>
                                    </form>
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
@endsection

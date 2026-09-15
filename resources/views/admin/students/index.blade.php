@extends('layouts.app')

@section('title', 'Manajemen Data Siswa')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-200 pb-3 gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Manajemen Data Siswa</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data peserta didik, kelas, dan reset password NIS</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.students.import') }}" class="bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs py-2 px-3 rounded-md transition shadow-sm">
                ↑ Import CSV Siswa
            </a>
            <a href="{{ route('admin.students.create') }}" class="bg-blue-700 hover:bg-blue-800 text-white font-semibold text-xs py-2 px-3 rounded-md transition shadow-sm">
                + Tambah Siswa
            </a>
        </div>
    </div>

    <!-- Filters Bar -->
    <form action="{{ route('admin.students.index') }}" method="GET" class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIS atau Nama Siswa..."
                class="w-full text-xs p-2.5 border border-slate-300 rounded focus:ring-blue-500">
        </div>
        <div class="w-full sm:w-48">
            <select name="class_id" class="w-full text-xs p-2.5 border border-slate-300 rounded">
                <option value="">-- Semua Kelas --</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs px-4 py-2.5 rounded border border-slate-300">
            Filter
        </button>
    </form>

    <!-- Students Table -->
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">NIS</th>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">L/P</th>
                        <th class="px-4 py-3">Kelas</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($students as $s)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $s->nis }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $s->name }}</td>
                            <td class="px-4 py-3 font-medium">{{ $s->gender }}</td>
                            <td class="px-4 py-3 text-slate-700 font-medium">{{ $s->schoolClass->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($s->is_active)
                                    <span class="bg-emerald-50 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded border border-emerald-200">AKTIF</span>
                                @else
                                    <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200">ARSIP</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <form action="{{ route('admin.students.resetPassword', $s->id) }}" method="POST" class="inline" onsubmit="return confirm('Reset password NIS {{ $s->nis }} ke default (123456)?')">
                                    @csrf
                                    <button type="submit" class="text-amber-700 hover:underline font-medium">Reset Pass</button>
                                </form>
                                <a href="{{ route('admin.students.edit', $s->id) }}" class="text-blue-700 hover:underline font-semibold">Edit</a>
                                <form action="{{ route('admin.students.destroy', $s->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:underline font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">Tidak ada data siswa ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Manajemen Kelas')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Manajemen Rombel Kelas</h2>
            <p class="text-xs text-slate-500 mt-0.5">Daftar kelas terpisah berdasarkan Tingkat (Grade), Jurusan, dan Nomor Kelas</p>
        </div>
        <a href="{{ route('admin.classes.create') }}" class="bg-blue-700 hover:bg-blue-800 text-white font-semibold text-xs py-2 px-3.5 rounded-md transition shadow-sm">
            + Tambah Kelas Baru
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Nama Kelas</th>
                        <th class="px-4 py-3">Tingkat (Grade)</th>
                        <th class="px-4 py-3">Jurusan</th>
                        <th class="px-4 py-3">Nomor Rombel</th>
                        <th class="px-4 py-3">Tahun Ajaran</th>
                        <th class="px-4 py-3">Jumlah Siswa</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($classes as $c)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-bold text-slate-900">{{ $c->name }}</td>
                            <td class="px-4 py-3 font-mono font-semibold text-blue-900">{{ $c->grade }}</td>
                            <td class="px-4 py-3 font-medium text-slate-700">{{ $c->major->name ?? '-' }} ({{ $c->major->code ?? '-' }})</td>
                            <td class="px-4 py-3 font-mono text-slate-600">{{ $c->class_number }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $c->academicYear->name ?? '-' }}</td>
                            <td class="px-4 py-3 font-mono font-bold text-emerald-700">{{ $c->students_count }} Siswa</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('admin.classes.edit', $c->id) }}" class="text-blue-700 hover:underline font-semibold">Edit</a>
                                <form action="{{ route('admin.classes.destroy', $c->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kelas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:underline font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">Belum ada data kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200">
            {{ $classes->links() }}
        </div>
    </div>
</div>
@endsection

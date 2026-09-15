@extends('layouts.app')

@section('title', 'Manajemen Guru')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Manajemen Akun Guru</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola akun guru pengajar dan pembagian mata pelajaran</p>
        </div>
        <a href="{{ route('admin.teachers.create') }}" class="bg-blue-700 hover:bg-blue-800 text-white font-semibold text-xs py-2 px-3.5 rounded-md transition shadow-sm">
            + Tambah Akun Guru
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Nama Pengajar</th>
                        <th class="px-4 py-3">Username</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Mata Pelajaran Ampuan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($teachers as $t)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $t->name }}</td>
                            <td class="px-4 py-3 font-mono font-bold text-blue-900">{{ $t->username }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $t->email ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($t->subjects as $subj)
                                        <span class="bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded border border-slate-200 text-[11px] font-medium">{{ $subj->code }}</span>
                                    @empty
                                        <span class="text-slate-400 italic text-[11px]">Belum diatur</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($t->is_active)
                                    <span class="bg-emerald-50 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded border border-emerald-200">AKTIF</span>
                                @else
                                    <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200">NONAKTIF</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('admin.teachers.edit', $t->id) }}" class="text-blue-700 hover:underline font-semibold">Edit</a>
                                <form action="{{ route('admin.teachers.destroy', $t->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus akun guru ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:underline font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">Belum ada akun guru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200">
            {{ $teachers->links() }}
        </div>
    </div>
</div>
@endsection

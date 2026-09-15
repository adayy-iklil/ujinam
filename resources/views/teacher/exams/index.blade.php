@extends('layouts.app')

@section('title', 'Kelola Ujian')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Kelola Daftar Ujian</h2>
            <p class="text-xs text-slate-500 mt-0.5">Daftar paket ujian sekolah yang Anda kelola</p>
        </div>
        <a href="{{ route('guru.exams.create') }}" class="bg-blue-700 hover:bg-blue-800 text-white font-semibold text-xs py-2 px-3.5 rounded-md transition shadow-sm">
            + Tambah Ujian Baru
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Judul Ujian</th>
                        <th class="px-4 py-3">Mata Pelajaran</th>
                        <th class="px-4 py-3">Kelas Target</th>
                        <th class="px-4 py-3">Durasi</th>
                        <th class="px-4 py-3">Soal</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($exams as $exam)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-semibold text-slate-900">
                                <div>{{ $exam->title }}</div>
                                <div class="text-[11px] font-normal text-slate-500 mt-0.5">
                                    {{ $exam->start_at->format('d M, H:i') }} - {{ $exam->end_at->format('d M Y, H:i') }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-600 font-medium">{{ $exam->subject->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($exam->classes as $c)
                                        <span class="bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded border border-slate-200 font-medium text-[11px]">{{ $c->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 font-mono font-medium">{{ $exam->duration_minutes }} mnt</td>
                            <td class="px-4 py-3 font-mono font-bold text-blue-700">{{ $exam->questions_count }} Soal</td>
                            <td class="px-4 py-3">
                                <form action="{{ route('guru.exams.toggleStatus', $exam->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold px-2 py-0.5 rounded border transition {{ $exam->status === 'published' ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border-slate-300 hover:bg-slate-200' }}">
                                        {{ strtoupper($exam->status) }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    <a href="{{ route('guru.exams.questions.index', $exam->id) }}" class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 hover:bg-blue-100 hover:border-blue-300 px-2 py-1 rounded border border-blue-200 font-medium text-[11px]">
                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Soal
                                    </a>
                                    <a href="{{ route('guru.exams.participants', $exam->id) }}" class="inline-flex items-center gap-1 bg-slate-50 text-slate-700 hover:bg-slate-100 hover:border-slate-400 px-2 py-1 rounded border border-slate-200 font-medium text-[11px]">
                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4m22 0a4 4 0 00-4-4H9a4 4 0 00-4 4m10 0a4 4 0 01-4 4 4 4 0 01-4-4m10 0V7a4 4 0 00-4-4H9a4 4 0 00-4 4v10"/></svg>
                                        Peserta
                                    </a>
                                    <a href="{{ route('guru.exams.edit', $exam->id) }}" class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 hover:bg-amber-100 hover:border-amber-300 px-2 py-1 rounded border border-amber-200 font-medium text-[11px]">
                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('guru.exams.destroy', $exam->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ujian ini?')">
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
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">Belum ada ujian. Klik tombol "+ Tambah Ujian Baru" untuk membuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200">
            {{ $exams->links() }}
        </div>
    </div>
</div>
@endsection

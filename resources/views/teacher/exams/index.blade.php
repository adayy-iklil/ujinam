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
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('guru.exams.questions.index', $exam->id) }}" class="bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold px-2 py-1 rounded border border-blue-200">Soal</a>
                                <a href="{{ route('guru.exams.participants', $exam->id) }}" class="bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium px-2 py-1 rounded border border-slate-200">Peserta</a>
                                <a href="{{ route('guru.exams.edit', $exam->id) }}" class="text-slate-600 hover:text-slate-900 font-medium">Edit</a>
                                <form action="{{ route('guru.exams.destroy', $exam->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ujian ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-medium">Hapus</button>
                                </form>
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

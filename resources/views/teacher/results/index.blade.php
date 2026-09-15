@extends('layouts.app')

@section('title', 'Rekap Hasil Ujian')

@section('content')
<div class="space-y-6">
    <div class="border-b border-slate-200 pb-3">
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Hasil & Rekap Nilai Ujian</h2>
        <p class="text-xs text-slate-500 mt-0.5">Pilih ujian untuk melihat rekapitulasi nilai siswa</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Nama Ujian</th>
                        <th class="px-4 py-3">Mata Pelajaran</th>
                        <th class="px-4 py-3">Kelas Target</th>
                        <th class="px-4 py-3">Total Peserta Submit</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($exams as $exam)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $exam->title }}</td>
                            <td class="px-4 py-3 text-slate-600 font-medium">{{ $exam->subject->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($exam->classes as $c)
                                        <span class="bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded border border-slate-200 font-medium text-[11px]">{{ $c->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 font-mono font-bold text-emerald-700">{{ $exam->attempts_count }} Peserta</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('guru.results.exam', $exam->id) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-3 py-1.5 rounded transition">
                                    Lihat Rekap Nilai →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada ujian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

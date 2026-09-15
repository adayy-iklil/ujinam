@extends('layouts.app')

@section('title', 'Detail Nilai Ujian')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
        <div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('guru.results.index') }}" class="text-xs text-blue-700 hover:underline">← Rekap Ujian</a>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-bold text-slate-700 uppercase">Nilai Detail</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight mt-1">{{ $exam->title }}</h2>
            <p class="text-xs text-slate-500">Mata Pelajaran: {{ $exam->subject->name ?? '-' }}</p>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Total Peserta Submit</span>
            <div class="text-2xl font-bold text-slate-900 font-mono mt-1">{{ $attempts->count() }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 block">Rata-rata Nilai</span>
            <div class="text-2xl font-bold text-blue-700 font-mono mt-1">{{ $averageScore }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 block">Nilai Tertinggi</span>
            <div class="text-2xl font-bold text-emerald-700 font-mono mt-1">{{ $highestScore }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-rose-600 block">Nilai Terendah</span>
            <div class="text-2xl font-bold text-rose-700 font-mono mt-1">{{ $lowestScore }}</div>
        </div>
    </div>

    <!-- Student Scores Table -->
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Peringkat</th>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">NIS</th>
                        <th class="px-4 py-3">Kelas</th>
                        <th class="px-4 py-3">Jawaban Benar</th>
                        <th class="px-4 py-3">Jawaban Salah</th>
                        <th class="px-4 py-3 text-right">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($attempts as $idx => $att)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-mono font-bold text-slate-500">#{{ $idx + 1 }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $att->student->name }}</td>
                            <td class="px-4 py-3 font-mono text-slate-600">{{ $att->student->nis }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $att->student->schoolClass->name ?? '-' }}</td>
                            <td class="px-4 py-3 font-mono text-emerald-700 font-semibold">{{ $att->correct_answers }} Soal</td>
                            <td class="px-4 py-3 font-mono text-rose-600">{{ $att->wrong_answers }} Soal</td>
                            <td class="px-4 py-3 text-right font-mono font-extrabold text-sm text-blue-900 bg-blue-50/50">
                                {{ number_format($att->score, 1) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">Belum ada nilai siswa yang masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

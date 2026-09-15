@extends('layouts.student')

@section('content')
<div class="max-w-xl mx-auto my-6">
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <div class="bg-slate-900 text-white p-6 text-center">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 bg-slate-800 px-2.5 py-1 rounded">Hasil Ujian Terverifikasi</span>
            <h2 class="text-lg font-bold mt-2">{{ $attempt->exam->title }}</h2>
            <p class="text-xs text-slate-400 mt-0.5">{{ $attempt->exam->subject->name ?? '' }}</p>
        </div>

        <div class="p-6 text-center space-y-6">
            <!-- Score Display -->
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-6 max-w-xs mx-auto">
                <span class="text-xs font-bold text-blue-700 uppercase tracking-wider block mb-1">Nilai Akhir Ujian</span>
                <div class="text-4xl font-extrabold text-blue-900 font-mono tracking-tight">
                    {{ number_format($attempt->score, 1) }}
                </div>
                <span class="text-[11px] text-blue-600 block mt-1">Skala 0 - 100</span>
            </div>

            <!-- Stats Breakdown Table -->
            <div class="grid grid-cols-3 gap-3 text-xs">
                <div class="bg-slate-50 border border-slate-200 p-3 rounded">
                    <span class="text-slate-500 block">Jawaban Benar</span>
                    <strong class="text-emerald-700 text-sm font-bold">{{ $attempt->correct_answers }}</strong>
                </div>
                <div class="bg-slate-50 border border-slate-200 p-3 rounded">
                    <span class="text-slate-500 block">Jawaban Salah</span>
                    <strong class="text-rose-700 text-sm font-bold">{{ $attempt->wrong_answers }}</strong>
                </div>
                <div class="bg-slate-50 border border-slate-200 p-3 rounded">
                    <span class="text-slate-500 block">Pelanggaran</span>
                    <strong class="text-slate-700 text-sm font-bold">{{ $attempt->violation_count }}x</strong>
                </div>
            </div>

            <div class="text-xs text-slate-500 text-left bg-slate-50 p-3.5 rounded border border-slate-200 space-y-1">
                <div><span>Selesai Pada:</span> <strong class="text-slate-800">{{ $attempt->submitted_at ? $attempt->submitted_at->format('d M Y, H:i:s') : '-' }} WIB</strong></div>
                <div><span>Siswa:</span> <strong class="text-slate-800">{{ $attempt->student->name }} ({{ $attempt->student->nis }})</strong></div>
            </div>

            <div class="pt-2">
                <a href="{{ route('siswa.dashboard') }}" class="w-full inline-block bg-blue-700 hover:bg-blue-800 text-white font-semibold text-xs py-2.5 px-4 rounded transition">
                    Kembali ke Dashboard Siswa
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

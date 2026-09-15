@extends('layouts.app')

@section('title', 'Hasil Kenaikan Kelas')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Laporan Kenaikan Kelas</h2>
        <a href="{{ route('admin.dashboard') }}" class="text-xs text-slate-600 hover:text-slate-900 font-medium">← Kembali ke Dashboard</a>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm space-y-6">
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-emerald-50 border border-emerald-200 p-4 rounded text-center">
                <span class="text-xs font-bold uppercase text-emerald-800 block">Siswa XI Dipromosikan (Ke XII)</span>
                <div class="text-3xl font-extrabold text-emerald-900 font-mono mt-1">{{ $result['promoted_students'] }} Siswa</div>
            </div>

            <div class="bg-slate-100 border border-slate-300 p-4 rounded text-center">
                <span class="text-xs font-bold uppercase text-slate-700 block">Siswa X Diarsipkan</span>
                <div class="text-3xl font-extrabold text-slate-900 font-mono mt-1">{{ $result['archived_students'] }} Siswa</div>
            </div>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Rincian Log Eksekusi Kenaikan Kelas:</h3>
            <div class="bg-slate-900 text-slate-100 rounded p-4 max-h-60 overflow-y-auto space-y-1 text-xs font-mono">
                @foreach($result['details'] as $line)
                    <div>✓ {{ $line }}</div>
                @endforeach
            </div>
        </div>

        <div class="pt-4 border-t border-slate-200 text-right">
            <a href="{{ route('admin.students.index') }}" class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded shadow-sm inline-block">
                Lihat Data Siswa Terkini
            </a>
        </div>
    </div>
</div>
@endsection

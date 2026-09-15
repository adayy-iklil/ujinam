@extends('layouts.app')

@section('title', 'Laporan Hasil Import Siswa')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Laporan Hasil Import Data Siswa</h2>
        <a href="{{ route('admin.students.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-medium">← Kembali ke Data Siswa</a>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-emerald-50 border border-emerald-200 p-4 rounded text-center">
                <span class="text-xs font-bold uppercase text-emerald-700 block">Berhasil Di-Import</span>
                <div class="text-3xl font-extrabold text-emerald-900 font-mono mt-1">{{ $result['success'] }}</div>
            </div>

            <div class="bg-rose-50 border border-rose-200 p-4 rounded text-center">
                <span class="text-xs font-bold uppercase text-rose-700 block">Gagal / Ditolak</span>
                <div class="text-3xl font-extrabold text-rose-900 font-mono mt-1">{{ $result['failed'] }}</div>
            </div>
        </div>

        <!-- Errors Breakdown -->
        @if(!empty($result['errors']))
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-rose-800 mb-2">Rincian Pesan Kesalahan (Errors):</h3>
                <div class="bg-rose-50 border border-rose-200 rounded p-4 max-h-60 overflow-y-auto space-y-1 text-xs text-rose-900 font-mono">
                    @foreach($result['errors'] as $err)
                        <div>• {{ $err }}</div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-emerald-50 border border-emerald-200 p-4 rounded text-xs text-emerald-800 font-medium text-center">
                ✓ Seluruh data dalam file CSV berhasil di-import tanpa kesalahan.
            </div>
        @endif

        <div class="pt-4 border-t border-slate-200 text-right">
            <a href="{{ route('admin.students.index') }}" class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded shadow-sm inline-block">
                Selesai & Lihat Data Siswa
            </a>
        </div>
    </div>
</div>
@endsection

@extends('layouts.student')

@section('content')
<div class="max-w-3xl mx-auto my-4 space-y-5">

    <!-- Action Toolbar (Hidden when printing) -->
    <div class="no-print flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white border border-slate-200 rounded-lg p-4 shadow-sm">
        <div>
            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Ujian Berhasil Diselesaikan
            </span>
            <p class="text-xs text-slate-500 mt-1">Nilai telah dihitung otomatis oleh sistem CBT SMKN 6 Jakarta.</p>
        </div>

        <div class="flex items-center gap-2">
            <button 
                type="button" 
                onclick="window.print()" 
                class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs py-2 px-3.5 rounded transition shadow-sm"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak Bukti Ujian</span>
            </button>

            <a 
                href="{{ route('siswa.dashboard') }}" 
                class="inline-flex items-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs py-2 px-3.5 rounded border border-slate-300 transition"
            >
                Kembali ke Beranda
            </a>
        </div>
    </div>

    <!-- Main Exam Result Card -->
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden print:border-none print:shadow-none">
        
        <!-- Compact Header with Logo & Exam Title -->
        <div class="p-5 border-b border-slate-100 bg-slate-50/70">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <img 
                        src="{{ asset('images/logo-smkn6jkt.png') }}" 
                        alt="Logo SMKN 6 Jakarta" 
                        class="h-11 w-auto flex-shrink-0"
                        style="height: 44px; width: auto; max-height: 44px;"
                    >
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                                {{ $attempt->exam->subject->name ?? 'Mata Pelajaran' }} ({{ $attempt->exam->subject->code ?? '-' }})
                            </span>
                            <span class="text-[11px] text-slate-400 font-mono hidden sm:inline">
                                ID: #CBT-{{ str_pad($attempt->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 mt-1 leading-snug">
                            {{ $attempt->exam->title }}
                        </h2>
                    </div>
                </div>

                <div class="text-right flex-shrink-0 hidden sm:block">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block tracking-wider">SMK Negeri 6 Jakarta</span>
                    <span class="text-xs text-slate-600 font-medium">CBT Examination System</span>
                </div>
            </div>
        </div>

        <!-- Score & Statistics Hero -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-center">
                
                <!-- Left: Big Score Display (5 cols) -->
                <div class="md:col-span-5 bg-slate-50 border border-slate-200 rounded-lg p-5 text-center flex flex-col justify-center items-center">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Nilai Akhir Ujian</span>
                    
                    <div class="text-5xl font-mono font-extrabold text-slate-900 tracking-tight my-2">
                        {{ (int) round($attempt->score) }}
                    </div>

                    <span class="text-[11px] text-slate-400 font-medium">Skala Penilaian: 0 — 100</span>

                    <div class="mt-3 w-full">
                        @if(round($attempt->score) >= 75)
                            <div class="w-full text-center py-1 px-3 rounded bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold">
                                ✓ TUNTAS (Memenuhi KKM)
                            </div>
                        @else
                            <div class="w-full text-center py-1 px-3 rounded bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold">
                                ⚠ BELUM TUNTAS (Perlu Remedial)
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right: Breakdown Metric Cards (7 cols) -->
                <div class="md:col-span-7 grid grid-cols-2 gap-3">
                    
                    <!-- Metric: Benar -->
                    <div class="bg-emerald-50/60 border border-emerald-200 rounded-md p-3.5">
                        <div class="flex items-center justify-between text-xs text-emerald-800 font-medium mb-1">
                            <span>Jawaban Benar</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        </div>
                        <div class="text-xl font-bold font-mono text-emerald-900">
                            {{ $attempt->correct_answers }} <span class="text-xs font-sans font-normal text-emerald-700">Soal</span>
                        </div>
                    </div>

                    <!-- Metric: Salah -->
                    <div class="bg-rose-50/60 border border-rose-200 rounded-md p-3.5">
                        <div class="flex items-center justify-between text-xs text-rose-800 font-medium mb-1">
                            <span>Jawaban Salah</span>
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        </div>
                        <div class="text-xl font-bold font-mono text-rose-900">
                            {{ $attempt->wrong_answers }} <span class="text-xs font-sans font-normal text-rose-700">Soal</span>
                        </div>
                    </div>

                    <!-- Metric: Akurasi -->
                    @php
                        $totalQ = $attempt->correct_answers + $attempt->wrong_answers;
                        $accuracy = $totalQ > 0 ? (int) round(($attempt->correct_answers / $totalQ) * 100) : 0;
                    @endphp
                    <div class="bg-blue-50/60 border border-blue-200 rounded-md p-3.5">
                        <div class="flex items-center justify-between text-xs text-blue-800 font-medium mb-1">
                            <span>Tingkat Akurasi</span>
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        </div>
                        <div class="text-xl font-bold font-mono text-blue-900">
                            {{ $accuracy }}%
                        </div>
                    </div>

                    <!-- Metric: Durasi -->
                    @php
                        $durationMin = ($attempt->started_at && $attempt->submitted_at) 
                            ? max(1, $attempt->started_at->diffInMinutes($attempt->submitted_at)) 
                            : 0;
                    @endphp
                    <div class="bg-slate-50 border border-slate-200 rounded-md p-3.5">
                        <div class="flex items-center justify-between text-xs text-slate-600 font-medium mb-1">
                            <span>Waktu Pengerjaan</span>
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                        </div>
                        <div class="text-xl font-bold font-mono text-slate-800">
                            {{ $durationMin }} <span class="text-xs font-sans font-normal text-slate-600">Menit</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Detail Information Table -->
            <div class="mt-6 pt-5 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Rincian Informasi Ujian</h3>
                
                <div class="bg-slate-50 rounded-lg border border-slate-200 divide-y divide-slate-200 text-xs">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 p-3 gap-1">
                        <span class="text-slate-500 font-medium">Nama Siswa</span>
                        <span class="sm:col-span-2 font-bold text-slate-900">{{ $attempt->student->name }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 p-3 gap-1">
                        <span class="text-slate-500 font-medium">Nomor Induk Siswa (NIS)</span>
                        <span class="sm:col-span-2 font-mono font-semibold text-slate-800">{{ $attempt->student->nis }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 p-3 gap-1">
                        <span class="text-slate-500 font-medium">Kelas / Rombel</span>
                        <span class="sm:col-span-2 font-semibold text-slate-800">{{ $attempt->student->schoolClass->name ?? '-' }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 p-3 gap-1">
                        <span class="text-slate-500 font-medium">Guru Pengampu</span>
                        <span class="sm:col-span-2 text-slate-800">{{ $attempt->exam->teacher->name ?? 'Tim Pengajar CBT' }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 p-3 gap-1">
                        <span class="text-slate-500 font-medium">Waktu Selesai</span>
                        <span class="sm:col-span-2 text-slate-800">{{ $attempt->submitted_at ? $attempt->submitted_at->format('d M Y, H:i:s') : '-' }} WIB</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 p-3 gap-1">
                        <span class="text-slate-500 font-medium">Integritas Ujian</span>
                        <span class="sm:col-span-2">
                            @if($attempt->violation_count == 0)
                                <span class="font-semibold text-emerald-700">✓ Sesi Tertib (Bebas Pelanggaran)</span>
                            @else
                                <span class="font-semibold text-amber-700">⚠ Tercatat {{ $attempt->violation_count }}x Peringatan Perpindahan Jendela</span>
                            @endif
                        </span>
                    </div>

                </div>
            </div>

            <!-- Print Footer / Verification Token (shown mainly when printing) -->
            <div class="hidden print:block mt-8 pt-4 border-t border-slate-300 text-[10px] text-slate-500 text-center font-mono">
                Lembar Hasil CBT SMKN 6 Jakarta • ID Sesi: #{{ $attempt->id }} • Dicetak pada {{ now()->format('d/m/Y H:i') }} WIB
            </div>

        </div>

    </div>

</div>

<!-- Print Styles -->
<style>
    @media print {
        header, footer, .no-print {
            display: none !important;
        }
        body {
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        main {
            padding: 0 !important;
            max-width: 100% !important;
        }
    }
</style>
@endsection

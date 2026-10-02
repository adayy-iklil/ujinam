@extends('layouts.student')

@section('content')
<div class="max-w-3xl mx-auto my-4 space-y-6">

    <!-- Modern Action Toolbar (Hidden when printing) -->
    <div class="no-print flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-sm">
        <div class="flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center font-bold text-lg flex-shrink-0 shadow-sm">
                ✓
            </div>
            <div>
                <h3 class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight">Hasil Ujian Terverifikasi</h3>
                <p class="text-xs text-slate-500">Skor dan capaian kompetensi telah dievaluasi oleh sistem CBT sekolah.</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <button 
                type="button" 
                onclick="window.print()" 
                class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs py-2.5 px-4 rounded-xl shadow-md shadow-slate-900/10 hover:shadow-lg transition-all"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak Lembar Nilai</span>
            </button>

            <a 
                href="{{ route('siswa.dashboard') }}" 
                class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs py-2.5 px-4 rounded-xl border border-slate-300 transition"
            >
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Main Exam Result Card -->
    <div class="bg-white border border-slate-200/90 rounded-3xl shadow-sm overflow-hidden print:border-none print:shadow-none">
        
        <!-- Header with School Identity & Exam Title -->
        <div class="p-6 sm:p-7 border-b border-slate-100 bg-gradient-to-r from-slate-50 via-indigo-50/20 to-slate-50">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-4">
                    <img 
                        src="{{ asset('images/logo-smkn6jkt.png') }}" 
                        alt="Logo SMKN 6 Jakarta" 
                        class="h-12 w-auto object-contain flex-shrink-0 drop-shadow-sm"
                        style="height: 48px; width: auto; max-height: 48px;"
                    >
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-lg border border-indigo-100">
                                {{ $attempt->exam->subject->name ?? 'Mata Pelajaran' }} ({{ $attempt->exam->subject->code ?? '-' }})
                            </span>
                            <span class="text-[11px] text-slate-400 font-mono hidden sm:inline">
                                Sesi ID #CBT-{{ str_pad($attempt->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-1 leading-snug">
                            {{ $attempt->exam->title }}
                        </h2>
                    </div>
                </div>

                <div class="text-right flex-shrink-0 hidden md:block">
                    <span class="text-[11px] font-extrabold uppercase text-slate-700 block tracking-wider">SMK NEGERI 6 JAKARTA</span>
                    <span class="text-xs text-indigo-600 font-semibold">Computer Based Test System</span>
                </div>
            </div>
        </div>

        <!-- Score & Analytics Showcase -->
        <div class="p-6 sm:p-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                
                <!-- Left: Big Score Display (5 cols) -->
                <div class="md:col-span-5 bg-gradient-to-b from-slate-50 to-indigo-50/30 border border-slate-200/90 rounded-2xl p-6 text-center flex flex-col justify-center items-center shadow-inner">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Nilai Akhir Ujian</span>
                    
                    <div class="text-6xl font-mono font-black text-slate-900 tracking-tight my-2">
                        {{ (int) round($attempt->score) }}
                    </div>

                    <span class="text-xs text-slate-400 font-medium">Skala Penilaian: 0 — 100</span>

                    <div class="mt-4 w-full">
                        @if(round($attempt->score) >= 75)
                            <div class="w-full text-center py-2 px-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 text-xs font-bold flex items-center justify-center gap-1.5 shadow-sm">
                                <span>✓</span>
                                <span>TUNTAS (Memenuhi KKM)</span>
                            </div>
                        @else
                            <div class="w-full text-center py-2 px-3 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-800 text-xs font-bold flex items-center justify-center gap-1.5 shadow-sm">
                                <span>⚠️</span>
                                <span>BELUM TUNTAS (Perlu Remedial)</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right: Metric Analytics (7 cols) -->
                <div class="md:col-span-7 grid grid-cols-2 gap-3.5">
                    
                    <!-- Metric: Benar -->
                    <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center justify-between text-xs text-emerald-800 font-semibold mb-1">
                            <span>Jawaban Benar</span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span>
                        </div>
                        <div class="text-2xl font-black font-mono text-emerald-950">
                            {{ $attempt->correct_answers }} <span class="text-xs font-sans font-normal text-emerald-700">Soal</span>
                        </div>
                    </div>

                    <!-- Metric: Salah -->
                    <div class="bg-rose-50/70 border border-rose-200/80 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center justify-between text-xs text-rose-800 font-semibold mb-1">
                            <span>Jawaban Salah</span>
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shadow-sm shadow-rose-500/50"></span>
                        </div>
                        <div class="text-2xl font-black font-mono text-rose-950">
                            {{ $attempt->wrong_answers }} <span class="text-xs font-sans font-normal text-rose-700">Soal</span>
                        </div>
                    </div>

                    <!-- Metric: Akurasi -->
                    @php
                        $totalQ = $attempt->correct_answers + $attempt->wrong_answers;
                        $accuracy = $totalQ > 0 ? (int) round(($attempt->correct_answers / $totalQ) * 100) : 0;
                    @endphp
                    <div class="bg-indigo-50/70 border border-indigo-200/80 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center justify-between text-xs text-indigo-800 font-semibold mb-1">
                            <span>Tingkat Akurasi</span>
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 shadow-sm shadow-indigo-600/50"></span>
                        </div>
                        <div class="text-2xl font-black font-mono text-indigo-950">
                            {{ $accuracy }}%
                        </div>
                    </div>

                    <!-- Metric: Durasi -->
                    @php
                        $durationMin = ($attempt->started_at && $attempt->submitted_at) 
                            ? max(1, $attempt->started_at->diffInMinutes($attempt->submitted_at)) 
                            : 0;
                    @endphp
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center justify-between text-xs text-slate-600 font-semibold mb-1">
                            <span>Waktu Pengerjaan</span>
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                        </div>
                        <div class="text-2xl font-black font-mono text-slate-800">
                            {{ $durationMin }} <span class="text-xs font-sans font-normal text-slate-600">Menit</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Detail Information Table -->
            <div class="mt-8 pt-6 border-t border-slate-100">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-3.5">
                    Rincian Data Peserta & Pelaksanaan Ujian
                </h3>
                
                <div class="bg-slate-50/80 rounded-2xl border border-slate-200/90 divide-y divide-slate-200/80 text-xs sm:text-sm">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 p-3.5 gap-1">
                        <span class="text-slate-500 font-medium">Nama Peserta</span>
                        <span class="sm:col-span-2 font-bold text-slate-900">{{ $attempt->student->name }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 p-3.5 gap-1">
                        <span class="text-slate-500 font-medium">Nomor Induk Siswa (NIS)</span>
                        <span class="sm:col-span-2 font-mono font-semibold text-slate-800">{{ $attempt->student->nis }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 p-3.5 gap-1">
                        <span class="text-slate-500 font-medium">Kelas / Rombel</span>
                        <span class="sm:col-span-2 font-semibold text-slate-800">{{ $attempt->student->schoolClass->name ?? '-' }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 p-3.5 gap-1">
                        <span class="text-slate-500 font-medium">Guru Pengampu</span>
                        <span class="sm:col-span-2 text-slate-800">{{ $attempt->exam->teacher->name ?? 'Tim Pengajar CBT' }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 p-3.5 gap-1">
                        <span class="text-slate-500 font-medium">Waktu Selesai</span>
                        <span class="sm:col-span-2 text-slate-800">{{ $attempt->submitted_at ? $attempt->submitted_at->format('d M Y, H:i:s') : '-' }} WIB</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 p-3.5 gap-1">
                        <span class="text-slate-500 font-medium">Integritas Ujian</span>
                        <span class="sm:col-span-2">
                            @if($attempt->violation_count == 0)
                                <span class="font-bold text-emerald-700 inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    Sesi Tertib (Bebas Pelanggaran)
                                </span>
                            @else
                                <span class="font-bold text-amber-700 inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    Tercatat {{ $attempt->violation_count }}x Peringatan Perpindahan Jendela
                                </span>
                            @endif
                        </span>
                    </div>

                </div>
            </div>

            <!-- Print Footer -->
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

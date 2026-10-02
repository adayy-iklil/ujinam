@extends('layouts.student')

@section('content')
<div class="max-w-2xl mx-auto my-4">
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
        <!-- Header Banner -->
        <div class="bg-slate-900 text-white p-6 sm:p-7 border-b border-slate-800">
            <div class="space-y-1.5">
                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-300 bg-slate-800 px-2.5 py-0.5 rounded border border-slate-700">
                    Petunjuk Pelaksanaan Ujian
                </span>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white leading-tight">
                    {{ $exam->title }}
                </h1>
                <p class="text-xs text-slate-300 font-medium">
                    Mata Pelajaran: {{ $exam->subject->name ?? '-' }} ({{ $exam->subject->code ?? '-' }})
                </p>
            </div>
        </div>

        <div class="p-6 sm:p-7 space-y-6">
            <!-- Exam Meta Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl text-center">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Durasi</span>
                    <strong class="text-sm font-bold font-mono text-slate-900 mt-0.5 block">{{ $exam->duration_minutes }} Menit</strong>
                </div>
                <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl text-center">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Butir Soal</span>
                    <strong class="text-sm font-bold font-mono text-slate-900 mt-0.5 block">{{ $exam->questions()->count() }} Butir</strong>
                </div>
                <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl text-center">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Mulai</span>
                    <strong class="text-xs font-semibold text-slate-800 mt-0.5 block">{{ $exam->start_at->format('d M, H:i') }}</strong>
                </div>
                <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl text-center">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Batas Selesai</span>
                    <strong class="text-xs font-semibold text-slate-800 mt-0.5 block">{{ $exam->end_at->format('d M, H:i') }}</strong>
                </div>
            </div>

            <!-- Rules & Instructions -->
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2.5">
                    Tata Tertib & Kepatuhan Integritas Ujian
                </h2>
                
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs text-slate-700 space-y-2.5 leading-relaxed">
                    <div class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded bg-slate-200 text-slate-800 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">1</span>
                        <div><strong>Pengawasan Sistem Otomatis:</strong> Dilarang berpindah tab browser, membuka aplikasi lain, atau meminimalkan jendela ujian.</div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded bg-slate-200 text-slate-800 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">2</span>
                        <div><strong>Batas Toleransi Pelanggaran (Maksimal 3x):</strong> Sistem merekam setiap pergantian tab. Pada pelanggaran ke-3, sesi ujian akan <strong>TERKUNCI SECARA OTOMATIS</strong>.</div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded bg-slate-200 text-slate-800 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">3</span>
                        <div><strong>Penyimpanan Otomatis:</strong> Jawaban yang Anda pilih disimpan langsung ke server CBT secara berkala.</div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded bg-slate-200 text-slate-800 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">4</span>
                        <div><strong>Timer Server Terpadu:</strong> Waktu terus berjalan di server dan tidak bertambah apabila peramban di-refresh.</div>
                    </div>
                </div>

                @if($exam->instructions)
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Instruksi Khusus Pengampu:</h3>
                        <div class="text-xs text-slate-700 leading-relaxed bg-slate-50 p-3 rounded-lg border border-slate-200 whitespace-pre-line">
                            {{ $exam->instructions }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Form -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-4">
                <a href="{{ route('siswa.dashboard') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                    <span>← Batal & Kembali</span>
                </a>

                @if($attempt && $attempt->status === 'submitted')
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-800 bg-emerald-50 px-3 py-2 rounded-lg border border-emerald-200">
                        <span>✓</span>
                        <span>Anda telah menyelesaikan ujian ini</span>
                    </span>
                @else
                    <form action="{{ route('siswa.exams.start', $exam->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm py-2.5 px-5 rounded-lg shadow-xs transition-colors">
                            <span>{{ $attempt ? 'Lanjutkan Ujian' : 'Saya Siap, Mulai Ujian' }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

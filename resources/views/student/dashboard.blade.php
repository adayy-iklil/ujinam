@extends('layouts.student')

@section('content')
<div class="space-y-6">
    <!-- Institutional Student Profile Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-base flex-shrink-0 shadow-xs">
                    {{ strtoupper(substr($student->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">
                            {{ $student->name }}
                        </h1>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Terverifikasi
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs text-slate-500 mt-1.5">
                        <span class="font-mono bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-semibold border border-slate-200">
                            NIS: {{ $student->nis }}
                        </span>
                        <span>•</span>
                        <span class="font-semibold text-slate-700">
                            Kelas: {{ $student->schoolClass->name ?? '-' }}
                        </span>
                        <span>•</span>
                        <span>
                            Jurusan: {{ $student->schoolClass->major->name ?? '-' }}
                        </span>
                        <span>•</span>
                        <span>
                            {{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- School Exam Center Badge -->
            <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 px-4 py-3 rounded-xl self-start md:self-auto">
                <div class="text-right">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Pusat Ujian CBT</span>
                    <span class="text-xs font-bold text-slate-800">SMKN 6 JAKARTA</span>
                </div>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    @php
        $totalExams = $availableExams->count();
        $completedExams = $availableExams->filter(function($e) use ($attempts) {
            $att = $attempts->get($e->id);
            return $att && $att->status === 'submitted';
        })->count();
        $inProgressExams = $availableExams->filter(function($e) use ($attempts) {
            $att = $attempts->get($e->id);
            return $att && $att->status === 'in_progress';
        })->count();
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200/90 rounded-xl p-4 sm:p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Ujian Terjadwal</span>
                <span class="text-2xl font-bold font-mono text-slate-900 mt-1 block">{{ $totalExams }}</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-xl p-4 sm:p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Selesai Dikerjakan</span>
                <span class="text-2xl font-bold font-mono text-emerald-600 mt-1 block">{{ $completedExams }}</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-xl p-4 sm:p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Sedang Berlangsung</span>
                <span class="text-2xl font-bold font-mono text-blue-600 mt-1 block">{{ $inProgressExams }}</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Available Exams List Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Daftar Jadwal Ujian</h2>
                <p class="text-xs text-slate-500">Pilih ujian aktif yang ditugaskan untuk rombel kelas Anda.</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-slate-200/80 text-slate-700 rounded-md font-mono">
                {{ $availableExams->count() }} Ujian
            </span>
        </div>

        @if($availableExams->isEmpty())
            <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center shadow-xs">
                <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Tidak Ada Ujian Aktif</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto leading-relaxed">
                    Saat ini belum ada jadwal ujian terbuka untuk kelas <strong>{{ $student->schoolClass->name ?? '-' }}</strong>. Silakan hubungi guru pengampu atau pantau secara berkala saat jam ujian dimulai.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($availableExams as $exam)
                    @php
                        $attempt = $attempts->get($exam->id);
                        $isOngoing = now()->between($exam->start_at, $exam->end_at);
                        $isUpcoming = now()->lessThan($exam->start_at);
                        $isExpired = now()->greaterThan($exam->end_at);
                    @endphp
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs hover:border-slate-400 transition-colors flex flex-col justify-between">
                        <div>
                            <!-- Header Tags -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $exam->subject->code ?? '-' }} • {{ $exam->subject->name ?? 'Mata Pelajaran' }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 bg-slate-50 px-2 py-0.5 rounded border border-slate-200">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $exam->duration_minutes }} Menit
                                </span>
                            </div>

                            <h3 class="font-bold text-base text-slate-900 leading-snug">
                                {{ $exam->title }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-1.5 line-clamp-2 leading-relaxed">
                                {{ $exam->description ?? 'Ujian evaluasi capaian kompetensi pembelajaran siswa.' }}
                            </p>

                            <!-- Timeline Info -->
                            <div class="mt-4 p-3 rounded-lg bg-slate-50 border border-slate-100 text-xs text-slate-600 space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Jadwal Mulai:</span>
                                    <span class="font-semibold text-slate-700">{{ $exam->start_at->format('d M Y, H:i') }} WIB</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Batas Waktu:</span>
                                    <span class="font-semibold text-slate-700">{{ $exam->end_at->format('d M Y, H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Action Bottom Bar -->
                        <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                            @if(!$attempt)
                                @if($isOngoing)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Dapat Dikerjakan
                                    </span>
                                    <a href="{{ route('siswa.exams.instruction', $exam->id) }}" class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs py-2 px-3.5 rounded-lg transition-colors">
                                        <span>Mulai Ujian</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                @elseif($isUpcoming)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-700 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-200">
                                        Belum Dibuka
                                    </span>
                                    <button disabled class="bg-slate-100 text-slate-400 font-semibold text-xs py-2 px-3.5 rounded-lg cursor-not-allowed">
                                        Menunggu Jadwal
                                    </button>
                                @else
                                    <span class="text-xs font-medium text-slate-400 bg-slate-100 px-2.5 py-1 rounded-md">
                                        Waktu Telah Berakhir
                                    </span>
                                    <button disabled class="bg-slate-100 text-slate-400 font-semibold text-xs py-2 px-3.5 rounded-lg cursor-not-allowed">
                                        Ditutup
                                    </button>
                                @endif
                            @else
                                @if($attempt->status === 'submitted')
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Selesai
                                    </span>
                                    @if($exam->show_result)
                                        <a href="{{ route('siswa.attempts.result', $attempt->id) }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs py-2 px-3.5 rounded-lg transition-colors">
                                            <span>Lihat Hasil</span>
                                            <span class="font-mono font-bold bg-slate-800 text-emerald-400 px-1.5 py-0.5 rounded text-[11px]">{{ (int) round($attempt->score) }}</span>
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium italic">Hasil Disembunyikan</span>
                                    @endif
                                @elseif($attempt->isLocked())
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-700 bg-rose-50 px-2.5 py-1 rounded-md border border-rose-200">
                                        Sesi Terkunci
                                    </span>
                                    <a href="{{ route('siswa.attempts.show', $attempt->id) }}" class="bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs py-2 px-3.5 rounded-lg transition-colors">
                                        Buka Pengawas
                                    </a>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-800 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-200">
                                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                        Sedang Berlangsung
                                    </span>
                                    <a href="{{ route('siswa.attempts.show', $attempt->id) }}" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs py-2 px-3.5 rounded-lg transition-colors">
                                        Lanjutkan Ujian →
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

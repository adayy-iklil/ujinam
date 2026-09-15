@extends('layouts.student')

@section('content')
<div class="space-y-6">
    <!-- Student Information Banner -->
    <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider bg-blue-50 px-2 py-0.5 rounded border border-blue-100">Profil Siswa</span>
            <h2 class="text-xl font-bold text-slate-900 mt-1">{{ $student->name }}</h2>
            <div class="text-xs text-slate-500 mt-0.5 space-x-3">
                <span>NIS: <strong class="text-slate-700 font-mono">{{ $student->nis }}</strong></span>
                <span>•</span>
                <span>Kelas: <strong class="text-slate-700">{{ $student->schoolClass->name ?? '-' }}</strong></span>
                <span>•</span>
                <span>Jenis Kelamin: <strong class="text-slate-700">{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</strong></span>
            </div>
        </div>

        <div class="bg-slate-50 px-3.5 py-2 rounded-md border border-slate-200 text-right">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Status Akun</span>
            <span class="text-xs font-bold text-emerald-700 flex items-center justify-end gap-1 mt-0.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Aktif & Terverifikasi
            </span>
        </div>
    </div>

    <!-- Available Exams List -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-base font-bold text-slate-900">Daftar Ujian Tersedia</h3>
            <span class="text-xs text-slate-500 font-medium">Total: {{ $availableExams->count() }} Ujian</span>
        </div>

        @if($availableExams->isEmpty())
            <div class="bg-white border border-slate-200 rounded-lg p-10 text-center">
                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-slate-400 font-bold mb-3">
                    ?
                </div>
                <h4 class="text-sm font-bold text-slate-800">Belum ada ujian</h4>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Saat ini belum ada ujian yang tersedia untuk kelas Anda ({{ $student->schoolClass->name ?? '-' }}). Silakan hubungi pengawas atau periksa jadwal ujian Anda.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($availableExams as $exam)
                    @php
                        $attempt = $attempts->get($exam->id);
                    @endphp
                    <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm flex flex-col justify-between hover:border-slate-300 transition">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="text-xs font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $exam->subject->code ?? 'MAPEL' }}
                                </span>
                                <span class="text-xs font-medium text-slate-500">
                                    Durasi: {{ $exam->duration_minutes }} Menit
                                </span>
                            </div>

                            <h4 class="font-bold text-base text-slate-900 leading-snug">{{ $exam->title }}</h4>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $exam->description ?? 'Tidak ada deskripsi ujian.' }}</p>

                            <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-600 space-y-1">
                                <div><span class="text-slate-400">Mulai:</span> {{ $exam->start_at->format('d M Y, H:i') }} WIB</div>
                                <div><span class="text-slate-400">Selesai:</span> {{ $exam->end_at->format('d M Y, H:i') }} WIB</div>
                            </div>
                        </div>

                        <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between">
                            @if(!$attempt)
                                @if(now()->between($exam->start_at, $exam->end_at))
                                    <span class="text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-1 rounded">Dapat Dikerjakan</span>
                                    <a href="{{ route('siswa.exams.instruction', $exam->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs py-2 px-3.5 rounded transition">
                                        Mulai Ujian
                                    </a>
                                @elseif(now()->lessThan($exam->start_at))
                                    <span class="text-xs font-medium text-amber-700 bg-amber-50 px-2 py-1 rounded">Belum Dimulai</span>
                                    <button disabled class="bg-slate-200 text-slate-500 font-semibold text-xs py-2 px-3.5 rounded cursor-not-allowed">
                                        Belum Buka
                                    </button>
                                @else
                                    <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded">Telah Berakhir</span>
                                    <button disabled class="bg-slate-200 text-slate-500 font-semibold text-xs py-2 px-3.5 rounded cursor-not-allowed">
                                        Berakhir
                                    </button>
                                @endif
                            @else
                                @if($attempt->status === 'submitted')
                                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded">Selesai Dikerjakan</span>
                                    @if($exam->show_result)
                                        <a href="{{ route('siswa.attempts.result', $attempt->id) }}" class="bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs py-2 px-3.5 rounded transition">
                                            Lihat Hasil
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium italic">Hasil Disembunyikan</span>
                                    @endif
                                @elseif($attempt->isLocked())
                                    <span class="text-xs font-bold text-rose-700 bg-rose-50 px-2 py-1 rounded">Sesi Terkunci</span>
                                    <a href="{{ route('siswa.attempts.show', $attempt->id) }}" class="bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs py-2 px-3.5 rounded transition">
                                        Lihat Status
                                    </a>
                                @else
                                    <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2 py-1 rounded">Sedang Berlangsung</span>
                                    <a href="{{ route('siswa.attempts.show', $attempt->id) }}" class="bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs py-2 px-3.5 rounded transition">
                                        Lanjutkan Ujian
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

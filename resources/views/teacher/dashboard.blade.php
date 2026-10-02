@extends('layouts.app')

@section('title', 'Dashboard Pengajar')

@section('content')
<div class="space-y-7">
    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200/80">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard Guru & Pengawas</h1>
            <p class="text-xs text-slate-500 mt-1">Ringkasan pelaksanaan ujian, monitoring integritas siswa, dan evaluasi hasil belajar</p>
        </div>
        <a href="{{ route('guru.exams.create') }}" class="inline-flex items-center justify-center gap-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs py-2.5 px-4 rounded-xl shadow-md shadow-indigo-600/20 hover:shadow-indigo-600/30 transition-all duration-200 w-full sm:w-auto text-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Buat Ujian Baru</span>
        </a>
    </div>

    <!-- Summary Operational Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 block">Total Ujian Saya</span>
                <span class="text-3xl font-black text-slate-900 font-mono mt-1 block">{{ $totalExams }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 block">Ujian Aktif</span>
                <span class="text-3xl font-black text-emerald-600 font-mono mt-1 block">{{ $activeExams }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 block">Peserta Sedang Ujian</span>
                <span class="text-3xl font-black text-indigo-600 font-mono mt-1 block">{{ $activeAttemptsCount }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 block">Sesi Terkunci</span>
                <span class="text-3xl font-black text-rose-600 font-mono mt-1 block">{{ $lockedCount }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Quick Shortcuts Grid -->
    <div>
        <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-3.5">Menu Tindakan Cepat</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
            <a href="{{ route('guru.exams.create') }}" class="group flex items-center gap-3.5 bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-indigo-400 hover:shadow-md transition-all duration-200">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-xs text-slate-900 group-hover:text-indigo-600 truncate transition-colors">Buat Ujian Baru</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5 truncate">Atur jadwal & rombel ujian</p>
                </div>
            </a>

            <a href="{{ route('guru.exams.index') }}" class="group flex items-center gap-3.5 bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-indigo-400 hover:shadow-md transition-all duration-200">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-xs text-slate-900 group-hover:text-blue-600 truncate transition-colors">Kelola Soal</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5 truncate">Bank butir soal & opsi</p>
                </div>
            </a>

            <a href="{{ route('guru.violations.index') }}" class="group flex items-center gap-3.5 bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-amber-400 hover:shadow-md transition-all duration-200">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 group-hover:bg-amber-500 group-hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-xs text-slate-900 group-hover:text-amber-600 truncate transition-colors">Monitoring Anti-Cheat</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5 truncate">Pantau integritas & lock</p>
                </div>
            </a>

            <a href="{{ route('guru.results.index') }}" class="group flex items-center gap-3.5 bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-emerald-400 hover:shadow-md transition-all duration-200">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-xs text-slate-900 group-hover:text-emerald-600 truncate transition-colors">Rekap Nilai Siswa</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5 truncate">Lihat skor & ekspor nilai</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Exams Table -->
    <div class="bg-white border border-slate-200/90 rounded-3xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-sm sm:text-base font-extrabold text-slate-900">Ujian Terbaru & Monitoring</h2>
                <p class="text-xs text-slate-500">Status publikasi dan partisipasi siswa secara real-time</p>
            </div>
            <a href="{{ route('guru.exams.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                Lihat Semua Ujian →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 min-w-[720px]">
                <thead class="bg-slate-50 text-slate-400 uppercase font-extrabold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Nama Ujian</th>
                        <th class="px-5 py-3.5">Mata Pelajaran</th>
                        <th class="px-5 py-3.5">Kelas Peserta</th>
                        <th class="px-5 py-3.5">Jumlah Soal</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentExams as $exam)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4 font-bold text-slate-900">{{ $exam->title }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $exam->subject->name ?? '-' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($exam->classes as $c)
                                        <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md font-semibold text-[11px]">{{ $c->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-slate-800">{{ $exam->questions_count }} Butir</td>
                            <td class="px-5 py-4">
                                @if($exam->status === 'published')
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-800 text-[10px] font-bold px-2.5 py-1 rounded-full border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        PUBLISHED
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-600 text-[10px] font-bold px-2.5 py-1 rounded-full border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        DRAFT
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('guru.exams.questions.index', $exam->id) }}" class="inline-flex items-center gap-1 text-indigo-700 bg-indigo-50/80 hover:bg-indigo-100 px-2.5 py-1 rounded-lg font-semibold text-[11px] transition">
                                        Soal
                                    </a>
                                    <a href="{{ route('guru.exams.participants', $exam->id) }}" class="inline-flex items-center gap-1 text-slate-700 bg-slate-100 hover:bg-slate-200 px-2.5 py-1 rounded-lg font-semibold text-[11px] transition">
                                        Peserta
                                    </a>
                                    <a href="{{ route('guru.results.exam', $exam->id) }}" class="inline-flex items-center gap-1 text-emerald-700 bg-emerald-50/80 hover:bg-emerald-100 px-2.5 py-1 rounded-lg font-semibold text-[11px] transition">
                                        Nilai
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400 font-medium">Belum ada ujian yang dibuat oleh Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
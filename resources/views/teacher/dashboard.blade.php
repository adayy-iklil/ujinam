@extends('layouts.app')

@section('title', 'Dashboard Pengajar')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 pb-3">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Dashboard Guru & Pengawas</h2>
            <p class="text-xs text-slate-500 mt-0.5">Ringkasan operasional ujian dan monitoring peserta aktif</p>
        </div>
        <a href="{{ route('guru.exams.create') }}" class="bg-blue-700 hover:bg-blue-800 text-white font-semibold text-xs py-2 px-3.5 rounded-md transition shadow-sm w-full sm:w-auto text-center">
            + Buat Ujian Baru
        </a>
    </div>

    <!-- Summary Operational Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg border border-slate-200 border-l-4 border-l-slate-600 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block">Total Ujian Saya</span>
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
            <div class="text-2xl font-bold text-slate-900 font-mono mt-1">{{ $totalExams }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-slate-200 border-l-4 border-l-emerald-600 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block">Ujian Aktif</span>
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div class="text-2xl font-bold text-emerald-700 font-mono mt-1">{{ $activeExams }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-slate-200 border-l-4 border-l-blue-600 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block">Peserta Sedang Ujian</span>
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                </svg>
            </div>
            <div class="text-2xl font-bold text-blue-700 font-mono mt-1">{{ $activeAttemptsCount }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-slate-200 border-l-4 border-l-rose-600 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block">Siswa Terkunci</span>
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <div class="text-2xl font-bold text-rose-700 font-mono mt-1">{{ $lockedCount }}</div>
        </div>
    </div>

    <!-- Recent Exams Table -->
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Ujian Terbaru & Monitoring</h3>
            <a href="{{ route('guru.exams.index') }}" class="text-xs font-medium text-blue-700 hover:underline whitespace-nowrap">Lihat Semua Ujian →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 min-w-[720px]">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Nama Ujian</th>
                        <th class="px-4 py-3">Mata Pelajaran</th>
                        <th class="px-4 py-3">Kelas Target</th>
                        <th class="px-4 py-3">Soal</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($recentExams as $exam)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $exam->title }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $exam->subject->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @foreach($exam->classes as $c)
                                    <span class="bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded border border-slate-200 font-medium">{{ $c->name }}</span>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 font-mono font-semibold">{{ $exam->questions_count }} Butir</td>
                            <td class="px-4 py-3">
                                @if($exam->status === 'published')
                                    <span class="bg-emerald-50 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded border border-emerald-200">PUBLISHED</span>
                                @else
                                    <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200">DRAFT</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2 flex-wrap">
                                    <a href="{{ route('guru.exams.questions.index', $exam->id) }}" class="inline-flex items-center gap-1 text-blue-700 hover:bg-blue-50 px-2 py-1 rounded border border-blue-100 hover:border-blue-300 font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Soal
                                    </a>
                                    <a href="{{ route('guru.exams.participants', $exam->id) }}" class="inline-flex items-center gap-1 text-slate-700 hover:bg-slate-100 px-2 py-1 rounded border border-slate-200 hover:border-slate-400 font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        Peserta
                                    </a>
                                    <a href="{{ route('guru.results.exam', $exam->id) }}" class="inline-flex items-center gap-1 text-emerald-700 hover:bg-emerald-50 px-2 py-1 rounded border border-emerald-100 hover:border-emerald-300 font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Nilai
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-slate-500">Belum ada ujian yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
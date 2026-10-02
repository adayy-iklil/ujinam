@extends('layouts.app')

@section('title', 'Dashboard Superadmin')

@section('content')
<div class="space-y-7">
    <!-- Header Title -->
    <div class="border-b border-slate-200/80 pb-2">
        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard Utama Superadmin</h1>
        <p class="text-xs text-slate-500 mt-1">Pusat kendali konfigurasi akademik, master data, dan monitoring integritas server CBT</p>
    </div>

    <!-- Active Academic Year Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-7 shadow-xl border border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
        <div class="relative z-10 flex items-center gap-4">
            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/10 text-indigo-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-wider text-indigo-300 bg-indigo-500/20 px-2.5 py-0.5 rounded-full border border-indigo-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Tahun Ajaran Aktif
                </span>
                <h2 class="text-xl sm:text-2xl font-extrabold mt-1 text-white tracking-tight">{{ $activeAcademicYear->name ?? 'Belum Diatur' }}</h2>
            </div>
        </div>

        <a href="{{ route('admin.promotion.index') }}" class="relative z-10 inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white font-bold text-xs py-2.5 px-4 rounded-xl border border-white/20 transition-all duration-200 whitespace-nowrap shadow-sm">
            <span>Kelola Kenaikan Kelas</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Total Siswa</span>
                <span class="text-2xl font-black text-slate-900 font-mono mt-1 block">{{ $totalStudents }}</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Total Guru</span>
                <span class="text-2xl font-black text-slate-900 font-mono mt-1 block">{{ $totalTeachers }}</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Rombel Kelas</span>
                <span class="text-2xl font-black text-slate-900 font-mono mt-1 block">{{ $totalClasses }}</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Ujian Aktif</span>
                <span class="text-2xl font-black text-emerald-600 font-mono mt-1 block">{{ $activeExams }}</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Siswa Terkunci</span>
                <span class="text-2xl font-black text-rose-600 font-mono mt-1 block">{{ $lockedStudents }}</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Quick Shortcuts Grid -->
    <div>
        <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-3.5">Tindakan Superadmin</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
            <a href="{{ route('admin.students.import') }}" class="group flex items-center gap-3.5 bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-indigo-400 hover:shadow-md transition-all duration-200">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-xs text-slate-900 group-hover:text-indigo-600 truncate transition-colors">Import Data Siswa (CSV)</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5 truncate">Upload file master peserta didik</p>
                </div>
            </a>

            <a href="{{ route('admin.teachers.create') }}" class="group flex items-center gap-3.5 bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-violet-400 hover:shadow-md transition-all duration-200">
                <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center shrink-0 group-hover:bg-violet-600 group-hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-xs text-slate-900 group-hover:text-violet-600 truncate transition-colors">Tambah Akun Pengajar</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5 truncate">Registrasi akun guru & penugasan</p>
                </div>
            </a>

            <a href="{{ route('admin.logs.index') }}" class="group flex items-center gap-3.5 bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-emerald-400 hover:shadow-md transition-all duration-200">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-xs text-slate-900 group-hover:text-emerald-600 truncate transition-colors">Audit Trail System</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5 truncate">Rekap aktivitas & log sistem</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
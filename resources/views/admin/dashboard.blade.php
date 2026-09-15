@extends('layouts.app')

@section('title', 'Dashboard Superadmin')

@section('content')
<div class="space-y-6">
    <div class="border-b border-slate-200 pb-3">
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Dashboard Utama Superadmin</h2>
        <p class="text-xs text-slate-500 mt-0.5">Pusat kendali dan status operasional sistem CBT sekolah</p>
    </div>

    <!-- Active Academic Year Banner -->
    <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white p-5 rounded-lg shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex w-12 h-12 bg-blue-950/60 rounded-lg items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 bg-blue-950 px-2 py-0.5 rounded">Tahun Ajaran Aktif</span>
                <h3 class="text-lg sm:text-xl font-bold mt-1 tracking-tight">{{ $activeAcademicYear->name ?? 'Belum Diatur' }}</h3>
            </div>
        </div>
        <a href="{{ route('admin.promotion.index') }}" class="bg-blue-700 hover:bg-blue-600 text-white font-semibold text-xs py-2 px-3.5 rounded border border-blue-500 transition whitespace-nowrap">
            Kelola Kenaikan Kelas →
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-lg border border-slate-200 border-l-4 border-l-blue-600 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block">Total Siswa Aktif</span>
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div class="text-2xl font-bold text-slate-900 font-mono mt-1">{{ $totalStudents }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-slate-200 border-l-4 border-l-violet-600 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block">Total Guru</span>
                <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div class="text-2xl font-bold text-slate-900 font-mono mt-1">{{ $totalTeachers }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-slate-200 border-l-4 border-l-cyan-600 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block">Total Rombel Kelas</span>
                <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div class="text-2xl font-bold text-slate-900 font-mono mt-1">{{ $totalClasses }}</div>
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
        <div class="bg-white p-4 rounded-lg border border-slate-200 border-l-4 border-l-rose-600 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block">Siswa Terkunci</span>
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <div class="text-2xl font-bold text-rose-700 font-mono mt-1">{{ $lockedStudents }}</div>
        </div>
    </div>

    <!-- Quick Shortcuts -->
    <div>
        <h3 class="text-sm font-bold text-slate-900 mb-3">Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <a href="{{ route('admin.students.import') }}" class="group flex items-center gap-3 bg-white p-4 rounded-lg border border-slate-200 hover:border-blue-300 hover:shadow-sm transition">
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-semibold text-sm text-slate-900 group-hover:text-blue-700 truncate">Import Data Siswa (CSV)</h4>
                    <p class="text-xs text-slate-500 mt-0.5 truncate">Upload file CSV master siswa baru</p>
                </div>
                <svg class="w-4 h-4 text-slate-300 shrink-0 group-hover:text-blue-500 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>

            <a href="{{ route('admin.teachers.create') }}" class="group flex items-center gap-3 bg-white p-4 rounded-lg border border-slate-200 hover:border-violet-300 hover:shadow-sm transition">
                <div class="w-9 h-9 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center shrink-0 group-hover:bg-violet-600 group-hover:text-white transition">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-semibold text-sm text-slate-900 group-hover:text-violet-700 truncate">Tambah Akun Pengajar</h4>
                    <p class="text-xs text-slate-500 mt-0.5 truncate">Buat kredensial login guru baru</p>
                </div>
                <svg class="w-4 h-4 text-slate-300 shrink-0 group-hover:text-violet-500 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>

            <a href="{{ route('admin.logs.index') }}" class="group flex items-center gap-3 bg-white p-4 rounded-lg border border-slate-200 hover:border-emerald-300 hover:shadow-sm transition">
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-semibold text-sm text-slate-900 group-hover:text-emerald-700 truncate">Audit Trail System</h4>
                    <p class="text-xs text-slate-500 mt-0.5 truncate">Pantau log aktivitas & pelanggaran</p>
                </div>
                <svg class="w-4 h-4 text-slate-300 shrink-0 group-hover:text-emerald-500 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection
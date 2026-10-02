<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ujinam') }} - @yield('title', 'Sistem Ujian Sekolah')</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}">
    
    <!-- Google Fonts Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-50/80 text-slate-800 antialiased min-h-screen flex flex-col selection:bg-indigo-500 selection:text-white">
    <!-- Top Modern Navigation Bar -->
    <header class="bg-slate-950/95 backdrop-blur-md text-white border-b border-slate-800 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Branding -->
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo-smkn6jkt.png') }}" alt="Logo Sekolah" class="h-9 w-auto object-contain flex-shrink-0 drop-shadow-sm" style="height: 36px; width: auto; max-height: 36px;">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-base tracking-tight text-white">UJINAM CBT</span>
                            <span class="text-[10px] uppercase font-bold tracking-wider bg-blue-900/60 text-blue-300 border border-blue-700/60 px-2 py-0.2 rounded hidden sm:inline">Management</span>
                        </div>
                        <span class="text-[11px] text-slate-400 block -mt-0.5 font-medium">SMK Negeri 6 Jakarta</span>
                    </div>
                </div>

                <!-- User Profile & Session -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    @auth('web')
                        <div class="flex items-center space-x-2.5 bg-slate-900 border border-slate-800 py-1.5 px-3 rounded-lg shadow-inner">
                            <div class="w-7 h-7 rounded bg-blue-600 text-white flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr(auth('web')->user()->name, 0, 2)) }}
                            </div>
                            <div class="text-left text-xs leading-tight hidden sm:block">
                                <span class="font-semibold text-slate-200 block">{{ auth('web')->user()->name }}</span>
                                <span class="text-[10px] text-slate-400 font-mono uppercase font-semibold">{{ auth('web')->user()->role }}</span>
                            </div>
                        </div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-semibold bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white px-3 py-1.5 rounded-lg border border-slate-800 transition-colors shadow-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span class="hidden sm:inline">Keluar</span>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <div class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-7 flex flex-col md:flex-row gap-7" x-data="{ sidebarOpen: true }">
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 flex-shrink-0">
            <button type="button"
                class="w-full flex md:hidden items-center justify-between bg-white border border-slate-200/90 rounded-2xl px-4 py-3 shadow-sm hover:border-slate-300 transition"
                @click="sidebarOpen = !sidebarOpen">
                <span class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    Menu Navigasi
                </span>
                <svg class="w-4 h-4 text-slate-500 transition-transform duration-200" :class="sidebarOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <nav class="bg-white border border-slate-200/90 rounded-3xl p-3 space-y-1 shadow-sm mt-2 md:mt-0 hidden md:block" :class="sidebarOpen ? '!block' : 'hidden'">
                @auth('web')
                    @if(auth('web')->user()->isSuperadmin())
                        <!-- Superadmin Links -->
                        <div class="px-3.5 pt-2 pb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Panel Administrator</div>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            Dashboard Admin
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-150 {{ request()->routeIs('admin.students.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            Data Siswa
                        </a>
                        <a href="{{ route('admin.teachers.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-150 {{ request()->routeIs('admin.teachers.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            Data Guru
                        </a>
                        <a href="{{ route('admin.classes.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-150 {{ request()->routeIs('admin.classes.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            Data Kelas
                        </a>
                        <a href="{{ route('admin.majors.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-150 {{ request()->routeIs('admin.majors.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            Data Jurusan
                        </a>
                        <a href="{{ route('admin.academic-years.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-150 {{ request()->routeIs('admin.academic-years.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            Tahun Ajaran
                        </a>
                        <a href="{{ route('admin.subjects.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-150 {{ request()->routeIs('admin.subjects.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            Mata Pelajaran
                        </a>
                        <a href="{{ route('admin.promotion.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-150 {{ request()->routeIs('admin.promotion.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            Kenaikan Kelas
                        </a>
                        <a href="{{ route('admin.logs.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-150 {{ request()->routeIs('admin.logs.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            Log Aktivitas
                        </a>
                    @endif

                    @if(auth('web')->user()->isGuru() || auth('web')->user()->isSuperadmin())
                        <!-- Teacher Links -->
                        <div class="px-3.5 pt-3.5 pb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Panel Pengajar</div>
                        <a href="{{ route('guru.dashboard') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-150 {{ request()->routeIs('guru.dashboard') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            Dashboard Guru
                        </a>
                        <a href="{{ route('guru.exams.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-150 {{ request()->routeIs('guru.exams.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            Kelola Ujian & Soal
                        </a>
                        <a href="{{ route('guru.violations.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-150 {{ request()->routeIs('guru.violations.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            Monitoring Pelanggaran
                        </a>
                        <a href="{{ route('guru.results.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-150 {{ request()->routeIs('guru.results.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            Hasil & Nilai Siswa
                        </a>
                    @endif
                @endauth
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 min-w-0">
            @if(session('success'))
                <div class="mb-5 bg-emerald-50/90 border border-emerald-200 text-emerald-800 text-xs sm:text-sm px-4 py-3.5 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs flex-shrink-0">✓</span>
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 bg-rose-50/90 border border-rose-200 text-rose-800 text-xs sm:text-sm px-4 py-3.5 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xs flex-shrink-0">!</span>
                        <span class="font-semibold">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Modern Footer -->
    <footer class="mt-auto border-t border-slate-200 bg-white py-5 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                &copy; {{ date('Y') }} <strong>SMK Negeri 6 Jakarta</strong> — Sistem CBT Ujinam.
            </div>
            <div class="text-slate-400 text-[11px]">
                Panel Manajerial Ujian Sekolah Terintegrasi
            </div>
        </div>
    </footer>
</body>
</html>

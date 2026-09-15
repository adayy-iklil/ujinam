<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ujinam') }} - @yield('title', 'Sistem Ujian Sekolah')</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}">
    
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col">
    <!-- Top Navigation Bar -->
    <header class="bg-slate-900 text-white border-b border-slate-800 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo-smkn6jkt.png') }}" alt="Logo Sekolah" class="h-9 w-auto">
                    <div>
                        <span class="font-bold text-base tracking-tight">UJINAM</span>
                        <span class="text-xs text-slate-400 block -mt-1 font-medium">Sistem Ujian Terintegrasi</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    @auth('web')
                        <div class="text-right hidden sm:block">
                            <span class="text-sm font-semibold text-slate-100 block">{{ auth('web')->user()->name }}</span>
                            <span class="text-xs text-slate-400 font-mono uppercase bg-slate-800 px-1.5 py-0.5 rounded">{{ auth('web')->user()->role }}</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 px-3 py-1.5 rounded border border-slate-700 transition">
                                Keluar
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <div class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col md:flex-row gap-6" x-data="{ sidebarOpen: true }">
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 flex-shrink-0">
            <button type="button"
                class="w-full flex md:hidden items-center justify-between bg-white border border-slate-200 rounded-lg px-4 py-2.5 shadow-sm hover:border-slate-300 transition"
                @click="sidebarOpen = !sidebarOpen">
                <span class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    Menu Navigasi
                </span>
                <svg class="w-4 h-4 text-slate-500 transition-transform" :class="sidebarOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <nav class="bg-white border border-slate-200 rounded-lg p-3 space-y-1 shadow-sm mt-2 md:mt-0 hidden md:block" :class="sidebarOpen ? '!block' : 'hidden'">
                @auth('web')
                    @if(auth('web')->user()->isSuperadmin())
                        <!-- Superadmin Links -->
                        <div class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-slate-400">Admin Utama</div>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                            Dashboard Admin
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.students.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                            Data Siswa
                        </a>
                        <a href="{{ route('admin.teachers.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.teachers.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                            Data Guru
                        </a>
                        <a href="{{ route('admin.classes.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.classes.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                            Data Kelas
                        </a>
                        <a href="{{ route('admin.majors.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.majors.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                            Data Jurusan
                        </a>
                        <a href="{{ route('admin.academic-years.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.academic-years.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                            Tahun Ajaran
                        </a>
                        <a href="{{ route('admin.subjects.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.subjects.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                            Mata Pelajaran
                        </a>
                        <a href="{{ route('admin.promotion.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.promotion.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                            Kenaikan Kelas
                        </a>
                        <a href="{{ route('admin.logs.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.logs.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                            Log Aktivitas
                        </a>
                    @endif

                    @if(auth('web')->user()->isGuru() || auth('web')->user()->isSuperadmin())
                        <!-- Teacher Links -->
                        <div class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-slate-400 mt-4">Panel Guru</div>
                        <a href="{{ route('guru.dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('guru.dashboard') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                            Dashboard Guru
                        </a>
                        <a href="{{ route('guru.exams.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('guru.exams.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                            Kelola Ujian & Soal
                        </a>
                        <a href="{{ route('guru.violations.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('guru.violations.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                            Monitoring Pelanggaran
                        </a>
                        <a href="{{ route('guru.results.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('guru.results.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                            Hasil & Nilai Ujian
                        </a>
                    @endif
                @endauth
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1">
            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-md flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 text-sm px-4 py-3 rounded-md flex justify-between items-center">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="mt-auto border-t border-slate-200 bg-white py-4 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            &copy; {{ date('Y') }} Sistem CBT Ujinam — Institusi Pendidikan. Hak Cipta Dilindungi.
        </div>
    </footer>
</body>
</html>

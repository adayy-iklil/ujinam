<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ujinam') }} - Portal Ujian Siswa</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}">
    
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
    <!-- Modern Student Header -->
    <header class="bg-slate-900/95 backdrop-blur-md text-white border-b border-slate-800 sticky top-0 z-40 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Branding -->
                <a href="{{ route('siswa.dashboard') }}" class="flex items-center space-x-3 group transition">
                    <img src="{{ asset('images/logo-smkn6jkt.png') }}" alt="Logo SMK Negeri 6 Jakarta" class="h-9 w-auto object-contain flex-shrink-0" style="height: 36px; width: auto; max-height: 36px;">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm sm:text-base tracking-tight text-white group-hover:text-blue-300 transition-colors">PORTAL SISWA</span>
                            <span class="text-[10px] uppercase font-bold tracking-wider bg-blue-900/60 text-blue-300 border border-blue-700/60 px-2 py-0.2 rounded hidden sm:inline">CBT Online</span>
                        </div>
                        <span class="text-[11px] text-slate-400 block -mt-0.5">SMK Negeri 6 Jakarta</span>
                    </div>
                </a>

                <!-- User Profile & Actions -->
                @auth('student')
                    <div class="flex items-center space-x-3 sm:space-x-4">
                        <div class="hidden sm:flex items-center space-x-2.5 bg-slate-800 border border-slate-700 py-1.5 px-3 rounded-lg shadow-inner">
                            <div class="w-7 h-7 rounded bg-blue-600 text-white flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr(auth('student')->user()->name, 0, 2)) }}
                            </div>
                            <div class="text-left text-xs leading-tight">
                                <div class="font-semibold text-white truncate max-w-[150px]">{{ auth('student')->user()->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">
                                    NIS: {{ auth('student')->user()->nis }} • {{ auth('student')->user()->schoolClass->name ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('siswa.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-3 py-1.5 rounded-lg border border-slate-700 transition-colors shadow-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-5 bg-emerald-50/90 border border-emerald-200 text-emerald-800 text-sm px-4 py-3.5 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in">
                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs flex-shrink-0">✓</span>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 bg-rose-50/90 border border-rose-200 text-rose-800 text-sm px-4 py-3.5 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in">
                <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xs flex-shrink-0">!</span>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Modern Footer -->
    <footer class="mt-auto border-t border-slate-200 bg-white py-5 text-center text-xs text-slate-500">
        <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                &copy; {{ date('Y') }} <strong>SMK Negeri 6 Jakarta</strong> — Sistem CBT Ujinam.
            </div>
            <div class="text-slate-400 text-[11px]">
                Platform Ujian Berbasis Komputer & Evaluasi Akademik Terintegrasi
            </div>
        </div>
    </footer>
</body>
</html>

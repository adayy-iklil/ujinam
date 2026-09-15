<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ujinam') }} - Dashboard Siswa</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 antialiased min-h-screen flex flex-col">
    <!-- Student Header -->
    <header class="bg-blue-900 text-white border-b border-blue-950 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo-smkn6jkt.png') }}" alt="Logo Sekolah" class="h-11 w-auto">
                    <div>
                        <h1 class="font-bold text-base tracking-tight leading-none">PORTAL UJIAN SISWA</h1>
                        <span class="text-xs text-blue-200">Sistem Pelaksanaan Ujian Berbasis Komputer</span>
                    </div>
                </div>

                @auth('student')
                    <div class="flex items-center space-x-4">
                        <div class="text-right hidden sm:block">
                            <div class="text-sm font-bold text-white">{{ auth('student')->user()->name }}</div>
                            <div class="text-xs text-blue-200">
                                NIS: {{ auth('student')->user()->nis }} | Kelas: {{ auth('student')->user()->schoolClass->name ?? '-' }}
                            </div>
                        </div>
                        <form action="{{ route('siswa.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs font-medium bg-blue-800 hover:bg-blue-700 text-white px-3.5 py-1.5 rounded border border-blue-700 transition">
                                Keluar
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
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 text-sm px-4 py-3 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="mt-auto border-t border-slate-200 bg-white py-4 text-center text-xs text-slate-500">
        <div class="max-w-6xl mx-auto px-4">
            &copy; {{ date('Y') }} Sistem CBT Ujinam — Institusi Pendidikan.
        </div>
    </footer>
</body>
</html>

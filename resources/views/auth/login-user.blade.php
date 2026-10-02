<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengajar & Admin — CBT SMKN 6 Jakarta</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style> 
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .bg-grid-pattern {
            background-size: 32px 32px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased min-h-screen flex flex-col justify-between selection:bg-blue-600 selection:text-white relative bg-grid-pattern">

    <!-- Top Institutional Bar -->
    <header class="w-full border-b border-slate-800/80 bg-slate-900/60 backdrop-blur-md px-4 py-3">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <img 
                    src="{{ asset('images/logo-smkn6jkt.png') }}" 
                    alt="Logo SMKN 6 Jakarta" 
                    class="h-8 w-auto object-contain flex-shrink-0"
                    style="height: 32px; width: auto;"
                >
                <div class="leading-none">
                    <span class="text-xs font-bold tracking-wider text-slate-200 uppercase">SMK NEGERI 6 JAKARTA</span>
                    <span class="text-[10px] text-slate-400 block mt-0.5">Sistem Manajemen Ujian & Bank Soal</span>
                </div>
            </div>

            <div class="flex items-center gap-2 text-[11px] text-slate-400 font-medium">
                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                <span class="hidden sm:inline">Panel Pengajar / Admin</span>
            </div>
        </div>
    </header>

    <!-- Main Content Form -->
    <main class="flex-1 flex items-center justify-center px-4 py-10 relative z-10">
        <div class="w-full max-w-[420px]">
            
            <!-- Login Card -->
            <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-7 sm:p-8 shadow-2xl backdrop-blur-xl">
                
                <!-- School Brand Header inside Card -->
                <div class="text-center space-y-3 pb-6 border-b border-slate-800/80">
                    <div class="inline-flex items-center justify-center p-2.5 rounded-2xl bg-slate-800/80 border border-slate-700/60 shadow-inner mx-auto">
                        <img 
                            src="{{ asset('images/logo-smkn6jkt.png') }}" 
                            alt="Logo SMKN 6 Jakarta" 
                            class="h-12 w-auto object-contain"
                            style="height: 48px; width: auto;"
                        >
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white tracking-tight">{{ $portalTitle ?? 'Panel Pengajar & Admin' }}</h1>
                        <p class="text-xs text-slate-400 mt-1">{{ $portalSubtitle ?? 'Kelola bank soal, jadwal ujian, dan penilaian siswa' }}</p>
                    </div>
                </div>

                @if(session('error'))
                    <div class="mt-5 bg-rose-950/40 border border-rose-800/70 text-rose-300 text-xs p-3.5 rounded-xl flex items-center gap-2.5">
                        <svg class="w-4 h-4 flex-shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ $loginAction ?? route('login') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="username" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                            {{ $userLabel ?? 'Username' }}
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            value="{{ old('username') }}" 
                            required 
                            autofocus 
                            placeholder="{{ $userPlaceholder ?? 'Masukkan username akun Anda' }}"
                            class="w-full px-3.5 py-2.5 text-sm text-white placeholder-slate-500 bg-slate-950/70 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('username') border-rose-500 @enderror"
                        >
                        @error('username')
                            <span class="text-xs text-rose-400 mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                            Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            placeholder="Masukkan password akun"
                            class="w-full px-3.5 py-2.5 text-sm text-white placeholder-slate-500 bg-slate-950/70 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('password') border-rose-500 @enderror"
                        >
                        @error('password')
                            <span class="text-xs text-rose-400 mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="w-full bg-slate-800 hover:bg-slate-700 text-white font-semibold text-sm py-3 px-4 rounded-xl border border-slate-600/60 shadow-md transition-colors duration-150 flex items-center justify-center gap-2"
                        >
                            <span>Masuk ke Sistem</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="text-center text-xs text-slate-500 mt-6 font-medium">
                &copy; {{ date('Y') }} SMK Negeri 6 Jakarta • CBT Ujinam v2.0
            </div>
        </div>
    </main>

    <!-- Bottom Institutional Bar -->
    <footer class="w-full border-t border-slate-800/80 bg-slate-900/60 backdrop-blur-md px-4 py-2.5 text-center text-[11px] text-slate-500">
        Pemerintah Provinsi DKI Jakarta • Dinas Pendidikan • SMKN 6 Jakarta
    </footer>

</body>
</html>
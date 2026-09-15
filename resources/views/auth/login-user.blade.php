<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengajar & Admin — CBT Ujinam</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-900 text-slate-100 antialiased min-h-screen flex flex-col justify-center items-center p-4">

    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="bg-slate-800 border border-slate-700 p-6 rounded-t-lg text-center">
            <img src="{{ asset('images/logo-smkn6jkt.png') }}" alt="Logo Sekolah" class="h-20 w-auto mx-auto mb-3">
            <h1 class="text-lg font-bold tracking-tight text-white">PANEL GURU & SUPERADMIN</h1>
            <p class="text-xs text-slate-400 mt-1">Sistem Manajerial Ujian Sekolah — Ujinam</p>
        </div>

        <!-- Form Body -->
        <div class="bg-slate-800/80 border-x border-b border-slate-700 p-6 rounded-b-lg shadow-xl">
            @if(session('error'))
                <div class="mb-4 bg-rose-950/50 border border-rose-800 text-rose-300 text-xs px-3 py-2.5 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="username" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus placeholder="Masukkan username pengajar/admin"
                        class="w-full px-3 py-2 text-sm bg-slate-900 border border-slate-700 text-white rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('username') border-rose-500 @enderror">
                    @error('username')
                        <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Masukkan password"
                        class="w-full px-3 py-2 text-sm bg-slate-900 border border-slate-700 text-white rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-rose-500 @enderror">
                    @error('password')
                        <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm py-2.5 px-4 rounded transition shadow-sm">
                        Masuk Sistem Manajerial
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-700 text-center">
                <a href="{{ route('siswa.login') }}" class="text-xs text-slate-400 hover:text-white font-medium">
                    ← Portal Ujian Siswa (NIS Login)
                </a>
            </div>
        </div>
    </div>

</body>
</html>

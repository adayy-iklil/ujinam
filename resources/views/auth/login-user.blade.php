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
<body class="bg-slate-100 text-slate-900 antialiased min-h-screen flex flex-col justify-center items-center p-4 pb-8 sm:pb-4">

    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="bg-white border border-slate-200 rounded-t-lg shadow-sm overflow-hidden text-center">
            <div class="h-1.5 bg-gradient-to-r from-blue-600 to-blue-800"></div>
            <div class="px-5 sm:px-6 pt-6 sm:pt-7 pb-5">
                <img src="{{ asset('images/logo-smkn6jkt.png') }}" alt="Logo Sekolah" class="h-16 sm:h-20 w-auto mx-auto mb-2 sm:mb-3">
                <h1 class="text-base sm:text-lg font-bold tracking-tight">PANEL GURU & SUPERADMIN</h1>
                <p class="text-xs text-slate-500 mt-1">Sistem Manajerial Ujian Sekolah — Ujinam</p>
            </div>
        </div>

        <!-- Form Body -->
        <div class="bg-white border-x border-b border-slate-200 rounded-b-lg shadow-sm px-5 sm:px-6 py-6">
            @if(session('error'))
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs px-3 py-2.5 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="username" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus placeholder="Masukkan username pengajar/admin"
                        class="w-full px-3.5 sm:px-3 py-3 sm:py-2 text-base sm:text-sm border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 @error('username') border-rose-500 @enderror">
                    @error('username')
                        <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Masukkan password"
                        class="w-full px-3.5 sm:px-3 py-3 sm:py-2 text-base sm:text-sm border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 @error('password') border-rose-500 @enderror">
                    @error('password')
                        <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold text-base sm:text-sm py-3.5 sm:py-2.5 px-4 rounded-md transition shadow-sm">
                        Masuk Sistem Manajerial
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                <a href="{{ route('siswa.login') }}" class="text-xs text-slate-500 hover:text-blue-700 font-medium">
                    ← Portal Ujian Siswa (NIS Login)
                </a>
            </div>
        </div>
    </div>

</body>
</html>
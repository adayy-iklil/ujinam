<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Siswa — CBT Ujinam</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-100 text-slate-900 antialiased min-h-screen flex flex-col justify-center items-center p-4">

    <div class="w-full max-w-md">
        <!-- Institution Header Card -->
        <div class="bg-blue-900 text-white p-6 rounded-t-lg shadow-sm text-center">
            <img src="{{ asset('images/logo-smkn6jkt.png') }}" alt="Logo Sekolah" class="h-20 w-auto mx-auto mb-3">
            <h1 class="text-xl font-bold tracking-tight">PORTAL UJIAN SISWA</h1>
            <p class="text-xs text-blue-200 mt-1">Sistem Ujian Berbasis Komputer — Ujinam</p>
        </div>

        <!-- Login Form Container -->
        <div class="bg-white p-6 rounded-b-lg border-x border-b border-slate-200 shadow-sm">
            @if(session('error'))
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs px-3 py-2.5 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('siswa.login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="nis" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nomor Induk Siswa (NIS)</label>
                    <input type="text" id="nis" name="nis" value="{{ old('nis') }}" required autofocus placeholder="Masukkan NIS Anda"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 font-mono @error('nis') border-rose-500 @enderror">
                    @error('nis')
                        <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Masukkan Password Anda"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 @error('password') border-rose-500 @enderror">
                    @error('password')
                        <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold text-sm py-2.5 px-4 rounded-md transition shadow-sm">
                        Masuk Ruang Ujian
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                <a href="{{ route('login') }}" class="text-xs text-slate-500 hover:text-blue-700 font-medium">
                    Login sebagai Guru / Pengawas →
                </a>
            </div>
        </div>

        <div class="text-center text-xs text-slate-400 mt-6">
            &copy; {{ date('Y') }} Institutional CBT System. All rights reserved.
        </div>
    </div>

</body>
</html>

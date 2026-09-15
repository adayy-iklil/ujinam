@extends('layouts.student')

@section('content')
<div class="max-w-md mx-auto my-8">
    <div class="bg-white border border-rose-200 rounded-lg p-6 shadow-sm text-center">
        <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4 font-bold text-2xl">
            🔒
        </div>

        <h2 class="text-xl font-bold text-slate-900 mb-2">Sesi Ujian Dihentikan</h2>
        <p class="text-xs text-rose-700 font-semibold bg-rose-50 px-3 py-1.5 rounded border border-rose-100 mb-4 inline-block">
            Status: TERKUNCI (Terdeteksi {{ $attempt->violation_count }} Pelanggaran)
        </p>

        <p class="text-xs text-slate-600 leading-relaxed mb-6">
            Sesi ujian Anda dihentikan karena sistem mendeteksi Anda telah mencapai batas maksimal meninggalkan halaman ujian (3 kali tab switch/blur).
            <br><br>
            Silakan hubungi <strong>guru / pengawas ujian</strong> di ruang ujian untuk melakukan verifikasi dan pemulihan kuncian (reset).
        </p>

        <a href="{{ route('siswa.dashboard') }}" class="w-full inline-block bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs py-2.5 px-4 rounded transition">
            Kembali ke Dashboard Siswa
        </a>
    </div>
</div>
@endsection

@extends('layouts.student')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <div class="bg-blue-900 text-white p-6">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-200 bg-blue-950 px-2 py-0.5 rounded">Petunjuk Pelaksanaan Ujian</span>
            <h2 class="text-xl font-bold mt-2 leading-tight">{{ $exam->title }}</h2>
            <p class="text-xs text-blue-100 mt-1 font-medium">Mata Pelajaran: {{ $exam->subject->name ?? '-' }} ({{ $exam->subject->code ?? '-' }})</p>
        </div>

        <div class="p-6 space-y-6">
            <!-- Exam Meta Table -->
            <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-md border border-slate-200 text-xs">
                <div>
                    <span class="text-slate-500 block">Durasi Pengerjaan:</span>
                    <strong class="text-slate-800 text-sm font-semibold">{{ $exam->duration_minutes }} Menit</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">Jumlah Soal:</span>
                    <strong class="text-slate-800 text-sm font-semibold">{{ $exam->questions()->count() }} Butir Soal</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">Waktu Mulai:</span>
                    <strong class="text-slate-800">{{ $exam->start_at->format('d M Y, H:i') }} WIB</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">Waktu Selesai:</span>
                    <strong class="text-slate-800">{{ $exam->end_at->format('d M Y, H:i') }} WIB</strong>
                </div>
            </div>

            <!-- Rules & Instructions -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 mb-2">Tata Tertib & Peraturan Ujian:</h3>
                <div class="bg-amber-50 border border-amber-200 text-amber-900 p-4 rounded-md text-xs space-y-2 leading-relaxed">
                    <p><strong>1. Pengawas Anti-Cheating System:</strong> Dilarang keras berpindah tab, meminimalkan jendela browser, atau meninggalkan halaman ujian.</p>
                    <p><strong>2. Batas Pelanggaran (3x Max):</strong> Sistem akan mendeteksi secara otomatis setiap kali Anda meninggalkan tab ujian. Pada pelanggaran ke-3, sesi ujian Anda akan <strong>LANGSUNG TERKUNCI OTOMATIS</strong>.</p>
                    <p><strong>3. Autosave Jawaban:</strong> Setiap jawaban yang Anda pilih tersimpan secara otomatis di server.</p>
                    <p><strong>4. Timer Berbasis Server:</strong> Timer akan terus berjalan dan tidak akan ter-reset meskipun Anda memperbarui (refresh) halaman browser.</p>
                </div>

                @if($exam->instructions)
                    <div class="mt-4 pt-4 border-t border-slate-200">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Instruksi Khusus Pengawas:</h4>
                        <div class="text-xs text-slate-600 leading-relaxed whitespace-pre-line bg-slate-50 p-3 rounded border border-slate-200">
                            {{ $exam->instructions }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Form -->
            <div class="pt-4 border-t border-slate-200 flex items-center justify-between">
                <a href="{{ route('siswa.dashboard') }}" class="text-xs text-slate-600 hover:text-slate-900 font-medium">
                    ← Batal & Kembali
                </a>

                @if($attempt && $attempt->status === 'submitted')
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-2 rounded">
                        Anda telah menyelesaikan ujian ini.
                    </span>
                @else
                    <form action="{{ route('siswa.exams.start', $exam->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-2.5 px-6 rounded-md transition shadow-sm">
                            {{ $attempt ? 'Lanjutkan Ujian' : 'Saya Siap, Mulai Ujian Sekarang' }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

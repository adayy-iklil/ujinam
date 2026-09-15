@extends('layouts.app')

@section('title', 'Bank Soal Ujian')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-200 pb-3 gap-3">
        <div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('guru.exams.index') }}" class="text-xs text-blue-700 hover:underline">← Ujian</a>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-bold text-slate-700 uppercase">Bank Soal</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight mt-1">{{ $exam->title }}</h2>
            <p class="text-xs text-slate-500">Mata Pelajaran: {{ $exam->subject->name ?? '-' }} | Total Soal: {{ $questions->count() }} Butir</p>
        </div>

        <a href="{{ route('guru.exams.questions.create', $exam->id) }}" class="bg-blue-700 hover:bg-blue-800 text-white font-semibold text-xs py-2 px-3.5 rounded-md transition shadow-sm">
            + Tambah Soal Pilihan Ganda
        </a>
    </div>

    <!-- Questions List -->
    <div class="space-y-4">
        @forelse($questions as $index => $q)
            <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm space-y-3">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="bg-slate-900 text-white font-mono font-bold text-xs px-2 py-0.5 rounded">Soal {{ $index + 1 }}</span>
                        <span class="text-xs text-slate-500 font-medium">Bobot: <strong class="text-slate-800">{{ $q->points }} Poin</strong></span>
                    </div>

                    <div class="flex items-center space-x-3 text-xs">
                        <a href="{{ route('guru.exams.questions.edit', [$exam->id, $q->id]) }}" class="text-blue-700 hover:underline font-semibold">Edit</a>
                        <form action="{{ route('guru.exams.questions.destroy', [$exam->id, $q->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 hover:underline font-semibold">Hapus</button>
                        </form>
                    </div>
                </div>

                <!-- Question Text -->
                <div class="text-xs sm:text-sm text-slate-800 font-medium leading-relaxed bg-slate-50 p-3 rounded border border-slate-100">
                    {!! nl2br(e($q->question_text)) !!}
                </div>

                <!-- Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 pt-2">
                    @foreach($q->options as $opt)
                        <div class="text-xs p-2 rounded border flex items-center space-x-2 {{ $opt->is_correct ? 'bg-emerald-50 border-emerald-300 text-emerald-950 font-semibold' : 'bg-slate-50 border-slate-200 text-slate-700' }}">
                            <span class="font-mono font-bold w-5 h-5 rounded flex items-center justify-center text-[10px] {{ $opt->is_correct ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700' }}">
                                {{ $opt->option_key }}
                            </span>
                            <span class="flex-1">{{ $opt->option_text }}</span>
                            @if($opt->is_correct)
                                <span class="text-[10px] text-emerald-700 font-bold uppercase tracking-wider bg-emerald-100 px-1.5 py-0.5 rounded">Jawaban Benar</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white border border-slate-200 rounded-lg p-10 text-center text-slate-500">
                Belum ada soal pada ujian ini. Klik "+ Tambah Soal Pilihan Ganda" untuk membuat.
            </div>
        @endforelse
    </div>
</div>
@endsection

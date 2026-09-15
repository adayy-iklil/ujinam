@extends('layouts.app')

@section('title', 'Tambah Soal Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Tambah Soal Pilihan Ganda</h2>
            <p class="text-xs text-slate-500 mt-0.5">Ujian: {{ $exam->title }}</p>
        </div>
        <a href="{{ route('guru.exams.questions.index', $exam->id) }}" class="text-xs text-slate-600 hover:text-slate-900 font-medium">← Kembali ke Bank Soal</a>
    </div>

    <form action="{{ route('guru.exams.questions.store', $exam->id) }}" method="POST" class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm space-y-6">
        @csrf

        <!-- Question Text -->
        <div>
            <label for="question_text" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pertanyaan / Soal *</label>
            <textarea id="question_text" name="question_text" rows="4" required placeholder="Tuliskan isi pertanyaan soal..." class="w-full text-xs p-3 border border-slate-300 rounded focus:ring-blue-500">{{ old('question_text') }}</textarea>
            @error('question_text') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Options A-E -->
        <div>
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Pilihan Jawaban (A - E) *</h3>
            
            <div class="space-y-3">
                @foreach(['A', 'B', 'C', 'D', 'E'] as $key)
                    <div class="flex items-center space-x-3">
                        <span class="w-7 h-7 bg-slate-100 text-slate-700 font-mono font-bold text-xs rounded border border-slate-300 flex items-center justify-center flex-shrink-0">
                            {{ $key }}
                        </span>
                        <input type="text" name="options[{{ $key }}]" value="{{ old('options.'.$key) }}" required placeholder="Teks pilihan jawaban {{ $key }}"
                            class="flex-1 text-xs p-2.5 border border-slate-300 rounded focus:ring-blue-500">
                    </div>
                @endforeach
            </div>
            @error('options') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Correct Option & Points -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-slate-200">
            <div>
                <label for="correct_option" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jawaban Benar *</label>
                <select id="correct_option" name="correct_option" required class="w-full text-xs p-2.5 border border-slate-300 rounded font-semibold text-emerald-800 bg-emerald-50">
                    <option value="">-- Pilih Jawaban Kunci --</option>
                    @foreach(['A', 'B', 'C', 'D', 'E'] as $key)
                        <option value="{{ $key }}" {{ old('correct_option') === $key ? 'selected' : '' }}>
                            Opsi {{ $key }} (Jawaban Benar)
                        </option>
                    @endforeach
                </select>
                @error('correct_option') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="points" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Bobot Poin *</label>
                <input type="number" step="0.5" id="points" name="points" value="{{ old('points', 1.0) }}" min="0.1" required
                    class="w-full text-xs p-2.5 border border-slate-300 rounded font-mono font-semibold">
                @error('points') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="pt-4 border-t border-slate-200 flex justify-end space-x-3">
            <a href="{{ route('guru.exams.questions.index', $exam->id) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded">Batal</a>
            <button type="submit" class="px-5 py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded shadow-sm">
                Simpan Soal
            </button>
        </div>
    </form>
</div>
@endsection

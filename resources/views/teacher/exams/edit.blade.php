@extends('layouts.app')

@section('title', 'Edit Ujian')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Edit Ujian — {{ $exam->title }}</h2>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi dan pengaturan ujian</p>
        </div>
        <a href="{{ route('guru.exams.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-medium">← Kembali</a>
    </div>

    <form action="{{ route('guru.exams.update', $exam->id) }}" method="POST" class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="subject_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mata Pelajaran *</label>
                <select id="subject_id" name="subject_id" required class="w-full text-xs p-2.5 border border-slate-300 rounded focus:ring-blue-500">
                    @foreach($subjects as $subj)
                        <option value="{{ $subj->id }}" {{ old('subject_id', $exam->subject_id) == $subj->id ? 'selected' : '' }}>
                            {{ $subj->code }} - {{ $subj->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="duration_minutes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Durasi Pengerjaan (Menit) *</label>
                <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $exam->duration_minutes) }}" min="5" max="300" required
                    class="w-full text-xs p-2.5 border border-slate-300 rounded focus:ring-blue-500 font-mono">
            </div>
        </div>

        <div>
            <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Ujian *</label>
            <input type="text" id="title" name="title" value="{{ old('title', $exam->title) }}" required
                class="w-full text-xs p-2.5 border border-slate-300 rounded focus:ring-blue-500">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status Ujian *</label>
                <select id="status" name="status" required class="w-full text-xs p-2.5 border border-slate-300 rounded focus:ring-blue-500">
                    <option value="draft" {{ old('status', $exam->status) === 'draft' ? 'selected' : '' }}>DRAFT (Belum Dipublikasikan)</option>
                    <option value="published" {{ old('status', $exam->status) === 'published' ? 'selected' : '' }}>PUBLISHED (Siap Dikerjakan)</option>
                    <option value="ongoing" {{ old('status', $exam->status) === 'ongoing' ? 'selected' : '' }}>ONGOING (Sedang Berlangsung)</option>
                    <option value="finished" {{ old('status', $exam->status) === 'finished' ? 'selected' : '' }}>FINISHED (Telah Selesai)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Target Kelas *</label>
                <div class="grid grid-cols-2 gap-2 bg-slate-50 p-2 rounded border border-slate-200 text-xs">
                    @foreach($classes as $c)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="class_ids[]" value="{{ $c->id }}" {{ in_array($c->id, old('class_ids', $selectedClassIds)) ? 'checked' : '' }} class="rounded text-blue-600">
                            <span>{{ $c->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="start_at" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Waktu Mulai *</label>
                <input type="datetime-local" id="start_at" name="start_at" value="{{ old('start_at', $exam->start_at->format('Y-m-d\TH:i')) }}" required
                    class="w-full text-xs p-2.5 border border-slate-300 rounded">
            </div>

            <div>
                <label for="end_at" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Waktu Selesai *</label>
                <input type="datetime-local" id="end_at" name="end_at" value="{{ old('end_at', $exam->end_at->format('Y-m-d\TH:i')) }}" required
                    class="w-full text-xs p-2.5 border border-slate-300 rounded">
            </div>
        </div>

        <div class="pt-3 border-t border-slate-200 space-y-2">
            <label class="flex items-center space-x-2 text-xs text-slate-800 cursor-pointer">
                <input type="checkbox" name="show_result" value="1" {{ old('show_result', $exam->show_result) ? 'checked' : '' }} class="rounded text-blue-600">
                <span class="font-medium">Tampilkan nilai/hasil akhir kepada siswa</span>
            </label>

            <label class="flex items-center space-x-2 text-xs text-slate-800 cursor-pointer">
                <input type="checkbox" name="randomize_questions" value="1" {{ old('randomize_questions', $exam->randomize_questions) ? 'checked' : '' }} class="rounded text-blue-600">
                <span class="font-medium">Acak urutan soal per siswa</span>
            </label>

            <label class="flex items-center space-x-2 text-xs text-slate-800 cursor-pointer">
                <input type="checkbox" name="randomize_options" value="1" {{ old('randomize_options', $exam->randomize_options) ? 'checked' : '' }} class="rounded text-blue-600">
                <span class="font-medium">Acak pilihan jawaban per siswa</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-200 flex justify-end space-x-3">
            <a href="{{ route('guru.exams.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded">Batal</a>
            <button type="submit" class="px-5 py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded shadow-sm">
                Perbarui Ujian
            </button>
        </div>
    </form>
</div>
@endsection

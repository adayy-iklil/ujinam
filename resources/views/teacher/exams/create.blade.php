@extends('layouts.app')

@section('title', 'Buat Ujian Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Form Tambah Ujian Baru</h2>
            <p class="text-xs text-slate-500 mt-0.5">Isi parameter dan pengaturan jadwal pelaksanaan ujian</p>
        </div>
        <a href="{{ route('guru.exams.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-medium">← Kembali</a>
    </div>

    <form action="{{ route('guru.exams.store') }}" method="POST" class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="subject_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mata Pelajaran *</label>
                <select id="subject_id" name="subject_id" required class="w-full text-xs p-2.5 border border-slate-300 rounded focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($subjects as $subj)
                        <option value="{{ $subj->id }}" {{ old('subject_id') == $subj->id ? 'selected' : '' }}>
                            {{ $subj->code }} - {{ $subj->name }}
                        </option>
                    @endforeach
                </select>
                @error('subject_id') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="duration_minutes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Durasi Pengerjaan (Menit) *</label>
                <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" min="5" max="300" required
                    class="w-full text-xs p-2.5 border border-slate-300 rounded focus:ring-blue-500 font-mono">
                @error('duration_minutes') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Ujian *</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Contoh: Penilaian Akhir Semester - Pemrograman Web"
                class="w-full text-xs p-2.5 border border-slate-300 rounded focus:ring-blue-500">
            @error('title') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Ringkas</label>
            <textarea id="description" name="description" rows="2" placeholder="Keterangan singkat ujian..." class="w-full text-xs p-2.5 border border-slate-300 rounded focus:ring-blue-500">{{ old('description') }}</textarea>
        </div>

        <div>
            <label for="instructions" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Instruksi & Tata Tertib Ujian</label>
            <textarea id="instructions" name="instructions" rows="3" placeholder="Petunjuk pengerjaan bagi siswa..." class="w-full text-xs p-2.5 border border-slate-300 rounded focus:ring-blue-500">{{ old('instructions') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="start_at" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Waktu Mulai Ujian *</label>
                <input type="datetime-local" id="start_at" name="start_at" value="{{ old('start_at', now()->format('Y-m-d\TH:i')) }}" required
                    class="w-full text-xs p-2.5 border border-slate-300 rounded focus:ring-blue-500">
                @error('start_at') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="end_at" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Waktu Selesai / Penutupan *</label>
                <input type="datetime-local" id="end_at" name="end_at" value="{{ old('end_at', now()->addDays(7)->format('Y-m-d\TH:i')) }}" required
                    class="w-full text-xs p-2.5 border border-slate-300 rounded focus:ring-blue-500">
                @error('end_at') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Class Assignment Multiple Checkboxes -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Target Kelas Peserta *</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 bg-slate-50 p-3 rounded border border-slate-200">
                @foreach($classes as $c)
                    <label class="flex items-center space-x-2 text-xs text-slate-800 cursor-pointer">
                        <input type="checkbox" name="class_ids[]" value="{{ $c->id }}" {{ is_array(old('class_ids')) && in_array($c->id, old('class_ids')) ? 'checked' : '' }}
                            class="rounded text-blue-600 focus:ring-blue-500">
                        <span>{{ $c->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('class_ids') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Toggles -->
        <div class="pt-3 border-t border-slate-200 space-y-2">
            <label class="flex items-center space-x-2 text-xs text-slate-800 cursor-pointer">
                <input type="checkbox" name="show_result" value="1" {{ old('show_result', true) ? 'checked' : '' }} class="rounded text-blue-600">
                <span class="font-medium">Tampilkan nilai/hasil akhir kepada siswa setelah ujian diserahkan</span>
            </label>

            <label class="flex items-center space-x-2 text-xs text-slate-800 cursor-pointer">
                <input type="checkbox" name="randomize_questions" value="1" {{ old('randomize_questions') ? 'checked' : '' }} class="rounded text-blue-600">
                <span class="font-medium">Acak urutan soal per siswa</span>
            </label>

            <label class="flex items-center space-x-2 text-xs text-slate-800 cursor-pointer">
                <input type="checkbox" name="randomize_options" value="1" {{ old('randomize_options') ? 'checked' : '' }} class="rounded text-blue-600">
                <span class="font-medium">Acak pilihan jawaban per siswa</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-200 flex justify-end space-x-3">
            <a href="{{ route('guru.exams.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded">Batal</a>
            <button type="submit" class="px-5 py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded shadow-sm">
                Simpan & Lanjut Kelola Soal →
            </button>
        </div>
    </form>
</div>
@endsection

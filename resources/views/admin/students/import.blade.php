@extends('layouts.app')

@section('title', 'Import Data Siswa CSV')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Import Data Siswa dari File CSV</h2>
        <a href="{{ route('admin.students.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-medium">← Kembali</a>
    </div>

    <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm space-y-5">
        @csrf

        <div class="bg-slate-50 border border-slate-200 p-4 rounded text-xs space-y-2 text-slate-700 leading-relaxed">
            <h4 class="font-bold text-slate-900 uppercase">Format Struktur File CSV:</h4>
            <p>File CSV harus berisi kolom berurutan sebagai berikut:</p>
            <pre class="bg-slate-900 text-slate-100 p-2.5 rounded font-mono text-[11px] overflow-x-auto">
NIS,Nama,L/P,Password,Kelas
1001,Ahmad Fauzi,L,password,XII RPL
1002,Annisa Rahma,P,password,XII DKV 1
            </pre>
            <p class="text-slate-500">* NIS harus unik dan password akan otomatis di-hash secara aman menggunakan <code>Hash::make()</code>.</p>
        </div>

        <div>
            <label for="file" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pilih File CSV *</label>
            <input type="file" id="file" name="file" accept=".csv, .txt" required
                class="w-full text-xs p-2 border border-slate-300 rounded bg-slate-50">
            @error('file') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="default_class_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kelas Default (Jika kolom kelas di CSV kosong)</label>
            <select id="default_class_id" name="default_class_id" class="w-full text-xs p-2.5 border border-slate-300 rounded">
                <option value="">-- Gunakan Nama Kelas dari CSV --</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="pt-4 border-t border-slate-200 flex justify-end space-x-3">
            <a href="{{ route('admin.students.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-semibold rounded">Batal</a>
            <button type="submit" class="px-5 py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded shadow-sm">
                Proses Import Data
            </button>
        </div>
    </form>
</div>
@endsection

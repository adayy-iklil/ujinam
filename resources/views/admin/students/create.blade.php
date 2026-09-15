@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Tambah Siswa Baru</h2>
        <a href="{{ route('admin.students.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-medium">← Kembali</a>
    </div>

    <form action="{{ route('admin.students.store') }}" method="POST" class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm space-y-4">
        @csrf

        <div>
            <label for="nis" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">NIS (Nomor Induk Siswa) *</label>
            <input type="text" id="nis" name="nis" value="{{ old('nis') }}" required class="w-full text-xs p-2.5 border border-slate-300 rounded font-mono">
            @error('nis') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap Siswa *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full text-xs p-2.5 border border-slate-300 rounded">
            @error('name') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="gender" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jenis Kelamin *</label>
                <select id="gender" name="gender" required class="w-full text-xs p-2.5 border border-slate-300 rounded">
                    <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                    <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                </select>
            </div>

            <div>
                <label for="class_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kelas *</label>
                <select id="class_id" name="class_id" required class="w-full text-xs p-2.5 border border-slate-300 rounded">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('class_id') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password Awal *</label>
            <input type="password" id="password" name="password" required value="123456" class="w-full text-xs p-2.5 border border-slate-300 rounded">
            <span class="text-[11px] text-slate-400 mt-0.5 block">Password otomatis di-hash menggunakan Laravel Hash::make()</span>
        </div>

        <div class="pt-4 border-t border-slate-200 flex justify-end space-x-3">
            <a href="{{ route('admin.students.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-semibold rounded">Batal</a>
            <button type="submit" class="px-5 py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded shadow-sm">
                Simpan Siswa
            </button>
        </div>
    </form>
</div>
@endsection

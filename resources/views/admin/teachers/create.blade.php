@extends('layouts.app')

@section('title', 'Tambah Akun Guru')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Buat Akun Guru Baru</h2>
        <a href="{{ route('admin.teachers.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-medium">← Kembali</a>
    </div>

    <form action="{{ route('admin.teachers.store') }}" method="POST" class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap Guru & Gelar *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso, S.Kom" class="w-full text-xs p-2.5 border border-slate-300 rounded">
            @error('name') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="username" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username Login *</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required class="w-full text-xs p-2.5 border border-slate-300 rounded font-mono">
                @error('username') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="guru@school.sch.id" class="w-full text-xs p-2.5 border border-slate-300 rounded">
            </div>
        </div>

        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password Login *</label>
            <input type="password" id="password" name="password" required class="w-full text-xs p-2.5 border border-slate-300 rounded">
            @error('password') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Mata Pelajaran yang Diampu</label>
            <div class="grid grid-cols-2 gap-2 bg-slate-50 p-3 rounded border border-slate-200 text-xs">
                @foreach($subjects as $subj)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="subject_ids[]" value="{{ $subj->id }}" {{ is_array(old('subject_ids')) && in_array($subj->id, old('subject_ids')) ? 'checked' : '' }} class="rounded text-blue-600">
                        <span>{{ $subj->code }} - {{ $subj->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="pt-4 border-t border-slate-200 flex justify-end space-x-3">
            <a href="{{ route('admin.teachers.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-semibold rounded">Batal</a>
            <button type="submit" class="px-5 py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded shadow-sm">
                Simpan Akun Guru
            </button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Akun Guru')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Edit Akun Guru</h2>
        <a href="{{ route('admin.teachers.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-medium">← Kembali</a>
    </div>

    <form action="{{ route('admin.teachers.update', $teacher->id) }}" method="POST" class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap Guru *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $teacher->name) }}" required class="w-full text-xs p-2.5 border border-slate-300 rounded">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="username" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username Login *</label>
                <input type="text" id="username" name="username" value="{{ old('username', $teacher->username) }}" required class="w-full text-xs p-2.5 border border-slate-300 rounded font-mono">
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $teacher->email) }}" class="w-full text-xs p-2.5 border border-slate-300 rounded">
            </div>
        </div>

        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password Baru (Opsional)</label>
            <input type="password" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password" class="w-full text-xs p-2.5 border border-slate-300 rounded">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Mata Pelajaran yang Diampu</label>
            <div class="grid grid-cols-2 gap-2 bg-slate-50 p-3 rounded border border-slate-200 text-xs">
                @foreach($subjects as $subj)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="subject_ids[]" value="{{ $subj->id }}" {{ in_array($subj->id, old('subject_ids', $selectedSubjectIds)) ? 'checked' : '' }} class="rounded text-blue-600">
                        <span>{{ $subj->code }} - {{ $subj->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="flex items-center space-x-2 text-xs text-slate-800 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $teacher->is_active) ? 'checked' : '' }} class="rounded text-blue-600">
                <span class="font-medium">Akun Guru Aktif</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-200 flex justify-end space-x-3">
            <a href="{{ route('admin.teachers.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-semibold rounded">Batal</a>
            <button type="submit" class="px-5 py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded shadow-sm">
                Perbarui Akun Guru
            </button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Edit Data Rombel Kelas</h2>
        <a href="{{ route('admin.classes.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-medium">← Kembali</a>
    </div>

    <form action="{{ route('admin.classes.update', $class->id) }}" method="POST" class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="grade" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tingkat (Grade) *</label>
                <select id="grade" name="grade" required class="w-full text-xs p-2.5 border border-slate-300 rounded font-semibold">
                    <option value="X" {{ old('grade', $class->grade) === 'X' ? 'selected' : '' }}>Kelas X</option>
                    <option value="XI" {{ old('grade', $class->grade) === 'XI' ? 'selected' : '' }}>Kelas XI</option>
                    <option value="XII" {{ old('grade', $class->grade) === 'XII' ? 'selected' : '' }}>Kelas XII</option>
                </select>
            </div>

            <div>
                <label for="major_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jurusan *</label>
                <select id="major_id" name="major_id" required class="w-full text-xs p-2.5 border border-slate-300 rounded">
                    @foreach($majors as $m)
                        <option value="{{ $m->id }}" {{ old('major_id', $class->major_id) == $m->id ? 'selected' : '' }}>{{ $m->code }} - {{ $m->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="class_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nomor Rombel *</label>
                <input type="number" id="class_number" name="class_number" value="{{ old('class_number', $class->class_number) }}" min="1" max="10" required
                    class="w-full text-xs p-2.5 border border-slate-300 rounded font-mono">
            </div>

            <div>
                <label for="academic_year_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tahun Ajaran *</label>
                <select id="academic_year_id" name="academic_year_id" required class="w-full text-xs p-2.5 border border-slate-300 rounded">
                    @foreach($academicYears as $ay)
                        <option value="{{ $ay->id }}" {{ old('academic_year_id', $class->academic_year_id) == $ay->id ? 'selected' : '' }}>{{ $ay->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Tampilan Kelas *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $class->name) }}" required
                class="w-full text-xs p-2.5 border border-slate-300 rounded font-bold">
        </div>

        <div>
            <label class="flex items-center space-x-2 text-xs text-slate-800 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $class->is_active) ? 'checked' : '' }} class="rounded text-blue-600">
                <span class="font-medium">Kelas Aktif</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-200 flex justify-end space-x-3">
            <a href="{{ route('admin.classes.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-semibold rounded">Batal</a>
            <button type="submit" class="px-5 py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded shadow-sm">
                Perbarui Kelas
            </button>
        </div>
    </form>
</div>
@endsection

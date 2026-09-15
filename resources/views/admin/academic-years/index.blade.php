@extends('layouts.app')

@section('title', 'Tahun Ajaran')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Form -->
    <div class="md:col-span-1">
        <form action="{{ route('admin.academic-years.store') }}" method="POST" class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm space-y-4">
            @csrf
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">Tambah Tahun Ajaran Baru</h3>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Tahun Ajaran *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: 2026/2027" class="w-full text-xs p-2.5 border border-slate-300 rounded font-bold">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="start_year" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tahun Mulai *</label>
                    <input type="number" id="start_year" name="start_year" value="{{ old('start_year', date('Y')) }}" required class="w-full text-xs p-2.5 border border-slate-300 rounded font-mono">
                </div>

                <div>
                    <label for="end_year" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tahun Selesai *</label>
                    <input type="number" id="end_year" name="end_year" value="{{ old('end_year', date('Y')+1) }}" required class="w-full text-xs p-2.5 border border-slate-300 rounded font-mono">
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs py-2.5 px-4 rounded shadow-sm">
                + Simpan Tahun Ajaran
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="md:col-span-2">
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
            <div class="p-4 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-900">Daftar Tahun Ajaran Sekolah</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3">Tahun Ajaran</th>
                            <th class="px-4 py-3">Periode</th>
                            <th class="px-4 py-3">Jumlah Kelas</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($academicYears as $ay)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3 font-bold text-slate-900">{{ $ay->name }}</td>
                                <td class="px-4 py-3 font-mono text-slate-600">{{ $ay->start_year }} - {{ $ay->end_year }}</td>
                                <td class="px-4 py-3 font-mono text-slate-700 font-semibold">{{ $ay->school_classes_count }} Kelas</td>
                                <td class="px-4 py-3">
                                    @if($ay->is_active)
                                        <span class="bg-emerald-50 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded border border-emerald-200">AKTIF (CURRENT)</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200">NONAKTIF</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @if(!$ay->is_active)
                                        <form action="{{ route('admin.academic-years.toggleActive', $ay->id) }}" method="POST" class="inline" onsubmit="return confirm('Aktifkan tahun ajaran {{ $ay->name }}?')">
                                            @csrf
                                            <button type="submit" class="bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold px-2 py-1 rounded text-[11px] border border-blue-200">
                                                Set Aktif
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-slate-500">Belum ada tahun ajaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@extends('layouts.app')

@section('title', 'Kenaikan Kelas')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="border-b border-slate-200 pb-3">
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Proses Kenaikan Kelas Berkelanjutan</h2>
        <p class="text-xs text-slate-500 mt-0.5">Otomasi transisi siswa antar tahun ajaran secara aman dan terstruktur</p>
    </div>

    <!-- Workflow Diagram / Explanation Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-5 text-xs text-blue-900 space-y-3 leading-relaxed">
        <h3 class="font-bold text-blue-950 uppercase tracking-wider">Aturan Alur Kerja Kenaikan Kelas:</h3>
        <div class="bg-white p-3.5 rounded border border-blue-200 font-mono text-[11px] space-y-1.5">
            <div><strong>Siswa Kelas X Lama:</strong> → Diarsipkan & Dibuat Inaktif (Arsip Riwayat Tetap Tersimpan)</div>
            <div><strong>Siswa Kelas XI Aktif:</strong> → Dipromosikan ke Kelas XII Tahun Ajaran Baru</div>
            <div><strong>Siswa Kelas XII Baru:</strong> → Menggunakan NIS & Identitas yang sama</div>
        </div>
        <p class="text-[11px] text-blue-800">
            * Seluruh riwayat hasil ujian lama siswa tidak akan terhapus. Hubungan database menggunakan konteks tahun ajaran yang aman.
        </p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.promotion.process') }}" method="POST" class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm space-y-5" onsubmit="return confirm('Apakah Anda yakin ingin menjalankan proses Kenaikan Kelas? Aksi ini akan mengubah status siswa dan memindahkan ke tahun ajaran baru.')">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="from_academic_year_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tahun Ajaran Asal (Lama) *</label>
                <select id="from_academic_year_id" name="from_academic_year_id" required class="w-full text-xs p-2.5 border border-slate-300 rounded font-bold text-slate-800">
                    @foreach($academicYears as $ay)
                        <option value="{{ $ay->id }}" {{ $ay->is_active ? 'selected' : '' }}>
                            {{ $ay->name }} {{ $ay->is_active ? '(Aktif Saat Ini)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('from_academic_year_id') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="to_academic_year_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tahun Ajaran Tujuan (Baru) *</label>
                <select id="to_academic_year_id" name="to_academic_year_id" required class="w-full text-xs p-2.5 border border-slate-300 rounded font-bold text-blue-900 bg-blue-50">
                    <option value="">-- Pilih Tahun Ajaran Baru --</option>
                    @foreach($academicYears as $ay)
                        @if(!$ay->is_active)
                            <option value="{{ $ay->id }}">{{ $ay->name }}</option>
                        @endif
                    @endforeach
                </select>
                @error('to_academic_year_id') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="pt-4 border-t border-slate-200 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded shadow-sm">
                Jalankan Otomasi Kenaikan Kelas →
            </button>
        </div>
    </form>
</div>
@endsection

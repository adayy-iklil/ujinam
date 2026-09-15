@extends('layouts.app')

@section('title', 'Monitoring Peserta Ujian')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
        <div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('guru.exams.index') }}" class="text-xs text-blue-700 hover:underline">← Ujian</a>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-bold text-slate-700 uppercase">Peserta</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight mt-1">{{ $exam->title }}</h2>
            <p class="text-xs text-slate-500">Monitoring Sesi Peserta Ujian & Penanganan Terkunci</p>
        </div>
    </div>

    <!-- Participants Table -->
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">NIS</th>
                        <th class="px-4 py-3">Kelas</th>
                        <th class="px-4 py-3">Waktu Mulai</th>
                        <th class="px-4 py-3">Status Sesi</th>
                        <th class="px-4 py-3">Pelanggaran</th>
                        <th class="px-4 py-3 text-right">Aksi Reset / Unlock</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($attempts as $att)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $att->student->name }}</td>
                            <td class="px-4 py-3 font-mono text-slate-600">{{ $att->student->nis }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $att->student->schoolClass->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $att->started_at->format('d M, H:i') }} WIB</td>
                            <td class="px-4 py-3">
                                @if($att->status === 'submitted')
                                    <span class="bg-emerald-50 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded border border-emerald-200">SUBMITTED (Nilai: {{ $att->score }})</span>
                                @elseif($att->isLocked())
                                    <span class="bg-rose-100 text-rose-800 text-[10px] font-bold px-2 py-0.5 rounded border border-rose-300 animate-pulse">LOCKED (TERKUNCI)</span>
                                @else
                                    <span class="bg-amber-50 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded border border-amber-200">IN PROGRESS</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-bold {{ $att->violation_count >= 3 ? 'text-rose-600 font-mono text-sm' : ($att->violation_count > 0 ? 'text-amber-600 font-mono' : 'text-slate-400 font-mono') }}">
                                    {{ $att->violation_count }} Pelanggaran
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <form action="{{ route('guru.attempts.reset', $att->id) }}" method="POST" class="inline" onsubmit="return confirm('Buka kunci dan reset angka pelanggaran siswa ini?')">
                                    @csrf
                                    <input type="hidden" name="action_type" value="unlock">
                                    <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-semibold px-2 py-1 rounded">
                                        Unlock & Reset Warning
                                    </button>
                                </form>

                                <form action="{{ route('guru.attempts.reset', $att->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin MERESET Ulang seluruh ujian siswa ini? Seluruh jawaban sebelumnya akan dihapus.')">
                                    @csrf
                                    <input type="hidden" name="action_type" value="full_reset">
                                    <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-800 border border-rose-300 font-semibold px-2 py-1 rounded">
                                        Reset Ulang Sesi
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">Belum ada siswa yang memulai ujian ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

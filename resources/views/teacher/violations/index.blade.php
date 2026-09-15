@extends('layouts.app')

@section('title', 'Log Pelanggaran Ujian')

@section('content')
<div class="space-y-6">
    <div class="border-b border-slate-200 pb-3">
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Monitoring & Audit Pelanggaran Ujian</h2>
        <p class="text-xs text-slate-500 mt-0.5">Catatan realtime deteksi tab switch / pergerakan kursor siswa selama ujian</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Waktu Terjadi</th>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">NIS / Kelas</th>
                        <th class="px-4 py-3">Ujian Target</th>
                        <th class="px-4 py-3">Tipe Pelanggaran</th>
                        <th class="px-4 py-3 text-right">Aksi Penanganan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($violations as $v)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-mono text-slate-600">{{ $v->occurred_at->format('d M Y, H:i:s') }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $v->student->name }}</td>
                            <td class="px-4 py-3 text-slate-600 font-mono">{{ $v->student->nis }} ({{ $v->student->schoolClass->name ?? '-' }})</td>
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $v->exam->title ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-rose-50 text-rose-800 text-[10px] font-bold px-2 py-0.5 rounded border border-rose-200">
                                    {{ strtoupper($v->violation_type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if($v->attempt)
                                    <form action="{{ route('guru.attempts.reset', $v->attempt->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="action_type" value="unlock">
                                        <button type="submit" class="bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold px-2 py-1 rounded text-[11px] border border-blue-200">
                                            Unlock Siswa
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">Belum ada catatan pelanggaran ujian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200">
            {{ $violations->links() }}
        </div>
    </div>
</div>
@endsection

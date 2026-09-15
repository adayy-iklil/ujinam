@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="space-y-6">
    <div class="border-b border-slate-200 pb-3">
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Audit Trail & Log Aktivitas Sistem</h2>
        <p class="text-xs text-slate-500 mt-0.5">Catatan jejak aktivitas user login, pembuatan soal, reset attempt, dan kegiatan sistem</p>
    </div>

    <!-- Filter Form -->
    <form action="{{ route('admin.logs.index') }}" method="GET" class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi atau IP address..."
                class="w-full text-xs p-2.5 border border-slate-300 rounded">
        </div>
        <div class="w-full sm:w-48">
            <input type="text" name="action" value="{{ request('action') }}" placeholder="Action code (e.g. LOGIN)"
                class="w-full text-xs p-2.5 border border-slate-300 rounded font-mono">
        </div>
        <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs px-4 py-2.5 rounded border border-slate-300">
            Filter Log
        </button>
    </form>

    <!-- Table -->
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Waktu</th>
                        <th class="px-4 py-3">Aktor / Pengguna</th>
                        <th class="px-4 py-3">Kode Aksi</th>
                        <th class="px-4 py-3">Deskripsi Aktivitas</th>
                        <th class="px-4 py-3">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-mono text-[11px]">
                    @forelse($logs as $l)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 text-slate-500">{{ $l->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="px-4 py-3 font-sans font-semibold text-slate-900">
                                @if($l->user)
                                    <span class="text-blue-900">{{ $l->user->name }}</span> ({{ $l->user->role }})
                                @elseif($l->student)
                                    <span class="text-emerald-800">{{ $l->student->name }}</span> (NIS: {{ $l->student->nis }})
                                @else
                                    <span class="text-slate-400">Sistem / Tamu</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-bold text-slate-800">
                                <span class="bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200 text-[10px]">{{ $l->action }}</span>
                            </td>
                            <td class="px-4 py-3 font-sans text-slate-800">{{ $l->description }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $l->ip_address ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500 font-sans">Belum ada log aktivitas terrekam.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection

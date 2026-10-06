@extends('layouts.app', ['title' => 'Portal Petugas Lapangan - SiklusSampah'])

@section('content')
    <div class="space-y-6">
        <!-- Header Petugas -->
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        
        <!-- Sisi Kiri: Profil & Info Petugas -->
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 font-bold text-emerald-800">
                {{ auth()->user()->initials }}
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-lg font-bold text-slate-900">Ruang Kerja Petugas</h1>
                    <span class="rounded-md bg-emerald-100 px-2 py-0.5 text-xs font-bold text-emerald-800">
                        {{ auth()->user()->zona_wilayah ?? 'Zona A' }}
                    </span>
                </div>
                <p class="mt-0.5 text-xs text-slate-500">
                    <strong class="text-slate-700">{{ auth()->user()->name }}</strong> — Armada Roda 3 TPS3R Mandiri
                </p>
            </div>
        </div>

        <!-- Sisi Kanan: Tanggal & Tombol Aksi -->
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3 lg:border-t-0 lg:pt-0">
            <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600">
                📅 {{ now()->translatedFormat('l, d F Y') }}
            </div>
            <a href="{{ route('petugas.pickups.index') }}" 
               class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2 text-xs font-bold text-white transition hover:bg-emerald-800 active:scale-95">
                <span>Buka Antrean Risk</span>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

    </div>
</div>

        <!-- Ringkasan Operasional -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                <span class="text-xs font-semibold text-amber-800">Menunggu Penanganan</span>
                <div class="mt-1 font-mono text-2xl font-bold text-amber-950">
                    {{ $pendingReports->count() }} Laporan
                </div>
            </div>

            <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">
                <span class="text-xs font-semibold text-blue-800">Dalam Pengerjaan</span>
                <div class="mt-1 font-mono text-2xl font-bold text-blue-950">
                    {{ $inProgressReports->count() }} Armada
                </div>
            </div>

            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                <span class="text-xs font-semibold text-emerald-800">Sampah Terpilah</span>
                <div class="mt-1 font-mono text-2xl font-bold text-emerald-950">
                    {{ number_format($totalSortedKg, 1) }} kg
                </div>
            </div>

            <div class="rounded-xl border border-purple-200 bg-purple-50 p-4">
                <span class="text-xs font-semibold text-purple-800">Penyaluran Mitra</span>
                <div class="mt-1 font-mono text-2xl font-bold text-purple-950">
                    {{ number_format($totalDistributedKg, 1) }} kg
                </div>
            </div>
        </div>

        <!-- Daftar Tugas Laporan Perlu Penanganan -->
        <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-xs">
            <div class="flex items-center justify-between border-b border-neutral-100 p-5">
                <h2 class="text-base font-bold text-neutral-900">Daftar Pengaduan Masuk (Perlu Tindak Lanjut)</h2>
                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">
                    {{ $pendingReports->count() }} Tugas Menunggu
                </span>
            </div>

            <div class="divide-y divide-neutral-100">
                @forelse ($pendingReports as $task)
                    <div class="flex flex-col justify-between gap-4 p-5 hover:bg-neutral-50/50 md:flex-row md:items-center">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-sm font-bold text-neutral-900">{{ $task->kode_laporan }}</span>
                                <span
                                    class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                {{ in_array($task->priority_level, ['high', 'critical'], true) ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800' }}"
                                >
                                    Risiko {{ $task->priority_level }}
                                </span>
                            </div>
                            <div class="text-xs font-semibold text-neutral-700">
                                Lokasi: {{ $task->alamat }} (RT {{ $task->rt }} / RW {{ $task->rw }})
                            </div>
                            <div class="text-xs text-neutral-500">
                                Jenis: <strong>{{ $task->jenis_tumpukan }}</strong> · "{{ $task->deskripsi }}"
                            </div>
                        </div>

                        <!-- Tombol Aksi Cepat Petugas -->
                        <div class="flex shrink-0 items-center gap-2">
                            <form action="{{ route('petugas.laporan.update-status', $task->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="in_process" />
                                <input type="hidden" name="catatan_petugas" value="Petugas mulai menangani laporan." />
                                <button
                                    type="submit"
                                    class="cursor-pointer rounded-xl bg-blue-600 px-3.5 py-2 text-xs font-bold text-white transition-colors hover:bg-blue-700"
                                >
                                    Mulai Penanganan
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-neutral-400">
                        Tidak ada aduan tertunda saat ini. Seluruh laporan telah ditangani dengan baik!
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

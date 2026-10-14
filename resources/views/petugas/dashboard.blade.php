@extends('layouts.app', ['title' => 'Portal Petugas Lapangan - SiklusSampah'])

@section('content')
<div class="space-y-5">
    <!-- Header Petugas -->
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <!-- Sisi Kiri: Profil & Info Petugas -->
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-base font-bold text-emerald-800">
                    {{ auth()->user()->initials }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl font-bold text-slate-900">Dashboard Petugas</h1>
                        <span class="rounded-md border border-emerald-200/60 bg-emerald-50 px-2 py-0.5 text-xs font-bold text-emerald-700">
                            {{ auth()->user()->zona_wilayah ?? 'Zona A' }}
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs text-slate-500">
                        <strong class="text-slate-700">{{ auth()->user()->name }}</strong> · Armada Roda 3 TPS3R Mandiri
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3 lg:border-t-0 lg:pt-0">
                <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-1.5 text-xs font-semibold text-slate-600">
                    <i class="fa-solid fa-calendar-days text-xs text-emerald-600"></i>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <a href="{{ route('petugas.pickups.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-xs transition hover:bg-emerald-700 active:scale-95">
                    <i class="fa-solid fa-truck"></i>
                    <span>Buka Antrean</span>
                </a>
            </div>

        </div>
    </div>

    @if ($newReportsCount > 0)
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 shadow-xs">
        <p class="text-sm font-semibold text-amber-900">{{ $newReportsCount }} laporan baru menunggu ditinjau.</p>
        <a href="{{ route('petugas.pickups.index') }}" class="text-sm font-bold text-amber-900 underline underline-offset-2">Buka antrean</a>
    </div>
    @endif

    <section class="space-y-3" aria-labelledby="work-summary-heading">
        <h2 id="work-summary-heading" class="text-xs font-bold uppercase tracking-wider text-slate-600">Ringkasan operasional</h2>
        <div class="grid grid-cols-2 gap-3.5 lg:grid-cols-4">
            <div class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-xs transition hover:shadow-md">
                <div>
                    <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Menunggu</span>
                    <div class="mt-1 flex items-baseline gap-1.5">
                        <span class="font-mono text-2xl font-extrabold text-amber-600">{{ $pendingReportsCount }}</span>
                        <span class="text-[11px] font-medium text-slate-400">laporan</span>
                    </div>
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <i class="fa-solid fa-clock text-base"></i>
                </div>
            </div>

            <div class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-xs transition hover:shadow-md">
                <div>
                    <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Dalam Pengerjaan</span>
                    <div class="mt-1 flex items-baseline gap-1.5">
                        <span class="font-mono text-2xl font-extrabold text-sky-600">{{ $inProgressReportsCount }}</span>
                        <span class="text-[11px] font-medium text-slate-400">laporan</span>
                    </div>
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                    <i class="fa-solid fa-truck-fast text-base"></i>
                </div>
            </div>

            <div class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-xs transition hover:shadow-md">
                <div>
                    <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Sampah Terpilah</span>
                    <div class="mt-1 flex items-baseline gap-1.5">
                        <span class="font-mono text-2xl font-extrabold text-emerald-600">{{ number_format($totalSortedKg, 1) }}</span>
                        <span class="text-[11px] font-medium text-slate-400">kg</span>
                    </div>
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <i class="fa-solid fa-recycle text-base"></i>
                </div>
            </div>

            <div class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-xs transition hover:shadow-md">
                <div>
                    <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Penyaluran Mitra</span>
                    <div class="mt-1 flex items-baseline gap-1.5">
                        <span class="font-mono text-2xl font-extrabold text-violet-600">{{ number_format($totalDistributedKg, 1) }}</span>
                        <span class="text-[11px] font-medium text-slate-400">kg</span>
                    </div>
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                    <i class="fa-solid fa-handshake text-base"></i>
                </div>
            </div>
        </div>
    </section>

    <section class="space-y-3" aria-labelledby="work-shortcuts-heading">
        <div>
            <h2 id="work-shortcuts-heading" class="text-base font-bold text-neutral-900">Akses cepat pekerjaan</h2>
        </div>
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
            <a href="{{ route('petugas.pickups.index') }}" class="flex items-center justify-between gap-4 rounded-xl border border-amber-200 bg-white p-4 transition hover:border-amber-400 hover:bg-amber-50">
                <span>
                    <span class="block text-sm font-bold text-neutral-900">Radar tugas pengangkutan</span>
                    <span class="mt-1 block text-xs text-neutral-500">{{ $highPriorityReportsCount }} laporan berprioritas tinggi atau kritis dari {{ $pendingReportsCount }} laporan menunggu</span>
                </span>
                <span class="shrink-0 rounded-lg bg-amber-100 px-3 py-2 text-xs font-bold text-amber-900">Buka antrean</span>
            </a>
            <a href="{{ route('petugas.routes.index') }}" class="flex items-center justify-between gap-4 rounded-xl border border-blue-200 bg-white p-4 transition hover:border-blue-400 hover:bg-blue-50">
                <span>
                    <span class="block text-sm font-bold text-neutral-900">Peta rute pengangkutan</span>
                    <span class="mt-1 block text-xs text-neutral-500">Atur navigasi untuk laporan aktif.</span>
                </span>
                <span class="shrink-0 rounded-lg bg-blue-100 px-3 py-2 text-xs font-bold text-blue-900">Buka peta</span>
            </a>
            <a href="{{ route('petugas.sortings.index') }}" class="flex items-center justify-between gap-4 rounded-xl border border-emerald-200 bg-white p-4 transition hover:border-emerald-400 hover:bg-emerald-50">
                <span>
                    <span class="block text-sm font-bold text-neutral-900">Pemilahan dan dampak</span>
                    <span class="mt-1 block text-xs text-neutral-500">{{ $sortingQueueCount }} laporan selesai menunggu pencatatan pemilahan.</span>
                </span>
                <span class="shrink-0 rounded-lg bg-emerald-100 px-3 py-2 text-xs font-bold text-emerald-900">Catat hasil</span>
            </a>
        </div>
    </section>
</div>
@endsection
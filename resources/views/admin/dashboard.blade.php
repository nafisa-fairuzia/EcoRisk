@extends('layouts.app', ['title' => 'Pusat Kendali Administrasi - SiklusSampah'])

@section('content')
<div class="space-y-5">
    <!-- Header Banner Card -->
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-base font-bold text-emerald-800">
                    {{ auth()->user()->initials }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl font-bold text-slate-900">Dashboard Administrator</h1>
                        <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-bold text-emerald-700 border border-emerald-200/60">
                            Pusat Kendali
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs text-slate-500">
                        <strong class="text-slate-700">{{ auth()->user()->name }}</strong> · {{ $totalPetugas }} petugas aktif di wilayah operasional
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3 lg:border-t-0 lg:pt-0">
                <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-1.5 text-xs font-semibold text-slate-600">
                    <i class="fa-solid fa-calendar-days text-xs text-emerald-600"></i>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <a
                    href="{{ route('admin.heatmap') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-xs transition hover:bg-emerald-700 active:scale-95"
                >
                    <i class="fa-solid fa-map-location-dot"></i>
                    Buka Heatmap Risiko
                </a>
            </div>
        </div>
    </div>

    <!-- 4 Metrik Kartu -->
    <div class="grid grid-cols-2 gap-3.5 lg:grid-cols-4">
        <!-- Card 1 -->
        <div class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-xs transition hover:shadow-md">
            <div>
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Penyelesaian</span>
                <div class="mt-1 flex items-baseline gap-1.5">
                    <span class="font-mono text-2xl font-extrabold text-slate-800">{{ $completionRate }}%</span>
                    <span class="text-[11px] font-medium text-slate-400">({{ $completedReports }}/{{ $totalReports }})</span>
                </div>
            </div>
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <i class="fa-solid fa-chart-line text-base"></i>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-xs transition hover:shadow-md">
            <div>
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Petugas Lapangan</span>
                <div class="mt-1 flex items-baseline gap-1.5">
                    <span class="font-mono text-2xl font-extrabold text-sky-600">{{ $totalPetugas }}</span>
                    <span class="text-[11px] font-medium text-slate-400">aktif</span>
                </div>
            </div>
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                <i class="fa-solid fa-user-shield text-base"></i>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-xs transition hover:shadow-md">
            <div>
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Sampah Terpilah</span>
                <div class="mt-1 flex items-baseline gap-1.5">
                    <span class="font-mono text-2xl font-extrabold text-amber-600">{{ number_format($totalSortedKg, 1) }}</span>
                    <span class="text-[11px] font-medium text-slate-400">kg</span>
                </div>
            </div>
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <i class="fa-solid fa-recycle text-base"></i>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-xs transition hover:shadow-md">
            <div>
                <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Emisi Dicegah</span>
                <div class="mt-1 flex items-baseline gap-1.5">
                    <span class="font-mono text-2xl font-extrabold text-emerald-600">{{ number_format((float) $impactTotals->prevented_emissions_kg, 1) }}</span>
                    <span class="text-[11px] font-medium text-slate-400">kg CO₂e</span>
                </div>
            </div>
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <i class="fa-solid fa-leaf text-base"></i>
            </div>
        </div>
    </div>

    @php
        $channelColors = [
            'maggot_bsf' => '#10b981',
            'compost_community' => '#f59e0b',
            'waste_bank' => '#0ea5e9',
            'recycling_umkm' => '#8b5cf6',
        ];
        $channelColorClasses = [
            'maggot_bsf' => 'bg-emerald-500',
            'compost_community' => 'bg-amber-500',
            'waste_bank' => 'bg-sky-500',
            'recycling_umkm' => 'bg-violet-500',
        ];
        $channelTotalWeight = (float) $channelDistribution->sum('weight_kg');
    @endphp

    <div class="grid gap-4 lg:grid-cols-3">
        <!-- Kolom Kiri: Chart Donut Mini (Tanpa JS, Bersih dari Error CSS) -->
        <div class="flex flex-col items-center justify-center gap-3 rounded-2xl bg-white p-4 shadow-xs">
            <div class="w-full text-center lg:text-left">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-600">Proporsi Penyaluran</h2>
            </div>

            <div class="relative flex size-36 shrink-0 items-center justify-center">
                <svg
                    data-channel-chart
                    viewBox="0 0 42 42"
                    role="img"
                    aria-label="Diagram proporsi penyaluran berdasarkan berat sampah"
                    class="size-full -rotate-90">
                    <circle cx="21" cy="21" r="15.9155" fill="transparent" stroke="#e2e8f0" stroke-width="5" />
                    @php($channelChartOffset = 0)
                    @foreach ($channelDistribution as $channelType => $channel)
                    @if ($channelTotalWeight > 0 && $channel['weight_kg'] > 0)
                    @php($channelChartPercentage = ($channel['weight_kg'] / $channelTotalWeight) * 100)
                    <circle
                        cx="21"
                        cy="21"
                        r="15.9155"
                        fill="transparent"
                        stroke="{{ $channelColors[$channelType] ?? '#94a3b8' }}"
                        stroke-width="5"
                        stroke-dasharray="{{ $channelChartPercentage }} {{ 100 - $channelChartPercentage }}"
                        stroke-dashoffset="{{ -$channelChartOffset }}" />
                    @php($channelChartOffset += $channelChartPercentage)
                    @endif
                    @endforeach
                </svg>
                <div class="absolute flex size-20 flex-col items-center justify-center rounded-full bg-white text-center shadow-inner">
                    <span class="font-mono text-sm font-extrabold leading-tight text-slate-800">{{ number_format($channelTotalWeight, 1) }}</span>
                    <span class="text-[9px] font-semibold text-slate-400">kg total</span>
                </div>
            </div>

            @if ($channelTotalWeight <= 0)
            <p class="text-center text-xs text-slate-500">Belum ada data penyaluran.</p>
            @else
            <ul class="flex flex-wrap justify-center gap-x-3 gap-y-1 text-[10px] text-slate-600">
                @foreach ($channelDistribution as $channelType => $channel)
                @if ($channel['weight_kg'] > 0)
                <li class="inline-flex items-center gap-1">
                    <span @class(['size-2 rounded-full', $channelColorClasses[$channelType] ?? 'bg-slate-400']) aria-hidden="true"></span>
                    {{ $channel['label'] }}
                </li>
                @endif
                @endforeach
            </ul>
            @endif

            <div class="w-full rounded-xl bg-slate-50 py-1.5 text-center text-[11px] text-slate-500">
                Total: <strong class="text-slate-700">{{ number_format($channelTotalWeight, 1) }} kg</strong>
            </div>
        </div>

        <!-- Kolom Kanan: Rincian Saluran -->
        <div class="rounded-2xl bg-white p-4 shadow-xs lg:col-span-2">
            <div class="mb-3 border-b border-slate-100 pb-2">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-600">Rincian Saluran Daur Ulang</h2>
            </div>

            <div class="grid gap-2.5 sm:grid-cols-2">
                @foreach ($channelDistribution as $channelType => $channel)
                <div class="rounded-xl bg-slate-50/70 p-3 transition hover:bg-slate-100/80">
                    <div class="flex items-center justify-between text-xs">
                        <span class="inline-flex items-center gap-2 font-bold text-slate-700">
                            <span @class(['size-2.5 rounded-full', $channelColorClasses[$channelType] ?? 'bg-slate-400']) aria-hidden="true"></span>
                            {{ $channel['label'] }}
                        </span>
                        <span class="rounded-md bg-white px-2 py-0.5 font-mono text-[11px] font-bold text-emerald-700 shadow-2xs">
                            {{ number_format($channel['percentage'], 1) }}%
                        </span>
                    </div>
                    <div class="mt-2 flex items-baseline justify-between">
                        <span class="font-mono text-base font-bold text-slate-900">{{ number_format($channel['weight_kg'], 1) }} <span class="text-xs font-normal text-slate-400">kg</span></span>
                    </div>
                    <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-200">
                        <svg class="block h-1.5 w-full" viewBox="0 0 100 6" preserveAspectRatio="none" aria-hidden="true">
                            <rect width="100" height="6" fill="#e2e8f0" />
                            <rect
                                width="{{ min(100, max(0, $channel['percentage'])) }}"
                                height="6"
                                fill="{{ $channelColors[$channelType] ?? '#94a3b8' }}" />
                        </svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Tabel Monitoring Aduan Wilayah -->
    <div class="border border-neutral-200 bg-white shadow-xs">
        <div class="flex items-center justify-between border-b border-neutral-100 p-5">
            <h2 class="text-base font-bold text-neutral-900">Rekap Aduan Sampah Seluruh Wilayah</h2>
            <span class="text-xs text-neutral-500">{{ $totalReports }} aduan tercatat</span>
        </div>

        <div data-table-container data-filter-column="5">
            <x-table-tools filter-label="status" />
            <div class="table-responsive">
                <table class="table table-hover" data-filter-table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode laporan</th>
                            <th>Pelapor & Alamat</th>
                            <th>Jenis Sampah</th>
                            <th>Urgensi</th>
                            <th>Status</th>
                            <th>Petugas Penangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $r)
                        <tr>
                            <td>{{ $reports->firstItem() + $loop->index }}</td>
                            <td class="font-mono font-bold text-neutral-900">{{ $r->kode_laporan }}</td>
                            <td>
                                <div class="font-bold text-neutral-900">{{ $r->nama_pelapor }}</div>
                                <div class="text-[11px] text-neutral-500">
                                    {{ $r->alamat }} (RT {{ $r->rt }}/RW {{ $r->rw }})
                                </div>
                            </td>
                            <td>{{ $r->jenis_tumpukan }}</td>
                            <td>
                                <x-report-badge type="urgency" :value="$r->urgensi" :label="$r->urgency_label" />
                            </td>
                            <td>
                                <x-report-badge type="status" :value="$r->status" :label="$r->status_label" />
                            </td>
                            <td>{{ $r->petugas ? $r->petugas->name : 'Menunggu penugasan' }}</td>
                            <td>
                                <a href="{{ route('admin.laporan.show', $r) }}" class="font-semibold text-emerald-800 hover:text-emerald-950">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-neutral-400">
                                Belum ada aduan sampah yang tercatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="table-pagination border-t border-neutral-100 px-4 py-3">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
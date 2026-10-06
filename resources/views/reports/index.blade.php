@extends('layouts.app', ['title' => 'Peta Risiko Lingkungan'])

@section('content')
<section class="space-y-6">
    <header>
        <h1 class="text-2xl font-bold text-neutral-900">Heatmap Risiko Wilayah</h1>
    </header>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-xl border border-neutral-200 bg-white p-4 shadow-xs">
            <p class="text-xs font-semibold text-neutral-500">Laporan aktif</p>
            <p class="mt-2 text-2xl font-bold text-neutral-900">{{ $riskSummary['active_reports'] }}</p>
        </article>
        <article class="rounded-xl border border-orange-200 bg-orange-50 p-4">
            <p class="text-xs font-semibold text-orange-800">Prioritas tinggi/kritis</p>
            <p class="mt-2 text-2xl font-bold text-orange-950">{{ $riskSummary['high_priority_reports'] }}</p>
        </article>
        <article class="rounded-xl border border-rose-200 bg-rose-50 p-4">
            <p class="text-xs font-semibold text-rose-800">Risiko kritis</p>
            <p class="mt-2 text-2xl font-bold text-rose-950">{{ $riskSummary['critical_reports'] }}</p>
        </article>
        <article class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
            <p class="text-xs font-semibold text-emerald-800">Wilayah terpantau</p>
            <p class="mt-2 text-2xl font-bold text-emerald-950">{{ $riskSummary['monitored_zones'] }}</p>
        </article>
    </div>

    <section class="space-y-3" aria-labelledby="risk-zones-heading">
        <div class="flex items-end justify-between gap-3">
            <div>
                <h2 id="risk-zones-heading" class="text-lg font-bold text-neutral-900">
                    Intensitas risiko per wilayah
                </h2>
            </div>
            <span class="text-xs font-semibold text-neutral-500">{{ $reports->count() }} laporan aktif</span>
        </div>

        @if ($riskZones->isNotEmpty())
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($riskZones as $zone)
            <article @class(['rounded-xl border p-4', 'border-rose-200 bg-rose-50'=> $zone['tone'] === 'rose',
                'border-orange-200 bg-orange-50' => $zone['tone'] === 'orange',
                'border-amber-200 bg-amber-50' => $zone['tone'] === 'amber',
                'border-emerald-200 bg-emerald-50' => $zone['tone'] === 'emerald',
                ])>
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-neutral-900">{{ $zone['name'] }}</h3>
                        <p class="mt-1 text-xs text-neutral-600">
                            {{ $zone['reports_count'] }} laporan aktif
                        </p>
                    </div>
                    <span class="rounded-full bg-white/80 px-2.5 py-1 text-xs font-bold text-neutral-800">
                        Skor {{ $zone['max_risk'] }}
                    </span>
                </div>
                <div
                    class="mt-4 h-2 overflow-hidden rounded-full bg-white/80"
                    role="meter"
                    aria-label="Skor risiko {{ $zone['name'] }}"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    aria-valuenow="{{ min(100, $zone['max_risk']) }}">
                    <div
                        @class(['h-full rounded-full', 'bg-rose-600'=> $zone['tone'] === 'rose',
                        'bg-orange-500' => $zone['tone'] === 'orange',
                        'bg-amber-500' => $zone['tone'] === 'amber',
                        'bg-emerald-600' => $zone['tone'] === 'emerald',
                        ])
                        style="width: {{ min(100, max(6, $zone['max_risk'])) }}%"
                        ></div>
                </div>
                <p class="mt-3 text-[11px] font-semibold text-neutral-600">
                    {{ $zone['critical_count'] }} laporan prioritas kritis
                </p>
            </article>
            @endforeach
        </div>
        @else
        <div class="rounded-xl border border-dashed border-neutral-300 bg-white px-5 py-8 text-center text-sm text-neutral-500">
            Belum ada laporan aktif untuk dipetakan.
        </div>
        @endif
    </section>

    <section class="overflow-hidden rounded-xl border border-neutral-200 bg-white" aria-labelledby="risk-map-heading">
        <header class="flex flex-wrap items-center justify-between gap-4 border-b border-neutral-200 px-5 py-4">
            <div>
                <h2 id="risk-map-heading" class="text-base font-bold text-neutral-900">Peta titik risiko</h2>
                <p class="mt-1 text-xs text-neutral-500">Pilih titik untuk melihat alamat dan tingkat risikonya.</p>
            </div>
            <div class="flex flex-wrap gap-x-4 gap-y-2 text-xs font-medium text-neutral-600" aria-label="Legenda skor risiko">
                <span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-full bg-emerald-600"></span>Rendah</span>
                <span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-full bg-amber-500"></span>Sedang</span>
                <span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-full bg-orange-500"></span>Tinggi</span>
                <span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-full bg-rose-600"></span>Kritis</span>
            </div>
        </header>
        <div
            data-risk-map
            data-points='{{ $mapPoints->toJson() }}'
            class="h-90 w-full bg-neutral-100 sm:h-110"
            role="application"
            aria-label="Peta interaktif titik laporan risiko"></div>
        @if ($mapPoints->isEmpty())
        <p class="border-t border-neutral-100 px-5 py-3 text-xs text-neutral-500" role="status">
            Belum ada laporan aktif dengan koordinat. Peta akan menampilkan titik setelah lokasi tersedia.
        </p>
        @endif
    </section>

    <div class="table-responsive overflow-hidden rounded-xl border border-neutral-200 bg-white">
        <table class="table-hover table">
            <thead>
                <tr>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Prioritas</th>
                    <th>Skor Risiko</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reports as $report)
                <tr>
                    <td>{{ $report->address }}</td>
                    <td>{{ $report->status }}</td>
                    <td>{{ $report->priority_level }}</td>
                    <td>{{ $report->risk_score }}</td>
                </tr>
                @empty
                <tr>
                    <td class="px-4 py-6 text-center text-neutral-500" colspan="4">Belum ada laporan aktif.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@endsection
@extends('layouts.app', ['title' => 'Rekap Dampak & Emisi'])

@section('content')
<section class="space-y-6">
    <header>
        <h1 class="text-2xl font-bold text-neutral-900">Rekap Dampak & Emisi</h1>
    </header>

    <div class="grid gap-4 sm:grid-cols-3">
        <article class="rounded-xl border border-emerald-200 bg-emerald-50 p-5">
            <p class="text-xs font-semibold text-emerald-800">Sampah berhasil ditangani</p>
            <p class="mt-2 text-xl font-bold text-emerald-950">
                {{ number_format((float) $impactTotals->total_weight_kg, 2) }} kg
            </p>
            <p class="mt-1 text-xs text-emerald-800">
                {{ number_format((float) $impactTotals->total_weight_kg / 1000, 3) }} ton
            </p>
        </article>
        <article class="rounded-xl border border-sky-200 bg-sky-50 p-5">
            <p class="text-xs font-semibold text-sky-800">Estimasi emisi dicegah</p>
            <p class="mt-2 text-xl font-bold text-sky-950">
                {{ number_format((float) $impactTotals->prevented_emissions_kg, 3) }} kg CO₂e
            </p>
        </article>
        <article class="rounded-xl border border-amber-200 bg-amber-50 p-5">
            <p class="text-xs font-semibold text-amber-800">Risiko pembakaran dicegah</p>
            <p class="mt-2 text-xl font-bold text-amber-950">
                {{ number_format((int) $impactTotals->prevented_burning_count) }} lokasi
            </p>
        </article>
    </div>

    <section class="rounded-2xl border border-neutral-200 bg-white p-5" aria-labelledby="channel-heading">
        <header>
            <h2 id="channel-heading" class="text-base font-bold text-neutral-900">Penyaluran berdasarkan berat</h2>
            <p class="mt-1 text-xs text-neutral-500">Persentase dihitung dari berat yang memiliki saluran pengolahan.</p>
        </header>

        <div class="mt-5 space-y-4">
            @foreach ($channelDistribution as $channel)
            <div>
                <div class="mb-1 flex items-center justify-between gap-4 text-sm">
                    <span class="font-medium text-neutral-700">{{ $channel['label'] }}</span>
                    <span class="text-neutral-500">
                        {{ number_format($channel['percentage'], 2) }}%
                        ({{ number_format($channel['weight_kg'], 2) }} kg)
                    </span>
                </div>
                <progress
                    class="h-2 w-full overflow-hidden rounded-full accent-emerald-600"
                    value="{{ min(100, max(0, $channel['percentage'])) }}"
                    max="100">
                    {{ number_format($channel['percentage'], 2) }}%
                </progress>
            </div>
            @endforeach
        </div>
    </section>
</section>
@endsection
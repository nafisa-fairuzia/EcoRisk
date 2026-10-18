@extends('layouts.guest', ['title' => 'Transparansi Dampak Lingkungan - EcoRisk'])

@section('content')
    <header class="border-b border-neutral-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="text-lg font-bold text-emerald-800">EcoRisk</a>
            <span class="text-sm font-medium text-neutral-600">Transparansi dampak lingkungan</span>
        </div>
    </header>

    <main class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        <section>
            <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">Dampak Pengelolaan Sampah</h1>
            <p class="mt-2 max-w-3xl text-sm text-neutral-600">
                Ringkasan sampah yang telah dipilah, estimasi emisi yang dicegah, serta risiko lingkungan yang berhasil
                ditangani.
            </p>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <article class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-neutral-500">Sampah berhasil ditangani</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($totalWeightKg, 2) }} kg</p>
                <p class="mt-1 text-sm text-neutral-500">{{ number_format($totalWeightTon, 3) }} ton</p>
            </article>
            <article class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-neutral-500">Estimasi emisi dicegah</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($preventedEmissionsKg, 3) }} kg CO₂e</p>
            </article>
            <article class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-neutral-500">Risiko pembakaran dicegah</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($preventedBurningCount) }} lokasi</p>
            </article>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <article class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold">Penyaluran berdasarkan berat</h2>
                <p class="mt-1 text-sm text-neutral-500">
                    Persentase dihitung dari berat yang memiliki saluran penyaluran.
                </p>
                <div class="mt-5 space-y-4">
                    @foreach ($channelDistribution as $channel)
                        <div>
                            <div class="mb-1 flex items-center justify-between gap-4 text-sm">
                                <span class="font-medium">{{ $channel['label'] }}</span>
                                <span class="text-neutral-600">
                                    {{ number_format($channel['percentage'], 2) }}% ({{ number_format($channel['weight_kg'], 2) }} kg)
                                </span>
                            </div>
                            <progress
                                class="h-2 w-full overflow-hidden rounded-full accent-emerald-600"
                                value="{{ min(100, max(0, $channel['percentage'])) }}"
                                max="100"
                            >
                                {{ number_format($channel['percentage'], 2) }}%
                            </progress>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="border border-neutral-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold">Lokasi terbaru yang ditangani</h2>
                <div class="mt-4" data-table-container data-filter-column="2">
                <x-table-tools filter-label="prioritas" />
                <div class="table-responsive mt-4">
                    <table class="table table-hover" data-filter-table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th class="py-3 pr-3">Lokasi</th>
                                <th class="py-3 pr-3">Prioritas risiko</th>
                                <th class="py-3">Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentHandledReports as $report)
                                <tr>
                                    <td>{{ $recentHandledReports->firstItem() + $loop->index }}</td>
                                    <td>
                                        <div class="font-medium">{{ $report->address }}</div>
                                        @if ($report->latitude !== null && $report->longitude !== null)
                                            <div class="mt-1 text-xs text-neutral-500">
                                                {{ $report->latitude }}, {{ $report->longitude }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <x-report-badge type="priority" :value="$report->priority_level" :label="$report->priority_label" />
                                    </td>
                                    <td>{{ $report->risk_score }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-neutral-500">
                                        Belum ada laporan selesai yang tercatat memiliki hasil pemilahan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="table-pagination border-t border-neutral-100 px-4 py-3">
                    {{ $recentHandledReports->links() }}
                </div>
                </div>
            </article>
        </section>
    </main>

    <footer class="mt-10 border-t border-neutral-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-5 text-xs text-neutral-500 sm:px-6 lg:px-8">
            Statistik dihitung dari catatan pemilahan yang tersimpan di sistem.
        </div>
    </footer>
@endsection

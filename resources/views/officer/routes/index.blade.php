@extends('layouts.app', ['title' => 'Peta Rute Pengangkutan'])

@section('content')
<section class="space-y-6">
    <header class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900">Peta Rute Pengangkutan</h1>
        </div>
        <a
            href="{{ route('petugas.pickups.index') }}"
            class="rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-50">Kembali ke antrean</a>
    </header>

    <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-xs">
        <div class="table-responsive">
            <table class="table table-hover min-w-180">
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Lokasi</th>
                        <th>Prioritas</th>
                        <th>Skor risiko</th>
                        <th>Status</th>
                        <th>Navigasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($routeReports as $report)
                    <tr>
                        <td class="font-mono font-bold text-neutral-500">
                            {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="max-w-sm font-medium text-neutral-900">{{ $report->address }}</td>
                        <td class="capitalize">{{ $report->priority_level }}</td>
                        <td class="font-semibold">{{ $report->risk_score }}</td>
                        <td>{{ str_replace('_', ' ', $report->status) }}</td>
                        <td>
                            <a
                                href="https://www.google.com/maps/dir/?api=1&amp;destination={{ $report->latitude !== null && $report->longitude !== null ? urlencode($report->latitude.','.$report->longitude) : urlencode($report->address) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center rounded-lg bg-emerald-700 px-3 py-2 text-xs font-bold text-white transition hover:bg-emerald-800">Buka navigasi</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="py-8 text-center text-neutral-500" colspan="6">
                            Belum ada lokasi aktif untuk dirutekan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
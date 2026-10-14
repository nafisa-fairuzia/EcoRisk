@extends('layouts.app', ['title' => 'Antrean Pemilahan'])

@section('content')
<section class="space-y-6">
    <header class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900">Antrean Pemilahan</h1>
        </div>
        <a
            href="{{ route('petugas.pickups.index') }}"
            class="rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-50">
            Kembali ke antrean angkut
        </a>
    </header>

    <div class="border border-neutral-200 bg-white shadow-xs">
        <div data-table-container data-filter-column="3">
            <x-table-tools filter-label="prioritas" />
        <div class="table-responsive">
            <table class="table table-hover" data-filter-table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Lokasi</th>
                        <th>Skor risiko</th>
                        <th>Prioritas</th>
                        <th>Tanggal selesai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                    <tr>
                        <td>{{ $reports->firstItem() + $loop->index }}</td>
                        <td class="max-w-md font-medium text-neutral-900">{{ $report->address }}</td>
                        <td>
                            <span class="inline-flex items-center rounded-md bg-neutral-100 px-2.5 py-1 text-xs font-medium text-neutral-800">
                                {{ $report->risk_score }}
                            </span>
                        </td>
                        <td>
                            <x-report-badge type="priority" :value="$report->priority_level" :label="$report->priority_label" />
                        </td>
                        <td>{{ $report->updated_at->translatedFormat('d M Y') }}</td>
                        <td>
                            <a
                                href="{{ route('petugas.sortings.create', $report->id) }}"
                                class="inline-flex items-center rounded-lg bg-emerald-700 px-3 py-2 text-xs font-bold text-white transition hover:bg-emerald-800">
                                Catat pemilahan
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="py-8 text-center text-neutral-500" colspan="6">
                            Tidak ada laporan selesai yang menunggu pemilahan.
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
</section>
@endsection
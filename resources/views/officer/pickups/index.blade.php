@extends('layouts.app', ['title' => 'Antrean Pengangkutan'])

@section('content')
<section class="space-y-5">
    <header class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900">Antrean Pengangkutan</h1>
        </div>
        <a
            href="{{ route('petugas.routes.index') }}"
            class="rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-50">
            Buka peta rute
        </a>
    </header>

    <div class="border border-neutral-200 bg-white shadow-xs">
        <div data-table-container data-filter-column="3">
            <x-table-tools filter-label="status" />
            <div class="table-responsive">
                <table class="table table-hover" data-filter-table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode laporan</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Skor risiko</th>
                            <th>Prioritas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                        <tr>
                            <td>{{ $reports->firstItem() + $loop->index }}</td>
                            <td class="font-mono font-semibold text-neutral-900">{{ $report->kode_laporan ?: 'Laporan #'.$report->id }}</td>
                            <td class="max-w-md font-medium text-neutral-900">{{ $report->alamat ?: $report->address }}</td>
                            <td><x-report-badge type="status" :value="$report->status" :label="$report->status_label" /></td>
                            <td>{{ $report->risk_score }}</td>
                            <td><x-report-badge type="priority" :value="$report->priority_level" :label="$report->priority_label" /></td>
                            <td>
                                <div class="flex flex-wrap items-center gap-2">
                                    <a
                                        href="{{ route('petugas.laporan.show', $report) }}"
                                        class="font-semibold text-emerald-800 hover:text-emerald-950">
                                        Detail
                                    </a>
                                    @if (in_array($report->status, ['reported', 'approved'], true))
                                    <form method="POST" action="{{ route('petugas.pickups.update-status', $report->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="in_process" />
                                        <button type="submit" class="rounded-lg bg-emerald-700 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-800">
                                            Mulai angkut
                                        </button>
                                    </form>
                                    @else
                                    <form method="POST" action="{{ route('petugas.pickups.update-status', $report->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed" />
                                        <button type="submit" class="rounded-lg bg-blue-700 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-800">
                                            Tandai selesai
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-neutral-500">Belum ada laporan yang perlu diangkut.</td>
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

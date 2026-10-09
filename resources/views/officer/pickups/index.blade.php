@extends('layouts.app', ['title' => 'Antrean Pengangkutan'])

@section('content')
<section class="space-y-8">
    <header>
        <h1 class="text-2xl font-bold text-neutral-900">Antrean Pengangkutan</h1>
    </header>

    <div class="space-y-4">
        <h2 class="text-lg font-semibold text-neutral-900">Laporan menunggu penanganan</h2>
        <div class="table-responsive rounded-xl border border-neutral-200 bg-white">
            <table class="table-hover table">
                <thead>
                    <tr>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Skor risiko</th>
                        <th>Prioritas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                    <tr>
                        <td>{{ $report->address }}</td>
                        <td>{{ $report->status }}</td>
                        <td>{{ $report->risk_score }}</td>
                        <td>{{ $report->priority_level }}</td>
                        <td>
                            <form
                                method="POST"
                                action="{{ route('petugas.pickups.update-status', $report->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="in_process" />
                                <button
                                    class="rounded-lg bg-emerald-600 px-3 py-2 text-white hover:bg-emerald-700"
                                    type="submit">
                                    Mulai angkut
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="px-4 py-6 text-center text-neutral-500" colspan="5">
                            Tidak ada laporan yang menunggu penanganan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-4">
        <h2 class="text-lg font-semibold text-neutral-900">Pengangkutan berlangsung</h2>
        <div class="table-responsive rounded-xl border border-neutral-200 bg-white">
            <table class="table-hover table">
                <thead>
                    <tr>
                        <th>Alamat</th>
                        <th>Skor risiko</th>
                        <th>Prioritas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inProcessReports as $report)
                    <tr>
                        <td>{{ $report->address }}</td>
                        <td>{{ $report->risk_score }}</td>
                        <td>{{ $report->priority_level }}</td>
                        <td>
                            <div class="flex flex-wrap gap-2">
                                <form
                                    method="POST"
                                    action="{{ route('petugas.pickups.update-status', $report->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed" />
                                    <button
                                        class="rounded-lg bg-blue-600 px-3 py-2 text-white hover:bg-blue-700"
                                        type="submit">
                                        Tandai selesai
                                    </button>
                                </form>
                                <a
                                    href="{{ route('petugas.sortings.create', $report->id) }}"
                                    class="rounded-lg border border-emerald-600 px-3 py-2 text-emerald-700 hover:bg-emerald-50">
                                    Catat pemilahan
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="px-4 py-6 text-center text-neutral-500" colspan="4">
                            Tidak ada pengangkutan yang sedang berlangsung.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</section>
@endsection
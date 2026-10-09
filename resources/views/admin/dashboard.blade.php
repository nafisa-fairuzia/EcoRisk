@extends('layouts.app', ['title' => 'Pusat Kendali Administrasi - SiklusSampah'])

@section('content')
<div class="space-y-6">
    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-neutral-100 font-bold text-neutral-800">
                    {{ auth()->user()->initials }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-lg font-bold text-slate-900">Dashboard Administrator</h1>
                        <span class="rounded-md bg-neutral-100 px-2 py-0.5 text-xs font-bold text-neutral-700">
                            Pusat kendali
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs text-slate-500">
                        <strong class="text-slate-700">{{ auth()->user()->name }}</strong> · {{ $totalPetugas }} petugas aktif di Kelurahan Sukamaju
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3 lg:border-t-0 lg:pt-0">
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
                <a
                    href="{{ route('admin.heatmap') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2 text-xs font-bold text-white transition hover:bg-emerald-800 active:scale-95"
                >Buka Heatmap Risiko</a>
            </div>
        </div>
    </div>

    <!-- 4 Metrik Kartu Statistik -->
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-2xs">
            <span class="text-xs font-medium text-neutral-500">Tingkat Penyelesaian</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-mono text-2xl font-bold text-neutral-900">{{ $completionRate }}%</span>
                <span class="text-xs text-neutral-500">({{ $completedReports }}/{{ $totalReports }} aduan)</span>
            </div>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-2xs">
            <span class="text-xs font-medium text-neutral-500">Petugas Lapangan</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-mono text-2xl font-bold text-emerald-700">{{ $totalPetugas }}</span>
                <span class="text-xs text-neutral-500">Personel aktif</span>
            </div>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-2xs">
            <span class="text-xs font-medium text-neutral-500">Total Sampah Terpilah</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-mono text-2xl font-bold text-blue-700">{{ number_format($totalSortedKg, 1) }}</span>
                <span class="text-xs text-neutral-500">kg masuk TPS3R</span>
            </div>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-2xs">
            <span class="text-xs font-medium text-neutral-500">Tersalurkan ke Mitra</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-mono text-2xl font-bold text-purple-700">{{ number_format($totalDistributedKg, 1) }}</span>
                <span class="text-xs text-neutral-500">kg didaur ulang</span>
            </div>
        </div>
    </div>

    <!-- Tabel Monitoring Aduan Wilayah -->
    <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-xs">
        <div class="flex items-center justify-between border-b border-neutral-100 p-5">
            <h2 class="text-base font-bold text-neutral-900">Rekap Aduan Sampah Seluruh Wilayah</h2>
            <span class="text-xs text-neutral-500">{{ $totalReports }} total aduan tercatat</span>
        </div>

        <div class="table-responsive">
            <table class="table-hover table">
                <thead>
                    <tr>
                        <th>Kode laporan</th>
                        <th>Pelapor & Alamat</th>
                        <th>Jenis Sampah</th>
                        <th>Urgensi</th>
                        <th>Status</th>
                        <th>Petugas Penangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $r)
                    <tr>
                        <td class="font-mono font-bold text-neutral-900">{{ $r->kode_laporan }}</td>
                        <td>
                            <div class="font-bold text-neutral-900">{{ $r->nama_pelapor }}</div>
                            <div class="text-[11px] text-neutral-500">
                                {{ $r->alamat }} (RT {{ $r->rt }}/RW {{ $r->rw }})
                            </div>
                        </td>
                        <td>{{ $r->jenis_tumpukan }}</td>
                        <td>
                            <span
                                class="px-2 py-0.5 rounded font-bold uppercase text-[10px]
                                    {{ $r->urgensi === 'tinggi' ? 'bg-red-100 text-red-800' : ($r->urgensi === 'sedang' ? 'bg-amber-100 text-amber-800' : 'bg-neutral-100 text-neutral-700') }}">
                                {{ $r->urgensi }}
                            </span>
                        </td>
                        <td>
                            <span
                                class="px-2 py-0.5 rounded font-bold text-[10px]
                                    {{ $r->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($r->status === 'in_process' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ str_replace('_', ' ', ucfirst($r->status)) }}
                            </span>
                        </td>
                        <td>{{ $r->petugas ? $r->petugas->name : 'Menunggu penugasan' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-neutral-400">
                            Belum ada aduan sampah yang tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

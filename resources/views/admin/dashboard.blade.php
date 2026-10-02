@extends('layouts.app', ['title' => 'Pusat Kendali Administrasi - SiklusSampah'])

@section('content')
<div class="space-y-6">
<div class="landing-page">
    <h1>Halo Friend</h1>
</div>
<!-- Header Banner -->
    <div class="p-6 rounded-2xl bg-white border border-neutral-200 shadow-xs">
        <div class="flex items-center gap-2 text-xs font-bold text-neutral-500 uppercase tracking-wider mb-1">
            <span class="w-2 h-2 rounded-full bg-neutral-900"></span>
            Pusat Kendali Administrasi Kelurahan
        </div>
        <h1 class="text-2xl font-bold text-neutral-900">Dashboard Pengelolaan Sistem</h1>
        <p class="text-sm text-neutral-500 mt-1">
            Monitoring seluruh aduan wilayah, manajemen personil petugas lapangan, master kategori sampah, dan rekapitulasi audit.
        </p>
        <div class="mt-3 inline-block px-3 py-1 bg-neutral-100 border border-neutral-200 rounded-lg text-xs font-semibold text-neutral-700">
            Wilayah: Kelurahan Sukamaju · Total Personel Petugas: {{ $totalPetugas }} orang
        </div>
    </div>

    <!-- 4 Metrik Kartu Statistik -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-white border border-neutral-200 shadow-2xs">
            <span class="text-xs text-neutral-500 font-medium">Tingkat Penyelesaian</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-neutral-900 font-mono">{{ $completionRate }}%</span>
                <span class="text-xs text-neutral-500">({{ $completedReports }}/{{ $totalReports }} aduan)</span>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white border border-neutral-200 shadow-2xs">
            <span class="text-xs text-neutral-500 font-medium">Petugas Lapangan</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-emerald-700 font-mono">{{ $totalPetugas }}</span>
                <span class="text-xs text-neutral-500">Personel aktif</span>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white border border-neutral-200 shadow-2xs">
            <span class="text-xs text-neutral-500 font-medium">Total Sampah Terpilah</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-blue-700 font-mono">{{ number_format($totalSortedKg, 1) }}</span>
                <span class="text-xs text-neutral-500">kg masuk TPS3R</span>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white border border-neutral-200 shadow-2xs">
            <span class="text-xs text-neutral-500 font-medium">Tersalurkan ke Mitra</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-purple-700 font-mono">{{ number_format($totalDistributedKg, 1) }}</span>
                <span class="text-xs text-neutral-500">kg didaur ulang</span>
            </div>
        </div>
    </div>

    <!-- Tabel Monitoring Aduan Wilayah -->
    <div class="bg-white rounded-2xl border border-neutral-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-neutral-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-neutral-900">Rekap Aduan Sampah Seluruh Wilayah</h2>
            <span class="text-xs text-neutral-500">{{ $totalReports }} total aduan tercatat</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-neutral-600">
                <thead class="bg-neutral-50 text-neutral-700 uppercase font-semibold text-[10px] border-b border-neutral-200">
                    <tr>
                        <th class="p-3.5">ID / Waktu</th>
                        <th class="p-3.5">Pelapor & Alamat</th>
                        <th class="p-3.5">Jenis Sampah</th>
                        <th class="p-3.5">Urgensi</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5">Petugas Penangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($reports as $r)
                        <tr class="hover:bg-neutral-50/50">
                            <td class="p-3.5 font-mono font-bold text-neutral-900">{{ $r->kode_laporan }}</td>
                            <td class="p-3.5">
                                <div class="font-bold text-neutral-900">{{ $r->nama_pelapor }}</div>
                                <div class="text-[11px] text-neutral-500">{{ $r->alamat }} (RT {{ $r->rt }}/RW {{ $r->rw }})</div>
                            </td>
                            <td class="p-3.5">{{ $r->jenis_tumpukan }}</td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded font-bold uppercase text-[10px]
                                    {{ $r->urgensi === 'tinggi' ? 'bg-red-100 text-red-800' : ($r->urgensi === 'sedang' ? 'bg-amber-100 text-amber-800' : 'bg-neutral-100 text-neutral-700') }}">
                                    {{ $r->urgensi }}
                                </span>
                            </td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded font-bold text-[10px]
                                    {{ $r->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($r->status === 'proses' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ ucfirst($r->status) }}
                                </span>
                            </td>
                            <td class="p-3.5">{{ $r->petugas ? $r->petugas->name : 'Menunggu penugasan' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-neutral-400">Belum ada aduan sampah yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

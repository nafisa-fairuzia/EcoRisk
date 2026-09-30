@extends('layouts.app', ['title' => 'Portal Petugas Lapangan - SiklusSampah'])

@section('content')
<div class="space-y-6">

    <!-- Header Petugas -->
    <div class="p-6 rounded-2xl bg-white border border-neutral-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-emerald-600 flex items-center justify-center font-bold text-white text-base">
                {{ auth()->user()->initials }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-neutral-900">Ruang Kerja Petugas Lapangan</h1>
                    <span class="text-[11px] font-semibold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full">
                        Shift Pagi: {{ auth()->user()->zona_wilayah ?? 'Zona A' }}
                    </span>
                </div>
                <p class="text-xs text-neutral-500 mt-0.5">
                    Petugas: <strong>{{ auth()->user()->name }}</strong> · Armada Roda 3 TPS3R Mandiri
                </p>
            </div>
        </div>
        <div class="text-xs text-neutral-500 bg-neutral-50 px-3 py-2 rounded-xl border border-neutral-200">
            Tanggal: {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <!-- Ringkasan Operasional -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200">
            <span class="text-xs font-semibold text-amber-800">Menunggu Penanganan</span>
            <div class="text-2xl font-bold font-mono text-amber-950 mt-1">{{ $pendingReports->count() }} Laporan</div>
        </div>

        <div class="p-4 rounded-xl bg-blue-50 border border-blue-200">
            <span class="text-xs font-semibold text-blue-800">Dalam Pengerjaan</span>
            <div class="text-2xl font-bold font-mono text-blue-950 mt-1">{{ $inProgressReports->count() }} Armada</div>
        </div>

        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200">
            <span class="text-xs font-semibold text-emerald-800">Sampah Terpilah</span>
            <div class="text-2xl font-bold font-mono text-emerald-950 mt-1">{{ number_format($totalSortedKg, 1) }} kg</div>
        </div>

        <div class="p-4 rounded-xl bg-purple-50 border border-purple-200">
            <span class="text-xs font-semibold text-purple-800">Penyaluran Mitra</span>
            <div class="text-2xl font-bold font-mono text-purple-950 mt-1">{{ number_format($totalDistributedKg, 1) }} kg</div>
        </div>
    </div>

    <!-- Daftar Tugas Laporan Perlu Penanganan -->
    <div class="bg-white rounded-2xl border border-neutral-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-neutral-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-neutral-900">Daftar Pengaduan Masuk (Perlu Tindak Lanjut)</h2>
            <span class="text-xs text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full font-bold">
                {{ $pendingReports->count() }} Tugas Menunggu
            </span>
        </div>

        <div class="divide-y divide-neutral-100">
            @forelse($pendingReports as $task)
                <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-neutral-50/50">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-neutral-900 text-sm">{{ $task->kode_laporan }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                {{ $task->urgensi === 'tinggi' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800' }}">
                                Urgensi {{ $task->urgensi }}
                            </span>
                        </div>
                        <div class="text-xs text-neutral-700 font-semibold">
                            Lokasi: {{ $task->alamat }} (RT {{ $task->rt }} / RW {{ $task->rw }})
                        </div>
                        <div class="text-xs text-neutral-500">
                            Jenis: <strong>{{ $task->jenis_tumpukan }}</strong> · "{{ $task->deskripsi }}"
                        </div>
                    </div>

                    <!-- Tombol Aksi Cepat Petugas -->
                    <div class="flex items-center gap-2 shrink-0">
                        <!-- Mulai Angkut -->
                        <form action="{{ route('petugas.laporan.update-status', $task->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="proses">
                            <input type="hidden" name="catatan_petugas" value="Armada roda 3 meluncur menuju lokasi aduan warga.">
                            <button type="submit" class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors cursor-pointer">
                                Luncurkan Armada
                            </button>
                        </form>

                        <!-- Selesaikan Langsung -->
                        <form action="{{ route('petugas.laporan.update-status', $task->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="selesai">
                            <input type="hidden" name="catatan_petugas" value="Tumpukan sampah telah berhasil dibersihkan dan diangkut ke TPS3R.">
                            <button type="submit" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors cursor-pointer">
                                Tandai Selesai
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-neutral-400 text-xs">
                    Tidak ada aduan tertunda saat ini. Seluruh laporan telah ditangani dengan baik!
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

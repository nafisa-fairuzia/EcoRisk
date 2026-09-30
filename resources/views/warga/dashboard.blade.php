@extends('layouts.app', ['title' => 'Portal Layanan Warga - SiklusSampah'])

@section('content')
<div class="space-y-6">

    <!-- Header Portal Warga -->
    <div class="p-6 rounded-2xl bg-white border border-neutral-200 shadow-xs">
        <h1 class="text-xl font-bold text-neutral-900">Portal Pengaduan & Layanan Kebersihan Warga</h1>
        <p class="text-xs text-neutral-500 mt-1">
            Laporkan tumpukan sampah liar atau TPS penuh di lingkungan Anda secara langsung ke petugas kebersihan desa.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Formulir Buat Aduan Baru (Kiri) -->
        <div class="lg:col-span-1 bg-white p-5 rounded-2xl border border-neutral-200 shadow-xs">
            <h2 class="text-base font-bold text-neutral-900 mb-4">Buat Pengaduan Baru</h2>

            <form action="{{ route('warga.laporan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-neutral-700 mb-1">Alamat Lengkap</label>
                    <input type="text" name="alamat" required placeholder="Jl. Melati No. 12" class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300 focus:ring-1 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 mb-1">RT</label>
                        <input type="text" name="rt" required placeholder="02" class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 mb-1">RW</label>
                        <input type="text" name="rw" required placeholder="04" class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-neutral-700 mb-1">Jenis Sampah</label>
                    <select name="jenis_tumpukan" class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300">
                        <option value="Sampah Domestik Campuran">Sampah Domestik Campuran</option>
                        <option value="Limbah Ranting & Daun Tebangan">Limbah Ranting & Daun Tebangan</option>
                        <option value="Tumpukan Plastik Liar">Tumpukan Plastik Liar</option>
                        <option value="Karton & Kardus Menumpuk">Karton & Kardus Menumpuk</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-neutral-700 mb-1">Tingkat Urgensi</label>
                    <select name="urgensi" class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300">
                        <option value="sedang">Sedang (Dalam 24 Jam)</option>
                        <option value="tinggi">Tinggi (Menimbulkan Bau / Banjir)</option>
                        <option value="rendah">Rendah (Pembersihan Berkala)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-neutral-700 mb-1">Deskripsi Tambahan</label>
                    <textarea name="deskripsi" rows="3" required placeholder="Jelaskan kondisi dan patokan lokasi..." class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition-colors cursor-pointer">
                    Kirim Aduan Sekarang
                </button>
            </form>
        </div>

        <!-- Daftar Riwayat Aduan Saya (Kanan) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-neutral-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-neutral-100 flex items-center justify-between">
                <h2 class="text-base font-bold text-neutral-900">Riwayat Aduan Saya</h2>
                <span class="text-xs text-neutral-500">{{ $totalMyReports }} Aduan Terkirim</span>
            </div>

            <div class="divide-y divide-neutral-100">
                @forelse($myReports as $report)
                    <div class="p-4 sm:p-5 flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-xs text-neutral-900">{{ $report->kode_laporan }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold
                                    {{ $report->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($report->status === 'proses' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                    Status: {{ ucfirst($report->status) }}
                                </span>
                            </div>
                            <div class="text-xs font-semibold text-neutral-800">{{ $report->alamat }} (RT {{ $report->rt }}/RW {{ $report->rw }})</div>
                            <p class="text-xs text-neutral-500">"{{ $report->deskripsi }}"</p>
                            @if($report->catatan_petugas)
                                <div class="text-[11px] text-emerald-800 bg-emerald-50 p-2 rounded-lg mt-2 border border-emerald-100">
                                    <strong>Catatan Petugas:</strong> {{ $report->catatan_petugas }}
                                </div>
                            @endif
                        </div>
                        <span class="text-[10px] text-neutral-400 shrink-0">{{ $report->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="p-8 text-center text-neutral-400 text-xs">
                        Anda belum pernah membuat laporan aduan sampah. Lingkungan Anda bersih!
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

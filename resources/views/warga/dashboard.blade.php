@extends('layouts.app', ['title' => 'Portal Layanan Warga - SiklusSampah'])

@section('content')
<div class="space-y-6">
    @if ($section === 'dashboard')
    <section class="space-y-5">
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 font-bold text-emerald-800">
                        {{ auth()->user()->initials }}
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-lg font-bold text-slate-900">Dashboard Warga</h1>
                            <span class="rounded-md bg-emerald-100 px-2 py-0.5 text-xs font-bold text-emerald-800">
                                {{ auth()->user()->zona_wilayah ?? 'Warga Sukamaju' }}
                            </span>
                        </div>
                        <p class="mt-0.5 text-xs text-slate-500">
                            <strong class="text-slate-700">{{ auth()->user()->name }}</strong> · Ringkasan laporan dan tindak lanjut lingkungan Anda
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3 lg:border-t-0 lg:pt-0">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                    <a
                        href="{{ route('warga.dashboard', ['section' => 'create']) }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2 text-xs font-bold text-white transition hover:bg-emerald-800 active:scale-95"
                    >Buat Laporan</a>
                </div>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <article class="rounded-xl border border-neutral-200 bg-white p-5">
                <p class="text-xs font-semibold text-neutral-500">Total laporan</p>
                <p class="mt-2 text-2xl font-bold text-neutral-900">{{ $totalMyReports }}</p>
            </article>
            <article class="rounded-xl border border-amber-200 bg-amber-50 p-5">
                <p class="text-xs font-semibold text-amber-800">Menunggu penanganan</p>
                <p class="mt-2 text-2xl font-bold text-amber-950">{{ $pendingCount }}</p>
            </article>
            <article class="rounded-xl border border-emerald-200 bg-emerald-50 p-5">
                <p class="text-xs font-semibold text-emerald-800">Selesai ditangani</p>
                <p class="mt-2 text-2xl font-bold text-emerald-950">{{ $completedCount }}</p>
            </article>
        </div>

        <div class="flex flex-wrap gap-3">
            <a
                href="{{ route('warga.dashboard', ['section' => 'create']) }}"
                class="rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-800">Buat laporan</a>
            <a
                href="{{ route('warga.dashboard', ['section' => 'reports']) }}"
                class="rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-sm font-bold text-neutral-700 transition hover:bg-neutral-50">Lihat status laporan</a>
        </div>
    </section>
    @elseif ($section === 'create')
    <section class="space-y-5">
        <header>
            <h1 class="text-2xl font-bold text-neutral-900">Buat Laporan Risiko</h1>
        </header>

        <div
            id="buat-laporan"
            class="w-full rounded-2xl border border-neutral-200 bg-white p-5 shadow-xs sm:p-7 lg:p-8">
            <form
                action="{{ route('warga.laporan.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="grid gap-x-6 gap-y-5 md:grid-cols-2">
                @csrf
                <div class="md:col-span-2">
                    <label for="alamat" class="mb-1.5 block text-sm font-semibold text-neutral-700">Alamat lengkap</label>
                    <input
                        type="text"
                        name="alamat"
                        id="alamat"
                        required
                        value="{{ old('alamat') }}"
                        placeholder="Jl. Melati No. 12"
                        class="w-full rounded-lg border border-neutral-300 px-3.5 py-3 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                </div>

                <div class="grid grid-cols-2 gap-4 md:col-span-2">
                    <div>
                        <label for="rt" class="mb-1.5 block text-sm font-semibold text-neutral-700">RT</label>
                        <input
                            type="text"
                            name="rt"
                            id="rt"
                            required
                            value="{{ old('rt') }}"
                            placeholder="02"
                            class="w-full rounded-lg border border-neutral-300 px-3.5 py-3 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                    </div>
                    <div>
                        <label for="rw" class="mb-1.5 block text-sm font-semibold text-neutral-700">RW</label>
                        <input
                            type="text"
                            name="rw"
                            id="rw"
                            required
                            value="{{ old('rw') }}"
                            placeholder="04"
                            class="w-full rounded-lg border border-neutral-300 px-3.5 py-3 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                    </div>
                </div>

                <div>
                    <label for="jenis_tumpukan" class="mb-1.5 block text-sm font-semibold text-neutral-700">Jenis sampah</label>
                    <select
                        name="jenis_tumpukan"
                        id="jenis_tumpukan"
                        required
                        class="w-full rounded-lg border border-neutral-300 px-3.5 py-3 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none">
                        <option value="Sampah Domestik Campuran">Sampah Domestik Campuran</option>
                        <option value="Limbah Ranting & Daun Tebangan">Limbah Ranting & Daun Tebangan</option>
                        <option value="Tumpukan Plastik Liar">Tumpukan Plastik Liar</option>
                        <option value="Karton & Kardus Menumpuk">Karton & Kardus Menumpuk</option>
                    </select>
                </div>

                <div>
                    <label for="urgensi" class="mb-1.5 block text-sm font-semibold text-neutral-700">Tingkat urgensi</label>
                    <select
                        name="urgensi"
                        id="urgensi"
                        required
                        class="w-full rounded-lg border border-neutral-300 px-3.5 py-3 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none">
                        <option value="sedang">Sedang (Dalam 24 Jam)</option>
                        <option value="tinggi">Tinggi (Menimbulkan Bau / Banjir)</option>
                        <option value="rendah">Rendah (Pembersihan Berkala)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="deskripsi" class="mb-1.5 block text-sm font-semibold text-neutral-700">Deskripsi tambahan</label>
                    <textarea
                        name="deskripsi"
                        id="deskripsi"
                        rows="3"
                        required
                        class="w-full rounded-lg border border-neutral-300 px-3.5 py-3 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="foto" class="mb-1.5 block text-sm font-semibold text-neutral-700">Foto lokasi <span class="font-normal text-neutral-500">(opsional, maksimal 5 MB)</span></label>
                    <input
                        type="file"
                        name="foto"
                        id="foto"
                        accept="image/*"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-3.5 py-3 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-emerald-800 hover:file:bg-emerald-100 focus:border-emerald-600 focus:outline-none" />
                </div>

                <button
                    type="submit"
                    class="w-full cursor-pointer rounded-lg bg-emerald-700 px-5 py-3.5 text-sm font-bold text-white transition hover:bg-emerald-800 focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 focus:outline-none md:col-span-2">
                    Kirim aduan
                </button>
            </form>
        </div>
    </section>
    @else
    <section class="space-y-5">
        <header>
            <div class="flex items-center justify-between gap-3">
                <h1 class="text-2xl font-bold text-neutral-900">Status & Riwayat Aduan</h1>

                <span class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-neutral-600">
                    {{ $totalMyReports }} laporan
                </span>
            </div>

        </header>

        <div id="laporan-saya" class="space-y-4">
            <div class="grid gap-4 xl:grid-cols-2">
                @forelse ($myReports as $report)
                <article class="flex h-full flex-col gap-4 rounded-xl border border-neutral-200 bg-white p-5 shadow-xs">
                    <header class="flex flex-wrap items-start justify-between gap-3 border-b border-neutral-100 pb-4">
                        <div class="min-w-0">
                            <p class="text-[11px] font-semibold text-neutral-500">Kode laporan</p>
                            <h3 class="mt-1 font-mono text-sm font-bold text-neutral-900">{{ $report->kode_laporan }}</h3>
                        </div>
                        <span @class([ 'inline-flex shrink-0 items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset' , 'bg-emerald-50 text-emerald-700 ring-emerald-600/15'=> $report->status === 'completed',
                            'bg-blue-50 text-blue-700 ring-blue-600/15' => $report->status === 'in_process',
                            'bg-sky-50 text-sky-700 ring-sky-600/15' => $report->status === 'approved',
                            'bg-rose-50 text-rose-700 ring-rose-600/15' => $report->status === 'rejected',
                            'bg-amber-50 text-amber-700 ring-amber-600/15' => in_array($report->status, ['reported', 'pending'], true),
                            ])>
                            {{ match ($report->status) {
                                            'approved' => 'Disetujui',
                                            'in_process' => 'Dalam proses',
                                            'completed' => 'Selesai',
                                            'rejected' => 'Ditolak',
                                            default => 'Menunggu',
                                        } }}
                        </span>
                    </header>

                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div class="min-w-0">
                            <dt class="text-[11px] font-semibold text-neutral-500">Lokasi</dt>
                            <dd class="mt-1 text-sm font-medium leading-5 text-neutral-800">
                                {{ $report->alamat ?: $report->address }}
                                @if ($report->rt || $report->rw)
                                <span class="block text-xs font-normal text-neutral-500">
                                    RT {{ $report->rt ?: '-' }} / RW {{ $report->rw ?: '-' }}
                                </span>
                                @endif
                            </dd>
                        </div>
                        <div class="min-w-0">
                            <dt class="text-[11px] font-semibold text-neutral-500">Jenis sampah</dt>
                            <dd class="mt-1 text-sm font-medium leading-5 text-neutral-800">
                                {{ $report->jenis_tumpukan ?: 'Belum ditentukan' }}
                            </dd>
                        </div>
                    </dl>

                    <div>
                        <p class="text-[11px] font-semibold text-neutral-500">Deskripsi</p>
                        <p class="mt-1 text-sm leading-5 text-neutral-700">
                            {{ $report->deskripsi ?: $report->description }}
                        </p>
                    </div>

                    @if ($report->catatan_petugas)
                    <div class="rounded-lg border border-emerald-100 bg-emerald-50 p-3 text-sm text-emerald-900">
                        <p class="text-[11px] font-bold text-emerald-800">Catatan petugas</p>
                        <p class="mt-1 leading-5">{{ $report->catatan_petugas }}</p>
                    </div>
                    @endif

                    <footer class="mt-auto border-t border-neutral-100 pt-3 text-xs text-neutral-500">
                        Dikirim {{ $report->created_at->diffForHumans() }}
                        @if ($report->petugas)
                        <span class="mx-1.5 text-neutral-300">·</span>
                        Petugas: {{ $report->petugas->name }}
                        @endif
                    </footer>
                </article>
                @empty
                <div class="rounded-xl border border-dashed border-neutral-300 bg-white px-5 py-10 text-center xl:col-span-2">
                    <p class="text-sm font-semibold text-neutral-700">Belum ada laporan</p>
                    <p class="mt-1 text-sm text-neutral-500">Laporan yang Anda kirim akan muncul di sini.</p>
                    <a
                        href="{{ route('warga.dashboard', ['section' => 'create']) }}"
                        class="mt-4 inline-flex rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-800">Buat laporan</a>
                </div>
                @endforelse
            </div>
        </div>
    </section>
    @endif
</div>
@endsection
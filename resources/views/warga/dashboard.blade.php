@extends('layouts.app')

@section('content')
<div class="space-y-6">
    @if ($section === 'dashboard')
    <section class="space-y-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 font-bold text-emerald-800">
                        {{ auth()->user()->initials }}
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-xl font-bold text-slate-900">Dashboard Warga</h1>
                            <span class="rounded-md border border-emerald-200/60 bg-emerald-50 px-2 py-0.5 text-xs font-bold text-emerald-700">
                                {{ auth()->user()->zona_wilayah ?? 'Warga Sukamaju' }}
                            </span>
                        </div>
                        <p class="mt-0.5 text-xs text-slate-500">
                            <strong class="text-slate-700">{{ auth()->user()->name }}</strong> · Ringkasan laporan dan tindak lanjut lingkungan Anda
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3 lg:border-t-0 lg:pt-0">
                    <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-1.5 text-xs font-semibold text-slate-600">
                        <i class="fa-solid fa-calendar-days text-xs text-emerald-600"></i>
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                    <a
                        href="{{ route('warga.dashboard', ['section' => 'create']) }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-xs transition hover:bg-emerald-700 active:scale-95">
                        <i class="fa-solid fa-plus"></i>
                        Buat Laporan
                    </a>
                </div>
            </div>
        </div>

        <section class="space-y-3" aria-labelledby="resident-report-summary">
            <h2 id="resident-report-summary" class="text-xs font-bold uppercase tracking-wider text-slate-600">Ringkasan laporan</h2>
            <div class="grid grid-cols-2 gap-3.5 lg:grid-cols-4">
                <a href="{{ route('warga.dashboard', ['section' => 'reports']) }}" class="flex items-center justify-between gap-2 rounded-2xl bg-white p-4 shadow-xs transition hover:shadow-md">
                    <div>
                        <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Total Laporan</span>
                        <div class="mt-1 flex items-baseline gap-1.5">
                            <span class="font-mono text-2xl font-extrabold text-emerald-600">{{ $totalMyReports }}</span>
                            <span class="text-[11px] font-medium text-slate-400">laporan</span>
                        </div>
                    </div>
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <i class="fa-solid fa-file-lines text-base"></i>
                    </div>
                </a>
                <a href="{{ route('warga.dashboard', ['section' => 'reports']) }}" class="flex items-center justify-between gap-2 rounded-2xl bg-white p-4 shadow-xs transition hover:shadow-md">
                    <div>
                        <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Menunggu</span>
                        <div class="mt-1 flex items-baseline gap-1.5">
                            <span class="font-mono text-2xl font-extrabold text-amber-600">{{ $pendingCount }}</span>
                            <span class="text-[11px] font-medium text-slate-400">laporan</span>
                        </div>
                    </div>
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <i class="fa-solid fa-clock text-base"></i>
                    </div>
                </a>
                <a href="{{ route('warga.dashboard', ['section' => 'reports']) }}" class="flex items-center justify-between gap-2 rounded-2xl bg-white p-4 shadow-xs transition hover:shadow-md">
                    <div>
                        <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Ditangani</span>
                        <div class="mt-1 flex items-baseline gap-1.5">
                            <span class="font-mono text-2xl font-extrabold text-sky-600">{{ $inProgressCount }}</span>
                            <span class="text-[11px] font-medium text-slate-400">laporan</span>
                        </div>
                    </div>
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                        <i class="fa-solid fa-truck-fast text-base"></i>
                    </div>
                </a>
                <a href="{{ route('warga.dashboard', ['section' => 'reports']) }}" class="flex items-center justify-between gap-2 rounded-2xl bg-white p-4 shadow-xs transition hover:shadow-md">
                    <div>
                        <span class="text-[11px] font-bold tracking-wider uppercase text-slate-400">Selesai</span>
                        <div class="mt-1 flex items-baseline gap-1.5">
                            <span class="font-mono text-2xl font-extrabold text-emerald-600">{{ $completedCount }}</span>
                            <span class="text-[11px] font-medium text-slate-400">laporan</span>
                        </div>
                    </div>
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <i class="fa-solid fa-circle-check text-base"></i>
                    </div>
                </a>
            </div>
        </section>

        <section class="space-y-3" aria-labelledby="latest-report-heading">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 id="latest-report-heading" class="text-xs font-bold uppercase tracking-wider text-slate-600">Pantau laporan terakhir</h2>
                    <p class="mt-1 text-xs text-slate-500">Lihat perkembangan dan tindak lanjut yang bisa Anda lakukan.</p>
                </div>
                <a href="{{ route('warga.dashboard', ['section' => 'reports']) }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-900">Semua laporan <span aria-hidden="true">→</span></a>
            </div>

            @if ($latestReport)
            <div class="flex flex-col justify-between gap-4 rounded-2xl bg-white p-4 shadow-xs transition hover:shadow-md sm:flex-row sm:items-center sm:p-5">
                <div class="min-w-0 space-y-1.5">
                    <p class="font-mono text-sm font-bold text-slate-800">{{ $latestReport->kode_laporan ?: 'Laporan #'.$latestReport->id }}</p>
                    <p class="break-words text-sm text-slate-600">{{ $latestReport->alamat ?: $latestReport->address }}</p>
                    <p class="flex flex-wrap items-center gap-1.5 text-xs text-slate-500">
                        Status:
                        <x-report-badge type="status" :value="$latestReport->status" :label="$latestReport->status_label" />
                        <span>· Diperbarui {{ ($latestReport->tanggal_update_status ?? $latestReport->updated_at)->diffForHumans() }}</span>
                    </p>
                </div>
                <div class="flex shrink-0 flex-wrap gap-2 sm:justify-end">
                    <a href="{{ route('warga.laporan.show', $latestReport) }}" class="rounded-lg border border-neutral-300 px-3 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">Detail</a>
                    @if ($latestReport->canBeEditedByResident())
                    <a href="{{ route('warga.laporan.edit', $latestReport) }}" class="rounded-lg bg-emerald-700 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-800">Perbarui aduan</a>
                    @endif
                </div>
            </div>
            @else
            <div class="flex flex-col gap-4 rounded-2xl bg-white p-4 shadow-xs sm:flex-row sm:items-center sm:justify-between sm:p-5">
                <div class="max-w-2xl">
                    <p class="text-sm font-semibold text-slate-800">Belum ada aduan yang perlu dipantau.</p>
                    <p class="mt-1 text-sm text-slate-500">Tandai lokasi di peta, jelaskan jenis sampah, lalu lampirkan foto agar petugas lebih mudah menindaklanjuti.</p>
                </div>
                <a href="{{ route('warga.dashboard', ['section' => 'create']) }}" class="shrink-0 rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-800">Buat laporan pertama</a>
            </div>
            @endif
        </section>
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
                    <div class="mb-2 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <label for="alamat" class="block text-sm font-semibold text-neutral-700">Tandai lokasi laporan</label>
                            <p class="mt-1 text-xs text-neutral-500">Klik titik yang tepat pada peta atau gunakan lokasi perangkat Anda.</p>
                        </div>
                        <button
                            type="button"
                            data-location-current
                            class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-800 transition hover:bg-emerald-100 focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            Gunakan lokasi saya
                        </button>
                    </div>
                    <div
                        id="report-location-map"
                        data-location-picker
                        data-latitude="{{ old('latitude') }}"
                        data-longitude="{{ old('longitude') }}"
                        class="isolate h-80 w-full overflow-hidden rounded-xl border border-neutral-200 bg-neutral-100"
                        role="application"
                        tabindex="0"
                        aria-label="Peta untuk memilih lokasi laporan"></div>
                    <p data-location-status class="mt-2 text-xs text-neutral-500" aria-live="polite">
                        Pilih lokasi pada peta untuk mengisi koordinat laporan.
                    </p>
                    <label for="alamat" class="mb-1.5 mt-4 block text-sm font-semibold text-neutral-700">Titik lokasi terpilih</label>
                    <input
                        type="text"
                        id="alamat"
                        required
                        readonly
                        value="{{ old('latitude') !== null && old('longitude') !== null ? 'Titik peta (' . old('latitude') . ', ' . old('longitude') . ')' : '' }}"
                        placeholder="Pilih titik di peta terlebih dahulu"
                        class="w-full rounded-lg border border-neutral-300 bg-neutral-50 px-3.5 py-3 text-sm text-neutral-700 focus:outline-none" />
                    <input type="hidden" name="latitude" data-location-latitude value="{{ old('latitude') }}" required />
                    <input type="hidden" name="longitude" data-location-longitude value="{{ old('longitude') }}" required />
                    @error('latitude')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                    @error('longitude')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="patokan" class="mb-1.5 block text-sm font-semibold text-neutral-700">Patokan lokasi <span class="font-normal text-neutral-500">(opsional)</span></label>
                    <input
                        type="text"
                        name="patokan"
                        id="patokan"
                        value="{{ old('patokan') }}"
                        placeholder="Contoh: dekat jembatan atau balai warga"
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

        <div id="laporan-saya" class="border border-neutral-200 bg-white">
            <div data-table-container data-filter-column="4">
                <x-table-tools filter-label="status" />
                <div class="table-responsive">
                    <table class="table table-hover" data-filter-table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode aduan</th>
                                <th>Lokasi</th>
                                <th>Jenis sampah</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($myReports as $report)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="font-mono font-semibold text-neutral-900">{{ $report->kode_laporan ?: 'Laporan #'.$report->id }}</td>
                                <td class="max-w-xs">{{ $report->alamat ?: $report->address }}</td>
                                <td>{{ $report->jenis_tumpukan ?: 'Belum ditentukan' }}</td>
                                <td><x-report-badge type="status" :value="$report->status" :label="$report->status_label" /></td>
                                <td class="whitespace-nowrap">{{ $report->created_at->translatedFormat('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('warga.laporan.show', $report) }}" class="font-semibold text-emerald-800 hover:text-emerald-950">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-neutral-500">
                                    Belum ada laporan. <a href="{{ route('warga.dashboard', ['section' => 'create']) }}" class="font-semibold text-emerald-800">Buat laporan</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    @endif
</div>
@endsection
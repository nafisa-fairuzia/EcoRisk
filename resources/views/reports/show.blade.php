@extends('layouts.app', ['title' => 'Detail Aduan'])

@section('content')
@php
    $statusLabel = $report->status_label;
    $urgencyLabel = $report->urgency_label;
    $isAdmin = auth()->user()->isAdmin();
    $isOfficer = auth()->user()->isPetugas();
@endphp

<section class="mx-auto max-w-5xl space-y-4 pb-8">
    <!-- Tombol Kembali -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <a
            href="{{ $isAdmin ? route('admin.heatmap') : ($isOfficer ? route('petugas.dashboard') : route('warga.dashboard', ['section' => 'reports'])) }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition hover:text-emerald-900">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            {{ $isAdmin ? 'Kembali ke daftar aduan' : ($isOfficer ? 'Kembali ke dashboard petugas' : 'Kembali ke daftar aduan') }}
        </a>
        @if ($canEditReport)
        <div class="flex items-center gap-2">
            <a
                href="{{ route('warga.laporan.edit', $report) }}"
                class="inline-flex items-center gap-2 rounded-lg border border-emerald-200 bg-white px-3.5 py-2 text-sm font-semibold text-emerald-800 transition hover:bg-emerald-50">
                <i class="fa-solid fa-pen text-xs"></i> Edit aduan
            </a>
            <form
                action="{{ route('warga.laporan.destroy', $report) }}"
                method="POST"
                onsubmit="return confirm('Hapus aduan ini? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-lg border border-rose-200 bg-white px-3.5 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-50">
                    <i class="fa-solid fa-trash text-xs"></i> Hapus aduan
                </button>
            </form>
        </div>
        @endif
    </div>

    @if (auth()->user()->isWarga() && ! $canEditReport)
    <p class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
        Aduan ini sudah diproses atau ditangani petugas, sehingga tidak dapat diedit atau dihapus.
    </p>
    @endif
    <!-- Header Detail -->
    <header class="flex flex-col gap-4 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs sm:flex-row sm:items-center sm:justify-between">
        <div class="space-y-1">
            <span class="inline-flex items-center gap-1.5 text-xs font-bold tracking-wider text-emerald-600 uppercase">
                <i class="fa-solid fa-file-lines text-xs"></i> Detail Aduan
            </span>
            <h1 class="font-mono text-xl font-extrabold tracking-tight text-slate-900">{{ $report->kode_laporan ?: 'Laporan #'.$report->id }}</h1>
            <p class="text-xs text-slate-500">Dikirim pada {{ $report->created_at->translatedFormat('d F Y, H:i') }}</p>
        </div>
        <x-report-badge type="status" :value="$report->status" :label="$statusLabel" />
    </header>

    <!-- Grid Utama & Sidebar Timeline -->
    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_21rem]">
        <div class="space-y-4">
            <!-- Informasi Laporan -->
            <section class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
                <h2 class="flex items-center gap-2 text-base font-bold text-slate-900">
                    <i class="fa-solid fa-circle-info text-emerald-600 text-sm"></i> Informasi Laporan
                </h2>
                <dl class="mt-4 grid gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Alamat Lengkap</dt>
                        <dd class="mt-1 text-sm font-medium leading-relaxed text-slate-800">{{ $report->alamat ?: $report->address }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">RT / RW</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800">RT {{ $report->rt ?: '-' }} / RW {{ $report->rw ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Patokan Lokasi</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $report->patokan ?: 'Tidak ada' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Jenis Sampah</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $report->jenis_tumpukan ?: 'Belum ditentukan' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Tingkat Urgensi</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700">
                                {{ $urgencyLabel }}
                            </span>
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Deskripsi</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm leading-relaxed text-slate-700 bg-slate-50/70 p-4 rounded-xl border border-slate-100">{{ $report->deskripsi ?: $report->description }}</dd>
                    </div>
                </dl>
            </section>

            <!-- Foto Laporan & Penanganan -->
            @if ($photoUrl || $completionPhotoUrl)
            <section class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
                <h2 class="flex items-center gap-2 text-base font-bold text-slate-900">
                    <i class="fa-solid fa-image text-emerald-600 text-sm"></i> Dokumentasi Foto
                </h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    @if ($photoUrl)
                    <figure class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                        <img src="{{ $photoUrl }}" alt="Foto lokasi yang dilaporkan" class="aspect-video w-full object-cover transition hover:scale-105" />
                        <figcaption class="p-3 text-xs font-medium text-slate-600 bg-white border-t border-slate-100">
                            <i class="fa-solid fa-camera text-slate-400 mr-1"></i> Foto Saat Aduan Dikirim
                        </figcaption>
                    </figure>
                    @endif
                    @if ($completionPhotoUrl)
                    <figure class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                        <img src="{{ $completionPhotoUrl }}" alt="Foto lokasi setelah penanganan" class="aspect-video w-full object-cover transition hover:scale-105" />
                        <figcaption class="p-3 text-xs font-medium text-emerald-700 bg-emerald-50/50 border-t border-emerald-100">
                            <i class="fa-solid fa-circle-check text-emerald-600 mr-1"></i> Foto Setelah Penanganan
                        </figcaption>
                    </figure>
                    @endif
                </div>
            </section>
            @endif

            <!-- Catatan Petugas -->
            @if ($report->catatan_petugas)
            <section class="rounded-2xl border border-emerald-200/80 bg-emerald-50/60 p-4 shadow-xs">
                <h2 class="flex items-center gap-2 text-sm font-bold text-emerald-900">
                    <i class="fa-solid fa-clipboard-user text-emerald-600"></i> Catatan Petugas Lapangan
                </h2>
                <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-emerald-950">{{ $report->catatan_petugas }}</p>
                @if ($report->petugas)
                <div class="mt-3 flex items-center gap-2 text-xs font-semibold text-emerald-800 border-t border-emerald-200/60 pt-3">
                    <span class="flex size-6 items-center justify-center rounded-full bg-emerald-600 text-white text-[10px]">
                        {{ substr($report->petugas->name, 0, 1) }}
                    </span>
                    Ditangani oleh {{ $report->petugas->name }}
                </div>
                @endif
            </section>
            @endif

            <!-- Informasi Pelapor (Khusus Petugas/Admin) -->
            @if ($isOfficer)
            <section class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
                <h2 class="flex items-center gap-2 text-base font-bold text-slate-900">
                    <i class="fa-solid fa-user-shield text-emerald-600 text-sm"></i> Informasi Pelapor & Risiko
                </h2>
                <dl class="mt-4 grid gap-4 sm:grid-cols-3">
                    <div>
                        <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Nama Pelapor</dt>
                        <dd class="mt-1 text-sm font-bold text-slate-800">{{ $report->nama_pelapor ?: $report->pelapor?->name ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Nomor Telepon</dt>
                        <dd class="mt-1 text-sm font-bold text-slate-800">{{ $report->telepon_pelapor ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Skor & Prioritas</dt>
                        <dd class="mt-1 text-sm font-bold text-emerald-700 capitalize">
                            <x-report-badge type="priority" :value="$report->priority_level" :label="$report->priority_label" />
                            <span class="text-slate-400 font-normal">(Skor: {{ $report->risk_score ?? '-' }})</span>
                        </dd>
                    </div>
                </dl>
            </section>
            @endif
        </div>

        <!-- Sidebar Timeline Penanganan -->
       <aside class="h-fit rounded-2xl border border-neutral-200 bg-white p-5 shadow-xs">
    <h2 class="text-base font-bold text-neutral-900">Timeline penanganan</h2>
    <ol class="mt-5 space-y-0">
        @forelse ($report->statusHistories as $event)
        @php
            $eventLabel = match ($event->status) {
                'approved' => 'Disetujui',
                'in_process' => 'Mulai penanganan',
                'completed' => 'Selesai ditangani',
                'rejected' => 'Aduan ditolak',
                default => 'Aduan dikirim',
            };
            $isLastEvent = $loop->last;
        @endphp
        <li class="relative flex gap-3.5 pb-5 last:pb-0">
            @unless ($isLastEvent)
            <span aria-hidden="true" class="absolute left-[0.4375rem] top-3.5 h-full w-px bg-neutral-200"></span>
            @endunless
            <span @class([
                'relative mt-1 size-3.5 shrink-0 rounded-full ring-4 ring-white',
                'bg-emerald-600' => $event->status === 'completed',
                'bg-rose-500' => $event->status === 'rejected',
                'bg-blue-600' => $event->status === 'in_process',
                'bg-amber-500' => in_array($event->status, ['reported', 'approved'], true),
            ])></span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-neutral-900">{{ $eventLabel }}</p>
                <time class="mt-0.5 block text-xs text-neutral-500" datetime="{{ $event->created_at->toIso8601String() }}">
                    {{ $event->created_at->translatedFormat('d M Y, H:i') }}
                </time>
                @if ($event->note)
                <p class="mt-2 whitespace-pre-line text-xs leading-relaxed text-neutral-600">{{ $event->note }}</p>
                @endif
            </div>
        </li>
        @empty
        <li class="text-sm text-neutral-500">Belum ada riwayat perubahan status.</li>
        @endforelse
    </ol>
</aside>
    </div>
</section>
@endsection
@extends('layouts.app', ['title' => 'Edit Aduan'])

@section('content')
<section class="mx-auto max-w-4xl space-y-5">
    <a
        href="{{ route('warga.laporan.show', $report) }}"
        class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition hover:text-emerald-900">
        <span aria-hidden="true">&larr;</span> Kembali ke detail aduan
    </a>

    <header>
        <p class="text-xs font-bold uppercase tracking-wide text-emerald-700">{{ $report->kode_laporan ?: 'Laporan #'.$report->id }}</p>
        <h1 class="mt-1 text-2xl font-bold text-slate-900">Edit aduan</h1>
        <p class="mt-1 text-sm text-slate-500">Perbarui informasi selama petugas belum mulai menangani aduan ini.</p>
    </header>

    @if ($errors->has('report'))
    <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
        {{ $errors->first('report') }}
    </div>
    @endif

    <form
        action="{{ route('warga.laporan.update', $report) }}"
        method="POST"
        enctype="multipart/form-data"
        class="grid gap-x-5 gap-y-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7 md:grid-cols-2">
        @csrf
        @method('PUT')

        <div class="md:col-span-2">
            <div class="mb-2 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <label for="alamat" class="block text-sm font-semibold text-slate-700">Titik lokasi aduan</label>
                    <p class="mt-1 text-xs text-slate-500">Klik peta untuk memindahkan titik lokasi.</p>
                </div>
                <button
                    type="button"
                    data-location-current
                    class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-800 transition hover:bg-emerald-100">
                    Gunakan lokasi saya
                </button>
            </div>
            <div
                data-location-picker
                data-latitude="{{ old('latitude', $report->latitude) }}"
                data-longitude="{{ old('longitude', $report->longitude) }}"
                class="h-80 w-full overflow-hidden rounded-xl border border-slate-200 bg-slate-100"
                role="application"
                tabindex="0"
                aria-label="Peta untuk memperbarui lokasi aduan"></div>
            <p data-location-status class="mt-2 text-xs text-slate-500" aria-live="polite">
                Titik aduan saat ini ditampilkan pada peta.
            </p>
            <label for="alamat" class="mb-1.5 mt-4 block text-sm font-semibold text-slate-700">Koordinat terpilih</label>
            <input
                type="text"
                id="alamat"
                required
                readonly
                value="{{ old('latitude') !== null && old('longitude') !== null ? 'Titik peta (' . old('latitude') . ', ' . old('longitude') . ')' : $report->address }}"
                class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3.5 py-3 text-sm text-slate-700 focus:outline-none" />
            <input type="hidden" name="latitude" data-location-latitude value="{{ old('latitude', $report->latitude) }}" required />
            <input type="hidden" name="longitude" data-location-longitude value="{{ old('longitude', $report->longitude) }}" required />
            @error('latitude')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
            @error('longitude')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="rt" class="mb-1.5 block text-sm font-semibold text-slate-700">RT</label>
            <input
                type="text"
                name="rt"
                id="rt"
                required
                maxlength="5"
                value="{{ old('rt', $report->rt) }}"
                class="w-full rounded-lg border border-slate-300 px-3.5 py-3 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
            @error('rt')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="rw" class="mb-1.5 block text-sm font-semibold text-slate-700">RW</label>
            <input
                type="text"
                name="rw"
                id="rw"
                required
                maxlength="5"
                value="{{ old('rw', $report->rw) }}"
                class="w-full rounded-lg border border-slate-300 px-3.5 py-3 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
            @error('rw')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="jenis_tumpukan" class="mb-1.5 block text-sm font-semibold text-slate-700">Jenis sampah</label>
            <input
                type="text"
                name="jenis_tumpukan"
                id="jenis_tumpukan"
                required
                maxlength="255"
                value="{{ old('jenis_tumpukan', $report->jenis_tumpukan) }}"
                class="w-full rounded-lg border border-slate-300 px-3.5 py-3 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
            @error('jenis_tumpukan')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="urgensi" class="mb-1.5 block text-sm font-semibold text-slate-700">Tingkat urgensi</label>
            <select
                name="urgensi"
                id="urgensi"
                required
                class="w-full rounded-lg border border-slate-300 px-3.5 py-3 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none">
                @foreach (['rendah' => 'Rendah', 'sedang' => 'Sedang', 'tinggi' => 'Tinggi'] as $value => $label)
                <option value="{{ $value }}" @selected(old('urgensi', $report->urgensi) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('urgensi')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2">
            <label for="patokan" class="mb-1.5 block text-sm font-semibold text-slate-700">Patokan lokasi <span class="font-normal text-slate-500">(opsional)</span></label>
            <input
                type="text"
                name="patokan"
                id="patokan"
                maxlength="255"
                value="{{ old('patokan', $report->patokan) }}"
                placeholder="Contoh: dekat jembatan atau balai warga"
                class="w-full rounded-lg border border-slate-300 px-3.5 py-3 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
            @error('patokan')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2">
            <label for="deskripsi" class="mb-1.5 block text-sm font-semibold text-slate-700">Deskripsi aduan</label>
            <textarea
                name="deskripsi"
                id="deskripsi"
                rows="4"
                required
                class="w-full rounded-lg border border-slate-300 px-3.5 py-3 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none">{{ old('deskripsi', $report->deskripsi ?: $report->description) }}</textarea>
            @error('deskripsi')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        @if ($photoUrl)
        <div class="md:col-span-2">
            <p class="mb-2 text-sm font-semibold text-slate-700">Foto saat ini</p>
            <img src="{{ $photoUrl }}" alt="Foto aduan saat ini" class="max-h-64 rounded-xl border border-slate-200 object-cover" />
        </div>
        @endif

        <div class="md:col-span-2">
            <label for="foto" class="mb-1.5 block text-sm font-semibold text-slate-700">Ganti foto <span class="font-normal text-slate-500">(opsional, maksimal 5 MB)</span></label>
            <input
                type="file"
                name="foto"
                id="foto"
                accept="image/jpeg,image/png,image/webp"
                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-3 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-emerald-800 hover:file:bg-emerald-100 focus:border-emerald-600 focus:outline-none" />
            @error('foto')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-5 md:col-span-2">
            <a
                href="{{ route('warga.laporan.show', $report) }}"
                class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
            <button
                type="submit"
                class="rounded-lg bg-emerald-700 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-800 focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 focus:outline-none">Simpan perubahan</button>
        </div>
    </form>
</section>
@endsection

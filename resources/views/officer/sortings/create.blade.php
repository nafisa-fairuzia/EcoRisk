@extends('layouts.app', ['title' => 'Catat Pemilahan Sampah'])

@section('content')
<section class="w-full space-y-6">
    <header class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900">Catat Pemilahan Sampah</h1>
            <p class="mt-1 text-sm text-neutral-600">Laporan #{{ $report->id }} · {{ $report->address }}</p>
        </div>
        <a
            href="{{ route('petugas.sortings.index') }}"
            class="text-sm font-semibold text-emerald-700 hover:text-emerald-900">Kembali ke antrean pemilahan</a>
    </header>

    <form
        method="POST"
        action="{{ route('petugas.sortings.store', $report->id) }}"
        class="w-full space-y-6 rounded-2xl border border-neutral-200 bg-white p-5 shadow-xs sm:p-7 lg:p-8">
        @csrf

        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
            <label class="space-y-1 text-sm font-medium text-neutral-700">
                <span>Berat organik (kg)</span>
                <input
                    type="number"
                    name="organic_weight_kg"
                    min="0"
                    step="0.01"
                    value="{{ old('organic_weight_kg', 0) }}"
                    required
                    class="w-full rounded-lg border border-neutral-300 px-3.5 py-3 text-base focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                @error('organic_weight_kg')
                <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </label>

            <label class="space-y-1 text-sm font-medium text-neutral-700">
                <span>Berat anorganik (kg)</span>
                <input
                    type="number"
                    name="inorganic_weight_kg"
                    min="0"
                    step="0.01"
                    value="{{ old('inorganic_weight_kg', 0) }}"
                    required
                    class="w-full rounded-lg border border-neutral-300 px-3.5 py-3 text-base focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                @error('inorganic_weight_kg')
                <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </label>

            <label class="space-y-1 text-sm font-medium text-neutral-700">
                <span>Berat kertas (kg)</span>
                <input
                    type="number"
                    name="paper_weight_kg"
                    min="0"
                    step="0.01"
                    value="{{ old('paper_weight_kg', 0) }}"
                    required
                    class="w-full rounded-lg border border-neutral-300 px-3.5 py-3 text-base focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                @error('paper_weight_kg')
                <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </label>
        </div>

        <label class="block space-y-1.5 text-sm font-medium text-neutral-700 xl:max-w-2xl">
            <span>Saluran pengolahan</span>
            <select name="channel_type" required class="w-full rounded-lg border border-neutral-300 px-3.5 py-3 text-base focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none">
                <option value="">Pilih saluran pengolahan</option>
                <option value="maggot_bsf" @selected(old('channel_type')==='maggot_bsf' )>Maggot BSF</option>
                <option value="compost_community" @selected(old('channel_type')==='compost_community' )>
                    Kompos komunitas
                </option>
                <option value="waste_bank" @selected(old('channel_type')==='waste_bank' )>Bank sampah</option>
                <option value="recycling_umkm" @selected(old('channel_type')==='recycling_umkm' )>
                    Daur ulang UMKM
                </option>
            </select>
            @error('channel_type')
            <span class="text-xs text-red-600">{{ $message }}</span>
            @enderror
        </label>

        <fieldset class="space-y-3 rounded-xl border border-neutral-200 bg-neutral-50 p-4 sm:p-5">
            <legend class="text-sm font-medium text-neutral-700">Risiko pembakaran berhasil dicegah?</legend>
            <input type="hidden" name="prevented_burning_risk" value="0" />
            <label class="flex items-center gap-2 text-sm text-neutral-700">
                <input
                    type="checkbox"
                    name="prevented_burning_risk"
                    value="1"
                    @checked(old('prevented_burning_risk'))
                    class="size-4 rounded border-neutral-300 text-emerald-600 focus:ring-emerald-600" />
                Ya, pembakaran sampah berhasil dicegah
            </label>
            @error('prevented_burning_risk')
            <span class="text-xs text-red-600">{{ $message }}</span>
            @enderror
        </fieldset>

        @error('report_id')
        <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div class="flex flex-col-reverse gap-3 border-t border-neutral-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
            <a
                href="{{ route('petugas.sortings.index') }}"
                class="text-sm font-medium text-neutral-600 hover:text-neutral-900">
                Kembali ke antrean pemilahan
            </a>
            <button
                type="submit"
                class="w-full rounded-lg bg-emerald-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-800 focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 focus:outline-none sm:w-auto">
                Simpan pemilahan
            </button>
        </div>
    </form>
</section>
@endsection
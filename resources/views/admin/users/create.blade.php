@extends('layouts.app', ['title' => 'Tambah Akun Operasional'])

@section('content')
<section class="mx-auto max-w-6xl space-y-6">
    <header class="space-y-1">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 transition hover:text-emerald-700">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Kembali ke daftar akun
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Tambah Akun Operasional</h1>
    </header>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-xs">
        <form 
            method="POST" 
            action="{{ route('admin.users.store') }}" 
            class="space-y-6 p-6 sm:p-8" 
            x-data="{ 
                role: @js(old('role', 'petugas')), 
                zone: @js(old('role') === 'admin' ? 'Koordinator Induk Seluruh Wilayah' : old('zona_wilayah', '')) 
            }" 
            x-effect="
                if (role === 'admin') zone = 'Koordinator Induk Seluruh Wilayah'; 
                else if (zone === 'Koordinator Induk Seluruh Wilayah') zone = '';
            "
        >
            @csrf

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="name" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Nama Lengkap</label>
                    <input 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        maxlength="255" 
                        autocomplete="name" 
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" 
                        placeholder="Masukkan nama lengkap"
                    />
                    @error('name')
                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Email untuk Masuk</label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        value="{{ old('email') }}" 
                        required 
                        maxlength="255" 
                        autocomplete="off" 
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" 
                        placeholder="contoh@eicorisk.com"
                    />
                    @error('email')
                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Peran Akun</label>
                    <select 
                        id="role" 
                        name="role" 
                        x-model="role" 
                        required 
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-600/20 focus:outline-none"
                    >
                        <option value="petugas">Petugas Lapangan</option>
                        <option value="admin">Administrator</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="zona_wilayah" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Zona / Wilayah Tugas</label>
                    <input 
                        id="zona_wilayah" 
                        name="zona_wilayah" 
                        x-model="zone" 
                        value="{{ old('role') === 'admin' ? 'Koordinator Induk Seluruh Wilayah' : old('zona_wilayah') }}" 
                        :readonly="role === 'admin'" 
                        :required="role === 'petugas'" 
                        maxlength="100" 
                        placeholder="Contoh: Zona A / RW 04" 
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 read-only:bg-slate-100 read-only:text-slate-500 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" 
                    />
                    <p class="mt-1.5 text-xs text-slate-500" x-text="role === 'admin' ? 'Wilayah administrator ditetapkan otomatis secara penuh.' : 'Isi nama zona, RW, atau cakupan wilayah tugas petugas.'"></p>
                    @error('zona_wilayah')
                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Kata Sandi Awal</label>
                    <div class="relative">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 pr-12 text-sm text-slate-800 placeholder-slate-400 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-600/20 focus:outline-none"
                            placeholder="Minimal 8 karakter" />
                        <button type="button" data-password-toggle aria-controls="password" aria-label="Tampilkan kata sandi" class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-slate-500 hover:text-emerald-700">
                            <i data-password-eye-open class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Ulangi Kata Sandi Awal</label>
                    <div class="relative">
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 pr-12 text-sm text-slate-800 placeholder-slate-400 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-600/20 focus:outline-none"
                            placeholder="Ulangi kata sandi" />
                        <button type="button" data-password-toggle aria-controls="password_confirmation" aria-label="Tampilkan kata sandi" class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-slate-500 hover:text-emerald-700">
                            <i data-password-eye-open class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 pt-5">
                <p class="text-xs text-slate-500">
                    Pastikan data akun sudah benar sebelum menyimpannya.
                </p>
                <button 
                    type="submit" 
                    class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-xs transition hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none"
                >
                    Buat Akun Sekarang
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
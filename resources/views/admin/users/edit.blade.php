@extends('layouts.app', ['title' => 'Edit Pengguna'])

@section('content')
<section class="mx-auto max-w-6xl space-y-6">
    <header class="space-y-1">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 transition hover:text-emerald-700">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Kembali ke daftar akun
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Edit Pengguna</h1>
        <p class="text-sm text-slate-500">Perbarui informasi akun {{ $managedUser->name }}.</p>
    </header>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-xs">
        <form
            method="POST"
            action="{{ route('admin.users.update', $managedUser) }}"
            class="space-y-6 p-6 sm:p-8"
            x-data="{ role: @js(old('role', $managedUser->role)), zone: @js(old('zona_wilayah', $managedUser->zona_wilayah ?? '')) }"
            x-effect="if (role === 'admin') zone = 'Koordinator Induk Seluruh Wilayah'; else if (zone === 'Koordinator Induk Seluruh Wilayah') zone = ''">
            @csrf
            @method('PUT')

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="name" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Nama lengkap</label>
                    <input id="name" name="name" value="{{ old('name', $managedUser->name) }}" required maxlength="255" autocomplete="name" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                    @error('name')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Alamat email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $managedUser->email) }}" required maxlength="255" autocomplete="email" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                    @error('email')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="role" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Peran akun</label>
                    <select id="role" name="role" x-model="role" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-600/20 focus:outline-none">
                        @if (auth()->id() === $managedUser->id)
                        <option value="{{ $managedUser->role }}">{{ $managedUser->roleLabel }}</option>
                        @else
                        <option value="admin" @selected(old('role', $managedUser->role) === 'admin')>Administrator</option>
                        <option value="petugas" @selected(old('role', $managedUser->role) === 'petugas')>Petugas Lapangan</option>
                        <option value="warga" @selected(old('role', $managedUser->role) === 'warga')>Warga Masyarakat</option>
                        <option value="mitra_daur_ulang" @selected(old('role', $managedUser->role) === 'mitra_daur_ulang')>Mitra Daur Ulang</option>
                        @endif
                    </select>
                    @error('role')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                    @if (auth()->id() === $managedUser->id)
                    <p class="mt-1.5 text-xs text-slate-500">Peran akun yang sedang digunakan tidak dapat diubah.</p>
                    @endif
                </div>

                <div>
                    <label for="zona_wilayah" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Zona / wilayah tugas</label>
                    <input
                        x-ref="zone"
                        id="zona_wilayah"
                        name="zona_wilayah"
                        x-model="zone"
                        :readonly="role === 'admin'"
                        :required="role === 'petugas'"
                        maxlength="100"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 read-only:bg-slate-100 read-only:text-slate-500 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-600/20 focus:outline-none"
                    />
                    @error('zona_wilayah')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="status" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Status akun</label>
                    <select id="status" name="status" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-600/20 focus:outline-none">
                        @if (auth()->id() === $managedUser->id)
                        <option value="{{ $managedUser->status }}">{{ $managedUser->status }}</option>
                        @else
                        <option value="Aktif" @selected(old('status', $managedUser->status) === 'Aktif')>Aktif</option>
                        <option value="Nonaktif" @selected(old('status', $managedUser->status) === 'Nonaktif')>Nonaktif</option>
                        @endif
                    </select>
                    @error('status')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="telepon" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Nomor telepon</label>
                    <input id="telepon" name="telepon" type="tel" value="{{ old('telepon', $managedUser->telepon) }}" maxlength="30" autocomplete="tel" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                    @error('telepon')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="alamat" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Alamat</label>
                    <input id="alamat" name="alamat" value="{{ old('alamat', $managedUser->alamat) }}" maxlength="255" autocomplete="street-address" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                    @error('alamat')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Kata sandi baru <span class="font-normal normal-case tracking-normal text-slate-400">(opsional)</span></label>
                    <div class="relative">
                        <input id="password" name="password" type="password" minlength="8" autocomplete="new-password" placeholder="Kosongkan jika tidak diubah" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 pr-12 text-sm text-slate-800 placeholder-slate-400 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                        <button type="button" data-password-toggle aria-controls="password" aria-label="Tampilkan kata sandi" class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-slate-500 hover:text-emerald-700">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    @error('password')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Konfirmasi kata sandi baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" autocomplete="new-password" placeholder="Ulangi kata sandi baru" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 pt-5">
                <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-xs transition hover:bg-emerald-700">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan perubahan
                </button>
            </div>
        </form>
    </div>
</section>
@endsection

@extends('layouts.app', ['title' => 'Profil Saya'])

@section('content')
<section class="mx-auto max-w-6xl space-y-4">
    <header>
        <h1 class="text-2xl font-bold text-neutral-900">Profil Saya</h1>
    </header>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 border border-neutral-200 bg-white p-4 sm:p-5">
        @csrf
        @method('PUT')

        <section class="space-y-4" aria-labelledby="profile-information-heading">
            <h2 id="profile-information-heading" class="text-base font-bold text-neutral-900">Informasi akun</h2>

            <div class="flex flex-wrap items-center gap-4">
                <div class="flex size-20 shrink-0 items-center justify-center overflow-hidden rounded-full border border-neutral-200 bg-emerald-100">
                    <img
                        data-profile-photo-preview
                        @if ($user->profile_photo_url) src="{{ $user->profile_photo_url }}" @endif
                    alt="Pratinjau foto profil {{ $user->name }}"
                    @class(['size-full object-cover', 'hidden' => ! $user->profile_photo_url]) />
                    <span data-profile-photo-initials @class(['flex size-full items-center justify-center text-xl font-bold text-emerald-800', 'hidden'=> $user->profile_photo_url])>{{ $user->initials }}</span>
                </div>
                <div>
                    <label for="foto_profil" class="mb-1.5 block text-sm font-semibold text-neutral-700">Foto profil</label>
                    <input id="foto_profil" name="foto_profil" type="file" accept="image/jpeg,image/png,image/webp" data-profile-photo-input class="block max-w-full text-sm text-neutral-600 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-emerald-800 hover:file:bg-emerald-100" />
                    <p class="mt-1 text-xs text-neutral-500">Pilih foto untuk melihat pratinjau. Foto baru disimpan setelah Anda menekan tombol Simpan Perubahan. Format JPG, PNG, atau WebP; ukuran maksimal 2 MB.</p>
                    <p data-profile-photo-feedback class="mt-1 text-xs text-rose-600" role="status" aria-live="polite"></p>
                    @error('foto_profil')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="name" class="mb-1.5 block text-sm font-semibold text-neutral-700">Nama</label>
                <input id="name" name="name" type="text" required maxlength="255" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border border-neutral-300 px-3.5 py-2.5 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-sm font-semibold text-neutral-700">Email</label>
                <input id="email" name="email" type="email" required maxlength="255" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border border-neutral-300 px-3.5 py-2.5 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                @if ($isWarga)
                <div>
                    <label for="rt" class="mb-1.5 block text-sm font-semibold text-neutral-700">RT</label>
                    <input id="rt" name="rt" type="text" required maxlength="5" value="{{ old('rt', $rt) }}" class="w-full rounded-lg border border-neutral-300 px-3.5 py-2.5 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                    @error('rt')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="rw" class="mb-1.5 block text-sm font-semibold text-neutral-700">RW</label>
                    <input id="rw" name="rw" type="text" required maxlength="5" value="{{ old('rw', $rw) }}" class="w-full rounded-lg border border-neutral-300 px-3.5 py-2.5 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                    @error('rw')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="alamat" class="mb-1.5 block text-sm font-semibold text-neutral-700">Alamat rumah</label>
                    <input id="alamat" name="alamat" type="text" required maxlength="255" autocomplete="street-address" value="{{ old('alamat', $user->alamat) }}" class="w-full rounded-lg border border-neutral-300 px-3.5 py-2.5 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" placeholder="Nama jalan, nomor rumah, atau patokan" />
                    @error('alamat')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                @else
                <div>
                    <label for="alamat" class="mb-1.5 block text-sm font-semibold text-neutral-700">Alamat <span class="font-normal text-neutral-500">(opsional)</span></label>
                    <input id="alamat" name="alamat" type="text" maxlength="255" value="{{ old('alamat', $user->alamat) }}" class="w-full rounded-lg border border-neutral-300 px-3.5 py-2.5 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                    @error('alamat')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                @endif
                <div class="sm:col-span-2">
                    <label for="telepon" class="mb-1.5 block text-sm font-semibold text-neutral-700">Nomor telepon <span class="font-normal text-neutral-500">(opsional)</span></label>
                    <input id="telepon" name="telepon" type="tel" maxlength="255" value="{{ old('telepon', $user->telepon) }}" class="w-full rounded-lg border border-neutral-300 px-3.5 py-2.5 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" placeholder="Contoh: 081234567890" />
                    @error('telepon')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="rounded-lg bg-neutral-50 px-4 py-3 text-sm text-neutral-600">
                <span class="font-semibold text-neutral-800">Peran:</span> {{ $user->roleLabel }}
                @if ($user->zona_wilayah && ! $isWarga)
                <span class="px-1">·</span><span class="font-semibold text-neutral-800">Wilayah tugas:</span> {{ $user->zona_wilayah }}
                @endif
            </div>
        </section>

        <section class="space-y-4 border-t border-neutral-200 pt-5" aria-labelledby="profile-password-heading">
            <div>
                <h2 id="profile-password-heading" class="text-base font-bold text-neutral-900">Ganti kata sandi</h2>
                <p class="mt-1 text-xs text-neutral-500">Kosongkan bagian ini jika tidak ingin mengganti kata sandi.</p>
            </div>
            <div>
                <label for="current_password" class="mb-1.5 block text-sm font-semibold text-neutral-700">Kata sandi saat ini</label>
                <div class="relative">
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="w-full rounded-lg border border-neutral-300 px-3.5 py-2.5 pr-12 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                    <button type="button" data-password-toggle aria-controls="current_password" aria-label="Tampilkan kata sandi" class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-neutral-500 hover:text-emerald-700">
                        <svg data-password-eye-open class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z" />
                            <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                        </svg>
                        <svg data-password-eye-closed class="hidden size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.6 6.2A10.9 10.9 0 0 1 12 6c6.1 0 9.5 6 9.5 6a16.6 16.6 0 0 1-3.1 3.7M6.2 6.7C3.8 8.2 2.5 12 2.5 12s3.4 6 9.5 6c1.3 0 2.5-.3 3.5-.8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.9 9.9a3 3 0 0 0 4.2 4.2" />
                        </svg>
                    </button>
                </div>
                @error('current_password')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="password" class="mb-1.5 block text-sm font-semibold text-neutral-700">Kata sandi baru</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" autocomplete="new-password" class="w-full rounded-lg border border-neutral-300 px-3.5 py-2.5 pr-12 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                        <button type="button" data-password-toggle aria-controls="password" aria-label="Tampilkan kata sandi" class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-neutral-500 hover:text-emerald-700">
                            <svg data-password-eye-open class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z" />
                                <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                            </svg>
                            <svg data-password-eye-closed class="hidden size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.6 6.2A10.9 10.9 0 0 1 12 6c6.1 0 9.5 6 9.5 6a16.6 16.6 0 0 1-3.1 3.7M6.2 6.7C3.8 8.2 2.5 12 2.5 12s3.4 6 9.5 6c1.3 0 2.5-.3 3.5-.8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.9 9.9a3 3 0 0 0 4.2 4.2" />
                            </svg>
                        </button>
                    </div>
                    @error('password')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-neutral-700">Ulangi kata sandi baru</label>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="w-full rounded-lg border border-neutral-300 px-3.5 py-2.5 pr-12 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                        <button type="button" data-password-toggle aria-controls="password_confirmation" aria-label="Tampilkan kata sandi" class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-neutral-500 hover:text-emerald-700">
                            <svg data-password-eye-open class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z" />
                                <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                            </svg>
                            <svg data-password-eye-closed class="hidden size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.6 6.2A10.9 10.9 0 0 1 12 6c6.1 0 9.5 6 9.5 6a16.6 16.6 0 0 1-3.1 3.7M6.2 6.7C3.8 8.2 2.5 12 2.5 12s3.4 6 9.5 6c1.3 0 2.5-.3 3.5-.8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.9 9.9a3 3 0 0 0 4.2 4.2" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <div class="flex flex-wrap justify-end gap-2 border-t border-neutral-200 pt-4">
            <button type="submit" class="rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-800">Simpan profil</button>
        </div>
    </form>
</section>
@endsection
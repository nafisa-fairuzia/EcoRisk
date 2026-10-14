<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Warga - EcoRisk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-page font-sans text-slate-800 antialiased">
    <main class="auth-panel grid overflow-hidden lg:grid-cols-[0.88fr_1.12fr]">
        <aside class="auth-visual relative hidden flex-col justify-between overflow-hidden p-8 text-white sm:p-10 lg:flex">
            <div class="absolute -right-20 -top-20 size-72 rounded-full border border-white/15"></div>
            <div class="absolute -bottom-28 -left-16 size-72 rounded-full border border-white/15"></div>

            <a href="{{ route('home') }}" class="relative inline-flex items-center gap-3">
                <span class="flex size-11 items-center justify-center rounded-2xl bg-white/15 font-extrabold shadow-lg ring-1 ring-white/30 backdrop-blur-md">ER</span>
                <span class="text-lg font-extrabold tracking-tight">EcoRisk</span>
            </a>

            <div class="relative max-w-md space-y-5">
                <span class="inline-flex rounded-full border border-emerald-100/25 bg-white/10 px-3.5 py-1.5 text-xs font-semibold text-emerald-50 shadow-sm backdrop-blur-md">
                    Komunitas peduli lingkungan
                </span>
                <h1 class="text-4xl font-extrabold leading-tight tracking-tight xl:text-5xl">
                    Mari rawat lingkungan, mulai dari sekitar kita.
                </h1>
                <p class="text-sm leading-7 text-emerald-50/90">
                    Daftarkan akun warga untuk melaporkan masalah, memilih lokasi, dan mengikuti proses penanganannya.
                </p>
                <div class="space-y-3 rounded-2xl border border-white/15 bg-white/10 p-4 text-sm text-emerald-50/95 shadow-lg backdrop-blur-md">
                    <p class="flex items-center gap-3"><span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-white/15 text-xs font-bold">1</span> Tandai lokasi laporan pada peta</p>
                    <p class="flex items-center gap-3"><span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-white/15 text-xs font-bold">2</span> Petugas menerima titik yang akurat</p>
                    <p class="flex items-center gap-3"><span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-white/15 text-xs font-bold">3</span> Pantau pembaruan penanganan</p>
                </div>
            </div>

            <p class="relative text-xs text-emerald-100/70">Daftar gratis sebagai warga EcoRisk.</p>
        </aside>

        <section class="p-6 sm:p-9 lg:p-10 xl:p-12">
            <div class="mb-7 flex items-center gap-3 lg:hidden">
                <span class="flex size-10 items-center justify-center rounded-xl bg-emerald-700 font-extrabold text-white">ER</span>
                <span class="text-lg font-extrabold tracking-tight text-slate-900">EcoRisk</span>
            </div>

            <div class="mb-7">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Mulai berkontribusi</p>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">Buat akun warga</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">Lengkapi informasi berikut untuk mulai menyampaikan laporan lingkungan.</p>
            </div>

            @if ($errors->any())
            <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="grid gap-x-4 gap-y-4 sm:grid-cols-2">
                @csrf
                <div class="sm:col-span-2">
                    <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama lengkap</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        autocomplete="name"
                        required
                        value="{{ old('name') }}"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm transition focus:border-emerald-600 focus:bg-white focus:ring-4 focus:ring-emerald-600/10 focus:outline-none"
                        placeholder="Nama lengkap" />
                </div>

                <div class="sm:col-span-2">
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        autocomplete="email"
                        required
                        value="{{ old('email') }}"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm transition focus:border-emerald-600 focus:bg-white focus:ring-4 focus:ring-emerald-600/10 focus:outline-none"
                        placeholder="nama@email.com" />
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">Kata sandi</label>
                    <div class="relative">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            autocomplete="new-password"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 pr-12 text-sm transition focus:border-emerald-600 focus:bg-white focus:ring-4 focus:ring-emerald-600/10 focus:outline-none"
                            placeholder="Minimal 6 karakter" />
                        <button type="button" data-password-toggle aria-controls="password" aria-label="Tampilkan kata sandi" class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-slate-500 hover:text-emerald-700">
                            <svg data-password-eye-open class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5" stroke-width="1.8"/></svg>
                            <svg data-password-eye-closed class="hidden size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.6 6.2A10.9 10.9 0 0 1 12 6c6.1 0 9.5 6 9.5 6a16.6 16.6 0 0 1-3.1 3.7M6.2 6.7C3.8 8.2 2.5 12 2.5 12s3.4 6 9.5 6c1.3 0 2.5-.3 3.5-.8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-slate-700">Ulangi kata sandi</label>
                    <div class="relative">
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            autocomplete="new-password"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 pr-12 text-sm transition focus:border-emerald-600 focus:bg-white focus:ring-4 focus:ring-emerald-600/10 focus:outline-none"
                            placeholder="Ulangi kata sandi" />
                        <button type="button" data-password-toggle aria-controls="password_confirmation" aria-label="Tampilkan kata sandi" class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-slate-500 hover:text-emerald-700">
                            <svg data-password-eye-open class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5" stroke-width="1.8"/></svg>
                            <svg data-password-eye-closed class="hidden size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.6 6.2A10.9 10.9 0 0 1 12 6c6.1 0 9.5 6 9.5 6a16.6 16.6 0 0 1-3.1 3.7M6.2 6.7C3.8 8.2 2.5 12 2.5 12s3.4 6 9.5 6c1.3 0 2.5-.3 3.5-.8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                        </button>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="alamat" class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat rumah</label>
                    <input
                        type="text"
                        name="alamat"
                        id="alamat"
                        autocomplete="street-address"
                        required
                        value="{{ old('alamat') }}"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm transition focus:border-emerald-600 focus:bg-white focus:ring-4 focus:ring-emerald-600/10 focus:outline-none"
                        placeholder="Nama jalan dan nomor rumah" />
                </div>

                <div>
                    <label for="rt" class="mb-1.5 block text-sm font-semibold text-slate-700">RT</label>
                    <input
                        type="text"
                        name="rt"
                        id="rt"
                        required
                        value="{{ old('rt') }}"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm transition focus:border-emerald-600 focus:bg-white focus:ring-4 focus:ring-emerald-600/10 focus:outline-none"
                        placeholder="Contoh: 02" />
                </div>

                <div>
                    <label for="rw" class="mb-1.5 block text-sm font-semibold text-slate-700">RW</label>
                    <input
                        type="text"
                        name="rw"
                        id="rw"
                        required
                        value="{{ old('rw') }}"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm transition focus:border-emerald-600 focus:bg-white focus:ring-4 focus:ring-emerald-600/10 focus:outline-none"
                        placeholder="Contoh: 04" />
                </div>

                <div class="sm:col-span-2">
                    <label for="telepon" class="mb-1.5 block text-sm font-semibold text-slate-700">Nomor telepon <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input
                        type="tel"
                        name="telepon"
                        id="telepon"
                        autocomplete="tel"
                        value="{{ old('telepon') }}"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm transition focus:border-emerald-600 focus:bg-white focus:ring-4 focus:ring-emerald-600/10 focus:outline-none"
                        placeholder="08xx-xxxx-xxxx" />
                </div>

                <button
                    type="submit"
                    class="mt-1 w-full rounded-xl bg-emerald-700 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800 focus:ring-4 focus:ring-emerald-600/20 focus:outline-none sm:col-span-2">
                    Daftar & Masuk ke Portal Warga
                </button>

                <div class="border-t border-slate-100 pt-5 text-center sm:col-span-2">
                    <p class="text-sm text-slate-600">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:text-emerald-900">Masuk</a>
                    </p>
                    <a href="{{ route('home') }}" class="mt-3 inline-block text-xs font-medium text-slate-500 hover:text-slate-800">
                        Kembali ke halaman utama
                    </a>
                </div>
            </form>
        </section>
    </main>
</body>

</html>

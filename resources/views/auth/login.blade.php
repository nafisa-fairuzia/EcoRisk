<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk - EcoRisk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-page font-sans text-slate-800 antialiased">
    <main class="auth-panel auth-panel--narrow grid overflow-hidden lg:grid-cols-[0.92fr_1.08fr]">
        <aside class="auth-visual relative hidden min-h-full flex-col justify-between overflow-hidden p-8 text-white sm:p-10 lg:flex">
            <div class="absolute -right-20 -top-20 size-72 rounded-full border border-white/15"></div>
            <div class="absolute -bottom-28 -left-20 size-80 rounded-full border border-white/15"></div>

            <a href="{{ route('home') }}" class="relative inline-flex items-center gap-3">
                <span class="flex size-11 items-center justify-center rounded-2xl bg-white/15 font-extrabold shadow-lg ring-1 ring-white/30 backdrop-blur-md">ER</span>
                <span class="text-lg font-extrabold tracking-tight">EcoRisk</span>
            </a>

            <div class="relative max-w-md space-y-6">
                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-100/25 bg-white/10 px-3.5 py-1.5 text-xs font-semibold text-emerald-50 shadow-sm backdrop-blur-md">
                    <span class="size-2 rounded-full bg-emerald-300"></span>
                    Bersama menjaga lingkungan
                </span>
                <h1 class="text-4xl font-extrabold leading-tight tracking-tight drop-shadow-md xl:text-5xl">
                    Lingkungan bersih dimulai dari kepedulian kita.
                </h1>
                <p class="max-w-sm text-sm leading-7 text-emerald-50/90">
                    Sampaikan laporan, lihat perkembangannya, dan ikut membangun lingkungan yang lebih sehat untuk semua.
                </p>
                <div class="flex items-center gap-3 rounded-2xl border border-white/15 bg-white/10 p-4 text-sm text-white/95 shadow-lg backdrop-blur-md">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-emerald-300/20 text-emerald-100" aria-hidden="true">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/><circle cx="12" cy="9" r="2.2" stroke-width="1.8"/></svg>
                    </span>
                    <span><span class="block font-bold">Laporan lebih terarah</span><span class="mt-0.5 block text-xs text-emerald-50/75">Pilih lokasi dan pantau tindak lanjutnya.</span></span>
                </div>
            </div>

            <p class="relative text-xs font-medium text-emerald-50/75">Satu langkah kecil, dampak nyata untuk sekitar.</p>
        </aside>

        <section class="p-6 sm:p-10 lg:p-12 xl:p-14">
            <div class="mb-8 flex items-center gap-3 lg:hidden">
                <span class="flex size-10 items-center justify-center rounded-xl bg-emerald-700 font-extrabold text-white">ER</span>
                <span class="text-lg font-extrabold tracking-tight text-slate-900">EcoRisk</span>
            </div>

            <div class="mb-8">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Selamat datang kembali</p>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">Masuk ke EcoRisk</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">Gunakan akun Anda untuk melanjutkan dan memantau aktivitas lingkungan.</p>
            </div>

            @if ($errors->any())
            <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="emailInput" class="mb-2 block text-sm font-semibold text-slate-700">Alamat email</label>
                    <input
                        type="email"
                        name="email"
                        id="emailInput"
                        autocomplete="email"
                        required
                        value="{{ old('email') }}"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-600 focus:bg-white focus:ring-4 focus:ring-emerald-600/10 focus:outline-none"
                        placeholder="nama@email.com" />
                </div>

                <div>
                    <label for="passwordInput" class="mb-2 block text-sm font-semibold text-slate-700">Kata sandi</label>
                    <div class="relative">
                        <input
                            type="password"
                            name="password"
                            id="passwordInput"
                            autocomplete="current-password"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 pr-12 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-600 focus:bg-white focus:ring-4 focus:ring-emerald-600/10 focus:outline-none"
                            placeholder="Masukkan kata sandi" />
                        <button type="button" data-password-toggle aria-controls="passwordInput" aria-label="Tampilkan kata sandi" class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-slate-500 hover:text-emerald-700">
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

                <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600" />
                    <span>Ingat saya</span>
                </label>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-emerald-700 px-4 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800 focus:ring-4 focus:ring-emerald-600/20 focus:outline-none">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-7 border-t border-slate-100 pt-6 text-center">
                <p class="text-sm text-slate-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-emerald-700 hover:text-emerald-900">Daftar sebagai warga</a>
                </p>
                <a href="{{ route('home') }}" class="mt-4 inline-block text-xs font-medium text-slate-500 hover:text-slate-800">
                    Kembali ke halaman utama
                </a>
            </div>
        </section>
    </main>
</body>

</html>
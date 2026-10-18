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
        <aside class="auth-visual relative flex min-h-[220px] flex-col justify-between overflow-hidden p-5 text-white sm:min-h-[260px] sm:p-8 lg:min-h-full lg:p-10">
            <div class="absolute -right-20 -top-20 size-72 rounded-full border border-white/15"></div>
            <div class="absolute -bottom-28 -left-20 size-80 rounded-full border border-white/15"></div>

            <a href="{{ route('home') }}" class="relative inline-flex items-center gap-3">
                <span class="flex size-10 items-center justify-center overflow-hidden rounded-2xl">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo EcoRisk" class="h-full w-full object-cover">
                </span>
                <span class="text-lg font-extrabold tracking-tight">EcoRisk</span>
            </a>

            <div class="relative max-w-md space-y-4 sm:space-y-6">
                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-100/25 bg-white/10 px-3.5 py-1.5 text-[10px] font-semibold text-emerald-50 shadow-sm backdrop-blur-md sm:text-xs">
                    <span class="size-2 rounded-full bg-emerald-300"></span>
                    Bersama menjaga lingkungan
                </span>
                <h1 class="text-2xl font-extrabold leading-tight tracking-tight drop-shadow-md sm:text-3xl xl:text-5xl">
                    Lingkungan bersih dimulai dari kepedulian kita.
                </h1>
                <p class="max-w-sm text-xs leading-6 text-emerald-50/90 sm:text-sm sm:leading-7">
                    Sampaikan laporan, lihat perkembangannya, dan ikut membangun lingkungan yang lebih sehat untuk semua.
                </p>
                <div class="flex items-center gap-3 rounded-2xl border border-white/15 bg-white/10 p-3 text-sm text-white/95 shadow-lg backdrop-blur-md sm:p-4">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-emerald-300/20 text-emerald-100" aria-hidden="true">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/><circle cx="12" cy="9" r="2.2" stroke-width="1.8"/></svg>
                    </span>
                    <span><span class="block font-bold">Laporan lebih terarah</span><span class="mt-0.5 block text-[11px] text-emerald-50/75 sm:text-xs">Pilih lokasi dan pantau tindak lanjutnya.</span></span>
                </div>
            </div>

            <p class="relative text-[11px] font-medium text-emerald-50/75 sm:text-xs">Satu langkah kecil, dampak nyata untuk sekitar.</p>
        </aside>

        <section class="p-6 sm:p-10 lg:p-12 xl:p-14">
           

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
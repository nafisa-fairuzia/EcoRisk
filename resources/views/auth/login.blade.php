<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk - SiklusSampah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-page font-sans text-slate-800 antialiased">
    <div class="auth-panel auth-panel--narrow">
        <div class="auth-brand">
            <div class="mx-auto mb-3 flex size-12 items-center justify-center rounded-xl bg-white/15 text-base font-extrabold ring-1 ring-white/20">
                ER
            </div>
            <h1 class="text-2xl font-bold">EcoRisk</h1>
            <p class="mt-1 text-xs text-emerald-100">Portal pengelolaan lingkungan</p>
        </div>

        <div class="auth-content space-y-5">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Masuk ke akun</h2>
                <p class="mt-1 text-sm text-slate-500">Akses portal sesuai peran akun Anda.</p>
            </div>

            @if ($errors->any())
            <div class="rounded-lg border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700" role="alert">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="emailInput" class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat email</label>
                    <input
                        type="email"
                        name="email"
                        id="emailInput"
                        autocomplete="email"
                        required
                        value="{{ old('email') }}"
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none"
                        placeholder="nama@email.com" />
                </div>

                <div>
                    <label for="passwordInput" class="mb-1.5 block text-sm font-semibold text-slate-700">Kata sandi</label>
                    <input
                        type="password"
                        name="password"
                        id="passwordInput"
                        autocomplete="current-password"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none"
                        placeholder="Masukkan kata sandi" />
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex cursor-pointer items-center gap-2 text-neutral-600">
                        <input type="checkbox" name="remember" class="rounded text-emerald-600" />
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-emerald-700 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-800 focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 focus:outline-none">
                    Masuk Sekarang
                </button>
            </form>

            <div class="border-t border-slate-100 pt-4 text-center text-sm text-slate-600">
                <p>
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-emerald-700 hover:text-emerald-900">Daftar sebagai warga</a>
                </p>
                <a
                    href="{{ route('home') }}"
                    class="mt-3 inline-block text-xs font-medium text-slate-500 hover:text-slate-800">Kembali ke halaman utama</a>
            </div>
        </div>
    </div>
</body>

</html>
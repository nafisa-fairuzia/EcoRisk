<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Akun Warga - SiklusSampah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-page font-sans text-slate-800 antialiased">
    <div class="auth-panel">
        <div class="auth-brand">
            <div class="mx-auto mb-3 flex size-12 items-center justify-center rounded-xl bg-white/15 text-base font-extrabold ring-1 ring-white/20">
                ER
            </div>
            <h1 class="text-2xl font-bold">Daftar Akun Warga</h1>
            <p class="mt-1 text-xs text-emerald-100">Buat akun untuk mengirim dan memantau laporan.</p>
        </div>

        <div class="auth-content">
            @if ($errors->any())
            <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700" role="alert">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="grid gap-x-5 gap-y-4 sm:grid-cols-2">
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
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none"
                        placeholder="Nama lengkap" />
                </div>
                <div class="sm:col-span-2">
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        autocomplete="email"
                        required
                        value="{{ old('email') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none"
                        placeholder="nama@email.com" />
                </div>
                <div class="grid grid-cols-1 gap-4 sm:col-span-2 sm:grid-cols-2">
                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">Kata sandi</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            autocomplete="new-password"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none"
                            placeholder="Minimal 6 karakter" />
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-slate-700">Ulangi sandi</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            autocomplete="new-password"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none"
                            placeholder="Ulangi sandi" />
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
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none"
                        placeholder="Jalan dan nomor rumah" />
                </div>
                <div class="grid grid-cols-2 gap-4 sm:col-span-2">
                    <div>
                        <label for="rt" class="mb-1.5 block text-sm font-semibold text-slate-700">RT</label>
                        <input
                            type="text"
                            name="rt"
                            id="rt"
                            required
                            value="{{ old('rt') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                    </div>
                    <div>
                        <label for="rw" class="mb-1.5 block text-sm font-semibold text-slate-700">RW</label>
                        <input
                            type="text"
                            name="rw"
                            id="rw"
                            required
                            value="{{ old('rw') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none" />
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label for="telepon" class="mb-1.5 block text-sm font-semibold text-slate-700">Nomor telepon</label>
                    <input
                        type="tel"
                        name="telepon"
                        id="telepon"
                        autocomplete="tel"
                        value="{{ old('telepon') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none"
                        placeholder="08xx-xxxx-xxxx" />
                </div>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-emerald-700 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-800 focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 focus:outline-none sm:col-span-2">
                    Daftar & Masuk ke Portal Warga
                </button>

                <div class="border-t border-slate-100 pt-4 text-center sm:col-span-2">
                    <p class="text-sm text-slate-600">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:text-emerald-900">Masuk</a>
                    </p>
                    <a
                        href="{{ route('home') }}"
                        class="mt-3 inline-block text-xs font-medium text-slate-500 hover:text-slate-800">
                        Kembali ke halaman utama
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
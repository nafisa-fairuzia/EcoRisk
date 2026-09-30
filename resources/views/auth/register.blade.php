<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Warga - SiklusSampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-neutral-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-6 space-y-4 border border-neutral-200">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-teal-800">Daftar Akun Warga</h1>
            <p class="text-xs text-neutral-500 mt-1">Daftarkan diri Anda untuk mengajukan pengaduan kebersihan lingkungan.</p>
        </div>

        @if($errors->any())
            <div class="p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-neutral-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" required value="{{ old('name') }}" class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300" placeholder="Rian Pratama">
            </div>
            <div>
                <label class="block text-xs font-semibold text-neutral-700 mb-1">Email</label>
                <input type="email" name="email" required value="{{ old('email') }}" class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300" placeholder="rian@warga.id">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-semibold text-neutral-700 mb-1">Kata Sandi</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300" placeholder="Minimal 6 digit">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-neutral-700 mb-1">Ulangi Sandi</label>
                    <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300" placeholder="Ulangi sandi">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-neutral-700 mb-1">Alamat Rumah</label>
                <input type="text" name="alamat" required value="{{ old('alamat') }}" class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300" placeholder="Jl. Melati No. 10">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-semibold text-neutral-700 mb-1">RT</label>
                    <input type="text" name="rt" required value="{{ old('rt') }}" class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-neutral-700 mb-1">RW</label>
                    <input type="text" name="rw" required value="{{ old('rw') }}" class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-neutral-700 mb-1">No. WhatsApp / Telepon</label>
                <input type="tel" name="telepon" value="{{ old('telepon') }}" class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300" placeholder="0812-xxxx-xxxx">
            </div>

            <button type="submit" class="w-full py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs shadow-md transition-colors cursor-pointer">
                Daftar & Masuk ke Portal Warga
            </button>

            <div class="text-center pt-2">
                <p class="text-xs text-neutral-500 mb-2">
                    Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-teal-700 hover:underline">Masuk</a>
                </p>
                <a href="{{ route('home') }}" class="text-xs text-neutral-500 hover:underline">
                    &larr; Kembali ke Halaman Utama
                </a>
            </div>
        </form>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SiklusSampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-neutral-100 min-h-screen flex items-center justify-center p-4 antialiased">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-neutral-200 overflow-hidden">
        
        <!-- Header Brand -->
        <div class="p-6 bg-emerald-700 text-white text-center">
            <div class="w-12 h-12 bg-white/20 rounded-2xl mx-auto flex items-center justify-center mb-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight">SiklusSampah</h1>
            <p class="text-xs text-emerald-100 mt-1">Sistem Tata Kelola & Pengelolaan Sampah Terpadu</p>
        </div>

        <!-- Form Login -->
        <div class="p-6 space-y-4">
            <h2 class="text-base font-bold text-neutral-900">Masuk ke Akun Anda</h2>
            <p class="text-xs text-neutral-500">Sistem otomatis mendeteksi role Anda (Admin, Petugas, atau Warga) setelah masuk.</p>

            @if($errors->any())
                <div class="p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-neutral-700 mb-1">Alamat Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="emailInput"
                        required 
                        value="{{ old('email') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                        placeholder="nama@email.com"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-neutral-700 mb-1">Kata Sandi</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="passwordInput"
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                        placeholder="••••••••"
                    >
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-neutral-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-emerald-600">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button 
                    type="submit" 
                    class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-md transition-colors cursor-pointer"
                >
                    Masuk Sekarang
                </button>
            </form>

            <p class="pt-4 border-t border-neutral-200 text-center text-xs text-neutral-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-emerald-700 hover:underline">Daftar sebagai warga</a>
            </p>
        </div>
    </div>
</body>
</html>

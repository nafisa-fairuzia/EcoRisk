<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiklusSampah - Portal Pengelolaan Sampah Lingkungan Sukamaju</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-neutral-50 text-neutral-900 font-sans antialiased min-h-screen flex flex-col" x-data='{"authModal": @json($errors->any()), "authTab": @json(old("_auth_form") === "register" ? "register" : "login") }'>

    <!-- Top Navbar -->
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-bold shadow-xs">
                    SS
                </div>
                <div>
                    <span class="text-xl font-bold tracking-tight text-neutral-900">SiklusSampah</span>
                    <span class="hidden sm:inline-block ml-2 text-[10px] font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                        Kelurahan Sukamaju
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <button 
                    @click="authModal = true; authTab = 'login'" 
                    class="px-3.5 py-2 text-xs font-bold text-neutral-700 hover:bg-neutral-100 rounded-xl transition-colors cursor-pointer"
                >
                    Masuk
                </button>
                <button 
                    @click="authModal = true; authTab = 'register'" 
                    class="px-4 py-2 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-xs transition-colors cursor-pointer"
                >
                    Daftar Warga
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="py-16 sm:py-24 bg-gradient-to-b from-emerald-50/70 via-neutral-50 to-neutral-50 border-b border-neutral-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center space-y-6">
            <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-semibold">
                Sistem Tata Kelola & Pengangkutan Sampah Terpadu
            </span>
            <h1 class="text-3xl sm:text-5xl font-black text-neutral-900 tracking-tight leading-tight">
                Bersih Lingkungannya, Terkelola Sampahnya
            </h1>
            <p class="text-sm sm:text-base text-neutral-600 max-w-2xl mx-auto leading-relaxed">
                Platform aduan tumpukan sampah bagi warga, rute kerja armada motor roda 3, penimbangan pemilahan di TPS3R, dan integrasi bank sampah mandiri.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-3">
                <button 
                    @click="authModal = true; authTab = 'register'" 
                    class="w-full sm:w-auto px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md transition-all cursor-pointer"
                >
                    Daftar Akun Warga
                </button>
                <button 
                    @click="authModal = true; authTab = 'login'" 
                    class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white hover:bg-neutral-100 text-neutral-800 font-bold text-sm border border-neutral-300 shadow-2xs transition-colors cursor-pointer"
                >
                    Masuk ke Sistem
                </button>
            </div>
        </div>
    </section>

    <!-- 3 Pilar Fitur -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- 1. Warga -->
            <div class="bg-white p-6 rounded-2xl border border-neutral-200 shadow-2xs space-y-3">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold">
                    W
                </div>
                <h3 class="text-base font-bold text-neutral-900">1. Portal Warga</h3>
                <p class="text-xs text-neutral-500 leading-relaxed">
                    Daftar akun warga, laporkan tumpukan sampah atau TPS penuh dengan alamat dan foto, serta pantau tindak lanjut petugas di lapangan.
                </p>
                <button @click="authModal = true; authTab = 'register'" class="text-xs font-bold text-teal-700 hover:underline cursor-pointer">
                    Daftar Akun Baru &rarr;
                </button>
            </div>

            <!-- 2. Petugas -->
            <div class="bg-white p-6 rounded-2xl border border-neutral-200 shadow-2xs space-y-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                    P
                </div>
                <h3 class="text-base font-bold text-neutral-900">2. Petugas Lapangan</h3>
                <p class="text-xs text-neutral-500 leading-relaxed">
                    Tindak lanjuti aduan warga, jalankan armada motor roda 3, catat timbangan pilah TPS3R, dan terbitkan surat jalan mitra.
                </p>
                <button @click="authModal = true; authTab = 'login'" class="text-xs font-bold text-emerald-700 hover:underline cursor-pointer">
                    Masuk Akun Petugas &rarr;
                </button>
            </div>

            <!-- 3. Admin -->
            <div class="bg-white p-6 rounded-2xl border border-neutral-200 shadow-2xs space-y-3">
                <div class="w-10 h-10 rounded-xl bg-neutral-900 text-white flex items-center justify-center font-bold">
                    A
                </div>
                <h3 class="text-base font-bold text-neutral-900">3. Admin Kelurahan</h3>
                <p class="text-xs text-neutral-500 leading-relaxed">
                    Pusat kendali operasional, monitoring performa penanganan kebersihan seluruh RW, audit laporan, dan pengelolaan personil.
                </p>
                <button @click="authModal = true; authTab = 'login'" class="text-xs font-bold text-neutral-900 hover:underline cursor-pointer">
                    Masuk Panel Admin &rarr;
                </button>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-auto border-t border-neutral-200 bg-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-neutral-500">
            <div><strong>SiklusSampah</strong> · Kelurahan Sukamaju © 2026</div>
            <div class="flex items-center gap-3">
                <button @click="authModal = true; authTab = 'login'" class="hover:underline">Masuk</button>
                <span>·</span>
                <button @click="authModal = true; authTab = 'register'" class="hover:underline font-bold text-emerald-700">Daftar Akun Warga</button>
            </div>
        </div>
    </footer>

    <!-- MODAL AUTENTIKASI (LOGIN & REGISTER) -->
    <div x-show="authModal" x-cloak class="fixed inset-0 z-50 bg-neutral-950/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div @click.away="authModal = false" class="bg-white rounded-2xl max-w-md w-full max-h-[92vh] overflow-y-auto shadow-2xl border border-neutral-200">

            @if($errors->any())
                <div class="mx-5 mt-5 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs">
                    {{ $errors->first() }}
                </div>
            @endif
            
            <!-- Modal Header -->
            <div class="p-4 border-b border-neutral-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-neutral-900" x-text="authTab === 'login' ? 'Masuk ke SiklusSampah' : 'Daftar Akun Warga Baru'"></h3>
                    <p class="text-[11px] text-neutral-500">Kelurahan Sukamaju</p>
                </div>
                <button @click="authModal = false" class="text-neutral-400 hover:text-neutral-900 text-lg">&times;</button>
            </div>

            <!-- Tab Switcher -->
            <div class="p-2 bg-neutral-50 border-b border-neutral-200 flex gap-2">
                <button 
                    @click="authTab = 'login'"
                    :class="authTab === 'login' ? 'bg-white text-neutral-900 shadow-2xs font-bold' : 'text-neutral-500'"
                    class="flex-1 py-1.5 rounded-lg text-xs transition-all cursor-pointer"
                >
                    Masuk (Login)
                </button>
                <button 
                    @click="authTab = 'register'"
                    :class="authTab === 'register' ? 'bg-white text-emerald-800 shadow-2xs font-bold' : 'text-neutral-500'"
                    class="flex-1 py-1.5 rounded-lg text-xs transition-all cursor-pointer"
                >
                    Daftar Akun Warga
                </button>
            </div>

            <!-- FORM LOGIN -->
            <div x-show="authTab === 'login'" class="p-5 space-y-4">
                <form action="{{ route('login') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="_auth_form" value="login">
                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 mb-1">Email</label>
                        <input type="email" name="email" id="loginEmail" required value="{{ old('email') }}" class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300" placeholder="nama@email.com">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 mb-1">Kata Sandi</label>
                        <input type="password" name="password" id="loginPass" required class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300" placeholder="••••••••">
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition-colors cursor-pointer">
                        Masuk Sekarang
                    </button>
                </form>

                <div class="text-center pt-2 border-t border-neutral-100">
                    <span class="text-[11px] text-neutral-500">Belum memiliki akun warga? </span>
                    <button type="button" @click="authTab = 'register'" class="text-[11px] text-emerald-700 font-bold hover:underline cursor-pointer">
                        Daftar di sini
                    </button>
                </div>
            </div>

            <!-- FORM REGISTER WARGA -->
            <div x-show="authTab === 'register'" class="p-5 space-y-3">
                <form action="{{ route('register') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="_auth_form" value="register">
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
                            <input type="password" name="password" required class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300" placeholder="••••••••">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-neutral-700 mb-1">Ulangi Sandi</label>
                            <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300" placeholder="••••••••">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 mb-1">Alamat Domisili</label>
                        <input type="text" name="alamat" required value="{{ old('alamat') }}" class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300" placeholder="Jl. Melati No. 10">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-neutral-700 mb-1">RT</label>
                            <input type="text" name="rt" required value="{{ old('rt') }}" class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300" placeholder="RT">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-neutral-700 mb-1">RW</label>
                            <input type="text" name="rw" required value="{{ old('rw') }}" class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300" placeholder="RW">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 mb-1">No. WhatsApp / Telepon</label>
                        <input type="tel" name="telepon" value="{{ old('telepon') }}" class="w-full px-3 py-2 border rounded-xl text-xs border-neutral-300" placeholder="0812-xxxx-xxxx">
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs shadow-md transition-colors cursor-pointer">
                        Daftar Akun Warga & Masuk
                    </button>
                </form>
            </div>

        </div>
    </div>

</body>
</html>

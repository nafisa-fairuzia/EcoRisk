<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SiklusSampah' }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js untuk interaktivitas modal yang ringan -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
    </style>
</head>
<body class="bg-neutral-50 text-neutral-900 min-h-screen flex flex-col antialiased overflow-x-hidden" x-data="{ profileModalOpen: false }">

    <!-- Top Navigation Bar: Bersih, Ramping, Tanpa Pilihan Role, Hanya Avatar di Pojok Kanan Atas -->
    <header class="sticky top-0 z-30 w-full bg-white/95 backdrop-blur-md border-b border-neutral-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <!-- Brand Logo -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-sm shadow-emerald-200 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-bold tracking-tight text-neutral-900">SiklusSampah</span>
                        <span class="hidden sm:inline-block text-[11px] font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                            @if(auth()->user()->role === 'admin') Panel Administrasi
                            @elseif(auth()->user()->role === 'petugas') Portal Kerja Lapangan
                            @else Layanan Warga @endif
                        </span>
                    </div>
                    <p class="hidden md:block text-xs text-neutral-500 mt-0.5">
                        Sistem Tata Kelola & Pengelolaan Sampah Terpadu
                    </p>
                </div>
            </div>

            <!-- Tombol Avatar Profil di Pojok Kanan Atas (Membuka Dashboard Profil) -->
            <button 
                type="button" 
                @click="profileModalOpen = true"
                class="flex items-center gap-2 p-1 rounded-xl hover:bg-neutral-100 transition-all cursor-pointer focus:outline-none"
                title="Buka Dashboard Profil Akun"
            >
                <div class="relative">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-xs text-white shadow-xs
                        {{ auth()->user()->role === 'admin' ? 'bg-neutral-900' : (auth()->user()->role === 'petugas' ? 'bg-emerald-600' : 'bg-teal-600') }}">
                        {{ auth()->user()->initials }}
                    </div>
                    <!-- Status dot hijau online -->
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                </div>
            </button>
        </div>
    </header>

    <!-- Notifikasi Flash Toast -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between text-sm shadow-xs">
                <span>{{ session('success') }}</span>
                <span class="text-xs text-emerald-600 font-semibold cursor-pointer" onclick="this.parentElement.remove()">&times;</span>
            </div>
        </div>
    @endif

    <!-- Konten Utama Viewport -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer Minimalis -->
    <footer class="mt-auto border-t border-neutral-200 bg-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-neutral-500">
            <div>
                <span class="font-bold text-neutral-800">SiklusSampah</span> · Tata Kelola & Penanganan Sampah Terpadu
            </div>
            <div class="flex items-center gap-4">
                <span>TPS3R Sentral Sukamaju</span>
                <span>·</span>
                <span>Bank Sampah Mandiri</span>
                <span>·</span>
                <button @click="profileModalOpen = true" class="text-emerald-700 hover:underline font-semibold">
                    Dashboard Profil ({{ auth()->user()->name }})
                </button>
            </div>
        </div>
    </footer>

    <!-- MODAL DASHBOARD PROFIL AKUN -->
    <div 
        x-show="profileModalOpen" 
        x-cloak 
        class="fixed inset-0 z-50 bg-neutral-950/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4"
    >
        <div 
            @click.away="profileModalOpen = false"
            class="bg-white rounded-2xl max-w-lg w-full max-h-[92vh] overflow-y-auto shadow-2xl border border-neutral-200"
        >
            <!-- Header Modal -->
            <div class="flex items-center justify-between p-4 sm:p-5 border-b border-neutral-100 bg-neutral-50/70">
                <span class="text-xs font-semibold uppercase tracking-wider text-neutral-700">Dashboard Profil Akun</span>
                <button @click="profileModalOpen = false" class="w-8 h-8 rounded-full hover:bg-neutral-200 flex items-center justify-center text-neutral-500 hover:text-neutral-900 transition-colors">
                    &times;
                </button>
            </div>

            <!-- Identitas Pengguna & Role Resmi -->
            <div class="p-5 sm:p-6 space-y-5">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-xl font-bold text-white shadow-md shrink-0
                        {{ auth()->user()->role === 'admin' ? 'bg-neutral-900' : (auth()->user()->role === 'petugas' ? 'bg-emerald-600' : 'bg-teal-600') }}">
                        {{ auth()->user()->initials }}
                    </div>
                    <div class="min-w-0 flex-1 space-y-1">
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-bold text-neutral-900 truncate">{{ auth()->user()->name }}</h3>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Aktif</span>
                        </div>
                        
                        <!-- Lencana Role Jelas & Terlihat -->
                        <div>
                            @if(auth()->user()->role === 'admin')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-neutral-900 text-white">
                                    Role: Administrator Sistem Kelurahan
                                </span>
                            @elseif(auth()->user()->role === 'petugas')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-600 text-white">
                                    Role: Petugas Lapangan (Zona A)
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-teal-700 text-white">
                                    Role: Warga Masyarakat (Pelapor)
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-neutral-500 truncate font-mono">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <!-- Info Hak Akses & Penugasan -->
                <div class="p-4 rounded-xl bg-neutral-50 border border-neutral-200 text-xs space-y-2">
                    <span class="font-bold text-neutral-800 block">Informasi Akun:</span>
                    <div class="flex justify-between">
                        <span class="text-neutral-500">ID Pengguna:</span>
                        <span class="font-mono font-bold text-neutral-900">#{{ auth()->user()->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-500">Wilayah / Penugasan:</span>
                        <span class="font-semibold text-neutral-800">{{ auth()->user()->zona_wilayah ?? 'Kelurahan Sukamaju' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-500">Nomor Telepon:</span>
                        <span class="font-semibold text-neutral-800">{{ auth()->user()->telepon ?? '-' }}</span>
                    </div>

                    <div class="pt-2 border-t border-neutral-200 text-neutral-600 leading-relaxed">
                        @if(auth()->user()->role === 'admin')
                            Akses penuh pengelolaan personil petugas kebersihan, data warga, master kategori sampah, dan audit rekapitulasi wilayah.
                        @elseif(auth()->user()->role === 'petugas')
                            Wewenang merespons aduan di lapangan, menimbang hasil pilah di TPS3R, serta menerbitkan surat jalan penyaluran daur ulang.
                        @else
                            Wewenang membuat laporan pengaduan sampah liar/tumpukan di lingkungan RT/RW dan memantau status tindak lanjut armada.
                        @endif
                    </div>
                </div>

                <!-- Tombol Logout -->
                <form action="{{ route('logout') }}" method="POST" class="pt-3 border-t border-neutral-100">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 text-xs font-bold transition-colors cursor-pointer">
                        Keluar dari Akun (Logout)
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>

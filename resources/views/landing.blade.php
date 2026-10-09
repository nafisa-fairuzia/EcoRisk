<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EcoRisk — Kelola Risiko Pulihkan Lingkungan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased">
    <header class="sticky top-0 z-30 border-b border-neutral-200 bg-white/95 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 font-bold text-white shadow-xs">
                    ER
                </div>
                <div>
                    <span class="text-xl font-bold tracking-tight text-neutral-900">EcoRisk</span>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <a
                    href="{{ route('login') }}"
                    class="cursor-pointer rounded-xl px-3.5 py-2 text-xs font-bold text-neutral-700 transition-colors hover:bg-neutral-100"
                >
                    Masuk
                </a>
                <a
                    href="{{ route('register') }}"
                    class="cursor-pointer rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-xs transition-colors hover:bg-emerald-700"
                >
                    Daftar Warga
                </a>
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="landing-hero relative isolate flex items-center overflow-hidden px-4 py-8 text-white sm:px-6 sm:py-10 lg:py-12">
        <div class="relative z-10 container mx-auto max-w-7xl px-2 sm:px-6">
            <div class="grid grid-cols-1 items-center gap-8 lg:grid-cols-12 lg:gap-10">
                <!-- Left Hero Content -->
                <div class="space-y-4 lg:col-span-7">
                    <span class="inline-block rounded-full border border-emerald-700/50 bg-emerald-800/80 px-3 py-1 text-xs font-semibold tracking-wider text-emerald-200 uppercase">
                        Platform Aksi Lingkungan
                    </span>
                    <h1 class="text-4xl leading-tight font-bold tracking-tight text-white md:text-5xl lg:text-5xl">
                        Kelola sampah.<br />
                        Kurangi risiko.<br />
                        <span class="text-emerald-400">Pulihkan lingkungan.</span>
                    </h1>
                    <p class="max-w-xl text-base leading-relaxed text-slate-300 md:text-lg">
                        Laporkan titik rawan, prioritaskan penanganan berdasarkan dampak lingkungan, dan lihat hasil
                        nyata yang tercipta untuk komunitas.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-2">
                        <a
                            href="{{ route('register') }}"
                            class="rounded-lg bg-lime-400 px-6 py-3 font-semibold text-emerald-950 shadow-md transition duration-200 hover:bg-lime-300"
                        >
                            Laporkan Risiko Lingkungan
                        </a>
                        <a
                            href="{{ route('login') }}"
                            class="rounded-lg border border-emerald-700/50 bg-emerald-900/80 px-6 py-3 font-medium text-white transition duration-200 hover:bg-emerald-800"
                        >
                            Masuk ke Portal
                        </a>
                    </div>
                    <div class="flex flex-wrap gap-x-5 gap-y-2 pt-2 text-xs text-slate-300">
                        <span class="flex items-center gap-1.5"
                            ><svg class="h-4 w-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Prioritas berbasis risiko</span>
                        <span class="flex items-center gap-1.5"
                            ><svg class="h-4 w-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Penanganan terukur</span>
                        <span class="flex items-center gap-1.5"
                            ><svg class="h-4 w-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Dampak transparan</span>
                    </div>
                </div>

                <!-- Right Glassmorphism Card -->
                <div class="lg:col-span-5">
                    <div class="space-y-4 rounded-2xl border border-emerald-700/40 bg-emerald-900/40 p-5 shadow-2xl backdrop-blur-md">
                        <div>
                            <span class="text-xs font-semibold tracking-wider text-emerald-400 uppercase">PENDEKATAN KAMI</span>
                            <h3 class="mt-1 text-xl font-bold text-white">Lingkungan lebih tangguh</h3>
                        </div>
                        <div class="space-y-4 text-sm">
                            <div class="flex gap-3 rounded-xl border border-emerald-800/30 bg-emerald-950/50 p-3.5">
                                <div class="h-fit rounded-lg bg-emerald-800/50 p-2 text-emerald-400">📍</div>
                                <div>
                                    <h4 class="font-semibold text-white">Kenali titik berisiko</h4>
                                    <p class="mt-0.5 text-xs text-slate-300">
                                        Catat kondisi lokasi agar dampak cepat teridentifikasi.
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-3 rounded-xl border border-emerald-800/30 bg-emerald-950/50 p-3.5">
                                <div class="h-fit rounded-lg bg-emerald-800/50 p-2 text-emerald-400">⚡</div>
                                <div>
                                    <h4 class="font-semibold text-white">Dahulukan yang paling kritis</h4>
                                    <p class="mt-0.5 text-xs text-slate-300">
                                        Petugas memprioritaskan penanganan berdasarkan skor risiko.
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-3 rounded-xl border border-emerald-800/30 bg-emerald-950/50 p-3.5">
                                <div class="h-fit rounded-lg bg-emerald-800/50 p-2 text-emerald-400">📊</div>
                                <div>
                                    <h4 class="font-semibold text-white">Ukur dampak bersama</h4>
                                    <p class="mt-0.5 text-xs text-slate-300">
                                        Pantau penurunan timbulan dan emisi karbon wilayah Anda.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between border-t border-emerald-800/50 pt-2 text-xs font-semibold text-slate-400">
                            <span>WARGA</span>
                            <span>PETUGAS</span>
                            <span>MITRA</span>
                            <span>LINGKUNGAN</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 1: PERAN KOLABORATIF -->
    <section class="bg-white py-20">
        <div class="container mx-auto max-w-7xl px-6">
            <div class="mx-auto mb-12 max-w-2xl text-center">
                <span class="rounded-full border border-emerald-100 bg-emerald-50 px-3 py-1 text-xs font-bold tracking-wider text-emerald-600 uppercase">Satu Ekosistem Terintegrasi</span>
                <h2 class="mt-3 text-3xl font-bold text-slate-900">Peran Kolaboratif untuk</h2>
                <p class="mt-2 text-sm text-slate-600">
                    Sistem dirancang khusus untuk menghubungkan warga, petugas lapangan, dan pengelola wilayah secara
                    real-time dan efisien.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <!-- Card 1 -->
                <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="space-y-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-xl font-bold text-blue-600">
                            👤
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Portal Warga</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Laporkan titik penumpukan sampah atau TPS liar lengkap dengan foto, deskripsi lokasi, dan
                            estimasi beban material secara instan.
                        </p>
                    </div>
                    <div class="mt-6 border-t border-slate-100 pt-6">
                        <a
                            href="{{ route('register') }}"
                            class="flex items-center gap-1 text-sm font-semibold text-emerald-700 hover:text-emerald-800"
                        >
                            Daftar Akun Warga <span>→</span>
                        </a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="space-y-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-xl font-bold text-amber-600">
                            🚚
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Petugas Lapangan</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Terima tugas penanganan berbasis prioritas otomatis, perbarui status penimbangan TPS3R, dan
                            perbarui status laporan.
                        </p>
                    </div>
                    <div class="mt-6 border-t border-slate-100 pt-6">
                        <a
                            href="{{ route('login') }}"
                            class="flex items-center gap-1 text-sm font-semibold text-emerald-700 hover:text-emerald-800"
                        >
                            Masuk Portal Petugas <span>→</span>
                        </a>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="space-y-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-xl font-bold text-emerald-600">
                            🏛️
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Admin Kelurahan</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Pantau heatmap kerawanan lingkungan tiap RT/RW, validasi penanganan armada, dan evaluasi
                            capaian penurunan emisi gas rumah kaca.
                        </p>
                    </div>
                    <div class="mt-6 border-t border-slate-100 pt-6">
                        <a
                            href="{{ route('login') }}"
                            class="flex items-center gap-1 text-sm font-semibold text-emerald-700 hover:text-emerald-800"
                        >
                            Panel Kendali Utama <span>→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: PROSES TRANSPARAN (FIXED OVERLAPPING ISSUES) -->
    <section class="bg-slate-900 py-20 text-white">
        <div class="container mx-auto max-w-7xl px-6">
            <div class="mx-auto mb-16 max-w-2xl text-center">
                <span class="rounded-full border border-slate-700 bg-slate-800 px-3 py-1 text-xs font-bold tracking-wider text-emerald-400 uppercase">PROSES TRANSPARAN</span>
                <h2 class="mt-3 text-3xl font-bold">Bagaimana EcoRisk Bekerja?</h2>
                <p class="mt-2 text-sm text-slate-400">
                    Dari pengaduan sederhana hingga tindakan nyata di lapangan, berikut 4 langkah utama yang dipastikan.
                </p>
            </div>

            <div class="relative grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                <!-- Step 1 -->
                <div class="relative flex h-full flex-col justify-between rounded-xl border border-slate-700 bg-slate-800/80 p-6">
                    <div>
                        <span class="mb-3 block text-4xl font-extrabold text-slate-600">01</span>
                        <h3 class="mb-2 text-lg font-bold text-white">1. Pelaporan Warga</h3>
                        <p class="text-xs leading-relaxed text-slate-400">
                            Warga mengunggah foto lokasi, memilih jenis sampah, dan menandai koordinat wilayah rawan.
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative flex h-full flex-col justify-between rounded-xl border border-slate-700 bg-slate-800/80 p-6">
                    <div>
                        <span class="mb-3 block text-4xl font-extrabold text-slate-600">02</span>
                        <h3 class="mb-2 text-lg font-bold text-white">2. Kalkulasi Risiko</h3>
                        <p class="text-xs leading-relaxed text-slate-400">
                            Sistem secara otomatis menghitung tingkat Risiko Real-Time (tingkat bahaya berdasarkan
                            volume, jenis limbah, dan jarak pemukiman).
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative flex h-full flex-col justify-between rounded-xl border border-slate-700 bg-slate-800/80 p-6">
                    <div>
                        <span class="mb-3 block text-4xl font-extrabold text-slate-600">03</span>
                        <h3 class="mb-2 text-lg font-bold text-white">3. Penjemputan Prioritas</h3>
                        <p class="text-xs leading-relaxed text-slate-400">
                            Petugas menerima notifikasi tugas darurat sehingga pengangkutan armada dilakukan dengan rute
                            terhitung.
                        </p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="relative flex h-full flex-col justify-between rounded-xl border border-slate-700 bg-slate-800/80 p-6">
                    <div>
                        <span class="mb-3 block text-4xl font-extrabold text-slate-600">04</span>
                        <h3 class="mb-2 text-lg font-bold text-white">4. Pemulihan & Re-Use</h3>
                        <p class="text-xs leading-relaxed text-slate-400">
                            Sampah dipilah di TPS3R, angka terdaur ulang dicatat ke data pengurangan emisi CO2 yang
                            transparan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: CAPAIAN KEBERSIHAN -->
    <section class="bg-slate-50 py-20">
        <div class="container mx-auto max-w-7xl px-6">
            <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-12">
                <!-- Left Title & CTA -->
                <div class="space-y-4 lg:col-span-5">
                    <span class="rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-bold tracking-wider text-emerald-700 uppercase">
                        DAMPAK TERUKUR
                    </span>
                    <h2 class="text-3xl leading-snug font-bold text-slate-900">Capaian Kebersihan Kelurahan</h2>
                    <p class="text-sm leading-relaxed text-slate-600">
                        Aktivitas kolektif dari ribuan warga dan petugas telah membuahkan hasil signifikan yang terukur
                        dan terencana secara berkelanjutan.
                    </p>
                    <div class="pt-2">
                        <a
                            href="{{ route('register') }}"
                            class="inline-block rounded-lg bg-emerald-800 px-6 py-3 text-sm font-semibold text-white shadow transition hover:bg-emerald-900"
                        >
                            Ikut Berkontribusi Sekarang
                        </a>
                    </div>
                </div>

                <!-- Right Metric Grid -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:col-span-7">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <span class="text-3xl font-extrabold text-emerald-600">1,240+ <span class="text-base font-medium text-slate-500">kg</span></span>
                        <h4 class="mt-1 text-sm font-semibold text-slate-800">Sampah Tertangani</h4>
                        <p class="mt-1 text-xs text-slate-500">Telah didaur ulang dan TPS3R difungsikan.</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <span class="text-3xl font-extrabold text-emerald-600">98.2%</span>
                        <h4 class="mt-1 text-sm font-semibold text-slate-800">Laporan Selesai</h4>
                        <p class="mt-1 text-xs text-slate-500">Tingkat responsivitas penanganan petugas.</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <span class="text-3xl font-extrabold text-emerald-600">3.4 <span class="text-base font-medium text-slate-500">Ton</span></span>
                        <h4 class="mt-1 text-sm font-semibold text-slate-800">Emisi CO2 Dicegah</h4>
                        <p class="mt-1 text-xs text-slate-500">Estimasi pencegahan gas rumah kaca.</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <span class="text-3xl font-extrabold text-emerald-600">06 <span class="text-base font-medium text-slate-500">RW</span></span>
                        <h4 class="mt-1 text-sm font-semibold text-slate-800">Cakupan Aktif</h4>
                        <p class="mt-1 text-xs text-slate-500">Seluruh wilayah terhubung.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: TESTIMONIALS -->
    <section class="bg-white py-20">
        <div class="container mx-auto max-w-7xl px-6">
            <div class="mx-auto mb-12 max-w-2xl text-center">
                <span class="rounded-full border border-emerald-100 bg-emerald-50 px-3 py-1 text-xs font-bold tracking-wider text-emerald-600 uppercase">SUARA KOMUNITAS</span>
                <h2 class="mt-3 text-3xl font-bold text-slate-900">Apa Kata Mereka Tentang EcoRisk?</h2>
                <p class="mt-2 text-sm text-slate-600">
                    Pengalaman nyata dari warga dan pengurus RT/RW dalam memanfaatkan sistem EcoRisk.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <!-- Testimonial 1 -->
                <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-slate-50 p-6">
                    <p class="text-xs leading-relaxed text-slate-600 italic">
                        "Dulu tumpukan sampah di pojok RT 03 sering dibiarkan berminggu-minggu. Sejak pakai EcoRisk,
                        begitu difoto dan dilaporkan, sorenya petugas armada langsung datang mengangkut."
                    </p>
                    <div class="mt-6 flex items-center gap-3 border-t border-slate-200 pt-4">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-600 text-xs font-bold text-white">
                            BP
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-slate-900">Bpk. H. Supriyanto</h4>
                            <p class="text-[11px] text-slate-500">Ketua RW 02</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-slate-50 p-6">
                    <p class="text-xs leading-relaxed text-slate-600 italic">
                        "Aplikasi ini sangat bagus. Aku suka fitur skor risikonya karena jadi bisa tahu daerah mana yang
                        paling rawan, sistemnya cepat dan warga sekitar sangat terbantu sekali."
                    </p>
                    <div class="mt-6 flex items-center gap-3 border-t border-slate-200 pt-4">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">
                            AR
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-slate-900">Ahmad Rizky</h4>
                            <p class="text-[11px] text-slate-500">Petugas Kebersihan Lapangan</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-slate-50 p-6">
                    <p class="text-xs leading-relaxed text-slate-600 italic">
                        "Sebagai warga biasa, saya merasa suara dan keluhan kami cepat ditanggapi. Transparansi grafik
                        penanganan dan dampaknya bikin kami makin semangat menjaga kebersihan lingkungan."
                    </p>
                    <div class="mt-6 flex items-center gap-3 border-t border-slate-200 pt-4">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-600 text-xs font-bold text-white">
                            SN
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-slate-900">Siti Nurhaliza</h4>
                            <p class="text-[11px] text-slate-500">Warga RT 01 / RW 03</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 5: FAQ -->
    <section class="border-t border-slate-200 bg-slate-50 py-20">
        <div class="container mx-auto max-w-4xl px-6">
            <div class="mb-12 text-center">
                <span class="rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-bold tracking-wider text-emerald-600 uppercase">PUSAT BANTUAN</span>
                <h2 class="mt-3 text-3xl font-bold text-slate-900">Pertanyaan Sering Diajukan</h2>
                <p class="mt-2 text-sm text-slate-600">
                    Informasi penting mengenai pembuatan akun, cara pelaporan, dan operasional layanan.
                </p>
            </div>

            <div class="space-y-4">
                <details class="group [&_summary::-webkit-details-marker]:hidden rounded-xl border border-slate-200 bg-white p-5">
                    <summary class="flex cursor-pointer items-center justify-between text-sm font-semibold text-slate-800">
                        <span>Apakah saya harus membayar untuk membuat akun warga?</span>
                        <span class="font-bold text-emerald-600 transition group-open:rotate-180">+</span>
                    </summary>
                    <p class="mt-3 text-xs leading-relaxed text-slate-600">
                        Tidak, seluruh fasilitas pelaporan dan akses platform EcoRisk disediakan secara gratis untuk
                        seluruh warga Kelurahan .
                    </p>
                </details>

                <details class="group [&_summary::-webkit-details-marker]:hidden rounded-xl border border-slate-200 bg-white p-5">
                    <summary class="flex cursor-pointer items-center justify-between text-sm font-semibold text-slate-800">
                        <span>Bagaimana skor risiko sampah dihitung oleh sistem?</span>
                        <span class="font-bold text-emerald-600 transition group-open:rotate-180">+</span>
                    </summary>
                    <p class="mt-3 text-xs leading-relaxed text-slate-600">
                        Skor risiko dihitung berdasarkan volume sampah, jenis material (organik/anorganik/B3), serta
                        kedekatannya dengan fasilitas umum atau pemukiman warga.
                    </p>
                </details>
            </div>
        </div>
    </section>
    <!-- FOOTER SECTION -->
    <footer class="border-t border-slate-800 bg-slate-950 pt-16 pb-12 text-slate-300">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-10 border-b border-slate-800 pb-12 md:grid-cols-2 lg:grid-cols-5">
                <!-- Brand & Deskripsi (2 Kolom di Layar Besar) -->
                <div class="space-y-4 lg:col-span-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500 font-bold text-slate-950 shadow-lg">
                            ER
                        </div>
                        <div>
                            <span class="text-xl font-bold tracking-tight text-white">EcoRisk</span>
                            <span class="ml-2 inline-block rounded-full border border-emerald-800 bg-emerald-950 px-2.5 py-0.5 text-[10px] font-bold text-emerald-300">
                            </span>
                        </div>
                    </div>
                    <p class="max-w-sm text-xs leading-relaxed text-slate-400">
                        Platform tata kelola lingkungan dan mitigasi risiko limbah berbasis partisipasi warga
                        terintegrasi untuk mewujudkan Kelurahan yang bersih, hijau, dan tangguh.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <a
                            href="#"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-800 bg-slate-900 text-slate-400 transition-colors hover:border-emerald-500/50 hover:text-emerald-400"
                            aria-label="Facebook"
                        >
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a
                            href="#"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-800 bg-slate-900 text-slate-400 transition-colors hover:border-emerald-500/50 hover:text-emerald-400"
                            aria-label="Instagram"
                        >
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07

</body>

</html>

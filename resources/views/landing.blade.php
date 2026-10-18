@extends('layouts.guest', ['title' => 'EcoRisk'])

@section('content')
    <header class="sticky top-0 z-30 border-b border-neutral-200 bg-white/95 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <div class="flex size-10 items-center justify-center overflow-hidden rounded-2xl">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-full w-full object-cover">
                </div>
                <div>
                    <span class="text-xl font-bold tracking-tight text-neutral-900">Eco<span class="text-emerald-600">Risk</span></span>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <a
                    href="{{ route('login') }}"
                    class="cursor-pointer rounded-xl px-3.5 py-2 text-xs font-bold text-neutral-700 transition-colors hover:bg-neutral-100">
                    Masuk
                </a>
                <a
                    href="{{ route('register') }}"
                    class="cursor-pointer rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-xs transition-colors hover:bg-emerald-700">
                    Daftar Warga
                </a>
            </div>
        </div>
    </header>

    <section class="landing-hero relative isolate flex items-center overflow-hidden px-4 py-8 text-white sm:px-6 sm:py-10 lg:py-12">
        <div class="relative z-10 container mx-auto max-w-7xl px-2 sm:px-6">
            <div class="grid grid-cols-1 items-center gap-8 lg:grid-cols-12 lg:gap-10">
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
                            class="rounded-lg bg-lime-400 px-6 py-3 font-semibold text-emerald-950 shadow-md transition duration-200 hover:bg-lime-300">
                            Laporkan Risiko Lingkungan
                        </a>
                        <a
                            href="{{ route('login') }}"
                            class="rounded-lg border border-emerald-700/50 bg-emerald-900/80 px-6 py-3 font-medium text-white transition duration-200 hover:bg-emerald-800">
                            Masuk ke Portal
                        </a>
                    </div>
                    <div class="flex flex-wrap gap-x-5 gap-y-2 pt-2 text-xs text-slate-300">
                        <span class="flex items-center gap-1.5"><svg class="h-4 w-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Prioritas berbasis risiko</span>
                        <span class="flex items-center gap-1.5"><svg class="h-4 w-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Penanganan terukur</span>
                        <span class="flex items-center gap-1.5"><svg class="h-4 w-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Dampak transparan</span>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="space-y-4 rounded-2xl border border-emerald-700/40 bg-emerald-900/40 p-5 shadow-2xl backdrop-blur-md">
                        <div>
                            <span class="text-xs font-semibold tracking-wider text-emerald-400 uppercase">PENDEKATAN KAMI</span>
                            <h3 class="mt-1 text-xl font-bold text-white">Lingkungan lebih tangguh</h3>
                        </div>
                        <div class="space-y-4 text-sm">
                            <div class="flex gap-3 rounded-xl border border-emerald-800/30 bg-emerald-950/50 p-3.5">
                                <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-emerald-800/50 text-emerald-400">
                                    <i class="fa-solid fa-map-pin text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-white">Kenali titik berisiko</h4>
                                    <p class="mt-0.5 text-xs text-slate-300">
                                        Catat kondisi lokasi agar dampak cepat teridentifikasi.
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-3 rounded-xl border border-emerald-800/30 bg-emerald-950/50 p-3.5">
                                <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-emerald-800/50 text-emerald-400">
                                    <i class="fa-solid fa-bolt text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-white">Dahulukan yang paling kritis</h4>
                                    <p class="mt-0.5 text-xs text-slate-300">
                                        Petugas memprioritaskan penanganan berdasarkan skor risiko.
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-3 rounded-xl border border-emerald-800/30 bg-emerald-950/50 p-3.5">
                                <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-emerald-800/50 text-emerald-400">
                                    <i class="fa-solid fa-chart-pie text-sm"></i>
                                </div>
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

                <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="space-y-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <i class="fa-solid fa-user text-lg"></i>
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
                            class="flex items-center gap-1 text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                            Daftar Akun Warga <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>


                <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="space-y-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                            <i class="fa-solid fa-truck text-lg"></i>
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
                            class="flex items-center gap-1 text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                            Masuk Portal Petugas <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>


                <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="space-y-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <i class="fa-solid fa-building-shield text-lg"></i>
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
                            class="flex items-center gap-1 text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                            Panel Kendali Utama <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


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

                <div class="relative flex h-full flex-col justify-between rounded-xl border border-slate-700 bg-slate-800/80 p-6">
                    <div>
                        <span class="mb-3 block text-4xl font-extrabold text-slate-600">01</span>
                        <h3 class="mb-2 text-lg font-bold text-white">1. Pelaporan Warga</h3>
                        <p class="text-xs leading-relaxed text-slate-400">
                            Warga mengunggah foto lokasi, memilih jenis sampah, dan menandai koordinat wilayah rawan.
                        </p>
                    </div>
                </div>


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


    <section class="bg-slate-50 py-20">
        <div class="container mx-auto max-w-7xl px-6">
            <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-12">

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
                            class="inline-block rounded-lg bg-emerald-800 px-6 py-3 text-sm font-semibold text-white shadow transition hover:bg-emerald-900">
                            Ikut Berkontribusi Sekarang
                        </a>
                    </div>
                </div>


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
                        <span class="font-bold text-emerald-600 transition duration-200 group-open:rotate-45">+</span>
                    </summary>
                    <p class="mt-3 text-xs leading-relaxed text-slate-600">
                        Tidak, seluruh fasilitas pelaporan dan akses platform EcoRisk disediakan secara gratis untuk seluruh warga.
                    </p>
                </details>


                <details class="group [&_summary::-webkit-details-marker]:hidden rounded-xl border border-slate-200 bg-white p-5">
                    <summary class="flex cursor-pointer items-center justify-between text-sm font-semibold text-slate-800">
                        <span>Bagaimana skor risiko sampah dihitung oleh sistem?</span>
                        <span class="font-bold text-emerald-600 transition duration-200 group-open:rotate-45">+</span>
                    </summary>
                    <p class="mt-3 text-xs leading-relaxed text-slate-600">
                        Skor risiko dihitung secara otomatis berdasarkan volume/luas tumpukan sampah, jenis material (organik, anorganik, B3), tingkat dampak lingkungan, serta kedekatannya dengan fasilitas umum atau pemukiman warga.
                    </p>
                </details>


                <details class="group [&_summary::-webkit-details-marker]:hidden rounded-xl border border-slate-200 bg-white p-5">
                    <summary class="flex cursor-pointer items-center justify-between text-sm font-semibold text-slate-800">
                        <span>Bagaimana cara melaporkan penumpukan sampah liar di sekitar lingkungan saya?</span>
                        <span class="font-bold text-emerald-600 transition duration-200 group-open:rotate-45">+</span>
                    </summary>
                    <p class="mt-3 text-xs leading-relaxed text-slate-600">
                        Anda cukup masuk ke akun Anda, klik tombol <strong>"Buat Laporan"</strong>, unggah foto lokasi, tentukan titik lokasi pada peta, dan isi deskripsi singkat mengenai kondisi sampah tersebut.
                    </p>
                </details>


                <details class="group [&_summary::-webkit-details-marker]:hidden rounded-xl border border-slate-200 bg-white p-5">
                    <summary class="flex cursor-pointer items-center justify-between text-sm font-semibold text-slate-800">
                        <span>Apakah laporan yang saya kirimkan bersifat anonim atau dapat dilihat publik?</span>
                        <span class="font-bold text-emerald-600 transition duration-200 group-open:rotate-45">+</span>
                    </summary>
                    <p class="mt-3 text-xs leading-relaxed text-slate-600">
                        Data lokasi dan detail laporan dapat dilihat di peta pemantauan umum, namun data pribadi Anda akan dijaga kerahasiaannya dan hanya dapat diakses oleh tim admin/petugas terkait untuk verifikasi.
                    </p>
                </details>


                <details class="group [&_summary::-webkit-details-marker]:hidden rounded-xl border border-slate-200 bg-white p-5">
                    <summary class="flex cursor-pointer items-center justify-between text-sm font-semibold text-slate-800">
                        <span>Berapa lama waktu yang dibutuhkan hingga laporan ditindaklanjuti?</span>
                        <span class="font-bold text-emerald-600 transition duration-200 group-open:rotate-45">+</span>
                    </summary>
                    <p class="mt-3 text-xs leading-relaxed text-slate-600">
                        Waktu penanganan bervariasi tergantung tingkat risiko dan prioritas. Laporan berisiko tinggi (seperti limbah B3 atau tumpukan yang menyumbat aliran air) akan diverifikasi dan diprioritaskan untuk penanganan lebih cepat oleh petugas.
                    </p>
                </details>


                <details class="group [&_summary::-webkit-details-marker]:hidden rounded-xl border border-slate-200 bg-white p-5">
                    <summary class="flex cursor-pointer items-center justify-between text-sm font-semibold text-slate-800">
                        <span>Bagaimana cara mengetahui status perkembangan laporan saya?</span>
                        <span class="font-bold text-emerald-600 transition duration-200 group-open:rotate-45">+</span>
                    </summary>
                    <p class="mt-3 text-xs leading-relaxed text-slate-600">
                        Anda dapat memantau status laporan (Menunggu, Diproses, Selesai, atau Ditolak) secara berkala melalui menu dashboard akun Anda.
                    </p>
                </details>
            </div>
        </div>
    </section>

    <footer class="border-t border-slate-800 bg-slate-950 pt-16 pb-12 text-slate-300">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-10 border-b border-slate-800 pb-12 md:grid-cols-2 lg:grid-cols-5">

                <div class="space-y-4 lg:col-span-2">
                    <div class="flex items-center gap-3">
                        <div class="flex size-10 items-center justify-center overflow-hidden rounded-2xl">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-full w-full object-cover">
                        </div>
                        <div>
                            <span class="text-xl font-bold tracking-tight text-white-900">Eco<span class="text-emerald-600">Risk</span></span>
                        </div>
                    </div>
                    <p class="max-w-sm text-xs leading-relaxed text-slate-400">
                        Platform tata kelola lingkungan dan mitigasi risiko limbah berbasis partisipasi warga terintegrasi untuk mewujudkan kelurahan yang bersih, hijau, dan tangguh.
                    </p>

                    <div class="flex items-center gap-3 pt-2">
                        <a
                            href="#"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-800 bg-slate-900 text-slate-400 transition-colors hover:border-emerald-500/50 hover:text-emerald-400"
                            aria-label="Facebook">
                            <i class="fa-brands fa-facebook-f text-xs"></i>
                        </a>
                        <a
                            href="#"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-800 bg-slate-900 text-slate-400 transition-colors hover:border-emerald-500/50 hover:text-emerald-400"
                            aria-label="Instagram">
                            <i class="fa-brands fa-instagram text-xs"></i>
                        </a>
                        <a
                            href="#"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-800 bg-slate-900 text-slate-400 transition-colors hover:border-emerald-500/50 hover:text-emerald-400"
                            aria-label="Twitter">
                            <i class="fa-brands fa-twitter text-xs"></i>
                        </a>
                        <a
                            href="#"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-800 bg-slate-900 text-slate-400 transition-colors hover:border-emerald-500/50 hover:text-emerald-400"
                            aria-label="YouTube">
                            <i class="fa-brands fa-youtube text-xs"></i>
                        </a>
                    </div>
                </div>


                <div class="space-y-4">
                    <h4 class="text-xs font-bold tracking-wider text-white uppercase">Navigasi Utama</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="{{ route('home') }}" class="transition hover:text-emerald-400">Beranda</a></li>
                        <li><a href="#" class="transition hover:text-emerald-400">Peta Risiko Wilayah</a></li>
                        <li><a href="#" class="transition hover:text-emerald-400">Laporan Publik</a></li>
                        <li><a href="#" class="transition hover:text-emerald-400">Edukasi & Panduan</a></li>
                    </ul>
                </div>


                <div class="space-y-4">
                    <h4 class="text-xs font-bold tracking-wider text-white uppercase">Peran Pengguna</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="{{ route('login') }}" class="transition hover:text-emerald-400">Portal Warga</a></li>
                        <li><a href="{{ route('login') }}" class="transition hover:text-emerald-400">Petugas Lapangan</a></li>
                        <li><a href="{{ route('login') }}" class="transition hover:text-emerald-400">Mitra Daur Ulang</a></li>
                        <li><a href="{{ route('login') }}" class="transition hover:text-emerald-400">Admin Kelurahan</a></li>
                    </ul>
                </div>


                <div class="space-y-4">
                    <h4 class="text-xs font-bold tracking-wider text-white uppercase">Dukungan & Legal</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="#" class="transition hover:text-emerald-400">Pusat Bantuan</a></li>
                        <li><a href="#" class="transition hover:text-emerald-400">Kebijakan Privasi</a></li>
                        <li><a href="#" class="transition hover:text-emerald-400">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="transition hover:text-emerald-400">Kontak Kami</a></li>
                    </ul>
                </div>
            </div>


            <div class="mt-8 flex flex-col items-center justify-between gap-4 text-xs text-slate-500 sm:flex-row">
                <p>&copy; {{ date('Y') }} EcoRisk. Seluruh hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>
@endsection
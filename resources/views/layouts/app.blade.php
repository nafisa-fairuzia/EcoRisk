<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? 'EcoRisk — Pengelolaan Sampah Berbasis Risiko' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body
    class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased"
    x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }"
    x-init="$watch('sidebarCollapsed', (value) => localStorage.setItem('sidebarCollapsed', value))">
    @php($currentRole = auth()->user()->role)

    <div class="min-h-screen">
        <div
            x-cloak
            x-show="sidebarOpen"
            x-transition.opacity
            class="fixed inset-0 z-40 bg-slate-950/55 backdrop-blur-[2px] lg:hidden"
            @click="sidebarOpen = false"
            aria-hidden="true"></div>

        @include('components.sidebar', [
        'user' => auth()->user(),
        'currentRole' => auth()->user()->role,
        ])

        <div class="min-h-screen transition-[padding] duration-300" :class="sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-72'">
            <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/85 backdrop-blur-xl">
                <div class="flex h-19 items-center justify-between gap-4 px-4 sm:px-6 lg:px-10">
                    <div class="flex min-w-0 items-center gap-3">
                        <button
                            type="button"
                            class="inline-flex size-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none lg:hidden"
                            @click="sidebarOpen = ! sidebarOpen"
                            :aria-expanded="sidebarOpen.toString()"
                            aria-label="Buka atau tutup menu navigasi">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <div class="min-w-0">
                            <nav
                                class="mb-0.5 hidden items-center gap-2 text-xs font-medium text-slate-400 sm:flex"
                                aria-label="Breadcrumb">
                                <a href="{{ route('home') }}" class="transition hover:text-emerald-700">EcoRisk</a>
                                <span aria-hidden="true">/</span>
                                <span class="truncate text-slate-500">
                                    {{
                                        match ($currentRole) {
                                            'admin' => 'Administrator',
                                            'petugas' => 'Operasional lapangan',
                                            'mitra_daur_ulang' => 'Mitra daur ulang',
                                            default => 'Portal warga',
                                        }
                                    }}
                                </span>
                            </nav>
                            <h1 class="truncate text-base font-bold tracking-tight text-slate-900 sm:text-lg">
                                @yield('header', $title ?? 'Dashboard')
                            </h1>
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                        <div class="hidden items-center gap-2 rounded-full border border-emerald-100 bg-emerald-50/80 px-3 py-1.5 text-xs font-semibold text-emerald-800 md:flex">
                            <span class="relative flex size-2">
                                <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-400 opacity-50"></span>
                                <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                            </span>
                            Sistem aktif
                        </div>

                        <details class="group relative">
                            <summary class="flex cursor-pointer list-none items-center gap-2 rounded-2xl border border-slate-200 bg-white p-1.5 pr-2.5 shadow-sm transition hover:border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                <span class="flex size-9 items-center justify-center rounded-xl bg-emerald-700 text-xs font-extrabold text-white">
                                    {{ auth()->user()->initials }}
                                </span>
                                <span class="hidden max-w-36 text-left sm:block">
                                    <span class="block truncate text-xs font-bold text-slate-800">{{ auth()->user()->name }}</span>
                                    <span class="block text-[10px] font-medium text-slate-500 capitalize">{{ str_replace('_', ' ', $currentRole) }}</span>
                                </span>
                                <svg class="hidden size-4 text-slate-400 transition group-open:rotate-180 sm:block" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.22 7.47a.75.75 0 0 1 1.06 0L10 11.19l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </summary>
                            <div class="absolute right-0 mt-3 w-64 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl shadow-slate-900/10">
                                <div class="border-b border-slate-100 px-3 py-2.5">
                                    <p class="truncate text-sm font-bold text-slate-900">{{ auth()->user()->name }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="px-3 py-2">
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-800 capitalize ring-1 ring-emerald-100">
                                        {{ str_replace('_', ' ', $currentRole) }}
                                    </span>
                                </div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="flex w-full items-center gap-2 rounded-xl px-3 py-2.5 text-left text-sm font-semibold text-rose-700 transition hover:bg-rose-50">
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 8.25V5.5A2.5 2.5 0 0 0 13.25 3h-6A2.5 2.5 0 0 0 4.75 5.5v13A2.5 2.5 0 0 0 7.25 21h6a2.5 2.5 0 0 0 2.5-2.5v-2.75M10 12h9.25m0 0-3-3m3 3-3 3" />
                                        </svg>
                                        Keluar dari akun
                                    </button>
                                </form>
                            </div>
                        </details>
                    </div>
                </div>
            </header>

            <main class="mx-auto w-full max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-10">
                @if (session('success'))
                <div
                    class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-900 shadow-sm"
                    role="status">
                    <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-xs font-bold text-white">✓</span>
                    <p class="flex-1">{{ session('success') }}</p>
                    <button
                        type="button"
                        class="text-lg leading-none text-emerald-700/70 transition hover:text-emerald-900"
                        onclick="this.parentElement.remove()"
                        aria-label="Tutup notifikasi">
                        &times;
                    </button>
                </div>
                @endif

                @if ($errors->any())
                <div
                    class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-sm text-rose-800 shadow-sm"
                    role="alert">
                    <p class="font-bold">Periksa kembali data yang dimasukkan.</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </main>

            <footer class="border-t border-slate-200/80 px-4 py-5 text-center text-xs text-slate-400 sm:px-6 lg:ml-0 lg:px-10">
                <span class="font-semibold text-slate-500">EcoRisk</span>
                <span class="mx-1.5">·</span>
                Pengelolaan sampah berbasis risiko lingkungan
            </footer>
        </div>
    </div>
</body>

</html>
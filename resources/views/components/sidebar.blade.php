@php
$roleLabel = match ($currentRole) {
'admin' => 'Administrator',
'petugas' => 'Petugas lapangan',
'mitra_daur_ulang' => 'Mitra daur ulang',
default => 'Warga',
};

$menuItems = match ($currentRole) {
'warga' => [
[
'label' => 'Dashboard',
'icon' => 'fa-solid fa-chart-pie',
'href' => route('warga.dashboard'),
'active' => request()->routeIs('warga.dashboard') && ! request()->query('section'),
],
[
'label' => 'Buat Laporan Risiko',
'icon' => 'fa-solid fa-triangle-exclamation',
'href' => route('warga.dashboard', ['section' => 'create']),
'active' => request()->routeIs('warga.dashboard') && request()->query('section') === 'create',
],
[
'label' => 'Peta & Status Laporan',
'icon' => 'fa-solid fa-map-location-dot',
'href' => route('warga.dashboard', ['section' => 'reports']),
'active' => request()->routeIs('warga.dashboard') && request()->query('section') === 'reports',
],
],
'petugas' => [
[
'label' => 'Dashboard',
'icon' => 'fa-solid fa-chart-pie',
'href' => route('petugas.dashboard'),
'active' => request()->routeIs('petugas.dashboard'),
],
[
'label' => 'Radar Tugas Krisis',
'icon' => 'fa-solid fa-fire',
'href' => route('petugas.pickups.index'),
'active' => request()->routeIs('petugas.pickups.index') && ! request()->query('section'),
'badge' => $pickupCount,
],
[
'label' => 'Peta Rute Pengangkutan',
'icon' => 'fa-solid fa-truck',
'href' => route('petugas.routes.index'),
'active' => request()->routeIs('petugas.routes.index'),
],
[
'label' => 'Pemilahan & Input Dampak',
'icon' => 'fa-solid fa-scale-balanced',
'href' => route('petugas.sortings.index'),
'active' => request()->routeIs('petugas.sortings.*'),
],
],
'admin' => [
[
'label' => 'Dashboard',
'icon' => 'fa-solid fa-chart-pie',
'href' => route('admin.dashboard'),
'active' => request()->routeIs('admin.dashboard'),
],
[
'label' => 'Heatmap Risiko Wilayah',
'icon' => 'fa-solid fa-map-pin',
'href' => route('admin.heatmap'),
'active' => request()->routeIs('admin.heatmap'),
],
[
'label' => 'Kelola Pengguna & Mitra',
'icon' => 'fa-solid fa-users',
'href' => route('admin.users.index'),
'active' => request()->routeIs('admin.users.*'),
],
],
default => [],
};
@endphp

<aside
    x-cloak
    class="fixed inset-y-0 left-0 z-50 flex w-[min(19rem,86vw)] flex-col border-r border-slate-200/80 bg-white text-slate-800 shadow-xl shadow-slate-200/50 transition-[width,transform] duration-300 ease-out lg:translate-x-0 lg:shadow-none"
    :class="{
        'lg:w-20': sidebarCollapsed,
        'lg:w-72': ! sidebarCollapsed,
        'translate-x-0': sidebarOpen,
        '-translate-x-full': ! sidebarOpen,
    }"
    aria-label="Navigasi utama">
    <!-- Header Sidebar -->
    <div
        class="flex h-19 items-center justify-between border-b border-slate-100 px-6"
        :class="{ 'lg:justify-center': sidebarCollapsed }">
        <a href="{{ route('home') }}" class="group flex items-center gap-3">
            <span class="flex size-10 items-center justify-center overflow-hidden rounded-2xl ">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-full w-full object-cover">
            </span>
            <span :class="sidebarCollapsed ? 'lg:hidden' : ''">
                <span class="block text-base font-extrabold tracking-tight text-slate-900">Eco<span class="text-emerald-600">Risk</span></span>
                <span class="block text-[10px] font-semibold tracking-wider text-emerald-600">ENVIRONMENTAL FIRST</span>
            </span>
        </a>
        <button
            type="button"
            class="inline-flex size-9 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 lg:hidden"
            @click="sidebarOpen = false"
            aria-label="Tutup menu navigasi">
            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m6 6 12 12M18 6 6 18" />
            </svg>
        </button>
    </div>

    <!-- Body / Navigation -->
    <div class="px-5 pt-6">
        <div class="mb-3 flex items-center justify-between px-3">
            <p
                :class="sidebarCollapsed ? 'lg:hidden' : ''"
                class="text-[10px] font-bold tracking-[0.18em] text-slate-400 uppercase">
                Workspace
            </p>
            <button
                type="button"
                class="hidden size-8 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none lg:inline-flex"
                @click="sidebarCollapsed = ! sidebarCollapsed"
                :aria-label="sidebarCollapsed ? 'Perluas sidebar' : 'Ringkas sidebar'"
                :title="sidebarCollapsed ? 'Perluas sidebar' : 'Ringkas sidebar'"
                :aria-expanded="(! sidebarCollapsed).toString()">
                <svg
                    class="size-4 transition-transform"
                    :class="{
                        'rotate-180': sidebarCollapsed,
                    }"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m14.5 5-7 7 7 7" />
                </svg>
            </button>
        </div>
        <nav class="space-y-1.5" aria-label="Menu {{ strtolower($roleLabel) }}">
            @foreach ($menuItems as $item)
            @if ($item['href'])
            <a
                href="{{ $item['href'] }}"
                @if ($item['active'] ?? false) aria-current="page" @endif
                @click="sidebarOpen = false"
                :aria-label="sidebarCollapsed ? @js($item['label']) : null"
                :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : ''"
                class="{{
                    ($item['active'] ?? false)
                        ? 'bg-emerald-50 text-emerald-700 font-bold ring-1 ring-inset ring-emerald-500/20'
                        : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 font-semibold'
                }} group flex min-h-11 items-center gap-3 rounded-2xl px-3.5 py-2.5 text-[13px] transition duration-200">

                <!-- Font Awesome Icon -->
                <span
                    class="flex size-8 shrink-0 items-center justify-center rounded-xl {{ ($item['active'] ?? false) ? 'bg-emerald-100/80 text-emerald-700' : 'bg-slate-100 text-slate-600 group-hover:bg-slate-200/70' }} transition"
                    :title="sidebarCollapsed ? @js($item['label']) : ''">
                    <i class="{{ $item['icon'] }} text-sm"></i>
                </span>

                <span
                    :class="sidebarCollapsed ? 'lg:hidden' : ''"
                    class="min-w-0 flex-1 leading-5">{{ $item['label'] }}</span>

                @if (($item['badge'] ?? 0) > 0)
                <span
                    :class="sidebarCollapsed ? 'lg:hidden' : ''"
                    class="inline-flex min-w-5 items-center justify-center rounded-full bg-rose-500 px-2 py-0.5 text-[10px] leading-none font-extrabold text-white shadow-sm shadow-rose-500/30"
                    aria-label="{{ $item['badge'] }} laporan baru">
                    {{ $item['badge'] }}
                </span>
                @endif
            </a>
            @else
            <div
                class="group flex min-h-11 cursor-not-allowed items-center gap-3 rounded-2xl px-3.5 py-2.5 text-[13px] font-semibold text-slate-400"
                aria-disabled="true"
                title="Halaman ini belum tersedia">
                <span class="flex size-8 shrink-0 items-center justify-center rounded-xl bg-slate-100/60 text-slate-500 opacity-60">
                    <i class="{{ $item['icon'] }} text-sm"></i>
                </span>
                <span
                    :class="sidebarCollapsed ? 'lg:hidden' : ''"
                    class="min-w-0 flex-1 leading-5">{{ $item['label'] }}</span>
                <span
                    :class="sidebarCollapsed ? 'lg:hidden' : ''"
                    class="rounded-full border border-slate-200 bg-slate-50 px-2 py-0.5 text-[9px] font-bold tracking-wide text-slate-400 uppercase">Segera</span>
            </div>
            @endif
            @endforeach
        </nav>
    </div>

    <!-- Footer Sidebar -->
    <div class="mt-auto p-4">
        <div
            class="flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-slate-50 p-3"
            :class="{ 'lg:justify-center': sidebarCollapsed }">
            @if ($user->profile_photo_url)
            <img src="{{ $user->profile_photo_url }}" alt="Foto profil {{ $user->name }}" class="size-9 shrink-0 rounded-xl object-cover shadow-sm" />
            @else
            <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-slate-800 text-xs font-extrabold text-white shadow-sm">
                {{ $user->initials }}
            </span>
            @endif
            <div :class="sidebarCollapsed ? 'lg:hidden' : ''" class="min-w-0 flex-1">
                <p class="truncate text-xs font-bold text-slate-800">{{ $user->name }}</p>
                <span class="mt-0.5 inline-flex rounded-full bg-slate-200/70 px-2 py-0.5 text-[9px] font-bold tracking-wide text-slate-600 capitalize">
                    {{ $user->roleLabel }}
                </span>
            </div>
            <span
                :class="sidebarCollapsed ? 'lg:hidden' : ''"
                class="size-2 shrink-0 rounded-full bg-emerald-500 ring-4 ring-emerald-500/20"
                title="Akun aktif"></span>
        </div>
    </div>
</aside>
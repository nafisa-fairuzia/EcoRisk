@extends('layouts.app', ['title' => 'Kelola Pengguna & Mitra'])

@section('content')
<section class="space-y-5">
    <header class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900">Kelola Pengguna & Mitra</h1>
        </div>
        <div class="flex items-center gap-3">
            <span class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-neutral-700">
                {{ $users->total() }} akun
            </span>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-800">
                <i class="fa-solid fa-user-plus text-xs"></i>
                Tambah akun
            </a>
        </div>
    </header>

    <section class="border border-neutral-200 bg-white shadow-xs" aria-labelledby="accounts-heading">
        <div class="border-b border-neutral-100 px-4 py-3">
            <h2 id="accounts-heading" class="text-base font-bold text-neutral-900">Daftar akun</h2>
        </div>
        <div data-table-container data-filter-column="3">
            <x-table-tools filter-label="peran" />
            <div class="table-responsive">
                <table class="table table-hover" data-filter-table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Peran</th>
                            <th>Wilayah tugas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $managedUser)
                        <tr>
                            <td>{{ $users->firstItem() + $loop->index }}</td>
                            <td class="font-bold text-neutral-900">{{ $managedUser->name }}</td>
                            <td>{{ $managedUser->email }}</td>
                            <td>{{ $managedUser->roleLabel }}</td>
                            <td>{{ $managedUser->zona_wilayah ?: '-' }}</td>
                            <td>
                                <span class="inline-flex items-center rounded-md border px-2.5 py-1 text-xs font-semibold {{ $managedUser->status === 'Aktif' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-neutral-200 bg-neutral-50 text-neutral-700' }}">
                                    {{ $managedUser->status }}
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.users.toggle', $managedUser->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        @disabled($managedUser->id === auth()->id())
                                        class="rounded-lg border px-3 py-2 text-[11px] font-bold transition disabled:cursor-not-allowed disabled:opacity-40 {{ $managedUser->status === 'Aktif' ? 'border-rose-200 text-rose-700 hover:bg-rose-50' : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }}">
                                        {{ $managedUser->status === 'Aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="p-8 text-center text-neutral-400" colspan="7">Belum ada akun pengguna.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="table-pagination border-t border-neutral-100 px-4 py-3">
                {{ $users->links() }}
            </div>
        </div>
    </section>
</section>
@endsection

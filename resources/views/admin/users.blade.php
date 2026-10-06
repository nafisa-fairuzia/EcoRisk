@extends('layouts.app', ['title' => 'Kelola User & Mitra'])

@section('content')
<section class="space-y-6">
    <header class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900">Kelola User & Mitra</h1>
        </div>
        <span class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-neutral-700">
            {{ $users->total() }} akun
        </span>
    </header>

    <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-xs">
        <div class="table-responsive">
            <table class="table table-hover min-w-180">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Wilayah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $managedUser)
                    <tr>
                        <td class="font-bold text-neutral-900">{{ $managedUser->name }}</td>
                        <td>{{ $managedUser->email }}</td>
                        <td class="capitalize">{{ str_replace('_', ' ', $managedUser->role) }}</td>
                        <td>{{ $managedUser->zona_wilayah ?: '-' }}</td>
                        <td>
                            <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $managedUser->status === 'Aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-neutral-100 text-neutral-600' }}">
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
                                    class="rounded-lg border px-3 py-2 text-[11px] font-bold transition disabled:cursor-not-allowed disabled:opacity-40 {{ $managedUser->status === 'Aktif' ? 'border-rose-200 text-rose-700 hover:bg-rose-50' : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }}"
                                    >
                                    {{ $managedUser->status === 'Aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="p-8 text-center text-neutral-400" colspan="6">Belum ada akun pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-neutral-100 px-4 py-3">
            {{ $users->links() }}
        </div>
    </div>
</section>
@endsection
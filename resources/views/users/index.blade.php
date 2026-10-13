@extends('layouts.app')

@section('title', 'Data User')

@section('content')
    @php
        $activeModal = session('modal');
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 rounded-xl bg-white p-6 shadow-sm sm:flex-row sm:items-center">
            <div>
                <p class="text-sm font-medium uppercase tracking-wide text-gray-500">Master Data</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-900">Data User</h1>
                <p class="mt-2 text-sm text-gray-500">Kelola akun pengguna aplikasi SIPENSA.</p>
            </div>

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg bg-black px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-700 focus:ring-offset-2"
                x-data
                x-on:click="$dispatch('open-modal', 'create-user')"
            >
                Tambah User
            </button>
        </div>

        <x-ui.search-bar
            :action="route('users.index')"
            :value="$search"
            placeholder="Cari nama, username, NIP, role, atau jenis kelamin..."
        />

        <x-ui.datatable
            :columns="['No', 'Nama', 'Role', 'Aksi']"
            :items="$users"
            :empty-message="$search ? 'Data tidak ditemukan.' : 'Data kosong.'"
        >
            @foreach ($users as $user)
                <tr class="transition hover:bg-gray-50">
                    <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                        {{ $users->firstItem() + $loop->index }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="font-semibold text-gray-900">{{ $user->nama }}</div>
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <span class="inline-flex rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-700">
                            {{ match ($user->role) { 'admin', 'operator' => 'Operator', 'guru_bk' => 'Guru BK', 'wali_kelas' => 'Wali Kelas', default => $user->role } }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-zinc-700 hover:text-zinc-900"
                                x-data
                                x-on:click="$dispatch('open-modal', 'detail-user-{{ $user->id }}')"
                            >
                                Detail
                            </button>

                            <button
                                type="button"
                                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-zinc-700 hover:text-zinc-900"
                                x-data
                                x-on:click="$dispatch('open-modal', 'edit-user-{{ $user->id }}')"
                            >
                                Edit
                            </button>

                            <button
                                type="button"
                                class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                x-data
                                x-on:click="$dispatch('open-modal', 'delete-user-{{ $user->id }}')"
                            >
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-ui.datatable>
    </div>

    <x-ui.modal-box name="create-user" title="Tambah User" :show="$activeModal === 'create-user'">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
            @csrf

            @include('users.partials.form', ['user' => null, 'mode' => 'create'])

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                <button
                    type="button"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    x-on:click="$dispatch('close-modal', 'create-user')"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="rounded-lg bg-black px-4 py-2 text-sm font-semibold text-white transition hover:bg-zinc-800"
                >
                    Simpan
                </button>
            </div>
        </form>
    </x-ui.modal-box>

    @foreach ($users as $user)
        <x-ui.detail-modal
            name="detail-user-{{ $user->id }}"
            title="Detail User"
            :items="[
                'Nama' => $user->nama,
                'Username' => $user->username,
                'NIP' => $user->nip,
                'Jenis Kelamin' => $user->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                'Role' => match ($user->role) { 'admin', 'operator' => 'Operator', 'guru_bk' => 'Guru BK', 'wali_kelas' => 'Wali Kelas', default => $user->role },
                'Dibuat' => $user->created_at?->format('d M Y H:i'),
                'Diperbarui' => $user->updated_at?->format('d M Y H:i'),
            ]"
        />

        <x-ui.modal-box name="edit-user-{{ $user->id }}" title="Edit User" :show="$activeModal === 'edit-user-'.$user->id">
            <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                @include('users.partials.form', ['user' => $user, 'mode' => 'edit'])

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <button
                        type="button"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        x-on:click="$dispatch('close-modal', 'edit-user-{{ $user->id }}')"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-black px-4 py-2 text-sm font-semibold text-white transition hover:bg-zinc-800"
                    >
                        Perbarui
                    </button>
                </div>
            </form>
        </x-ui.modal-box>

        <x-ui.modal-box name="delete-user-{{ $user->id }}" title="Hapus User" max-width="md">
            <form action="{{ route('users.destroy', $user) }}" method="POST" class="space-y-5">
                @csrf
                @method('DELETE')

                <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
                    Yakin ingin menghapus user <span class="font-semibold">{{ $user->nama }}</span>? Data yang sudah dihapus tidak dapat dikembalikan.
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <button
                        type="button"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        x-on:click="$dispatch('close-modal', 'delete-user-{{ $user->id }}')"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700"
                    >
                        Hapus
                    </button>
                </div>
            </form>
        </x-ui.modal-box>
    @endforeach
@endsection

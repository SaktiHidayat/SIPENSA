@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
    @php
        $activeModal = session('modal');
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 rounded-xl bg-white p-6 shadow-sm sm:flex-row sm:items-center">
            <div>
                <p class="text-sm font-medium uppercase tracking-wide text-gray-500">Master Data</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-900">Data Siswa</h1>
                <p class="mt-2 text-sm text-gray-500">Kelola data siswa yang digunakan di aplikasi SIPENSA.</p>
            </div>

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg bg-black px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-700 focus:ring-offset-2"
                x-data
                x-on:click="$dispatch('open-modal', 'create-siswa')"
            >
                Tambah Siswa
            </button>
        </div>

        <x-ui.search-bar
            :action="route('siswas.index')"
            :value="$search"
            placeholder="Cari nama, NISN, kelas, atau jenis kelamin..."
        />

        <x-ui.datatable
            :columns="['No', 'Nama', 'NISN', 'Jenis Kelamin', 'Kelas', 'Dibuat', 'Aksi']"
            :items="$siswas"
            :empty-message="$search ? 'Data tidak ditemukan.' : 'Data kosong.'"
        >
            @foreach ($siswas as $siswa)
                <tr class="transition hover:bg-gray-50">
                    <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                        {{ $siswas->firstItem() + $loop->index }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="font-semibold text-gray-900">{{ $siswa->nama }}</div>
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                        {{ $siswa->nisn }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                            {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                        {{ $siswa->kelas }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                        {{ $siswa->created_at?->format('d M Y') }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-zinc-700 hover:text-zinc-900"
                                x-data
                                x-on:click="$dispatch('open-modal', 'edit-siswa-{{ $siswa->id }}')"
                            >
                                Edit
                            </button>

                            <button
                                type="button"
                                class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                x-data
                                x-on:click="$dispatch('open-modal', 'delete-siswa-{{ $siswa->id }}')"
                            >
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-ui.datatable>
    </div>

    <x-ui.modal-box name="create-siswa" title="Tambah Siswa" :show="$activeModal === 'create-siswa'">
        <form action="{{ route('siswas.store') }}" method="POST" class="space-y-5">
            @csrf

            @include('siswas.partials.form', ['siswa' => null, 'mode' => 'create'])

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                <button
                    type="button"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    x-on:click="$dispatch('close-modal', 'create-siswa')"
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

    @foreach ($siswas as $siswa)
        <x-ui.modal-box name="edit-siswa-{{ $siswa->id }}" title="Edit Siswa" :show="$activeModal === 'edit-siswa-'.$siswa->id">
            <form action="{{ route('siswas.update', $siswa) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                @include('siswas.partials.form', ['siswa' => $siswa, 'mode' => 'edit'])

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <button
                        type="button"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        x-on:click="$dispatch('close-modal', 'edit-siswa-{{ $siswa->id }}')"
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

        <x-ui.modal-box name="delete-siswa-{{ $siswa->id }}" title="Hapus Siswa" max-width="md">
            <form action="{{ route('siswas.destroy', $siswa) }}" method="POST" class="space-y-5">
                @csrf
                @method('DELETE')

                <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
                    Yakin ingin menghapus siswa <span class="font-semibold">{{ $siswa->nama }}</span>? Data yang sudah dihapus tidak dapat dikembalikan.
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <button
                        type="button"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        x-on:click="$dispatch('close-modal', 'delete-siswa-{{ $siswa->id }}')"
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

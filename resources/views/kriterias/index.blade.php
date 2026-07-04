@extends('layouts.app')

@section('title', 'Data Kriteria')

@section('content')
    @php
        $activeModal = session('modal');
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 rounded-xl bg-white p-6 shadow-sm sm:flex-row sm:items-center">
            <div>
                <p class="text-sm font-medium uppercase tracking-wide text-gray-500">Master Data</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-900">Data Kriteria</h1>
                <p class="mt-2 text-sm text-gray-500">Kelola kode, nama, dan bobot kriteria untuk proses SPK.</p>
            </div>

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg bg-black px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-700 focus:ring-offset-2"
                x-data
                x-on:click="$dispatch('open-modal', 'create-kriteria')"
            >
                Tambah Kriteria
            </button>
        </div>

        <x-ui.search-bar
            :action="route('kriterias.index')"
            :value="$search"
            placeholder="Cari kode, nama kriteria, atau bobot..."
        />

        <x-ui.datatable
            :columns="['No', 'Kode', 'Nama Kriteria', 'Aksi']"
            :items="$kriterias"
            :empty-message="$search ? 'Data tidak ditemukan.' : 'Data kosong.'"
        >
            @foreach ($kriterias as $kriteria)
                <tr class="transition hover:bg-gray-50">
                    <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                        {{ $kriterias->firstItem() + $loop->index }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <span class="inline-flex rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-700">
                            {{ $kriteria->kode_kriteria }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="font-semibold text-gray-900">{{ $kriteria->nama_kriteria }}</div>
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-zinc-700 hover:text-zinc-900"
                                x-data
                                x-on:click="$dispatch('open-modal', 'detail-kriteria-{{ $kriteria->id }}')"
                            >
                                Detail
                            </button>

                            <button
                                type="button"
                                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-zinc-700 hover:text-zinc-900"
                                x-data
                                x-on:click="$dispatch('open-modal', 'edit-kriteria-{{ $kriteria->id }}')"
                            >
                                Edit
                            </button>

                            <button
                                type="button"
                                class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                x-data
                                x-on:click="$dispatch('open-modal', 'delete-kriteria-{{ $kriteria->id }}')"
                            >
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-ui.datatable>
    </div>

    <x-ui.modal-box name="create-kriteria" title="Tambah Kriteria" :show="$activeModal === 'create-kriteria'">
        <form action="{{ route('kriterias.store') }}" method="POST" class="space-y-5">
            @csrf

            @include('kriterias.partials.form', ['kriteria' => null, 'mode' => 'create'])

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                <button
                    type="button"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    x-on:click="$dispatch('close-modal', 'create-kriteria')"
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

    @foreach ($kriterias as $kriteria)
        <x-ui.detail-modal
            name="detail-kriteria-{{ $kriteria->id }}"
            title="Detail Kriteria"
            :items="[
                'Kode Kriteria' => $kriteria->kode_kriteria,
                'Nama Kriteria' => $kriteria->nama_kriteria,
                'Bobot' => rtrim(rtrim(number_format((float) $kriteria->bobot, 4, '.', ''), '0'), '.'),
                'Dibuat' => $kriteria->created_at?->format('d M Y H:i'),
                'Diperbarui' => $kriteria->updated_at?->format('d M Y H:i'),
            ]"
        />

        <x-ui.modal-box name="edit-kriteria-{{ $kriteria->id }}" title="Edit Kriteria" :show="$activeModal === 'edit-kriteria-'.$kriteria->id">
            <form action="{{ route('kriterias.update', $kriteria) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                @include('kriterias.partials.form', ['kriteria' => $kriteria, 'mode' => 'edit'])

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <button
                        type="button"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        x-on:click="$dispatch('close-modal', 'edit-kriteria-{{ $kriteria->id }}')"
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

        <x-ui.modal-box name="delete-kriteria-{{ $kriteria->id }}" title="Hapus Kriteria" max-width="md">
            <form action="{{ route('kriterias.destroy', $kriteria) }}" method="POST" class="space-y-5">
                @csrf
                @method('DELETE')

                <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
                    Yakin ingin menghapus kriteria <span class="font-semibold">{{ $kriteria->nama_kriteria }}</span>? Data yang sudah dihapus tidak dapat dikembalikan.
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <button
                        type="button"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        x-on:click="$dispatch('close-modal', 'delete-kriteria-{{ $kriteria->id }}')"
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

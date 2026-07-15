@extends('layouts.app')

@section('title', 'Profil')

@section('content')
    @php
        $photoUrl = $user->foto_url;
    @endphp

    <div class="space-y-6">
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <p class="text-sm font-medium uppercase tracking-wide text-gray-500">Akun</p>
            <h1 class="mt-1 text-2xl font-bold text-gray-900">Profil Saya</h1>
            <p class="mt-2 text-sm text-gray-500">Kelola data akun yang sedang login. Role hanya bisa diubah dari menu Data User.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.4fr_0.9fr]">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <div class="mb-6 flex items-center gap-4">
                    <img src="{{ $photoUrl }}" alt="Foto profil {{ $user->nama }}" class="h-20 w-20 rounded-full object-cover ring-4 ring-gray-100">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Data Profil</h2>
                        <p class="mt-1 text-sm text-gray-500">Perbarui nama, username, jenis kelamin, dan foto profil.</p>
                    </div>
                </div>

                <form
                    action="{{ route('profile.update') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-5"
                    x-data="{ photoPreview: '{{ $photoUrl }}' }"
                >
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="nama" class="mb-1 block text-sm font-medium text-gray-700">Nama</label>
                        <input id="nama" name="nama" type="text" value="{{ old('nama', $user->nama) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700" required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="mb-1 block text-sm font-medium text-gray-700">Username</label>
                        <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700" required>
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nip" class="mb-1 block text-sm font-medium text-gray-700">NIP</label>
                        <input id="nip" name="nip" type="text" value="{{ old('nip', $user->nip) }}" inputmode="numeric" maxlength="18" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700">
                        <p class="mt-1 text-xs text-gray-500">NIP boleh dikosongkan. Kalau diisi, harus tepat 18 digit angka.</p>
                        @error('nip')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="mb-1 block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700" required>
                            <option value="">Pilih jenis kelamin</option>
                            <option value="L" @selected(old('jenis_kelamin', $user->jenis_kelamin) === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('jenis_kelamin', $user->jenis_kelamin) === 'P')>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="foto" class="mb-1 block text-sm font-medium text-gray-700">Foto Profil</label>

                        <div class="mb-3 rounded-xl border border-dashed border-gray-300 bg-gray-50 p-4">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                <div class="h-32 w-32 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                                    <img
                                        :src="photoPreview"
                                        alt="Preview foto profil"
                                        class="h-full w-full object-cover"
                                    >
                                </div>

                                <div class="text-sm text-gray-600">
                                    <p class="font-semibold text-gray-800">Preview Foto Profil</p>
                                    <p class="mt-1">Gambar baru akan langsung tampil di kotak ini sebelum disimpan.</p>
                                </div>
                            </div>
                        </div>

                        <input
                            id="foto"
                            name="foto"
                            type="file"
                            accept="image/png,image/jpeg,image/webp"
                            class="block w-full rounded-lg border border-gray-300 text-sm text-gray-700 shadow-sm file:mr-4 file:border-0 file:bg-black file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-white hover:file:bg-zinc-800 focus:border-zinc-700 focus:ring-zinc-700"
                            x-on:change="
                                const file = $event.target.files[0];
                                if (! file) return;
                                const reader = new FileReader();
                                reader.onload = event => photoPreview = event.target.result;
                                reader.readAsDataURL(file);
                            "
                        >
                        <p class="mt-1 text-xs text-gray-500">Format JPG, PNG, atau WEBP. Maksimal 2 MB.</p>
                        @error('foto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Role</label>
                        <input type="text" value="{{ $user->role === 'admin' ? 'Admin' : 'Guru BK' }}" class="w-full rounded-lg border-gray-200 bg-gray-100 text-gray-500 shadow-sm" readonly>
                    </div>

                    <div class="flex justify-between gap-3 border-t border-gray-100 pt-5">
                        <a href="{{ url()->previous() === url()->current() ? route('dashboard') : url()->previous() }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                            Kembali
                        </a>
                        <button type="submit" class="rounded-lg bg-black px-4 py-2 text-sm font-semibold text-white transition hover:bg-zinc-800">
                            Simpan Profil
                        </button>
                    </div>
                </form>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
@endsection

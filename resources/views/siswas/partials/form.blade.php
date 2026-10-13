@php
    $siswa = $siswa ?? null;
    $mode = $mode ?? 'create';
    $selectedGender = old('jenis_kelamin', $siswa?->jenis_kelamin);
@endphp

<div class="grid gap-4">
    <div>
        <label for="{{ $mode }}-nama-{{ $siswa?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Nama
        </label>
        <input
            id="{{ $mode }}-nama-{{ $siswa?->id ?? 'new' }}"
            name="nama"
            type="text"
            value="{{ old('nama', $siswa?->nama) }}"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
        @error('nama')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-nisn-{{ $siswa?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            NISN
        </label>
        <input
            id="{{ $mode }}-nisn-{{ $siswa?->id ?? 'new' }}"
            name="nisn"
            type="text"
            value="{{ old('nisn', $siswa?->nisn) }}"
            inputmode="numeric"
            maxlength="10"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
        @error('nisn')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-jenis-kelamin-{{ $siswa?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Jenis Kelamin
        </label>
        <select
            id="{{ $mode }}-jenis-kelamin-{{ $siswa?->id ?? 'new' }}"
            name="jenis_kelamin"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
            <option value="">Pilih jenis kelamin</option>
            <option value="L" @selected($selectedGender === 'L')>Laki-Laki</option>
            <option value="P" @selected($selectedGender === 'P')>Perempuan</option>
        </select>
        @error('jenis_kelamin')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-kelas-{{ $siswa?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Kelas
        </label>
        <input
            id="{{ $mode }}-kelas-{{ $siswa?->id ?? 'new' }}"
            name="kelas"
            type="text"
            value="{{ old('kelas', $siswa?->kelas) }}"
            placeholder="Contoh: VII-I"
            pattern="(VII|VIII|IX)(-[IVXLCDM0-9A-Z]+)?"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
        <p class="mt-1 text-xs text-gray-500">Wajib romawi VII sampai IX. Contoh: VII-I. Kelas X-I tidak valid.</p>
        @error('kelas')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    <div>
     <label for="{{ $mode }}-alamat-{{ $siswa?->id ?? 'new' }}"
        class="mb-1 block text-sm font-medium text-gray-700">
        Alamat Rumah
    </label>
    <textarea
        id="{{ $mode }}-alamat-{{ $siswa?->id ?? 'new' }}"
        name="alamat"
        rows="3"
        maxlength="2000"
        class="w-full rounded-lg border-gray-300 shadow-sm"
        required
    >{{ old('alamat', $siswa?->alamat) }}</textarea>

    @error('alamat')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    </div>
</div>

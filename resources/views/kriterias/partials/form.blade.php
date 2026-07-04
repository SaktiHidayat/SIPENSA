@php
    $kriteria = $kriteria ?? null;
    $mode = $mode ?? 'create';
@endphp

<div class="grid gap-4">
    <div>
        <label for="{{ $mode }}-kode-kriteria-{{ $kriteria?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Kode Kriteria
        </label>
        <input
            id="{{ $mode }}-kode-kriteria-{{ $kriteria?->id ?? 'new' }}"
            name="kode_kriteria"
            type="text"
            value="{{ old('kode_kriteria', $kriteria?->kode_kriteria) }}"
            placeholder="Contoh: C1"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
        @error('kode_kriteria')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-nama-kriteria-{{ $kriteria?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Nama Kriteria
        </label>
        <input
            id="{{ $mode }}-nama-kriteria-{{ $kriteria?->id ?? 'new' }}"
            name="nama_kriteria"
            type="text"
            value="{{ old('nama_kriteria', $kriteria?->nama_kriteria) }}"
            placeholder="Contoh: Kehadiran"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
        @error('nama_kriteria')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-bobot-{{ $kriteria?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Bobot Awal
        </label>
        <input
            id="{{ $mode }}-bobot-{{ $kriteria?->id ?? 'new' }}"
            name="bobot"
            type="number"
            value="{{ old('bobot', $kriteria?->bobot) }}"
            min="0"
            max="1"
            step="0.0001"
            placeholder="Contoh: 0.25"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
        <p class="mt-1 text-xs text-gray-500">Bobot wajib diisi dengan nilai 0 sampai 1. Boleh desimal.</p>
        @error('bobot')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

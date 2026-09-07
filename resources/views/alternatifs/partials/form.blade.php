@php
    $alternatif = $alternatif ?? null;
    $mode = $mode ?? 'create';
    $siswas = $siswas ?? collect();
    $jenisPelanggarans = $jenisPelanggarans ?? collect();
    $tingkatKeterlibatans = $tingkatKeterlibatans ?? collect();
    $selectedSiswaId = old('siswa_id', $alternatif?->siswa_id);
    $selectedJenisPelanggaranId = old('jenis_pelanggaran_id', $alternatif?->jenis_pelanggaran_id);
    $selectedTingkatKeterlibatanId = old('tingkat_keterlibatan_id', $alternatif?->tingkat_keterlibatan_id);
@endphp

<div
    class="grid gap-4"
    x-data="{
        siswas: @js($siswas->map(fn ($siswa) => ['id' => $siswa->id, 'nama' => $siswa->nama, 'nisn' => $siswa->nisn])->values()),
        pelanggarans: @js($jenisPelanggarans->map(fn ($item) => ['id' => $item->id, 'label' => $item->kode_pelanggaran.' - '.$item->nama_pelanggaran, 'poin' => $item->poin_pelanggaran])->values()),
        keterlibatans: @js($tingkatKeterlibatans->map(fn ($item) => ['id' => $item->id, 'label' => $item->kode_tingkat_keterlibatan.' - '.$item->nama_keterangan, 'skala_fuzzy' => $item->skala_fuzzy])->values()),
        siswaId: @js($selectedSiswaId),
        nisn: @js(old('nisn', $alternatif?->nisn)),
        jenisPelanggaranId: @js($selectedJenisPelanggaranId),
        poinPelanggaran: @js(old('poin_jenis_pelanggaran', $alternatif?->poin_jenis_pelanggaran)),
        skalaFuzzyPelanggaran: @js(old('skala_fuzzy_jenis_pelanggaran', $alternatif?->skala_fuzzy_jenis_pelanggaran)),
        tingkatKeterlibatanId: @js($selectedTingkatKeterlibatanId),
        skalaFuzzyKeterlibatan: @js(old('skala_fuzzy_tingkat_keterlibatan', $alternatif?->skala_fuzzy_tingkat_keterlibatan)),
        syncSiswa() {
            const siswa = this.siswas.find(item => String(item.id) === String(this.siswaId));
            this.nisn = siswa ? siswa.nisn : '';
        },
        syncPelanggaran() {
            const item = this.pelanggarans.find(item => String(item.id) === String(this.jenisPelanggaranId));
            this.poinPelanggaran = item ? item.poin : '';
            this.skalaFuzzyPelanggaran = item ? (item.poin <= 4 ? 0 : item.poin <= 15 ? 0.25 : item.poin <= 30 ? 0.5 : item.poin <= 75 ? 0.75 : 1) : '';
        },
        syncKeterlibatan() {
            const item = this.keterlibatans.find(item => String(item.id) === String(this.tingkatKeterlibatanId));
            this.skalaFuzzyKeterlibatan = item ? item.skala_fuzzy : '';
        },
        initForm() {
            this.syncSiswa();
            this.syncPelanggaran();
            this.syncKeterlibatan();
        }
    }"
    x-init="initForm()"
>
    <div>
        <label for="{{ $mode }}-kode-alternatif-{{ $alternatif?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Kode
        </label>
        <input
            id="{{ $mode }}-kode-alternatif-{{ $alternatif?->id ?? 'new' }}"
            name="kode_alternatif"
            type="text"
            value="{{ old('kode_alternatif', $alternatif?->kode_alternatif) }}"
            placeholder="ALT001"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
        @error('kode_alternatif')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-siswa-id-{{ $alternatif?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Nama Siswa
        </label>
        <select
            id="{{ $mode }}-siswa-id-{{ $alternatif?->id ?? 'new' }}"
            name="siswa_id"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
            x-model="siswaId"
            @change="syncSiswa()"
        >
            <option value="">Pilih siswa</option>
            @foreach ($siswas as $siswa)
                <option value="{{ $siswa->id }}" @selected((string) $selectedSiswaId === (string) $siswa->id)>{{ $siswa->nama }}</option>
            @endforeach
        </select>
        @error('siswa_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-nisn-{{ $alternatif?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            NISN
        </label>
        <input
            id="{{ $mode }}-nisn-{{ $alternatif?->id ?? 'new' }}"
            type="text"
            x-model="nisn"
            class="w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            readonly
        >
        <p class="mt-1 text-xs text-gray-500">NISN terisi otomatis dari data siswa.</p>
    </div>

    <div>
        <label for="{{ $mode }}-jenis-pelanggaran-id-{{ $alternatif?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            C1 - Jenis Pelanggaran
        </label>
        <select
            id="{{ $mode }}-jenis-pelanggaran-id-{{ $alternatif?->id ?? 'new' }}"
            name="jenis_pelanggaran_id"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
            x-model="jenisPelanggaranId"
            @change="syncPelanggaran()"
        >
            <option value="">Pilih jenis pelanggaran</option>
            @foreach ($jenisPelanggarans as $item)
                <option value="{{ $item->id }}" @selected((string) $selectedJenisPelanggaranId === (string) $item->id)>
                    {{ $item->kode_pelanggaran }} - {{ $item->nama_pelanggaran }}
                </option>
            @endforeach
        </select>
        @error('jenis_pelanggaran_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-xs text-gray-500">Dipilih dari master jenis pelanggaran, lalu poin terisi otomatis.</p>
    </div>

    <div>
        <label for="{{ $mode }}-poin-jenis-pelanggaran-{{ $alternatif?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Poin Jenis Pelanggaran
        </label>
        <input
            id="{{ $mode }}-poin-jenis-pelanggaran-{{ $alternatif?->id ?? 'new' }}"
            type="text"
            x-model="poinPelanggaran"
            class="w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            readonly
        >
    </div>

    <div>
        <label for="{{ $mode }}-skala-fuzzy-jenis-pelanggaran-{{ $alternatif?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Skala Fuzzy C1
        </label>
        <input
            id="{{ $mode }}-skala-fuzzy-jenis-pelanggaran-{{ $alternatif?->id ?? 'new' }}"
            type="text"
            x-model="skalaFuzzyPelanggaran"
            class="w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            readonly
        >
    </div>

    <div>
        <label for="{{ $mode }}-bobot-awal-c1-{{ $alternatif?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Bobot Awal C1
        </label>
        <input
            id="{{ $mode }}-bobot-awal-c1-{{ $alternatif?->id ?? 'new' }}"
            type="text"
            value="0.75"
            class="w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm"
            readonly
        >
    </div>

    <div>
        <label for="{{ $mode }}-tingkat-keterlibatan-id-{{ $alternatif?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            C2 - Tingkat Keterlibatan
        </label>
        <select
            id="{{ $mode }}-tingkat-keterlibatan-id-{{ $alternatif?->id ?? 'new' }}"
            name="tingkat_keterlibatan_id"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
            x-model="tingkatKeterlibatanId"
            @change="syncKeterlibatan()"
        >
            <option value="">Pilih tingkat keterlibatan</option>
            @foreach ($tingkatKeterlibatans as $item)
                <option value="{{ $item->id }}" @selected((string) $selectedTingkatKeterlibatanId === (string) $item->id)>
                    {{ $item->kode_tingkat_keterlibatan }} - {{ $item->nama_keterangan }}
                </option>
            @endforeach
        </select>
        @error('tingkat_keterlibatan_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-xs text-gray-500">Dipilih dari master tingkat keterlibatan, lalu poin terisi otomatis.</p>
    </div>

    <div>
        <label for="{{ $mode }}-skala-fuzzy-tingkat-keterlibatan-{{ $alternatif?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Skala Fuzzy C2
        </label>
        <input
            id="{{ $mode }}-skala-fuzzy-tingkat-keterlibatan-{{ $alternatif?->id ?? 'new' }}"
            type="text"
            x-model="skalaFuzzyKeterlibatan"
            class="w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            readonly
        >
    </div>

    <div>
        <label for="{{ $mode }}-bobot-awal-c2-{{ $alternatif?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Bobot Awal C2
        </label>
        <input
            id="{{ $mode }}-bobot-awal-c2-{{ $alternatif?->id ?? 'new' }}"
            type="text"
            value="0.25"
            class="w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm"
            readonly
        >
    </div>
</div>

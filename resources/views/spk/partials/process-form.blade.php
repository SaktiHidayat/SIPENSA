@php
    $jenisPelanggarans = $jenisPelanggarans ?? collect();
    $tingkatKeterlibatans = $tingkatKeterlibatans ?? collect();
    $pointTotals = $pointTotals ?? collect();
@endphp

<div
    class="grid gap-4"
    x-data="{
        pelanggarans: @js($jenisPelanggarans->map(fn ($item) => ['id' => $item->id, 'label' => $item->kode_pelanggaran.' - '.$item->nama_pelanggaran, 'skala_fuzzy' => $item->skala_fuzzy])->values()),
        keterlibatans: @js($tingkatKeterlibatans->map(fn ($item) => ['id' => $item->id, 'label' => $item->kode_tingkat_keterlibatan.' - '.$item->nama_keterangan, 'skala_fuzzy' => $item->skala_fuzzy])->values()),
        jenisPelanggaranId: @js(old('jenis_pelanggaran_id', request('jenis_pelanggaran_id', ''))),
        tingkatKeterlibatanId: @js(old('tingkat_keterlibatan_id', request('tingkat_keterlibatan_id', ''))),
        skalaFuzzyPelanggaran: '',
        skalaFuzzyKeterlibatan: '',
        displayValue(value) {
            return value === '' || value === null || value === undefined ? '-' : value;
        },
        syncPelanggaran() {
            const item = this.pelanggarans.find(item => String(item.id) === String(this.jenisPelanggaranId));
            this.skalaFuzzyPelanggaran = item ? item.skala_fuzzy : '';
        },
        syncKeterlibatan() {
            const item = this.keterlibatans.find(item => String(item.id) === String(this.tingkatKeterlibatanId));
            this.skalaFuzzyKeterlibatan = item ? item.skala_fuzzy : '';
        },
        resetForm() {
            this.jenisPelanggaranId = '';
            this.tingkatKeterlibatanId = '';
            this.skalaFuzzyPelanggaran = '';
            this.skalaFuzzyKeterlibatan = '';
            this.$refs.nama.value = '';
            this.$refs.kelas.value = '';
            this.$refs.pelanggaran.value = '';
            this.$refs.pelanggaran.selectedIndex = 0;
            this.$refs.keterlibatan.value = '';
            this.$refs.keterlibatan.selectedIndex = 0;
        },
        init() {
            this.jenisPelanggaranId = this.$refs.pelanggaran.value;
            this.tingkatKeterlibatanId = this.$refs.keterlibatan.value;
            this.syncPelanggaran();
            this.syncKeterlibatan();
        }
    }"
    x-init="init()"
    x-on:reset-spk-form.window="resetForm()"
>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="spk-nama" class="mb-1 block text-sm font-medium text-gray-700">Nama Siswa</label>
            <input
                x-ref="nama"
                id="spk-nama"
                name="nama"
                type="text"
                value="{{ old('nama') }}"
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
                required
            >
            @error('nama')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="spk-kelas" class="mb-1 block text-sm font-medium text-gray-700">Kelas</label>
            <input
                x-ref="kelas"
                id="spk-kelas"
                name="kelas"
                type="text"
                value="{{ old('kelas') }}"
                placeholder="Contoh: VII-I"
                pattern="(VII|VIII|IX)(-[IVXLCDM0-9A-Z]+)?"
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
                required
            >
            @error('kelas')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="spk-pelanggaran" class="mb-1 block text-sm font-medium text-gray-700">Jenis Pelanggaran</label>
        <select
            x-ref="pelanggaran"
            id="spk-pelanggaran"
            name="jenis_pelanggaran_id"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            x-model="jenisPelanggaranId"
            x-on:change="syncPelanggaran()"
            required
        >
            <option value="">Pilih jenis pelanggaran</option>
            @foreach ($jenisPelanggarans as $pelanggaran)
                <option value="{{ $pelanggaran->id }}">{{ $pelanggaran->kode_pelanggaran }} - {{ $pelanggaran->nama_pelanggaran }}</option>
            @endforeach
        </select>
        @error('jenis_pelanggaran_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="spk-keterlibatan" class="mb-1 block text-sm font-medium text-gray-700">Tingkat Keterlibatan</label>
        <select
            x-ref="keterlibatan"
            id="spk-keterlibatan"
            name="tingkat_keterlibatan_id"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            x-model="tingkatKeterlibatanId"
            x-on:change="syncKeterlibatan()"
            required
        >
            <option value="">Pilih tingkat keterlibatan</option>
            @foreach ($tingkatKeterlibatans as $keterlibatan)
                <option value="{{ $keterlibatan->id }}">{{ $keterlibatan->kode_tingkat_keterlibatan }} - {{ $keterlibatan->nama_keterangan }}</option>
            @endforeach
        </select>
        @error('tingkat_keterlibatan_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-3 sm:grid-cols-2">
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Skala Fuzzy C1</p>
            <p class="mt-1 text-lg font-semibold text-gray-900" x-text="displayValue(skalaFuzzyPelanggaran)">-</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Skala Fuzzy C2</p>
            <p class="mt-1 text-lg font-semibold text-gray-900" x-text="displayValue(skalaFuzzyKeterlibatan)">-</p>
        </div>
    </div>

    <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-4">
        <p class="text-sm font-semibold text-gray-800">Catatan Proses</p>
        <div class="mt-2 space-y-1 text-sm text-gray-600">
            <p>C1: jenis pelanggaran, C2: tingkat keterlibatan.</p>
            <p>Skala fuzzy diambil langsung dari master data yang dipilih.</p>
        </div>
    </div>
</div>

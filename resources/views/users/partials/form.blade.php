@php
    $user = $user ?? null;
    $mode = $mode ?? 'create';
    $isEdit = $mode === 'edit';
    $selectedGender = old('jenis_kelamin', $user?->jenis_kelamin);
    $selectedRole = old('role', $user?->role);
@endphp

<div class="grid gap-4">
    <div>
        <label for="{{ $mode }}-nama-{{ $user?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Nama
        </label>
        <input
            id="{{ $mode }}-nama-{{ $user?->id ?? 'new' }}"
            name="nama"
            type="text"
            value="{{ old('nama', $user?->nama) }}"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
        @error('nama')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-username-{{ $user?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Username
        </label>
        <input
            id="{{ $mode }}-username-{{ $user?->id ?? 'new' }}"
            name="username"
            type="text"
            value="{{ old('username', $user?->username) }}"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
        @error('username')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-nip-{{ $user?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            NIP
        </label>
        <input
            id="{{ $mode }}-nip-{{ $user?->id ?? 'new' }}"
            name="nip"
            type="text"
            value="{{ old('nip', $user?->nip) }}"
            inputmode="numeric"
            maxlength="18"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
        <p class="mt-1 text-xs text-gray-500">NIP wajib tepat 18 digit angka.</p>
        @error('nip')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-jenis-kelamin-{{ $user?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Jenis Kelamin
        </label>
        <select
            id="{{ $mode }}-jenis-kelamin-{{ $user?->id ?? 'new' }}"
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
        <label for="{{ $mode }}-role-{{ $user?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Role
        </label>
        <select
            id="{{ $mode }}-role-{{ $user?->id ?? 'new' }}"
            name="role"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
            <option value="">Pilih role</option>
            <option value="admin" @selected($selectedRole === 'admin')>Admin</option>
            <option value="guru_bk" @selected($selectedRole === 'guru_bk')>Guru BK</option>
        </select>
        @error('role')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-password-{{ $user?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Password
        </label>
        <input
            id="{{ $mode }}-password-{{ $user?->id ?? 'new' }}"
            name="password"
            type="password"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            @required(! $isEdit)
        >
        @if ($isEdit)
            <p class="mt-1 text-xs text-gray-500">Kosongkan jika password tidak ingin diubah.</p>
        @endif
        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

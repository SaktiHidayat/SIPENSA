<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrasi | SIPENSA</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-zinc-100 via-gray-100 to-zinc-200">
    <div class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-2xl">
                <h1 class="text-center text-2xl font-bold text-gray-900">Buat Akun SIPENSA</h1>
                <p class="mb-8 mt-2 text-center text-gray-500">Daftarkan akun tenaga pendidik</p>

                <x-ui.flash-message />

                <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="nama" class="mb-2 block text-sm font-semibold text-gray-700">Nama</label>
                        <input id="nama" type="text" name="nama" value="{{ old('nama') }}" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap" class="w-full rounded-xl border border-gray-300 px-4 py-3 transition outline-none focus:border-black focus:ring-4 focus:ring-gray-200">
                        @error('nama')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="mb-2 block text-sm font-semibold text-gray-700">Username</label>
                        <input id="username" type="text" name="username" value="{{ old('username') }}" required autocomplete="username" placeholder="Buat username" class="w-full rounded-xl border border-gray-300 px-4 py-3 transition outline-none focus:border-black focus:ring-4 focus:ring-gray-200">
                        @error('username')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nip" class="mb-2 block text-sm font-semibold text-gray-700">NIP</label>
                        <input id="nip" type="text" name="nip" value="{{ old('nip') }}" required inputmode="numeric" maxlength="18" autocomplete="off" placeholder="Masukkan 18 digit NIP" class="w-full rounded-xl border border-gray-300 px-4 py-3 transition outline-none focus:border-black focus:ring-4 focus:ring-gray-200">
                        @error('nip')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="mb-2 block text-sm font-semibold text-gray-700">Jenis kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" required class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 transition outline-none focus:border-black focus:ring-4 focus:ring-gray-200">
                            <option value="">Pilih jenis kelamin</option>
                            <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="mb-2 block text-sm font-semibold text-gray-700">Role</label>
                        <select id="role" name="role" required class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 transition outline-none focus:border-black focus:ring-4 focus:ring-gray-200">
                            <option value="">Pilih role</option>
                            <option value="guru_bk" @selected(old('role') === 'guru_bk')>Guru BK</option>
                            <option value="wali_kelas" @selected(old('role') === 'wali_kelas')>Wali Kelas</option>
                        </select>
                        @error('role')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-gray-700">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Buat password minimal 8 karakter" class="w-full rounded-xl border border-gray-300 px-4 py-3 transition outline-none focus:border-black focus:ring-4 focus:ring-gray-200">
                        @error('password')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-gray-700">Konfirmasi password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" class="w-full rounded-xl border border-gray-300 px-4 py-3 transition outline-none focus:border-black focus:ring-4 focus:ring-gray-200">
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-black py-3 font-semibold text-white transition hover:bg-zinc-800">
                        Daftar
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-gray-900 underline hover:text-gray-600">Masuk</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
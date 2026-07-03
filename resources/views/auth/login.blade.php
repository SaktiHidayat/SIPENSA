<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | SIPENSA</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-zinc-100 via-gray-100 to-zinc-200">

<div class="flex items-center justify-center min-h-screen px-4">

    <div class="w-full max-w-md">


        <!-- Card -->

        <div class="bg-white rounded-3xl shadow-2xl border border-gray-200 p-8">

            <h2 class="text-2xl font-bold text-center text-gray-900">

                Selamat Datang

            </h2>

            <p class="text-center text-gray-500 mt-2 mb-8">

                Login menggunakan akun Anda

            </p>

            <x-ui.flash-message />

            <form method="POST"
                action="{{ route('login') }}"
                class="space-y-6">

                @csrf

                <!-- Username -->

                <div>

                    <label class="mb-2 block text-sm font-semibold text-gray-700">

                        Username

                    </label>

                    <input
                        type="text"
                        name="username"
                        value="{{ old('username') }}"
                        required
                        autofocus
                        placeholder="Masukkan username"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 transition focus:border-black focus:ring-4 focus:ring-gray-200 outline-none">

                    @error('username')

                        <p class="mt-2 text-sm text-red-500">

                            {{ $message }}

                        </p>

                    @enderror

                </div>

                <!-- Password -->

                <div>

                    <label class="mb-2 block text-sm font-semibold text-gray-700">

                        Password

                    </label>

                    <div class="relative">

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            placeholder="Masukkan password"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 pr-12 transition focus:border-black focus:ring-4 focus:ring-gray-200 outline-none">

                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-black">

                            <!-- Mata -->

                            <svg id="eyeOpen"
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5
                                    12 5c4.478 0 8.268 2.943
                                    9.542 7-1.274 4.057-5.064
                                    7-9.542 7-4.477
                                    0-8.268-2.943-9.542-7z"/>

                            </svg>

                            <!-- Mata Tutup -->

                            <svg id="eyeClose"
                                xmlns="http://www.w3.org/2000/svg"
                                class="hidden w-6 h-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 3l18 18"/>

                            </svg>

                        </button>

                    </div>

                    @error('password')

                        <p class="mt-2 text-sm text-red-500">

                            {{ $message }}

                        </p>

                    @enderror

                </div>

                <!-- Button -->

                <button
                    class="w-full rounded-xl bg-black py-3 font-semibold text-white transition hover:bg-zinc-800">

                    Masuk

                </button>

            </form>

        </div>

    </div>

</div>

<script>

function togglePassword(){

    const input=document.getElementById('password');

    const eyeOpen=document.getElementById('eyeOpen');

    const eyeClose=document.getElementById('eyeClose');

    if(input.type==="password"){

        input.type="text";

        eyeOpen.classList.add('hidden');

        eyeClose.classList.remove('hidden');

    }else{

        input.type="password";

        eyeOpen.classList.remove('hidden');

        eyeClose.classList.add('hidden');

    }

}

</script>

</body>

</html>

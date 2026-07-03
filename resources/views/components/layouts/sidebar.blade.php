<aside
    :class="sidebar ? 'w-64' : 'w-20'"
    class="flex flex-col h-screen bg-black text-white shadow-xl transition-all duration-300">

    {{-- Logo --}}
    <div class="h-16 flex items-center justify-center border-b border-zinc-700">

        <a
            href="{{ route('dashboard') }}"
            x-show="sidebar"
            class="text-xl font-bold tracking-widest hover:text-gray-200">

            SIPENSA

        </a>

        <a
            href="{{ route('dashboard') }}"
            x-show="!sidebar"
            class="text-2xl font-bold hover:text-gray-200">

            S

        </a>

    </div>

    {{-- Menu --}}
    <nav class="flex-1 overflow-y-auto">

        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-5 py-4 border-b border-zinc-700 transition
            {{ request()->routeIs('dashboard') ? 'bg-zinc-800 border-l-4 border-white' : 'hover:bg-zinc-900' }}">

            🏠

            <span x-show="sidebar">
                Dashboard
            </span>

        </a>

        <a href="{{ route('users.index') }}"
            class="flex items-center gap-3 px-5 py-4 border-b border-zinc-700 transition
            {{ request()->routeIs('users.*') ? 'bg-zinc-800 border-l-4 border-white' : 'hover:bg-zinc-900' }}">

            👤

            <span x-show="sidebar">
                Data User
            </span>

        </a>

        <a href="{{ route('siswas.index') }}"
            class="flex items-center gap-3 px-5 py-4 border-b border-zinc-700 transition
            {{ request()->routeIs('siswas.*') ? 'bg-zinc-800 border-l-4 border-white' : 'hover:bg-zinc-900' }}">

            🎓

            <span x-show="sidebar">
                Data Siswa
            </span>

        </a>

        <a href="#"
            class="flex items-center gap-3 px-5 py-4 border-b border-zinc-700 hover:bg-zinc-900 transition">

            📋

            <span x-show="sidebar">
                Data Kriteria
            </span>

        </a>

        <a href="#"
            class="flex items-center gap-3 px-5 py-4 border-b border-zinc-700 hover:bg-zinc-900 transition">

            📑

            <span x-show="sidebar">
                Data Sub Kriteria
            </span>

        </a>

        <a href="#"
            class="flex items-center gap-3 px-5 py-4 border-b border-zinc-700 hover:bg-zinc-900 transition">

            ⚠️

            <span x-show="sidebar">
                Jenis Pelanggaran
            </span>

        </a>

        <a href="#"
            class="flex items-center gap-3 px-5 py-4 border-b border-zinc-700 hover:bg-zinc-900 transition">

            🛡️

            <span x-show="sidebar">
                Jenis Sanksi
            </span>

        </a>

        <a href="#"
            class="flex items-center gap-3 px-5 py-4 border-b border-zinc-700 hover:bg-zinc-900 transition">

            ⚙️

            <span x-show="sidebar">
                Proses SPK
            </span>

        </a>

    </nav>

    {{-- Logout --}}
    <div class="border-t border-zinc-700">

        <form action="{{ route('logout') }}" method="POST">

            @csrf

            <button
                type="submit"
                class="w-full flex items-center gap-3 px-5 py-4 text-red-400 hover:bg-red-600 hover:text-white transition">

                🚪

                <span x-show="sidebar">
                    Logout
                </span>

            </button>

        </form>

    </div>

</aside>

<aside
    :class="sidebar ? 'w-64' : 'w-20'"
    class="flex h-screen flex-col bg-black text-white shadow-xl transition-all duration-300">

    <div class="flex h-16 items-center justify-center border-b border-zinc-700">
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

    @php
        $iconClass = 'h-5 w-5 shrink-0 text-gray-300';
    @endphp

    <nav class="flex flex-1 flex-col overflow-y-auto">
        <x-layouts.sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <x-slot name="icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="{{ $iconClass }}">
                    <path d="M3 11.5L12 4l9 7.5" />
                    <path d="M5 10.5V20h14v-9.5" />
                    <path d="M9 20v-6h6v6" />
                </svg>
            </x-slot>
            Dashboard
        </x-layouts.sidebar-link>

        <x-layouts.sidebar-group
            title="Master Data"
            :open="request()->routeIs('users.*', 'siswas.*', 'alternatifs.*')"
            :active="request()->routeIs('users.*', 'siswas.*', 'alternatifs.*')"
        >
            <x-slot name="icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="{{ $iconClass }}">
                    <path d="M4 6h16" />
                    <path d="M4 12h10" />
                    <path d="M4 18h13" />
                    <circle cx="18" cy="12" r="2" />
                </svg>
            </x-slot>

            <x-layouts.sidebar-link :href="route('users.index')" :active="request()->routeIs('users.*')" :indent="true">
                <x-slot name="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="{{ $iconClass }}">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" />
                        <circle cx="10" cy="7" r="4" />
                        <path d="M20 21v-2a3 3 0 0 0-2-2.83" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </x-slot>
                Data User
            </x-layouts.sidebar-link>

            <x-layouts.sidebar-link :href="route('siswas.index')" :active="request()->routeIs('siswas.*')" :indent="true">
                <x-slot name="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="{{ $iconClass }}">
                        <path d="M12 3l8 4-8 4-8-4 8-4z" />
                        <path d="M4 11l8 4 8-4" />
                        <path d="M4 15l8 4 8-4" />
                    </svg>
                </x-slot>
                Data Siswa
            </x-layouts.sidebar-link>

            <x-layouts.sidebar-link :href="route('alternatifs.index')" :active="request()->routeIs('alternatifs.*')" :indent="true">
                <x-slot name="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="{{ $iconClass }}">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                        <path d="M7 7l-2 5 2 5" />
                        <path d="M17 7l2 5-2 5" />
                    </svg>
                </x-slot>
                Data Alternatif
            </x-layouts.sidebar-link>
        </x-layouts.sidebar-group>

        <x-layouts.sidebar-group
            title="Data Penilaian"
            :open="request()->routeIs('kriterias.*', 'pelanggarans.*', 'keterlibatans.*', 'hukumans.*')"
            :active="request()->routeIs('kriterias.*', 'pelanggarans.*', 'keterlibatans.*', 'hukumans.*')"
        >
            <x-slot name="icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="{{ $iconClass }}">
                    <path d="M4 6h16" />
                    <path d="M4 12h10" />
                    <path d="M4 18h13" />
                    <circle cx="18" cy="12" r="2" />
                </svg>
            </x-slot>

            <x-layouts.sidebar-link :href="route('kriterias.index')" :active="request()->routeIs('kriterias.*')" :indent="true">
                <x-slot name="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="{{ $iconClass }}">
                        <path d="M4 6h16" />
                        <path d="M4 12h10" />
                        <path d="M4 18h13" />
                        <circle cx="18" cy="12" r="2" />
                    </svg>
                </x-slot>
                Data Kriteria
            </x-layouts.sidebar-link>

            <x-layouts.sidebar-link :href="route('pelanggarans.index')" :active="request()->routeIs('pelanggarans.*')" :indent="true">
                <x-slot name="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="{{ $iconClass }}">
                        <path d="M12 3l9 16H3L12 3z" />
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                    </svg>
                </x-slot>
                Data Jenis Pelanggaran
            </x-layouts.sidebar-link>

            <x-layouts.sidebar-link :href="route('keterlibatans.index')" :active="request()->routeIs('keterlibatans.*')" :indent="true">
                <x-slot name="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="{{ $iconClass }}">
                        <path d="M4 7h6v6H4z" />
                        <path d="M14 7h6v6h-6z" />
                        <path d="M7 13v4" />
                        <path d="M17 13v4" />
                        <path d="M7 17h10" />
                    </svg>
                </x-slot>
                Data Tingkat Keterlibatan
            </x-layouts.sidebar-link>

            <x-layouts.sidebar-link :href="route('hukumans.index')" :active="request()->routeIs('hukumans.*')" :indent="true">
                <x-slot name="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="{{ $iconClass }}">
                        <path d="M9 3h6" />
                        <path d="M10 3l-2 4h8l-2-4" />
                        <path d="M6 7h12" />
                        <path d="M7 7l-2 8h14l-2-8" />
                        <path d="M12 15v4" />
                    </svg>
                </x-slot>
                Data Jenis Hukuman
            </x-layouts.sidebar-link>
        </x-layouts.sidebar-group>

        <x-layouts.sidebar-link :href="'#'" :active="false">
            <x-slot name="icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="{{ $iconClass }}">
                    <path d="M12 3v18" />
                    <path d="M5 7h14" />
                    <path d="M7 7l-2 8h4l-2-8z" />
                    <path d="M17 7l-2 8h4l-2-8z" />
                </svg>
            </x-slot>
            Proses SPK
        </x-layouts.sidebar-link>
    </nav>

    <div class="border-t border-zinc-700">
        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button
                type="submit"
                class="flex w-full items-center gap-3 px-5 py-4 text-red-400 transition hover:bg-red-600 hover:text-white">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 shrink-0">
                    <path d="M10 17l5-5-5-5" />
                    <path d="M15 12H3" />
                    <path d="M14 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                </svg>
                <span x-show="sidebar">Logout</span>
            </button>
        </form>
    </div>
</aside>

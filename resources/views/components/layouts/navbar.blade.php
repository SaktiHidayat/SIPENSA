<header
class="bg-white border-b shadow-sm">

<div class="flex items-center justify-between px-6 h-16">

    {{-- Left --}}

    <div class="flex items-center gap-3">

        <button

            @click="sidebar=!sidebar"

            class="p-2 rounded-lg hover:bg-gray-100">

            ☰

        </button>

        <a href="{{ route('dashboard') }}" class="font-bold text-black text-xl hover:text-zinc-700">

            SIPENSA

        </a>

    </div>

    {{-- Right --}}

    <div class="flex items-center gap-2">
<div
        x-data="{open:false}"
        class="relative">

        <button

            @click="open=!open"

            class="flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-gray-100">

            <img

                src="{{ auth()->user()->foto_url }}"
                onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama) }}&background=111827&color=ffffff';"

                class="w-10 h-10 shrink-0 rounded-full object-cover">

            <div class="hidden md:block text-left">

                <div class="font-semibold">

                    {{ auth()->user()->nama }}

                </div>

                <div class="text-xs text-gray-500">

                    {{ match (auth()->user()->role) { 'admin', 'operator' => 'Operator', 'guru_bk' => 'Guru BK', 'wali_kelas' => 'Wali Kelas', default => auth()->user()->role } }}

                </div>

            </div>

        </button>

        <div

            x-show="open"

            @click.outside="open=false"

            x-transition

            class="absolute right-0 mt-3 bg-white rounded-xl shadow-xl w-48 overflow-hidden">

            <a
                href="{{ route('profile.edit') }}"
                class="block px-5 py-3 hover:bg-gray-100">

                Profil

            </a>

            <form

                action="{{ route('logout') }}"

                method="POST">

                @csrf

                <button

                    class="w-full text-left px-5 py-3 hover:bg-red-100 text-red-600">

                    Logout

                </button>

            </form>

        </div>

    </div>

</div>

</header>

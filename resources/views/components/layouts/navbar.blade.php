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

        <h1 class="font-bold text-black text-xl">

            SIPENSA

        </h1>

    </div>

    {{-- Right --}}

    <div
        x-data="{open:false}"
        class="relative">

        <button

            @click="open=!open"

            class="flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-gray-100">

            <img

                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama) }}"

                class="w-10 h-10 rounded-full">

            <div class="hidden md:block text-left">

                <div class="font-semibold">

                    {{ auth()->user()->nama }}

                </div>

                <div class="text-xs text-gray-500">

                    {{ auth()->user()->getRoleNames()->first() }}

                </div>

            </div>

        </button>

        <div

            x-show="open"

            @click.outside="open=false"

            x-transition

            class="absolute right-0 mt-3 bg-white rounded-xl shadow-xl w-48 overflow-hidden">

            <a
                href="#"
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
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <title>@yield('title','SIPERSA')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

</head>

<body class="bg-gray-100">

<div
    x-data="{ sidebar:true }"
    class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}

    @include('components.layouts.sidebar')

    <div class="flex flex-col flex-1 overflow-hidden">

        {{-- Navbar --}}

        @include('components.layouts.navbar')

        {{-- Content --}}

        <main class="flex-1 overflow-y-auto p-6">

            @yield('content')

        </main>

        {{-- Footer --}}

        @include('components.layouts.footer')

    </div>

</div>

</body>

</html>
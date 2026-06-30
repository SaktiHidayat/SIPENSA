@extends('layouts.app')

@section('title','Dashboard')

@section('content')

<div class="bg-white rounded-2xl shadow p-8">

    <h2 class="text-2xl font-bold">

        Selamat Datang,

        {{ auth()->user()->nama }}

    </h2>

    <p class="mt-3 text-gray-500">

        Anda berhasil login ke aplikasi SIPENSA.

    </p>

</div>

@endsection
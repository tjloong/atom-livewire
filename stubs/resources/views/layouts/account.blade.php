@extends('atom::layout')

@section('content')
    <x-popup/>
    <x-loader/>

    <x-navbar class="bg-white shadow py-2" class.logo="h-[40px]"/>

    <div class="min-h-screen bg-gray-100 py-20">
        {{ $slot }}
    </div>
@endsection

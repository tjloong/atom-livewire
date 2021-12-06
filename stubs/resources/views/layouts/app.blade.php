@extends('atom::layout', ['noindex' => true, 'tracking' => false])

@section('content')
    <x-admin-panel :dropdown="$dropdown" :navs="$navs">
        <x-slot name="brand">
            <img src="/storage/img/logo-sm.svg" class="w-7 h-7 object-contain mr-2">
            <div class="text-white text-lg">
                <span class="font-bold">Atom</span><span class="font-light">cms</span>
            </div>
        </x-slot>

        <x-slot name="navbarLeft">
            <a href="/" class="text-gray-800 flex items-center">
                Go to site <x-icon name="right-arrow-alt" size="18px"/>
            </a>
        </x-slot>

        {{ $slot }}
    </x-admin-panel>
@endsection
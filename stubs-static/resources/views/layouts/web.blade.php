@extends('atom::layout', ['script' => 'js/web.js'])

@section('content')
    <x-builder.navbar>
        <x-builder.navbar-item href="{{ route('contact', ['ref' => 'landing']) }}"/>
    </x-builder.navbar>

    <x-fullscreen-loader/>

    {{ $slot }}

    <footer>
        <x-builder.footer/>
    </footer>
@endsection
@extends('atom::layout', ['script' => mix('js/web.js')])

@section('content')
    <x-builder.navbar align="right">
        <x-builder.navbar item href="{{ route('blog.show') }}">Blogs</x-builder.navbar>
        <x-builder.navbar item href="{{ route('contact', ['ref' => 'landing']) }}">Contact</x-builder.navbar>

        <x-slot name="auth">
            <x-builder.navbar dropdown-item href="{{ route('dashboard') }}">Go to Dashboard</x-builder.navbar>
        </x-slot>
    </x-builder.navbar>

    <x-fullscreen-loader/>
    
    {{ $slot }}

    <footer>
        <x-builder.footer/>
    </footer>
@endsection
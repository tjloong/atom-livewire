@extends('atom::layout')

@section('content')
    <x-builder.navbar align="right">
        <x-builder.navbar-item href="{{ route('blog.show') }}">Blogs</x-builder.navbar-item>
        <x-builder.navbar-item href="{{ route('contact', ['ref' => 'landing']) }}">Contact</x-builder.navbar-item>

        <x-slot name="auth">
            <x-builder.navbar-item dropdown-item href="{{ route('dashboard') }}">Go to Dashboard</x-builder.navbar-item>
        </x-slot>
    </x-builder.navbar>

    <x-fullscreen-loader/>
    
    {{ $slot }}

    <footer>
        <x-builder.footer/>
    </footer>
@endsection
@extends('atom::layout')

@push('scripts')
    <script src="{{ mix('js/web.js') }}" defer></script>
@endpush

@section('content')
    <x-builder.navbar align="right">
        @if (Route::has('blogs'))
            <x-builder.navbar item href="{{ route('blogs') }}">Blogs</x-builder.navbar>
        @endif

        @if (Route::has('contact'))
            <x-builder.navbar item href="{{ route('contact', ['ref' => 'landing']) }}">Contact</x-builder.navbar>
        @endif

        <x-slot name="auth">
            @if (auth()->user()->canAccessApp())
                <x-builder.navbar dropdown-item href="{{ route('app.home') }}" icon="home">Back to App</x-builder.navbar>
            @else
                <x-builder.navbar dropdown-item href="{{ route('user.home') }}" icon="user-pin">Account</x-builder.navbar>
            @endif
        </x-slot>
    </x-builder.navbar>

    <x-fullscreen-loader/>
    
    {{ $slot }}

    <footer>
        <x-builder.footer/>
    </footer>
@endsection
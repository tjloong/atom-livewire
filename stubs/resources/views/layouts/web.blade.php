@extends('atom::layout')

@push('scripts')
    <script src="{{ mix('js/web.js') }}" defer></script>
@endpush

@section('content')
    <x-builder.navbar align="right" back-to-app>
        @if (Route::has('blogs'))
            <x-builder.navbar item href="{{ route('blogs') }}">Blogs</x-builder.navbar>
        @endif

        @if (Route::has('contact'))
            <x-builder.navbar item href="{{ route('contact', ['ref' => 'landing']) }}">Contact</x-builder.navbar>
        @endif
    </x-builder.navbar>

    <x-loader/>
    
    {{ $slot }}

    <footer>
        <x-builder.footer/>
    </footer>
@endsection
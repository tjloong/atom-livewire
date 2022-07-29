@extends('atom::layout', [
    'indexing' => true, 
    'analytics' => true,
    'cdn' => ['swiper', 'social-share'],
])

@section('content')
    <x-navbar align="right">
        @if (Route::has('blogs'))
            <x-navbar.item href="{{ route('page', ['blog']) }}" label="Blogs"/>
        @endif

        @if (Route::has('contact'))
            <x-navbar.item href="{{ route('page', ['slug' => 'contact', 'ref' => 'landing']) }}" label="Contact"/>
        @endif
    </x-navbar>

    <x-loader/>
    
    {{ $slot }}

    <footer>
        <x-footer/>
    </footer>
@endsection
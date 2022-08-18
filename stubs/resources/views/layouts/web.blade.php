@extends('atom::layout', [
    'indexing' => true, 
    'analytics' => true,
    'cdn' => ['swiper', 'social-share'],
])

@section('content')
    <x-navbar align="right">
        @module('blogs')
            <x-navbar.item href="/blog" label="Blogs"/>
        @endmodule

        <x-navbar.item href="/contact-us?ref=landing" label="Contact"/>
    </x-navbar>

    <x-loader/>
    
    {{ $slot }}

    <footer>
        <x-footer/>
    </footer>
@endsection
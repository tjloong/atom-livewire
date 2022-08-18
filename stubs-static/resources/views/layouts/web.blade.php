@extends('atom::layout', [
    'indexing' => true, 
    'analytics' => true,
    'cdn' => ['swiper'],
])

@section('content')
    <x-navbar align="right">
        <x-navbar.item href="/contact-us?ref=landing" label="Contact"/>
    </x-navbar>

    <x-loader/>
    
    {{ $slot }}

    <footer>
        <x-footer/>
    </footer>
@endsection
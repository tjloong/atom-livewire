@extends('atom::layout', [
    'indexing' => true, 
    'analytics' => true,
    'cdn' => ['swiper'],
])

@section('content')
    <x-navbar>
        <x-slot:body>
            <x-navbar.item href="/contact-us?ref=landing" label="Contact"/>
        </x-slot:body>
    </x-navbar>
    
    {{ $slot }}

    <footer>
        <x-footer/>
    </footer>
@endsection
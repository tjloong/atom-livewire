@extends('atom::layout', ['indexing' => true, 'analytics' => true])

@push('scripts')
    <script src="{{ mix('js/web.js') }}" defer></script>
@endpush

@section('content')
    <x-navbar align="right" back-to-app>
        @if (Route::has('blogs'))
            <x-navbar.item label="Blogs" :href="route('page', ['slug' => 'blogs'])"/>
        @endif

        @if (Route::has('contact'))
            <x-navbar.item label="Contact" :href="route('page', ['slug' => 'contact', 'params' => ['ref' => 'landing']])"/>
        @endif
    </x-navbar>

    <x-loader/>
    
    {{ $slot }}

    <footer>
        <x-footer/>
    </footer>
@endsection
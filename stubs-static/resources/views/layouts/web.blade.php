@extends('atom::layout')

@push('scripts')
    <script src="{{ mix('js/web.js') }}" defer></script>
@endpush

@section('content')
    <x-builder.navbar align="right">
        <x-builder.navbar item href="{{ route('contact', ['ref' => 'landing']) }}">Contact</x-builder.navbar>
    </x-builder.navbar>

    <x-loader/>

    {{ $slot }}

    <footer>
        <x-builder.footer/>
    </footer>
@endsection
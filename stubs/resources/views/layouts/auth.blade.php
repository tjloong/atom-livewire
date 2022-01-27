@extends('atom::layout', ['noindex' => true, 'tracking' => false])

@push('scripts')
    <script src="{{ mix('js/web.js') }}" defer></script>
@endpush

@section('content')
    <x-fullscreen-loader/>

    <div class="min-h-screen relative bg-gray-100 px-4 py-12 md:py-20">
        {{ $slot }}
    </div>
@endsection
@extends('atom::layout', ['noindex' => true, 'tracking' => false])

@section('content')
    <div class="min-h-screen relative bg-gray-100 px-4 py-12 md:py-20">
        {{ $slot }}
    </div>
@endsection
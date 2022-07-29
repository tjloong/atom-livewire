@extends('atom::layout')

@section('content')
    <x-loader/>

    <div class="min-h-screen relative bg-gray-100 px-4 py-12 md:py-20">
        {{ $slot }}
    </div>
@endsection
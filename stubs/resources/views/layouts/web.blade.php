@extends('atom::layout')

@push('scripts')
    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
@endpush

@section('content')
    <nav 
        x-data="{ show: false }"
        x-on:click.away="show = false"
        class="sticky left-0 right-0 top-0 z-10 bg-white drop-shadow p-4 relative"
    >
        <div class="max-w-screen-xl mx-auto h-8 md:flex md:items-center md:justify-between md:space-x-4">
            <div class="flex items-center justify-between h-full">
                <a href="/" class="w-40 h-full">
                    <img src="/storage/img/logo.svg" class="w-full h-full object-contain object-left" alt="{{ config('app.name') }}">
                </a>

                <a x-on:click="show = true" class="flex items-center justify-center md:hidden">
                    <x-icon name="menu"/>
                </a>
            </div>

            <div
                x-bind:class="show ? 'flex' : 'hidden'"
                class="
                    absolute top-full left-0 right-0 flex-col items-center space-y-2 bg-white pb-4 
                    md:static md:flex md:flex-grow md:flex-row md:justify-between md:space-y-0 md:pb-0
                "
            >
                <div class="flex flex-col items-center space-y-1.5 md:flex-row md:space-x-2 md:space-y-0">
                    <a href="/" class="px-3 text-gray-800 font-medium hover:text-theme">
                        Home
                    </a>
                    <a href="{{ route('blog.show') }}" class="px-3 text-gray-800 font-medium hover:text-theme">
                        Blogs
                    </a>
                    <a href="{{ route('contact', ['ref' => 'landing']) }}" class="px-3 text-gray-800 font-medium hover:text-theme">
                        Contact
                    </a>
                </div>

                <div class="flex flex-col items-center space-y-1.5 md:flex-row md:space-x-4 md:space-y-0">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-3 text-gray-800 font-medium hover:text-theme">
                            Go to Dashboard
                        </a>

                    @else
                        <a href="{{ route('login') }}" class="px-3 text-gray-800 font-medium hover:text-theme">
                            Login
                        </a>

                        <x-button href="{{ route('register', ['ref' => 'landing']) }}">
                            Get Started
                        </x-button>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <x-fullscreen-loader/>
    
    {{ $slot }}
@endsection
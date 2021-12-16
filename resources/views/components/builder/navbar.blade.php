<nav
    x-data="{ show: false }"
    x-on:click.away="show = false"
    x-cloak
    class="
        relative p-4 
        {{ $sticky ? 'sticky top-0 z-10 drop-shadow' : '' }}
        {{ $attributes->get('class') ?? 'bg-white' }}
    "
>
    <div class="max-w-screen-xl mx-auto grid space-y-4 md:flex md:space-y-0 md:space-x-4">
        <div class="flex justify-between items-center">
            <a href="/" class="w-32 h-full">
                <img
                    src="{{ $attributes->get('logo') ?? '/storage/img/logo.svg' }}"
                    class="w-full h-full object-contain object-left"
                    width="200px"
                    height="100px"
                    alt="{{ config('app.name') }} Logo"
                >
            </a>

            <a x-on:click="show = !show" class="flex items-center justify-center md:hidden">
                <x-icon x-show="!show" name="menu"/>
                <x-icon x-show="show" name="x" size="30px"/>
            </a>
        </div>

        <div 
            x-bind:class="show ? 'grid' : 'hidden'"
            class="
                justify-center md:flex-grow md:flex md:items-center
                @if ($align === 'left') md:justify-start
                @elseif ($align === 'center') md:justify-center
                @elseif ($align === 'right') md:justify-end
                @endif
            "
        >
            {{ $slot }}
        </div>

        @if ($login || $register)
            <div 
                x-bind:class="show ? 'grid' : 'hidden'"
                class="justify-center items-center md:flex-shrink-0 md:flex md:space-x-2"
            >
                @auth
                    <x-builder.navbar-item dropdown>
                        <div class="flex items-center justify-center space-x-2">
                            <x-icon name="user-circle"/>
                            <div class="truncate">{{ auth()->user()->name }}</div>
                            <x-icon name="chevron-down"/>
                        </div>

                        <x-slot name="dropdown">
                            @isset($auth)
                                {{ $auth }}
                            @endisset

                            <x-builder.navbar-item dropdown-item href="{{ route('login', ['logout' => true]) }}">
                                Logout
                            </x-builder.navbar-item>
                        </x-slot>
                    </x-builder.navbar-item>
                @else
                    <x-builder.navbar-item href="{{ route('login') }}">
                        Login
                    </x-builder.navbar-item>

                    <x-button href="{{ route('register', ['ref' => 'landing']) }}">
                        Get Started
                    </x-button>
                @endauth
            </div>
        @endif
    </div>
</nav>

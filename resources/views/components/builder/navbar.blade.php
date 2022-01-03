@if ($attributes->has('dropdown'))
    <div x-data="{ open: false }" x-on:click.away="open = false" class="relative">
        <a 
            x-on:click.prevent="open = true"
            {{ $attributes->merge(['class' => 'block py-1.5 px-3 text-center text-gray-800 font-medium hover:text-theme']) }}
        >
            {{ $slot }}
        </a>

        <div
            x-show="open"
            x-transition
            class="
                grid 
                md:absolute md:z-10 md:right-0 md:w-max 
                md:bg-white md:drop-shadow-md md:rounded-md md:border
                md:py-2 md:min-w-[200px]
            "
        >
            {{ $dropdown }}
        </div>
    </div>
@elseif ($attributes->has('dropdown-item'))
    <a {{ $attributes->merge([
        'class' => '
            py-1.5 px-3 text-center text-sm text-gray-500 font-medium hover:text-theme 
            md:text-left md:text-gray-800 md:hover:bg-gray-100
        '
    ]) }}>
        {{ $slot }}
    </a>
@elseif ($attributes->has('item'))
    <a {{ $attributes->merge(['class' => 'py-1.5 px-3 text-center text-gray-800 font-medium hover:text-theme']) }}>
        {{ $slot }}
    </a>
@else
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
                @isset($logo)
                    {{ $logo }}
                @else
                    <a href="/" class="w-24 h-8 md:w-40">
                        @if ($attributes->get('logo'))
                            <img src="{{ $attributes->get('logo') }}" width="300" height="150" alt="{{ config('app.name') }}" class="w-full h-full object-contain">
                        @else
                            <x-atom-logo/>
                        @endif
                    </a>
                @endisset

                <a x-on:click="show = !show" class="flex items-center justify-center md:hidden">
                    <x-icon x-show="!show" name="menu"/>
                    <x-icon x-show="show" name="x" size="30px"/>
                </a>
            </div>

            <div 
                x-bind:class="show ? 'grid' : 'hidden'"
                class="
                    justify-center md:flex-grow md:flex md:items-center md:gap-2
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
                        <x-builder.navbar dropdown>
                            <div class="flex items-center justify-center space-x-2">
                                <x-icon name="user-circle"/>
                                <div class="truncate">{{ auth()->user()->name }}</div>
                                <x-icon name="chevron-down"/>
                            </div>

                            <x-slot name="dropdown">
                                @isset($auth)
                                    {{ $auth }}
                                @endisset

                                <x-builder.navbar dropdown-item href="{{ route('login', ['logout' => true]) }}">
                                    Logout
                                </x-builder.navbar>
                            </x-slot>
                        </x-builder.navbar>
                    @else
                        <x-builder.navbar item href="{{ route('login') }}">
                            Login
                        </x-builder.navbar>

                        <x-button href="{{ route('register', ['ref' => 'landing']) }}">
                            {{ $registerPlaceholder }}
                        </x-button>
                    @endauth
                </div>
            @endif
        </div>
    </nav>
@endif

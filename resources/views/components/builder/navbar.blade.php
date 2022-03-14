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
                md:absolute md:z-10 md:w-max {{ $attributes->has('right') ? 'md:right-0' : '' }}
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
            inline-flex items-center justify-center gap-2
            py-1.5 text-gray-800 font-medium hover:text-theme 
            md:px-3 md:hover:bg-gray-100 md:justify-start
        '
    ]) }}>
        @if ($icon = $attributes->get('icon'))
            <x-icon name="{{ $icon }}" size="20px" class="text-gray-400" type="{{ $attributes->get('icon-type') ?? 'regular' }}"/>
        @endif
        {{ $slot }}
    </a>

@elseif ($attributes->has('item'))
    <a 
        href="{!! $attributes->get('href') !!}"
        class="
            py-1.5 px-3 text-center font-medium
            {{ $attributes->get('class') ?? 'text-gray-800 hover:text-theme' }}
        "
    >
        {{ $slot }}
    </a>

@else
    <nav 
        x-data="{ show: false }"
        x-cloak
        class="{{
            collect([
                'relative',
                ($sticky ? 'sticky top-0 z-10' : null),
                ($attributes->get('class') ?? 'p-4'),
            ])->filter()->join(' ')
        }}"
    >
        <div class="max-w-screen-xl mx-auto grid divide-y">
            <div class="grid gap-4 md:flex">
                <div class="flex justify-between items-center">
                    @isset($logo)
                        {{ $logo }}
                    @else
                        <a href="/" class="{{ $attributes->get('logo-class') ?? 'max-w-[100px] max-h-[50px] md:max-w-[150px] md:max-h-[75px]' }}">
                            @if ($attributes->get('logo'))
                                <img src="{{ $attributes->get('logo') }}" width="300" height="150" alt="{{ config('app.name') }}" class="w-full h-full object-contain">
                            @else
                                <x-atom-logo/>
                            @endif
                        </a>
                    @endisset
    
                    <a x-on:click="show = !show" class="flex items-center justify-center text-gray-800 md:hidden">
                        <x-icon name="menu"/>
                    </a>
                </div>

                {{-- Desktop view --}}
                <div class="flex-grow hidden gap-3 items-center justify-between md:flex">
                    <div
                        class="{{
                            collect([
                                'flex-grow flex gap-3 items-center',
                                ($align === 'left' ? 'justify-start' : null),
                                ($align === 'center' ? 'justify-center' : null),
                                ($align === 'right' ? 'justify-end' : null),
                            ])->filter()->join(' ')
                        }}"
                    >
                        {{ $slot }}

                        @if ($lang)
                            <a>
                                <x-icon name="language"/>
                            </a>
                        @endif
                    </div>

                    @if ($showAuth)
                        @auth
                            <x-builder.navbar dropdown right>
                                <div class="flex items-center justify-center gap-2">
                                    <x-icon name="user-circle"/>
                                    <div class="flex-grow">{{ str(auth()->user()->name)->limit(15) }}</div>
                                    <x-icon name="chevron-down"/>
                                </div>

                                <x-slot name="dropdown">
                                    <div class="grid divide-y gap-3">
                                        <div class="grid">
                                            <x-builder.navbar dropdown-item href="{{ route('account.home') }}" icon="user-pin">
                                                Account
                                            </x-builder.navbar>

                                            @module('plans')
                                                @if (auth()->user()->canAccessBillingPortal())
                                                    <x-builder.navbar dropdown-item href="{{ route('billing') }}" icon="dollar-circle">
                                                        Billing
                                                    </x-builder.navbar>
                                                @endif
                                            @endmodule

                                            @isset($auth)
                                                {{ $auth }}
                                            @endisset
                                        </div>
    
                                        <div class="grid pt-2">
                                            @if ($attributes->has('back-to-app') && auth()->user()->canAccessAppPortal())
                                                <x-builder.navbar dropdown-item href="{{ Route::has('app.home') ? route('app.home') : route('home') }}" icon="home-alt">
                                                    Back to App
                                                </x-builder.navbar>
                                            @endif

                                            <x-builder.navbar dropdown-item href="{{ route('login', ['logout' => true]) }}" icon="log-out">
                                                Logout
                                            </x-builder.navbar>
                                        </div>
                                    </div>
                                </x-slot>
                            </x-builder.navbar>
                        @else
                            <div class="flex items-center gap-3">
                                <x-builder.navbar item href="{{ route('login') }}">Login</x-builder.navbar>

                                @module('accounts')
                                    <x-button href="{{ route('register', ['ref' => 'navbar']) }}">
                                        Register
                                    </x-button>
                                @endmodule
                            </div>
                        @endauth
                    @endif
                </div>

                {{-- Mobile view --}}
                <div x-show="show" x-transition.opacity class="fixed inset-0 z-40 px-4 py-6">
                    <div x-on:click="show = !show" class="absolute inset-0 bg-gray-400/50"></div>

                    <div class="relative bg-white rounded-lg shadow p-4 max-h-[100%] overflow-auto">
                        <div class="grid gap-3">
                            {{ $slot }}

                            @if ($showAuth)
                                <div class="bg-gray-100 rounded-lg p-4">
                                    @auth
                                        <div class="flex-shrink-0 flex items-center justify-center gap-1">
                                            <x-icon name="user-circle" class="text-gray-400"/>
                                            <div class="truncate">{{ auth()->user()->name }}</div>
                                        </div>

                                        <div class="grid mt-2">
                                            <x-builder.navbar dropdown-item href="{{ route('account.home') }}" icon="user-pin">
                                                Account
                                            </x-builder.navbar>

                                            @module('plans')
                                                @if (auth()->user()->canAccessBillingPortal())
                                                    <x-builder.navbar dropdown-item href="{{ route('billing') }}" icon="dollar-circle">
                                                        Billing
                                                    </x-builder.navbar>
                                                @endif
                                            @endmodule

                                            @isset($auth)
                                                {{ $auth }}
                                            @endisset
    
                                            @if ($attributes->has('back-to-app') && auth()->user()->canAccessAppPortal())
                                                <x-builder.navbar dropdown-item href="{{ Route::has('app.home') ? route('app.home') : route('home') }}" icon="home-alt">
                                                    Back to App
                                                </x-builder.navbar>
                                            @endif

                                            <x-builder.navbar dropdown-item href="{{ route('login', ['logout' => 1]) }}" icon="log-out">
                                                Logout
                                            </x-builder.navbar>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center gap-3">
                                            <x-button color="gray" href="{{ route('login') }}">Login</x-button>
                                            @module('accounts')
                                                <x-button href="{{ route('register', ['ref' => 'navbar']) }}">Register</x-button>
                                            @endmodule
                                        </div>
                                    @endauth
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="absolute top-4 right-2 w-8 h-8">
                        <a
                            x-on:click="show = false" 
                            class="block w-full h-full rounded-full bg-white shadow flex items-center justify-center text-gray-500"
                        >
                            <x-icon name="x" size="28px"/>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
@endif

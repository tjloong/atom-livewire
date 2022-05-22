<nav 
    x-cloak
    x-data="{ show: false }"
    class="{{
        collect([
            ($fixed ? 'fixed top-0 left-0 right-0 z-10' : 'relative'),
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
                    <a href="/" class="{{ $attributes->get('class.logo') ?? 'max-w-[100px] max-h-[50px] md:max-w-[150px] md:max-h-[75px]' }}">
                        @if ($attributes->get('logo'))
                            <img src="{{ $attributes->get('logo') }}" width="300" height="150" alt="{{ config('app.name') }}" class="w-full h-full object-contain">
                        @else
                            <x-logo class="w-full h-full"/>
                        @endif
                    </a>
                @endisset

                <a x-on:click="show = !show" class="flex items-center justify-center text-gray-400 md:hidden">
                    <x-icon name="menu" size="md"/>
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

                    @if ($attributes->has('locales') && count(config('atom.locales', [])) > 1)
                        <div class="{{ $attributes->get('class.locales') ?? 'text-gray-800' }}">
                            <x-navbar.locale/>
                        </div>
                    @endif
                </div>

                @if ($showAuth)
                    @auth
                        <x-dropdown>
                            <x-slot:anchor>
                                <div class="flex items-center justify-center gap-2">
                                    <x-icon name="user-circle"/>
                                    <div class="flex-grow">{{ str(auth()->user()->name)->limit(15) }}</div>
                                    <x-icon name="chevron-down"/>
                                </div>
                            </x-slot:anchor>

                            <x-dropdown.item :href="route('account')" icon="user-pin" label="Account"/>

                            @if (auth()->user()->canAccessPortal('billing'))
                                <x-dropdown.item :href="route('billing')" icon="dollar-circle" label="Billing"/>
                            @endif

                            @isset($auth)
                                {{ $auth }}
                            @endisset

                            @if ($backToApp)
                                <x-dropdown.item :href="app_route()" icon="home-alt" label="Back to App"/>
                            @endif

                            <x-dropdown.item :href="route('login', ['logout' => true])" icon="log-out" label="Logout"/>
                        </x-dropdown>

                    @elseif (Route::has('login') || Route::has('register'))
                        <div class="flex items-center gap-3">
                            @if (Route::has('login'))
                                <x-navbar.item :href="route('login')" :label="$loginPlaceholder"/>
                            @endif

                            @if (Route::has('register'))
                                <x-button :href="route('register', ['ref' => 'navbar'])" :label="$registerPlaceholder"/>
                            @endif
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

                        @if ($attributes->has('locales') && count(config('atom.locales', [])) > 1)
                            <x-navbar.locale/>
                        @endif

                        @if ($showAuth)
                            @auth
                                <div class="bg-gray-100 rounded-lg p-4">
                                    <div class="flex-shrink-0 flex items-center justify-center gap-1">
                                        <x-icon name="user-circle" class="text-gray-400"/>
                                        <div class="truncate">{{ auth()->user()->name }}</div>
                                    </div>

                                    <div class="grid mt-2">
                                        <x-dropdown.item :href="route('account')" icon="user-pin" label="Account"/>

                                        @if (auth()->user()->canAccessPortal('billing'))
                                            <x-dropdown.item :href="route('billing')" icon="dollar-circle" label="Billing"/>
                                        @endif

                                        @isset($auth)
                                            {{ $auth }}
                                        @endisset

                                        @if ($backToApp)
                                            <x-dropdown.item :href="app_route()" icon="home-alt" label="Back to App"/>
                                        @endif

                                        <x-dropdown.item :href="route('login', ['logout' => 1])" icon="log-out" label="Logout"/>
                                    </div>
                                </div>
                            @elseif (Route::has('login') || Route::has('register'))
                                <div class="bg-gray-100 rounded-lg p-4">
                                    <div class="flex items-center justify-center gap-3">
                                        @if (Route::has('login'))
                                            <x-button color="gray" :href="route('login')" :label="$loginPlaceholder"/>
                                        @endif

                                        @if (Route::has('register'))
                                            <x-button :href="route('register', ['ref' => 'navbar'])" :label="$registerPlaceholder"/>
                                        @endif
                                    </div>
                                </div>
                            @endauth
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

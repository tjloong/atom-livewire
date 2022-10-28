@props([
    'fixed' => $attributes->get('fixed', false),
    'sticky' => $attributes->get('sticky', false),
    'scrollBreakpoint' => $attributes->get('scroll-breakpoint', 300),
])

<nav 
    x-cloak
    x-data="{ 
        show: false,
        scrollBreakpoint: @js($scrollBreakpoint),
        init () {
            this.$nextTick(() => this.$dispatch('scroll-unbreak'))
        },
    }"
    x-on:scroll.window="window.pageYOffset >= scrollBreakpoint 
        ? $dispatch('scroll-break') 
        : $dispatch('scroll-unbreak')"
    {{ $attributes->class([
        $fixed ? 'fixed top-0 left-0 right-0 z-40' : 'relative',
        $sticky ? 'stickty top-0 z-10 relative' : null,
        $attributes->get('class', 'transition p-4'),
    ])->except(['fixed', 'sticky', 'scroll']) }}
>
    <div class="max-w-screen-xl mx-auto grid divide-y">
        <div class="grid gap-4 items-center md:flex">
            <div class="flex justify-between items-center">
                @isset($logo)
                    {{ $logo }}
                @else
                    <a href="/" class="max-w-[100px] max-h-[50px] md:max-w-[150px] md:max-h-[75px]">
                        @if ($attributes->get('logo'))
                            <img src="{{ $attributes->get('logo') }}" width="300" height="150" alt="{{ config('app.name') }}" class="w-full h-full object-contain">
                        @else
                            <x-logo class="w-full h-full"/>
                        @endif
                    </a>
                @endisset

                <div x-on:click="show = !show" id="navbar-burger" class="flex cursor-pointer md:hidden">
                    @isset($burger) {{ $burger }}
                    @else <x-icon name="chevron-down" size="20" class="m-auto"/>
                    @endisset
                </div>
            </div>

            <div 
                x-bind:class="show ? 'fixed inset-0 z-40' : 'hidden'" 
                class="grow p-6 md:static md:p-0 md:block"
            >
                <div x-on:click="show = false" class="absolute inset-0 bg-gray-400/50 md:hidden"></div>

                <div class="
                    relative flex flex-col items-center gap-3 bg-white rounded-lg shadow p-4 max-h-[100%] overflow-auto
                    md:static md:bg-transparent md:shadow-none md:p-0 md:max-h-fit md:overflow-visible
                    md:flex-row
                ">
                    <div class="grow">
                        @isset($body)
                            <div {{ $body->attributes->merge([
                                'class' => 'flex flex-col items-center gap-3 md:flex-row',
                                'id' => 'navbar-body',
                            ]) }}>
                                {{ $body }}
                            </div>
                        @endisset
                    </div>

                    <div class="shrink-0 w-full md:w-auto">
                        @auth
                            @isset($auth)
                                {{ $auth }}
                            @else
                                <x-navbar.dropdown.auth/>
                            @endisset
                        @else
                            @isset($notauth)
                                {{ $notauth }}
                            @else
                                <x-navbar.login/>
                            @endisset
                        @endauth
                    </div>
                </div>

                <a x-on:click="show = false" class="absolute top-4 right-2 block w-8 h-8 md:hidden">
                    <div class="w-full h-full rounded-full bg-white shadow flex">
                        <x-icon name="xmark" class="m-auto text-gray-400"/>
                    </div>
                </a>
            </div>
        </div>
    </div>
</nav>

<nav
    x-cloak
    x-data="{ show: false }"
>
    <div class="flex items-center py-2 md:py-0">
        <div class="shrink-0">
            @if (isset($logo))
                {{ $logo }}
            @elseif (isset($head))
                {{ $head }}
            @else
                <a href="/" class="block w-[100px] h-[50px] md:w-[150px] md:h-[75px]">
                    @if ($attributes->get('logo'))
                        <img src="{{ $attributes->get('logo') }}" width="300" height="150" alt="{{ config('app.name') }}" class="w-full h-full object-contain object-left">
                    @else
                        <x-logo class="w-full h-full"/>
                    @endif
                </a>
            @endif
        </div>

        <div class="grow flex items-center justify-end gap-3">
            <div 
                x-bind:class="show ? 'fixed inset-0 z-40' : 'hidden'"
                class="md:grow md:static md:block"
            >
                <div x-on:click="show = false" class="absolute inset-0 bg-black/50 md:hidden"></div>

                <div class="
                    relative p-2 flex flex-col items-center gap-3 overflow-auto
                    md:justify-between md:flex-row md:m-0 md:overflow-visible
                ">
                    @isset($body)
                        <div id="{{ $body->attributes->get('id', 'navbar-body') }}" {{ $body->attributes->class([
                            'w-full flex flex-col items-center p-2 md:grow md:flex-row md:gap-3 md:p-0 md:w-auto',
                            $body->attributes->get('class', 'bg-white shadow rounded-lg md:bg-transparent md:shadow-none'),
                        ])->only('class') }}>
                            {{ $body }}
                        </div>
                    @else
                        <div class="grow"></div>
                    @endisset

                    @isset($end)
                        <div id="{{ $end->attributes->get('id', 'navbar-end')}}" {{ $end->attributes->class([
                            'w-full flex flex-col items-center gap-3 p-2 md:shrink-0 md:p-0 md:w-auto',
                            $end->attributes->get('class', 'bg-white shadow rounded-lg md:bg-transparent md:shadow-none'),
                        ]) }}>
                            {{ $end }}
                        </div>
                    @endisset

                    @isset($auth) {{ $auth }}
                    @else
                        @auth 
                            <x-navbar.auth/>
                        @else
                            <div class="w-full flex flex-col items-center bg-white shadow rounded-lg p-2 md:flex-row md:gap-3 md:bg-transparent md:shadow-none md:w-auto md:shrink-0">
                                @if (has_route('login'))
                                    <x-navbar.item href="/login" label="Login"/>
                                @endif

                                @if (has_route('register'))
                                    <x-navbar.item href="/register?ref=navbar" label="Register" class="md:hidden"/>
                                    <div class="hidden md:block">
                                        <x-button href="/register?ref=navbar" label="Register" color="theme"/>
                                    </div>
                                @endif
                            </div>
                        @endauth
                    @endisset
                </div>
            </div>

            <div x-on:click="show = !show" id="navbar-burger" class="shrink-0 flex cursor-pointer border rounded-lg p-2 md:hidden">
                @isset($menu) {{ $menu }}
                @else <x-icon name="bars" class="m-auto"/>
                @endisset
            </div>
        </div>
    </div>
</nav>

{{-- <nav 
    x-cloak
    x-data="{
        show: false,
        classes: {},
        scrollPos: 0,
        config: {
            fixed: false,
            sticky: false,
            scrollBreakpoint: 300,
            ...@js($attributes->get('config', [])),
        },
        init () {
            this.classes = {
                'fixed top-0 left-0 right-0 z-40': this.config.fixed,
                'sticky top-0 z-10': this.config.sticky,
                'relative': !this.config.fixed && !this.config.sticky,
            }

            if (this.config.scrollHide) this.toggleScroll(false)
        },
        detectScroll () {
            this.scrollPos = window.pageYOffset
            if (this.scrollPos >= this.config.scrollBreakpoint) this.$dispatch('scroll-reveal', this.$el.id)
            else this.$dispatch('scroll-hide', this.$el.id)
        },
        toggleScroll (bool) {
            const revealClassName = this.config.scrollReveal?.nav
            const hideClassName = this.config.scrollHide?.nav
            if (revealClassName) this.classes[revealClassName] = bool
            if (hideClassName) this.classes[hideClassName] = !bool
        },
    }"
    x-on:scroll.window="detectScroll"
    x-on:scroll-reveal="toggleScroll(true)"
    x-on:scroll-hide="toggleScroll(false)"
    id="{{ $attributes->get('id', 'navbar') }}"
    {{ $attributes->merge(['class' => 'relative transition'])->except('config') }}
>
    <div class="flex items-center py-2 md:py-0">
        <div class="shrink-0">
            @if (isset($logo))
                {{ $logo }}
            @elseif (isset($head))
                {{ $head }}
            @else
                <a href="/" class="block w-[100px] h-[50px] md:w-[150px] md:h-[75px]">
                    @if ($attributes->get('logo'))
                        <img src="{{ $attributes->get('logo') }}" width="300" height="150" alt="{{ config('app.name') }}" class="w-full h-full object-contain object-left">
                    @else
                        <x-logo class="w-full h-full"/>
                    @endif
                </a>
            @endif
        </div>

        <div class="grow flex items-center justify-end gap-3">
            <div 
                x-bind:class="show ? 'fixed inset-0 z-40' : 'hidden'"
                class="md:grow md:static md:block"
            >
                <div x-on:click="show = false" class="absolute inset-0 bg-black/50 md:hidden"></div>

                <div class="
                    relative p-2 flex flex-col items-center gap-3 overflow-auto
                    md:justify-between md:flex-row md:m-0 md:overflow-visible
                ">
                    @isset($body)
                        <div id="{{ $body->attributes->get('id', 'navbar-body') }}" {{ $body->attributes->class([
                            'w-full flex flex-col items-center p-2 md:grow md:flex-row md:gap-3 md:p-0 md:w-auto',
                            $body->attributes->get('class', 'bg-white shadow rounded-lg md:bg-transparent md:shadow-none'),
                        ])->only('class') }}>
                            {{ $body }}
                        </div>
                    @else
                        <div class="grow"></div>
                    @endisset

                    @isset($end)
                        <div id="{{ $end->attributes->get('id', 'navbar-end')}}" {{ $end->attributes->class([
                            'w-full flex flex-col items-center gap-3 p-2 md:shrink-0 md:p-0 md:w-auto',
                            $end->attributes->get('class', 'bg-white shadow rounded-lg md:bg-transparent md:shadow-none'),
                        ]) }}>
                            {{ $end }}
                        </div>
                    @endisset

                    @isset($auth) {{ $auth }}
                    @else
                        @auth <x-navbar.auth/>
                        @else
                            <div class="w-full flex flex-col items-center bg-white shadow rounded-lg p-2 md:flex-row md:gap-3 md:bg-transparent md:shadow-none md:w-auto md:shrink-0">
                                @if (Route::has('login'))
                                    <x-navbar.item href="/login" label="Login"/>
                                @endif

                                @if (Route::has('register'))
                                    <x-navbar.item href="/register?ref=navbar" label="Register" class="md:hidden"/>
                                    <div class="hidden md:block">
                                        <x-button href="/register?ref=navbar" label="Register" color="theme"/>
                                    </div>
                                @endif
                            </div>
                        @endauth
                    @endisset
                </div>
            </div>

            <div x-on:click="show = !show" id="navbar-burger" class="shrink-0 flex cursor-pointer border rounded-lg p-2 md:hidden">
                @isset($menu) {{ $menu }}
                @else <x-icon name="bars" class="m-auto"/>
                @endisset
            </div>
        </div>
    </div>
</nav> --}}

<nav 
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
    x-bind:class="classes"
    {{ $attributes->merge(['id' => 'navbar'])->class([
        $attributes->get('class', 'transition p-4'),
    ])->except(['config']) }}
>
    <div class="max-w-screen-xl mx-auto grid divide-y">
        <div class="grid gap-4 items-center md:flex">
            <div class="flex justify-between items-center">
                @isset($logo)
                    {{ $logo }}
                @else
                    <a href="/" class="w-[100px] h-[50px] md:w-[150px] md:h-[75px]">
                        @if ($attributes->get('logo'))
                            <img src="{{ $attributes->get('logo') }}" width="300" height="150" alt="{{ config('app.name') }}" class="w-full h-full object-contain object-left">
                        @else
                            <x-logo class="w-full h-full"/>
                        @endif
                    </a>
                @endisset

                <div x-on:click="show = !show" id="navbar-burger" class="flex px-2 cursor-pointer md:hidden">
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

                    <div class="shrink-0 w-full flex flex-col items-center gap-3 md:w-auto md:flex-row">
                        @isset($end)
                            <div {{ $end->attributes->merge([
                                'class' => 'flex flex-col items-center gap-3 md:flex-row',
                                'id' => 'navbar-end',
                            ]) }}>
                                {{ $end }}
                            </div>
                        @endisset

                        @isset($auth) {{ $auth }}
                        @else
                            @auth <x-navbar.auth/>
                            @else
                                <div class="flex items-center justify-center gap-3">
                                    @if (Route::has('login'))
                                        <x-navbar.item href="/login" label="Login"/>
                                    @endif

                                    @if (Route::has('register'))
                                        <x-button href="/register?ref=navbar" label="Register"/>
                                    @endif
                                </div>                        
                            @endauth
                        @endisset
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

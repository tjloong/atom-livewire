@auth
    {{-- mobile view --}}
    <div class="bg-gray-100 p-4 rounded-md grid md:hidden">
        <div class="flex items-center justify-center gap-2">
            <x-icon name="circle-user" size="18px" class="text-gray-500"/>
            {{ str(auth()->user()->name)->limit(15) }}
        </div>

        @if ($slot->isNotEmpty()) {{ $slot }}
        @else 
            <x-navbar.dropdown.item :href="route('app.settings')" label="Settings"/>
            @can('preference.manage') <x-navbar.dropdown.item :href="route('app.preferences')" label="Preferences"/> @endcan
        @endif

        @if ($canBackToApp)
            <x-navbar.dropdown.item :href="auth()->user()->home()" icon="house" label="Back to App"/>
        @endif

        <x-navbar.dropdown.item :href="route('login', ['logout' => true])" label="Logout"/>

        @isset($foot)
            {{ $foot }}
        @endisset
    </div>

    {{-- desktop view --}}
    <div class="hidden md:block">
        <x-dropdown>
            <x-slot:anchor>
                <div 
                    x-data="{
                        classes: {},
                        init () {
                            if (this.config.scrollHide) this.toggleScroll(false)
                        },
                        toggleScroll (bool) {
                            const revealClassName = this.config.scrollReveal?.item
                            const hideClassName = this.config.scrollHide?.item
                            if (revealClassName) this.classes[revealClassName] = bool
                            if (hideClassName) this.classes[hideClassName] = !bool
                        },
                    }"
                    x-on:scroll-reveal.window="toggleScroll(true)"
                    x-on:scroll-hide.window="toggleScroll(false)"
                    x-bind:class="classes"
                    class="flex items-center justify-center gap-2 px-3 text-center font-medium"
                >
                    @if (
                        $avatar = $attributes->get('avatar')
                            ?? optional(auth()->user()->avatar)->url
                    )
                        <x-thumbnail :url="$avatar" size="24" circle/>
                    @else
                        <x-icon name="circle-user" size="18" class="opacity-60"/> 
                    @endif

                    {{ str(auth()->user()->name)->limit(15) }}
                    <x-icon name="chevron-down" size="12"/>
                </div>
            </x-slot:anchor>
    
            <div class="grid divide-y">
                @if ($slot->isNotEmpty()) {{ $slot }}
                @else 
                    <x-navbar.dropdown.item :href="route('app.settings')" label="Settings"/>
                    @can('preference.manage') <x-navbar.dropdown.item :href="route('app.preferences')" label="Preferences"/> @endcan
                @endif
                    
                @if ($canBackToApp)
                    <x-navbar.dropdown.item :href="auth()->user()->home()" label="Back to App"/>
                @endif
    
                <x-navbar.dropdown.item :href="route('login', ['logout' => true])" label="Logout"/>

                @isset($foot)
                    {{ $foot }}
                @endisset
            </div>
        </x-dropdown>
    </div>
@endauth

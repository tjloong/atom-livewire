@auth
    {{-- mobile view --}}
    <div class="w-full bg-gray-100 p-4 rounded-md grid md:hidden">
        <div class="py-2 px-4 flex items-center justify-center gap-2 rounded-lg bg-slate-200 font-semibold">
            <x-icon name="circle-user" class="text-gray-500"/>
            {{ str(user('name'))->limit(15) }}
        </div>

        {{ $slot }}

        <x-navbar.dropdown.item :href="route('logout')" icon="logout" label="Logout"/>

        @isset($foot)
            {{ $foot }}
        @endisset
    </div>

    {{-- desktop view --}}
    <div class="hidden md:block">
        <x-dropdown>
            <x-slot:anchor>
                <div 
                    {{-- x-data="{
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
                    x-bind:class="classes" --}}
                    class="flex items-center justify-center gap-2 px-3 text-center font-medium"
                >
                    @if ($avatar = $attributes->get('avatar') ?? user('avatar.url'))
                        <x-thumbnail :url="$avatar" size="24" circle/>
                    @else
                        <x-icon name="circle-user lg" class="opacity-60"/> 
                    @endif

                    {{ str(user('name'))->limit(15) }}
                    <x-icon name="chevron-down" size="12"/>
                </div>
            </x-slot:anchor>
    
            <div class="flex flex-col divide-y">
                {{ $slot }}
                    
                <x-navbar.dropdown.item :href="route('logout')" icon="logout" label="Logout"/>
                        
                @isset($foot)
                    {{ $foot }}
                @endisset
            </div>
        </x-dropdown>
    </div>
@endauth

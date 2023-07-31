@auth
    {{-- mobile view --}}
    <div class="w-full bg-gray-100 p-4 rounded-md grid md:hidden">
        <div class="py-2 px-4 flex items-center justify-center gap-2 rounded-lg bg-slate-200 font-semibold">
            <x-icon name="circle-user lg" class="text-gray-500"/>
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
                <div class="flex items-center justify-center gap-2 px-3 text-center font-medium">
                    <div class="shrink-0 flex items-center justify-center">
                        @if ($avatar = $attributes->get('avatar') ?? user('avatar.url'))
                            <x-thumbnail :url="$avatar" size="24" circle/>
                        @else
                            <x-icon name="circle-user lg" class="opacity-60"/> 
                        @endif
                    </div>

                    {{ str(user('name'))->limit(15) }}

                    <div class="shrink-0 flex items-center justify-center">
                        <x-icon name="chevron-down xs"/>
                    </div>
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

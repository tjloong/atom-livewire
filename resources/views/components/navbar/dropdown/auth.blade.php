@auth
    {{-- mobile view --}}
    <div class="bg-gray-100 p-4 rounded-md grid md:hidden">
        <div class="flex items-center justify-center gap-2">
            <x-icon name="circle-user" size="18px" class="text-gray-500"/>
            {{ str(auth()->user()->name)->limit(15) }}
        </div>

        <x-navbar.dropdown.item :href="route('app.account.home')" icon="address-card" label="Account"/>

        {{ $slot }}

        @if ($canBackToApp)
            <x-navbar.dropdown.item :href="app_route()" icon="house" label="Back to App"/>
        @endif

        <x-navbar.dropdown.item :href="route('login', ['logout' => true])" icon="logout" label="Logout"/>
    </div>

    {{-- desktop view --}}
    <div class="hidden md:block">
        <x-navbar.dropdown>
            <x-slot:anchor>
                <div class="flex items-center justify-center gap-2">
                    <x-icon name="circle-user" size="18px" class="text-gray-500"/> {{ str(auth()->user()->name)->limit(15) }}
                </div>
            </x-slot:anchor>
    
            <div class="grid divide-y">
                <x-navbar.dropdown.item :href="route('app.account.home')" icon="address-card" label="My Account"/>
    
                {{ $slot }}
    
                @if ($canBackToApp)
                    <x-navbar.dropdown.item :href="app_route()" icon="house" label="Back to App"/>
                @endif
    
                <x-navbar.dropdown.item :href="route('login', ['logout' => true])" icon="logout" label="Logout"/>
            </div>
        </x-navbar.dropdown>
    </div>
@endauth

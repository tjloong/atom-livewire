@auth
    {{-- mobile view --}}
    <div class="bg-gray-100 p-4 rounded-md grid md:hidden">
        <div class="flex items-center justify-center gap-2">
            <x-icon name="circle-user" size="18px" class="text-gray-500"/>
            {{ str(auth()->user()->name)->limit(15) }}
        </div>

        <x-navbar.dropdown.item :href="route('account')" icon="address-card" label="Account"/>

        @if (auth()->user()->canAccessPortal('billing'))
            <x-navbar.dropdown.item :href="route('billing')" icon="dollar-circle" label="Billing"/>
        @endif

        {{ $slot }}

        @if ($canBackToApp)
            <x-navbar.dropdown.item :href="app_route()" icon="house-user" label="Back to App"/>
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
                <x-navbar.dropdown.item :href="route('account')" icon="address-card" label="My Account"/>
    
                @if (auth()->user()->canAccessPortal('billing'))
                    <x-navbar.dropdown.item :href="route('billing')" icon="dollar-circle" label="Billing"/>
                @endif
    
                {{ $slot }}
    
                @if ($canBackToApp)
                    <x-navbar.dropdown.item :href="app_route()" icon="house-user" label="Back to App"/>
                @endif
    
                <x-navbar.dropdown.item :href="route('login', ['logout' => true])" icon="logout" label="Logout"/>
            </div>
        </x-navbar.dropdown>
    </div>
@endauth

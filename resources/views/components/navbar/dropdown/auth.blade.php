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
            <x-navbar.dropdown.item :href="route('app.preferences')" label="Preferences"/>
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
        <x-navbar.dropdown>
            <x-slot:anchor>
                <div class="flex items-center justify-center gap-2">
                    <x-icon name="circle-user" size="18px" class="text-gray-500"/> {{ str(auth()->user()->name)->limit(15) }}
                </div>
            </x-slot:anchor>
    
            <div class="grid divide-y">
                @if ($slot->isNotEmpty()) {{ $slot }}
                @else 
                    <x-navbar.dropdown.item :href="route('app.settings')" label="Settings"/>
                    <x-navbar.dropdown.item :href="route('app.preferences')" label="Preferences"/>
                @endif
                    
                @if ($canBackToApp)
                    <x-navbar.dropdown.item :href="auth()->user()->home()" label="Back to App"/>
                @endif
    
                <x-navbar.dropdown.item :href="route('login', ['logout' => true])" label="Logout"/>

                @isset($foot)
                    {{ $foot }}
                @endisset
            </div>
        </x-navbar.dropdown>
    </div>
@endauth

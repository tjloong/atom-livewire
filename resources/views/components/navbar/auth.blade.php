@php
    $avatar = $attributes->get('avatar') ?? user('avatar.url');
@endphp

@auth
    <x-dropdown placement="bottom-end">
        <x-slot:anchor>
            <div class="flex items-center gap-2 px-3 font-medium cursor-pointer max-w-[150px] w-max md:max-w-[250px]">
                <div class="shrink-0 flex items-center justify-center">
                    @if ($avatar) <x-image :src="$avatar" avatar size="30x30"/>
                    @else <x-icon name="circle-user" class="text-lg md:text-xl opacity-60"/> 
                    @endif
                </div>

                <div class="grow truncate text-sm md:text-base">
                    {!! user('name') !!}
                </div>

                <div class="shrink-0 flex items-center justify-center text-xs">
                    <x-icon name="chevron-down"/>
                </div> 
            </div>
        </x-slot:anchor>

        <div class="flex flex-col divide-y">
            {{ $slot }}
            <x-dropdown.item :href="route('logout')" icon="logout" label="common.label.logout"/>
        </div>
    </x-dropdown>
@endauth

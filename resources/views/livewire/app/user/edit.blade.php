<x-drawer submit wire:close="$emit('closeUser')">
@if ($user)
    @if ($user->exists)
        <x-slot:heading
            title="{!! $user->name !!}"
            subtitle="{{ $user->email }}">
        </x-slot:heading>

        <x-slot:buttons>
            @if ($user->trashed() && !$user->isAuth())
                <x-button action="restore"/>
                <x-button action="delete" no-label invert/>
            @else
                <x-button action="submit"/>
                @if (!$user->isAuth()) <x-button action="trash" no-label invert/> @endif
            @endif
        </x-slot:buttons>
    @else
        <x-slot:heading title="Create User"></x-slot:heading>
    @endif

    <x-inputs>
        <x-input wire:model.defer="user.name"/>
        <x-input type="email" wire:model.defer="user.email" label="Login Email"/>
        <x-input type="password" wire:model.defer="inputs.password"/>
        <x-checkbox wire:model="inputs.is_blocked" label="Blocked"/>
        @tier('root') <x-checkbox wire:model="inputs.is_root" label="Root"/> @endtier
    </x-inputs>
@endif
</x-drawer>

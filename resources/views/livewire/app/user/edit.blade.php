<x-drawer submit wire:close="$emit('closeUser')">
@if ($user)
    @if ($user->exists) <x-slot:heading label="app.label.edit-user"></x-slot:heading>
    @else <x-slot:heading title="app.label.create-user"></x-slot:heading>
    @endif

    <x-slot:buttons>
        @if ($user->exists && $user->trashed() && !$user->isAuth())
            <x-button action="restore"/>
            <x-button action="delete" no-label invert/>
        @else
            <x-button action="submit"/>

            @if ($user->exists && !$user->isAuth())
                <x-button action="trash" no-label invert/>
            @endif
        @endif
    </x-slot:buttons>

    <x-inputs>
        <x-input wire:model.defer="user.name"/>
        <x-input type="email" wire:model.defer="user.email" label="Login Email"/>
        <x-input type="password" wire:model.defer="inputs.password"/>
        <x-checkbox wire:model="inputs.is_blocked" label="Blocked"/>
        @tier('root') <x-checkbox wire:model="inputs.is_root" label="Root"/> @endtier
    </x-inputs>
@endif
</x-drawer>

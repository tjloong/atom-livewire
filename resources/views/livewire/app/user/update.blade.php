<x-form.drawer>
@if ($user)
    @if ($user->exists)
        <x-slot:buttons>
            @if ($user->trashed() && !$user->isAuth())
                <x-button action="restore"/>
                <x-button action="delete" no-label invert/>
            @else
                <x-button action="submit"/>
                @if (!$user->isAuth()) <x-button action="trash" no-label invert/> @endif
            @endif
        </x-slot:buttons>

        <x-slot:heading
            title="{!! $user->name !!}"
            subtitle="{{ $user->email }}">
        </x-slot:heading>
    @else
        <x-slot:heading title="Create User"></x-slot:heading>
    @endif

    <x-group cols="2">
        <div class="md:col-span-2">
            <x-input wire:model.defer="user.name"/>
        </div>

        <x-input type="email" wire:model.defer="user.email" label="Login Email"/>
        <x-input type="password" wire:model.defer="inputs.password"/>

        <div class="md:col-span-2 flex flex-col gap-2">
            <x-checkbox wire:model="inputs.is_blocked" label="Blocked"/>
            @tier('root') <x-checkbox wire:model="inputs.is_root" label="Root"/> @endtier
        </div>
    </x-group>

    <x-group heading="app.label.permission">
        <x-form.permission wire:model.defer="inputs.permissions"/>
    </x-group>
@endif
</x-form.drawer>

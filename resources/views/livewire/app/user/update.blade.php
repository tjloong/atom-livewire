<x-form.drawer>
@if ($user)
    @if ($user->exists)
        <x-slot:buttons
            :trash="!$user->trashed()"
            :restore="$user->trashed()"
            :delete="$user->trashed()">
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
            <x-form.text wire:model.defer="user.name"/>
        </div>

        <x-form.email wire:model.defer="user.email" label="Login Email"/>
        <x-form.password wire:model.defer="inputs.password"/>

        <div class="md:col-span-2 flex flex-col gap-2">
            <x-form.checkbox wire:model="inputs.is_blocked" label="Blocked"/>
            @tier('root') <x-form.checkbox wire:model="inputs.is_root" label="Root"/> @endtier
        </div>
    </x-group>

    <x-group heading="app.label.permission">
        <x-form.permission wire:model.defer="inputs.permissions"/>
    </x-group>
@endif
</x-form.drawer>

<x-form.drawer>
@if ($user)
    @if ($user->exists)
        <x-slot:heading title="{{ $user->name }}" subtitle="{{ $user->email }}"></x-slot:heading>

        <x-slot:buttons
            :trash="!$user->trashed()"
            :restore="$user->trashed()"
            :delete="$user->trashed()"></x-slot:buttons>
    @else
        <x-slot:heading title="Create User"></x-slot:heading>
    @endif

    <x-form.group cols="2">
        <div class="col-span-2">
            <x-form.text wire:model.defer="user.name"/>
        </div>

        @if ($this->isLoginMethod('username'))
            <x-form.text wire:model.defer="user.username"/>
        @endif

        @if ($this->isLoginMethod(['email', 'email-verified']))
            <x-form.email wire:model.defer="user.email" label="Login Email"/>
        @endif

        <x-form.password wire:model.defer="inputs.password"/>

        <div class="col-span-2">
            <x-form.checkbox wire:model="inputs.is_blocked" label="Blocked"/>

            @tier('root')
                <x-form.checkbox wire:model="inputs.is_root" label="Root"/>
            @endtier
        </div>
    </x-form.group>

    <x-form.group heading="app.label.permission">
        <x-form.permission wire:model.defer="inputs.permissions"/>
    </x-form.group>
@endif
</x-form.drawer>

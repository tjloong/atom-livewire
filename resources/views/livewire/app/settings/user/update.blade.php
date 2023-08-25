<x-form.drawer id="user-update" wire:close="$emit('userSaved')">
@if ($user)
    @if ($user->exists)
        <x-slot:heading title="{{ $user->name }}" subtitle="{{ $user->email }}"></x-slot:heading>

        <x-slot:buttons>
        @if ($user->trashed())
            <x-button.restore size="sm"/>
            <x-button.delete size="sm"/>
        @else
            <x-button.submit size="sm"/>
            <x-button.trash size="sm"/>
        @endif
        </x-slot:buttons>
    @else
        <x-slot:heading title="Create User"></x-slot:heading>
    @endif

    <div class="flex flex-col gap-4">
        <x-form.group>
            <x-form.text wire:model.defer="user.name"/>
    
            @if ($this->isLoginMethod('username'))
                <x-form.text wire:model.defer="user.username"/>
            @endif
    
            @if ($this->isLoginMethod('email'))
                <x-form.email wire:model.defer="user.email" label="Login Email"/>
            @endif
    
            <x-form.password wire:model.defer="inputs.password"/>
    
            @if (!$user->isTier('root'))
                <x-form.select.role wire:model="user.role_id"/>
                <x-form.select.team wire:model="inputs.teams" multiple/>
            @endif
    
            <div>
                <x-form.checkbox wire:model="inputs.is_blocked" label="Blocked"/>
    
                @tier('root')
                    <x-form.checkbox wire:model="inputs.is_root" label="Root"/>
                @endtier
            </div>
        </x-form.group>

        @if ($user->exists)
            <x-box.trace :data="$user->fresh()"/>
        @endif
    </div>
@endif
</x-form.drawer>

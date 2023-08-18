<x-form id="user-update" drawer
    :title="optional($user)->exists ? $user->name : 'Create User'"
    :subtitle="optional($user)->exists ? $user->email : null"
    wire:close="$emit('userSaved')">
@if ($user)
    @if ($user->exists)
        <x-slot:buttons>
        @if ($user->trashed())
            <x-button.restore size="sm"/>
            <x-button.delete size="sm"/>
        @else
            <x-button.submit size="sm"/>
            <x-button.trash size="sm"/>
        @endif
        </x-slot:buttons>
    @endif

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
        <x-form.group label="Additional Information" cols="2">
            <x-form.field label="Last Login" :value="format_date($user->login_at, 'datetime') ?? '--'"/>
            <x-form.field label="Last Active" :value="format_date($user->last_active_at, 'datetime') ?? '--'"/>
            @if ($user->blocked_at)
                <x-form.field label="Blocked At" :value="format_date($user->blocked_at, 'datetime')"/>
            @endif
        </x-form.group>
    @endif
@endif
</x-form>

<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$user->name" :status="$user->isTenantOwner() ? ['yellow' => 'owner'] : null" back="auto">
        <div class="flex items-center gap-2">
            @if (tenant())
                <x-button.confirm label="Remove User" color="red" inverted icon="close"
                    title="Remove User"
                    message="Are you sure to REMOVE this user?"
                    callback="remove"
                />
            @else
                @livewire(atom_lw('app.user.btn-block'), compact('user'), key('block'))
                @livewire(atom_lw('app.user.btn-delete'), compact('user'), key('delete'))
            @endif
        </div>
    </x-page-header>

    <div class="flex flex-col gap-6">
        @if ($user->status === 'inactive')
            <x-alert type="warning">
                {{ __('User account is pending for activation.') }}<br>
                <x-link wire:click="resend" label="Resend account activation email" class="text-sm"/>
            </x-alert>
        @endif

        <x-box>
            <div class="flex flex-col divide-y">
                @foreach (array_merge(
                    [
                        'Name' => $user->name,
                        'Email' => $user->email,
                        'Join' => format_date($user->created_at, 'datetime') ?? '--'
                    ],

                    $user->blocked_at ? [
                        'Blocked' => format_date($user->blocked_at, 'datetime') ?? '--',
                    ] : [],

                    $user->deleted_at ? [
                        'Trashed' => format_date($user->deleted_at, 'datetime') ?? '--',
                    ] : [],

                    ['Last Active' => format_date($user->last_active_at, 'datetime') ?? '--'],
                ) as $key => $val)
                    <x-field :label="$key" 
                        :value="is_string($val) ? $val : data_get($val, 'value')"
                        :small="data_get($val, 'small')"
                    />
                @endforeach
            </div>
        </x-box>

        @livewire(atom_lw('app.user.role'), compact('user'), key('role'))
        @livewire(atom_lw('app.user.team'), compact('user'), key('team'))
        @livewire(atom_lw('app.user.permission'), compact('user'), key('permission'))
        @livewire(atom_lw('app.user.visibility'), compact('user'), key('visibility'))
    </div>
</div>

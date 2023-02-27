<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="$user->name" back>
        <div class="flex items-center gap-2">
            @can('user.'.$user->tier.'.block')
                @livewire(lw('app.user.update.block'), compact('user'), key('block'))
            @endcan

            @can('user.'.$user->tier.'.delete')
                @livewire(lw('app.user.update.delete'), compact('user'), key('delete'))
            @endcan
        </div>
    </x-page-header>

    <div class="flex flex-col gap-6">
        @livewire(lw('app.user.form'), compact('user'), key('form'))

        <x-box header="Additional Information">
            <div class="flex flex-col divide-y">
                <x-box.row label="Status">
                    <x-badge>{{ $user->status }}</x-badge>
                </x-box.row>
    
                <x-box.row label="Created Date">
                    {{ format_date($user->created_at) }}
                </x-box.row>
    
                @if ($user->blocked_at)
                    <x-box.row label="Blocked Date">
                        {{ format_date($user->blocked_at) }}
                    </x-box.row>
    
                    <x-box.row label="Blocked By">
                        {{ $user->blockedBy->name }}
                    </x-box.row>
                @endif
    
                @if ($user->trashed())
                    <x-box.row label="Trashed Date">
                        {{ format_date($user->deleted_at) }}
                    </x-box.row>
    
                    <x-box.row label="Trashed By">
                        {{ $user->deletedBy->name }}
                    </x-box.row>
                @endif

                @if ($user->status === 'inactive')
                    <div class="p-4">
                        <x-alert type="warning">
                            {{ __('User account is pending for activation.') }}<br>
                            <a wire:click="resendActivationEmail" class="text-sm">
                                {{ __('Resend account activation email to user') }}
                            </a>
                        </x-alert>
                    </div>
                @endif
            </div>
        </x-box>

        @module('permissions')
            @livewire(lw('app.permission.form'), compact('user'), key('permission'))
        @endmodule
    </div>
</div>
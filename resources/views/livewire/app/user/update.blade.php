<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$user->name" back>
        <div class="flex items-center gap-2">
            @livewire(lw('app.user.btn-block'), compact('user'), key('block'))
            @livewire(lw('app.user.btn-delete'), compact('user'), key('delete'))
        </div>
    </x-page-header>

    <div class="flex flex-col gap-4 md:flex-row">
        <div class="md:w-2/3 flex flex-col gap-4">
            @livewire(lw('app.user.form'), compact('user'), key('form'))

            @module('permissions')
                @livewire(lw('app.permission.form'), compact('user'), key('permission'))
            @endmodule
        </div>

        <div class="md:w-1/3">
            <x-box header="Additional Information">
                <div class="flex flex-col divide-y">
                    <x-field label="Status" :badge="$user->status"/>
                    <x-field label="Created Date" :value="format_date($user->created_at)"/>
        
                    @if ($user->blocked_at)
                        <x-field label="Blocked Date" :value="format_date($user->blocked_at)"/>
                        <x-field label="Blocked By" :value="$user->blockedBy->name"/>
                    @endif
        
                    @if ($user->trashed())
                        <x-field label="Trashed Date" :value="format_date($user->deleted_at)"/>
                        <x-field label="Trashed By" :value="$user->deletedBy->name"/>
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
        </div>
    </div>
</div>
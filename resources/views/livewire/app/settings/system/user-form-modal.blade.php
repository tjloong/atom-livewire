<x-modal uid="user-form-modal" icon="user"
    :header="optional($user)->exists ? 'Update User' : 'Create User'"
    class="max-w-screen-sm"
>
    @if ($user)
        <div class="flex flex-col gap-6">
            @if (auth()->user()->isAccountType('root') && $user->account->type !== 'root')
                <x-box>
                    <div class="p-4 grid gap-4">
                        <x-form.field label="Account Name">
                            <div class="text-lg font-bold">{{ $user->account->name }}</div>
                            <div class="font-medium text-gray-500">
                                @if ($email = $user->account->email) {{ $email }}<br> @endif
                                @if ($phone = $user->account->phone) {{ $phone }}<br> @endif
                            </div>
                        </x-form.field>
    
                        <x-form.field label="Account Type">
                            {{ ucfirst($user->account->type) }}
                        </x-form.field>    
                    </div>
                </x-box>
            @endif
    
            <x-form.text 
                label="Login Name"
                wire:model.defer="user.name" 
                :error="$errors->first('user.name')" 
                required
            />
    
            <x-form.email
                label="Login Email"
                wire:model.defer="user.email"
                :error="$errors->first('user.email')"
                :caption="$user->exists ? 'User will need to verify the email again if you change this.' : ''"
                required
            />
    
            @if (!$user->isAccountType('root') && (
                config('atom.app.user.data_visibility')
                || enabled_module('roles')
                || enabled_module('teams')
            ))
                @if (config('atom.app.user.data_visibility'))
                    <x-form.checkbox-select label="Data Visibility"
                        wire:model="user.visibility"
                        :options="data_get($this->options, 'visibilities')"
                    />
                @endif
    
                @module('roles')
                    <x-form.select 
                        label="Role"
                        wire:model="user.role_id" 
                        :options="data_get($this->options, 'roles')"
                    />
                @endmodule
    
                @module('teams')
                    <x-form.select 
                        label="Teams"
                        wire:model="teams"
                        :options="data_get($this->options, 'teams')"
                        multiple
                    />
                @endmodule
            @endif
                        
            @if ($user->exists)
                <x-form.field label="Status">
                    <x-badge>{{ $user->status }}</x-badge>
                </x-form.field>

                <x-form.field label="Created Date">
                    {{ format_date($user->created_at) }}
                </x-form.field>
    
                @if ($user->status === 'inactive')
                    <x-alert type="warning">
                        {{ __('User account is pending for activation.') }}<br>
                        <a wire:click="resendActivationEmail" class="text-sm">
                            {{ __('Resend account activation email to user') }}
                        </a>
                    </x-alert>
                @endif
            @endif
        </div>
    @endif

    <x-slot:foot>
        <x-button.submit type="button" wire:click="submit"/>
    </x-slot:foot>
</x-modal>

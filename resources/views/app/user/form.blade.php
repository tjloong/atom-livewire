<form wire:submit.prevent="submit">
    <x-box>
        <div class="grid divide-y">
            <div class="p-5">
                <x-input.text wire:model.defer="user.name" :error="$errors->first('user.name')" required>
                    {{ __('Login Name') }}
                </x-input.text>
    
                <x-input.email
                    wire:model.defer="user.email"
                    :error="$errors->first('user.email')"
                    required
                    :caption="$user->exists ? 'User will need to verify the email again if you change this.' : ''"
                >
                    {{ __('Login Email') }}
                </x-input.email>

                @if ($user->exists)
                    <x-input.field>
                        <x-slot:label>{{ __('Status') }}</x-slot:label>
                        <x-badge>{{ $user->status }}</x-badge>
                    </x-input.field>
                @endif

                @root
                    @if ($user->account)
                        <x-input.field>
                            <x-slot:label>{{ __('Account Type') }}</x-slot:label>
                            {{ str($user->account->type)->headline() }}
                        </x-input.field>
                    @endif
                @endroot
    
                @if ($user->exists && $user->status === 'inactive')
                    <x-alert icon="info-circle">
                        {{ __('User account is pending for activation.') }}
                    </x-alert>
                @endif
            </div>

            @if (!$user->isAccountType('root') && (
                config('atom.app.user.data_visibility')
                || enabled_module('roles')
                || enabled_module('teams')
            ))
                <div class="p-5">
                    @if (config('atom.app.user.data_visibility'))
                        <x-input.field>
                            <x-slot:label>{{ __('Data Visiblity') }}</x-slot:label>
                            <div class="flex flex-col gap-2">
                                @foreach ($visibilities as $opt)
                                    <x-input.radio name="visiblity" wire:model.defer="user.visibility" :value="$opt['value']" :checked="$user->visibility === $opt['value']">
                                        <div>
                                            <div class="font-medium">{{ str()->headline($opt['value']) }}</div>
                                            <div class="text-sm text-gray-500">{{ $opt['caption'] }}</div>
                                        </div>
                                    </x-input.radio>
                                @endforeach
                            </div>
                        </x-input.field>
                    @endif

                    @module('roles')
                        <x-input.select wire:model="user.role_id" :options="$roles">
                            {{ __('Role') }}
                        </x-input.select>
                    @endmodule

                    @module('teams')
                        <x-input.tags wire:model.defer="selectedTeams" :options="$teams">
                            {{ __('Teams') }}
                        </x-input.tags>
                    @endmodule
                </div>
            @endif
                    
            @if ($user->status === 'inactive' || !$user->exists)
                <div class="p-5">
                    <x-input.checkbox wire:model="sendAccountActivationEmail">
                        @if ($user->exists)
                            {{ __('Resend account activation email to user') }}
                        @else
                            {{ __('Send account activation email to user') }}
                        @endif
                    </x-input.checkbox>
                </div>
            @endif
        </div>

        <x-slot name="buttons">
            <x-button type="submit" icon="check" color="green">
                {{ __('Save User') }}
            </x-button>
        </x-slot>
    </x-box>
</form>

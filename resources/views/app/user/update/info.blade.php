<x-form header="User Information">
    @if (auth()->user()->isAccountType('root') && $user->account->type !== 'root')
        <x-box>
            <div class="p-5">
                <x-form.field label="Account Name">
                    <div class="text-lg font-bold">{{ $user->account->name }}</div>
                    <div class="font-medium text-gray-500">
                        @if ($email = $user->account->email) {{ $email }}<br> @endif
                        @if ($phone = $user->account->phone) {{ $phone }}<br> @endif
                    </div>
                </x-form.field>

                <x-form.field label="Account Type">
                    {{ str($user->account->type)->headline() }}
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
            <x-form.field label="Data Visiblity">
                <div class="flex flex-col gap-2">
                    @foreach (data_get($this->options, 'visibilities') as $opt)
                        <x-form.radio 
                            name="visiblity" 
                            wire:model.defer="user.visibility" 
                            :value="data_get($opt, 'value')" 
                            :checked="$user->visibility === data_get($opt, 'value')"
                        >
                            <div>
                                <div class="font-medium">
                                    {{ str()->headline(data_get($opt, 'value')) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ data_get($opt, 'label') }}
                                </div>
                            </div>
                        </x-form.radio>
                    @endforeach
                </div>
            </x-form.field>
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
                wire:model="selectedTeams"
                :options="data_get($this->options, 'teams')"
                multiple
            />
        @endmodule
    @endif
                    
    @if ($user->exists)
        <x-form.field label="Status">
            <x-badge>{{ $user->status }}</x-badge>
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

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>

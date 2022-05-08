<x-form>
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

    @if ($user->exists)
        <x-form.field label="Status">
            <x-badge>{{ $user->status }}</x-badge>
        </x-form.field>
    @endif

    @root
        @if ($user->account)
            <x-form.field label="Account Type">
                {{ str($user->account->type)->headline() }}
            </x-form.field>
        @endif
    @endroot

    @if ($user->exists && $user->status === 'inactive')
        <x-alert icon="info-circle">
            {{ __('User account is pending for activation.') }}
        </x-alert>
    @endif

    @if (!$user->isAccountType('root') && (
        config('atom.app.user.data_visibility')
        || enabled_module('roles')
        || enabled_module('teams')
    ))
        @if (config('atom.app.user.data_visibility'))
            <x-form.field label="Data Visiblity">
                <div class="flex flex-col gap-2">
                    @foreach ($visibilities as $opt)
                        <x-form.radio 
                            name="visiblity" 
                            wire:model.defer="user.visibility" 
                            :value="$opt['value']" :checked="$user->visibility === $opt['value']"
                        >
                            <div>
                                <div class="font-medium">{{ str()->headline($opt['value']) }}</div>
                                <div class="text-sm text-gray-500">{{ $opt['caption'] }}</div>
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
                :options="$roles"
            />
        @endmodule

        @module('teams')
            <x-form.tags 
                label="Teams"
                wire:model.defer="selectedTeams" 
                :options="$teams"
            />
        @endmodule
    @endif
                    
    @if ($user->status === 'inactive' || !$user->exists)
        <x-form.checkbox 
            :label="$user->exists 
                ? 'Resend account activation email to user'
                : 'Send account activation email to user'"
            wire:model="sendAccountActivationEmail"
        />
    @endif

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>

<x-form>
    <div class="-m-6 flex flex-col divide-y">
        <div class="p-6 flex flex-col gap-6">
            <div class="grid gap-6 md:grid-cols-2">
                <x-form.text label="Login Name"
                    wire:model.defer="user.name" 
                    :error="$errors->first('user.name')" 
                    required
                />
            
                <x-form.email label="Login Email"
                    wire:model.defer="user.email"
                    :error="$errors->first('user.email')"
                    :caption="$user->exists && config('atom.auth.verify') 
                        ? 'User will need to verify the email again if you change this.' 
                        : null"
                    required
                />

                @if ($roles = data_get($this->options, 'roles'))
                    <x-form.select label="Role"
                        wire:model="user.role_id" 
                        :options="$roles"
                    />
                @endif
            
                @if ($teams = data_get($this->options, 'teams'))
                    <x-form.select label="Teams"
                        wire:model="teams"
                        :options="$teams"
                        multiple
                    />
                @endif
            </div>

            @if (!$user->is_root && config('atom.app.user.data_visibility'))
                <x-form.checkbox-select label="Data Visibility"
                    wire:model="user.visibility"
                    :options="data_get($this->options, 'visibilities')"
                />
            @endif

            @tier('root')
                <x-form.checkbox label="With root privilege" wire:model="user.is_root"/>
            @endtier
        </div>

        @if ($user->exists)
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
        @endif
    
        @if ($user->exists && $user->status === 'inactive')
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

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>

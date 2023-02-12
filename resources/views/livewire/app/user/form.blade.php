<x-form>
    <div class="-m-6">
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

            @if ($user->enableDataVisibility)
                <x-form.checkbox-select label="Data Visibility"
                    wire:model="user.visibility"
                    :options="data_get($this->options, 'visibilities')"
                />
            @endif

            @tier('root')
                @if ($user->enableRootEdit)
                    <x-form.checkbox label="With root privilege" wire:model="user.is_root"/>
                @elseif ($user->is_root)
                    <x-alert>
                        {{ __('This user has root privilege.') }}
                    </x-alert>
                @endif
            @endtier
        </div>
    </div>

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>

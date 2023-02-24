<x-form>
    <div class="-m-6">
        <div class="p-6 flex flex-col gap-6">
            <div class="grid gap-6 md:grid-cols-2">
                @if(data_get($this->can, 'name'))
                    <x-form.text label="Login Name"
                        wire:model.defer="user.name" 
                        :error="$errors->first('user.name')" 
                        required
                    />
                @else
                    <x-form.field label="Login Name">{{ $user->name }}</x-form.field>
                @endif
                
                @if (data_get($this->can, 'email'))
                    <x-form.email label="Login Email"
                        wire:model.defer="user.email"
                        :error="$errors->first('user.email')"
                        :caption="$user->exists && config('atom.auth.verify') 
                            ? 'User will need to verify the email again if you change this.' 
                            : null"
                        required
                    />
                @else
                    <x-form.field label="Login Email">{{ $user->email }}</x-form.field>
                @endif

                @module('roles')
                    @if (data_get($this->can, 'role'))
                        <x-form.select label="Role"
                            wire:model="user.role_id" 
                            :options="data_get($this->options, 'roles')"
                        />
                    @else
                        <x-form.field label="Role">{{ $user->role->name ?? '--' }}</x-form.field>
                    @endif
                @endmodule
            
                @module('teams')
                    @if (data_get($this->can, 'team'))
                        <x-form.select label="Teams"
                            wire:model="teams"
                            :options="data_get($this->options, 'teams')"
                            multiple
                        />
                    @else
                        <x-form.field label="Teams">{{ $user->teams->pluck('name')->join(', ') }}</x-form.field>
                    @endif
                @endmodule
            </div>

            @if (data_get($this->can, 'visibility'))
                <x-form.checkbox-select label="Data Visibility"
                    wire:model="user.visibility"
                    :options="data_get($this->options, 'visibilities')"
                />
            @endif

            @if(data_get($this->can, 'root'))
                @if ($user->isTier('root')) <x-alert>{{ __('This user has root privilege.') }}</x-alert>
                @else <x-form.checkbox label="With root privilege" wire:model="user.is_root"/>
                @endif
            @endif
        </div>
    </div>

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>

<form wire:submit.prevent="submit">
    <x-box>
        <div class="grid divide-y">
            <div class="p-5">
                <x-input.text wire:model.defer="form.name" :error="$errors->first('form.name')" required>
                    Name
                </x-input.text>
    
                <x-input.email
                    wire:model.defer="form.email"
                    :error="$errors->first('form.email')"
                    required
                    :caption="$isSelf ? 'You will need to verify your email again if you change this.' : ''"
                >
                    Login Email
                </x-input.email>
    
                @if ($isSelf)
                    <x-input.password wire:model.defer="form.password" error="{{ $errors->first('form.password') }}">
                        Login Password
                    </x-input.password>
                
                    @module('roles')
                        <x-input.field>
                            <x-slot name="label">My Role</x-slot>
                            {{ $user->role->name ?? '--' }}
                        </x-input.field>
                    @endmodule

                    @module('teams')
                        <x-input.field>
                            <x-slot name="label">My Teams</x-slot>
                            <div class="flex flex-wrap items-center gap-2">
                                @forelse ($user->teams as $team)
                                    <div class="bg-gray-100 rounded-md py-1 px-2 text-sm uppercase">
                                        {{ $team->name }}
                                    </div>
                                @empty
                                    --
                                @endforelse
                            </div>
                        </x-input.field>
                    @endmodule
                @else
                    <div class="grid gap-4">
                        <div class="grid gap-2">
                            @if ($user->exists && $user->is_root)
                                <x-input.checkbox wire:model="form.is_root" disabled>
                                    This is a root user
                                </x-input.checkbox>
                            @elseif (auth()->user()->is_root && !$user->exists)
                                <x-input.checkbox wire:model="form.is_root">
                                    This is a root user
                                </x-input.checkbox>
                            @endif

                            <x-input.checkbox wire:model="form.is_active">
                                This user is active
                            </x-input.checkbox>
                        </div>

                        @if ($user->exists && $user->is_pending)
                            <div class="flex items-center gap-1">
                                <x-icon name="info-circle" class="text-gray-400" size="20px"/>
                                <div class="font-medium text-gray-500">User account is pending for activation.</div>
                            </div>
                        @endif
                    </div>
                @endif                    
            </div>

            @if (!$isSelf && $form['is_active'])
                <div class="p-5">
                    @if (!$form['is_root'])
                        @if (config('atom.user.data_visibility'))
                            <x-input.field>
                                <x-slot name="label">Data Visiblity</x-slot>
                                <div class="flex flex-col gap-2">
                                    @foreach ($visibilities as $opt)
                                        <x-input.radio name="visiblity" wire:model.defer="form.visibility" :value="$opt['value']" :checked="$form['visibility'] === $opt['value']">
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
                            <x-input.select wire:model="form.role_id" :options="$roles">
                                Role
                            </x-input.select>
                        @endmodule

                        @module('teams')
                            <x-input.tags wire:model.defer="selectedTeams" :options="$teams">
                                Teams
                            </x-input.tags>
                        @endmodule
                    @endif

                    @if ($user->status === 'pending' || !$user->exists)
                        <x-input.checkbox wire:model="sendAccountActivationEmail">
                            @if ($user->status === 'pending')
                                Resend account activation email to user
                            @else
                                Send account activation email to user
                            @endif
                        </x-input.checkbox>
                    @endif
                </div>
            @endif
        </div>

        <x-slot name="buttons">
            <x-button type="submit" icon="check" color="green">
                Save User
            </x-button>
        </x-slot>
    </x-box>
</form>

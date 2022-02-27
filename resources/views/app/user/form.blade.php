<form wire:submit.prevent="submit">
    <x-box>
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
            @endif

            @module('roles')
                @if ($isSelf)
                    <x-input.field>
                        <x-slot name="label">My Role</x-slot>
                        {{ $user->role->name ?? '--' }}
                    </x-input.field>
                @else
                    <x-input.select wire:model="form.role_id" :options="$roles">
                        Role
                    </x-input.select>
                @endif
            @endmodule

            @if (!$isSelf && (
                (enabled_module('roles') && !in_array(optional($role)->slug, ['admin', 'administrator']))
                || !enabled_module('roles')
            ))
                <x-input.field>
                    <x-slot name="label">Data Visiblity</x-slot>
                    <div class="flex flex-col gap-2">
                        @foreach ($visibilities as $opt)
                            <x-input.radio name="visiblity" wire:model.defer="form.visibility" :value="$opt['value']" :checked="$form['visibility'] === $opt['value']">
                                <div>
                                    <div class="font-medium">{{ str()->headline($opt['value']) }}</div>
                                    <div class="text-xs text-gray-500">{{ $opt['caption'] }}</div>
                                </div>
                            </x-input.radio>
                        @endforeach
                    </div>
                </x-input.field>
            @endif

            @module('teams')
                @if ($isSelf)
                    <x-input.field>
                        <x-slot name="label">Teams</x-slot>
                        <div class="flex flex-wrap items-center gap-2">
                            @forelse ($user->teams as $team)
                                <div class="bg-gray-100 rounded-md py-1 px-2 text-xs uppercase">
                                    {{ $team->name }}
                                </div>
                            @empty
                                --
                            @endforelse
                        </div>
                    </x-input.field>
                @else
                    <x-input.tags wire:model.defer="selectedTeams" :options="$teams">
                        Teams
                    </x-input.tags>
                @endif
            @endmodule

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

        <x-slot name="buttons">
            <x-button type="submit" icon="check" color="green">
                Save User
            </x-button>
        </x-slot>
    </x-box>
</form>

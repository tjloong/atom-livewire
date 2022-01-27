<form wire:submit.prevent="save">
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

                <x-input.field>
                    <x-slot name="label">My Role</x-slot>
                    {{ $user->role->name }}
                </x-input.field>
            @else
                <x-input.select
                    wire:model.defer="form.role_id"
                    :options="$roles"
                    :error="$errors->first('form.role_id')"
                    required
                >
                    Role
                </x-input.select>
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

        <x-slot name="buttons">
            <x-button type="submit" icon="check" color="green">
                Save User
            </x-button>
        </x-slot>
    </x-box>
</form>

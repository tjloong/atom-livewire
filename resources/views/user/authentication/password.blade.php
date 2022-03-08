<form wire:submit.prevent="submit">
    <x-box>
        <x-slot name="header">Change Password</x-slot>

        <div class="p-5">
            <x-input.password wire:model.defer="password.current" :error="$errors->first('password.current')" required>
                Current Password
            </x-input.password>

            <div class="grid gap-4 md:grid-cols-2">
                <x-input.password wire:model.defer="password.new" :error="$errors->first('password.new')" required>
                    New Password
                </x-input.password>

                <x-input.password wire:model.defer="password.new_confirmation" :error="$errors->first('password.new_confirmation')" required>
                    Confirm New Password
                </x-input.password>
            </div>
        </div>

        <x-slot name="buttons">
            <x-button icon="check" color="green" type="submit">
                Change Password
            </x-button>
        </x-slot>
    </x-box>
</form>

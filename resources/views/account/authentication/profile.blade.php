<form wire:submit.prevent="submit">
    <x-box>
        <x-slot name="header">Change Profile Information</x-slot>

        <div class="p-5">
            <x-input.text wire:model.defer="user.name" :error="$errors->first('user.name')" required>
                Login Name
            </x-input.text>

            <x-input.email wire:model.defer="user.email" :error="$errors->first('user.email')" required>
                Login Email
            </x-input.email>
        </div>

        <x-slot name="buttons">
            <x-button icon="check" color="green" type="submit">
                Update Profile
            </x-button>
        </x-slot>
    </x-box>
</form>

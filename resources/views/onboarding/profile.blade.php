<form wire:submit.prevent="submit">
    <x-box>
        <x-slot name="header">Personal Information</x-slot>

        <div class="grid divide-y">
            <div class="p-5">
                <x-input.text wire:model.defer="account.name" :error="$errors->first('account.name')" required>
                    Name
                </x-input.text>

                <x-input.email wire:model.defer="account.email" :error="$errors->first('account.email')" required>
                    Contact Email
                </x-input.email>
    
                <x-input.phone wire:model.defer="account.phone" :error="$errors->first('account.phone')" required>
                    Phone Number
                </x-input.phone>
            </div>
        </div>

        <x-slot name="buttons">
            <x-button color="green" icon="check" type="submit">
                Save Personal Information
            </x-button>
        </x-slot>
    </x-box>
</form>
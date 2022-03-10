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
    
                <x-input.date wire:model="account.dob">
                    Date of Birth
                </x-input.date>
    
                <x-input.gender wire:model="account.gender" :error="$errors->first('account.gender')" required>
                    Gender
                </x-input.gender>
            </div>

            <div class="p-5">
                <x-input.text wire:model.defer="account.address">
                    Address
                </x-input.text>

                <div class="grid gap-4 md:gap-0">
                    <div class="grid md:gap-4 md:grid-cols-2">
                        <x-input.text wire:model.defer="account.city">
                            City
                        </x-input.text>
    
                        <x-input.text wire:model.defer="account.postcode">
                            Postcode
                        </x-input.text>
                    </div>
    
                    <div class="grid md:gap-4 md:grid-cols-2">
                        <x-input.state wire:model="account.state" :country="$account->country ?? null">
                            State
                        </x-input.state>
    
                        <x-input.country wire:model="account.country">
                            Country
                        </x-input.country>
                    </div>
                </div>
            </div>
        </div>

        <x-slot name="buttons">
            <x-button color="green" icon="check" type="submit">
                Save Personal Information
            </x-button>
        </x-slot>
    </x-box>
</form>
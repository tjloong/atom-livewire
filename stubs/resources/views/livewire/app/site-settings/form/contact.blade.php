<form wire:submit.prevent="save" class="max-w-lg">
    <x-box>
        <x-slot name="header">Contact Information</x-slot>
        <div class="p-5">
            <x-input.text wire:model.defer="settings.company">
                Company
            </x-input.text>

            <x-input.text wire:model.defer="settings.phone">
                Phone
            </x-input.text>

            <x-input.phone wire:model.defer="settings.whatsapp">
                Whatsapp
            </x-input.phone>

            <x-input.text wire:model.defer="settings.email">
                Email
            </x-input.text>

            <x-input.textarea wire:model.defer="settings.address">
                Address
            </x-input.textarea>

            <x-input.text wire:model.defer="settings.facebook">
                Facebook
            </x-input.text>

            <x-input.text wire:model.defer="settings.instagram">
                Instagram
            </x-input.text>

            <x-input.text wire:model.defer="settings.twitter">
                Twitter
            </x-input.text>

            <x-input.text wire:model.defer="settings.linkedin">
                Linked In
            </x-input.text>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" icon="check" color="green">
                Save
            </x-button>
        </x-slot>
    </x-box>
</form>
<form wire:submit.prevent="submit" class="max-w-lg">
    <x-box>
        <div class="p-5">
            <x-input.text wire:model.defer="settings.company">
                Company
            </x-input.text>

            <x-input.text wire:model.defer="settings.phone">
                Phone
            </x-input.text>
                
            <x-input.text wire:model.defer="settings.email">
                Email
            </x-input.text>

            <x-input.textarea wire:model.defer="settings.address">
                Address
            </x-input.textarea>

            <x-input.text wire:model.defer="settings.gmap_url">
                Google Map URL
            </x-input.text>

            <x-input.textarea wire:model.defer="settings.briefs">
                Brief Description
            </x-input.textarea>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" icon="check" color="green">
                Save
            </x-button>
        </x-slot>
    </x-box>
</form>

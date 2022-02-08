<form wire:submit.prevent="save" class="max-w-lg">
    <x-box>
        <x-slot name="header">Google Map</x-slot>

        <div class="p-5">
            <x-input.text wire:model.defer="settings.gmap_api">
                Google Map API Key
            </x-input.text>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" color="green" icon="check">
                Save
            </x-button>
        </x-slot>
    </x-box>
</form>
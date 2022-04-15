<form wire:submit.prevent="submit">
    <x-box>
        <x-slot name="header">Analytics</x-slot>
        
        <div class="p-5">
            <x-input.text wire:model.defer="settings.ga_id">
                Google Analytics ID
            </x-input.text>
    
            <x-input.text wire:model.defer="settings.gtm_id">
                Google Tag Manager ID
            </x-input.text>
    
            <x-input.text wire:model.defer="settings.fbpixel_id">
                Facebook Pixel ID
            </x-input.text>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" color="green" icon="check">
                Save
            </x-button>
        </x-slot>
    </x-box>
</form>
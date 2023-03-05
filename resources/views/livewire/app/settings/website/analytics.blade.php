<x-form header="Website Analytics">
    <x-form.group>
        <x-form.text 
            label="Google Analytics ID"
            wire:model.defer="settings.ga_id"
        />
    
        <x-form.text 
            label="Google Tag Manager ID"
            wire:model.defer="settings.gtm_id"
        />
    
        <x-form.text 
            label="Facebook Pixel ID"
            wire:model.defer="settings.fbpixel_id"
        />
    </x-form.group>
</x-form>
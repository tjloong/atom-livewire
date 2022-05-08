<x-form header="Site Profile">
    <x-form.text 
        label="Company"
        wire:model.defer="settings.company"
    />

    <x-form.text 
        label="Phone"
        wire:model.defer="settings.phone"
    />
        
    <x-form.text 
        label="Email"
        wire:model.defer="settings.email"
    />

    <x-form.textarea 
        label="Address"
        wire:model.defer="settings.address"
    />

    <x-form.text 
        label="Google Map URL"
        wire:model.defer="settings.gmap_url"
    />

    <x-form.textarea 
        label="Brief Description"
        wire:model.defer="settings.briefs"
    />

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>

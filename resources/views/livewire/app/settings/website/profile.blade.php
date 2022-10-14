<x-form header="Website Profile">
    <x-form.text 
        label="Company"
        wire:model.defer="settings.company"
    />

    <x-form.textarea 
        label="Brief Description"
        wire:model.defer="settings.briefs"
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

    <x-form.checkbox
        label="Enable Whatsapp Bubble"
        wire:model="settings.whatsapp_bubble"
    />

    @if (data_get($settings, 'whatsapp_bubble'))
        <x-form.phone 
            label="Whatsapp Number"
            wire:model.defer="settings.whatsapp" 
        />

        <x-form.textarea 
            label="Whatsapp Prefill Text"
            wire:model.defer="settings.whatsapp_text"
        />
    @endif

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>

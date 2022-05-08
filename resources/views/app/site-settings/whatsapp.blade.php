<x-form header="Whatsapp Bubble">
    <x-form.checkbox 
        label="Enable Whatsapp bubble"
        wire:model="settings.whatsapp_bubble"
    />
    
    <div x-data="{ show: @entangle('settings.whatsapp_bubble') }" x-show="show" class="grid gap-6">
        <x-form.phone 
            label="Whatsapp Number"
            wire:model.defer="settings.whatsapp" 
        />

        <x-form.textarea 
            label="Prefill Text"
            wire:model.defer="settings.whatsapp_text"
        />
    </div>

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>

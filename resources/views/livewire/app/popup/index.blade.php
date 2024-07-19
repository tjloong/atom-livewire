<div x-init="@js($popupId) && $wire.emit('updatePopup', @js($popupId))" class="max-w-screen-xl mx-auto">
    <x-heading title="popup.heading.popup" xl>
        <x-button icon="add" label="popup.button.new" 
            wire:click="$emit('createPopup')"/>
    </x-heading>
    @livewire('app.popup.listing', key('listing'))
</div>
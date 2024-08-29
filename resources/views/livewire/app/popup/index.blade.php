<div
    x-init="$nextTick(() => id && $wire.emit('editPopup', { id }))"
    x-wire-on:edit-popup="id = $args?.id"
    x-wire-on:close-popup="id = null"
    class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.popup" xl>
        <x-button icon="add" label="app.label.new-popup" wire:click="$emit('editPopup')"/>
    </x-heading>
    <livewire:app.popup.listing wire:key="listing"/>
</div>
<div
    x-init="$nextTick(() => id && $wire.emit('editAnnouncement', { id }))"
    x-wire-on:edit-announcement="id = $args?.id"
    x-wire-on:close-announcement="id = null"
    class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.announcement" xl>
        <x-button icon="add" label="app.label.new-announcement" wire:click="$emit('editAnnouncement')"/>
    </x-heading>
    <livewire:root.announcement.listing wire:key="listing"/>
</div>
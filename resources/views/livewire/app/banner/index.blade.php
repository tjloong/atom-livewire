<div
    x-data="{ ulid: @entangle('ulid') }"
    x-init="$nextTick(() => ulid && $wire.emit('editBanner', { ulid }))"
    x-wire-on:edit-banner="ulid = $args?.ulid"
    x-wire-on:close-banner="ulid = null"
    class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.banner" xl>
        <x-button icon="add" label="app.label.new-banner" wire:click="$emit('editBanner')"/>
    </x-heading>
    <livewire:app.banner.listing wire:key="listing"/>
</div>
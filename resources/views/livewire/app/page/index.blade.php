<div
    x-data="{ id: @entangle('ulid') }"
    x-init="$nextTick(() => id && $wire.emit('editPage', id))"
    x-wire-on:edit-page="id = $args"
    x-wire-on:close-page="id = null"
    class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.page" xl/>
    <livewire:root.page.listing wire:key="listing"/>
</div>

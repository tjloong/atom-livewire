<div
    x-data="{ ulid: @entangle('ulid') }"
    x-init="$nextTick(() => ulid && $wire.emit('editPage', ulid))"
    x-wire-on:edit-page="ulid = $args"
    x-wire-on:close-page="ulid = null"
    class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.page" xl/>
    <livewire:app.page.listing wire:key="listing"/>
</div>

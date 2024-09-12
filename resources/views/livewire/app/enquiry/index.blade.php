<div
    x-data="{ ulid: @entangle('ulid') }"
    x-init="$nextTick(() => ulid && $wire.emit('editEnquiry', ulid))"
    x-wire-on:edit-enquiry="ulid = $args"
    x-wire-on:close-enquiry="ulid = null"
    class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.enquiry" xl/>
    <livewire:app.enquiry.listing wire:key="listing"/>
</div>
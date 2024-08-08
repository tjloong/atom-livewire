<div
    x-data="{ id: @entangle('enquiryId') }"
    x-init="$nextTick(() => id && $wire.emit('editEnquiry', id))"
    x-wire-on:edit-enquiry="id = $args"
    x-wire-on:close-enquiry="id = null"
    class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.enquiry" xl/>
    <livewire:app.enquiry.listing wire:key="listing"/>
</div>
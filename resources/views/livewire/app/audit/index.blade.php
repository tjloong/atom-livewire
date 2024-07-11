<div
    x-data="{ id: @entangle('auditId') }"
    x-init="$wire.emit('showAudit', id)"
    x-wire-on:show-audit="id = $args"
    x-wire-on:close-audit="id = null"
    class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.audit-trail" 2xl/>
    <livewire:app.audit.listing wire:key="listing"/>
</div>
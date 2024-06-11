<div
    x-data="{ id: @entangle('auditId') }"
    x-init="id && $wire.emit('showAudit', id)"
    x-on:audit-id.window="id = $event.detail"
    class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.audit-trail:2" 2xl/>
    @livewire('app.audit.listing', key('listing'))
</div>
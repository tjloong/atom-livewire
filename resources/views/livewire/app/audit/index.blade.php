<div x-init="@js($auditId) && $wire.emit('showAudit', @js($auditId))" class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.audit-trail:2" 2xl/>
    @livewire('app.audit.listing', key('listing'))
</div>
<div x-init="@js($notilogId) && $wire.emit('showNotilog', @js($notilogId))" class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.outbox" 2xl/>
    @livewire('app.notilog.listing', key('listing'))
</div>
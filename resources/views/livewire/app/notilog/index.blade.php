<div
    x-data="{ id: @entangle('notilogId') }"
    x-init="$wire.emit('showNotilog', { id })"
    x-wire-on:show-notilog="id = $args.id"
    x-wire-on:close-notilog="id = null"
    class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.outbox" xl/>
    @livewire('app.notilog.listing', key('listing'))
</div>
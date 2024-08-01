<div
    x-data="{ ulid: @entangle('ulid') }"
    x-init="$nextTick(() => ulid && $wire.emit('editSendmail', { ulid }))"
    x-wire-on:edit-sendmail="ulid = $args?.ulid"
    x-wire-on:close-sendmail="ulid = null"
    class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.outbox" xl/>
    <livewire:app.sendmail.listing wire:key="listing"/>
</div>

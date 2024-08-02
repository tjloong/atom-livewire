<div
    x-data="{ id: @entangle('signupId') }"
    x-init="$nextTick(() => id && $wire.emit('editSignup', id))"
    x-wire-on:edit-signup="id = $args"
    x-wire-on:close-signup="id = null"
    class="max-w-screen-xl mx-auto">
    <x-heading title="signup.heading.signup" xl/>
    <livewire:app.signup.listing wire:key="listing"/>
</div>
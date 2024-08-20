<div
    x-init="$nextTick(() => id && $wire.emit('editBlog', { id }))"
    x-wire-on:edit-blog="id = $args?.id"
    x-wire-on:close-blog="id = null"
    class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.blog" xl>
        <x-button icon="add" label="app.label.new-article" wire:click="$emit('editBlog')"/>
    </x-heading>
    <livewire:app.blog.listing wire:key="listing"/>
</div>
<div x-init="@js($blogId) && $wire.emit('updateBlog', @js($blogId))" class="max-w-screen-xl mx-auto">
    <x-heading title="common.label.blog:2" 2xl>
        <x-button icon="add" label="blog.label.new-article" wire:click="$emit('createBlog')"/>
    </x-heading>
    @livewire('app.blog.listing', key('listing'))
</div>
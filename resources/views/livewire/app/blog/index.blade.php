<div class="max-w-screen-xl mx-auto">
    <x-heading title="atom::blog.heading.blog" 2xl>
        <x-button icon="add"
            label="atom::blog.button.new"
            wire:click="$emit('createBlog')"/>
    </x-heading>
    @livewire('app.blog.listing', key('listing'))
</div>
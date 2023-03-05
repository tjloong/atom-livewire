<x-form>
    <x-form.group>
        <x-form.title label="Blog Title" wire:model.lazy="blog.title" />
        <x-form.textarea label="Blog Excerpt" wire:model.defer="blog.excerpt"/>

        <x-form.richtext
            label="Blog Content"
            wire:model.debounce.500ms="blog.content"
            :caption="$autosavedAt ? ('Auto saved at ' . format_date($autosavedAt, 'time-full')) : ''"
        />
    </x-form.group>
</x-form>

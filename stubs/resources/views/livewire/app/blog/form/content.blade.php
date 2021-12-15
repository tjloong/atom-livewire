<form wire:submit.prevent="save">
    <x-input.title wire:model.lazy="blog.title" :error="$errors->first('blog.title')">
        Blog Title
    </x-input.title>

    <x-input.richtext
        wire:model.debounce.500ms="blog.content"
        :error="$errors->first('blog.content')"
        :caption="$autosavedAt ? ('Auto saved at ' . format_date($autosavedAt, 'time-full')) : ''"
    >
        Blog Content
    </x-input.richtext>
    
    <x-button type="submit" color="green" icon="check">
        Save Blog
    </x-button>
</form>

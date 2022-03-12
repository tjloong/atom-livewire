<form wire:submit.prevent="submit">
    <x-input.title wire:model.lazy="blog.title" :error="$errors->first('blog.title')">
        Blog Title
    </x-input.title>

    <x-input.textarea wire:model.defer="blog.excerpt">
        Blog Excerpt
    </x-input.textarea>

    <x-input.richtext
        wire:model.debounce.500ms="blog.content"
        :error="$errors->first('blog.content')"
        :caption="$autosavedAt ? ('Auto saved at ' . format_date($autosavedAt, 'time-full')) : ''"
    >
        Blog Content
    </x-input.richtext>

    <div class="grid gap-4">
        @if ($errors->any())
            <x-alert :errors="$errors->all()"/>
        @endif
        
        <div>
            <x-button type="submit" color="green" icon="check">
                Save Blog
            </x-button>
        </div>
    </div>
</form>

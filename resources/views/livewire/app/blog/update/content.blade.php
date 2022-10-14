<x-form>
    <x-form.title 
        label="Blog Title"
        wire:model.lazy="blog.title" 
        :error="$errors->first('blog.title')"
    />

    <x-form.textarea 
        label="Blog Excerpt"
        wire:model.defer="blog.excerpt"
    />

    <x-form.richtext
        label="Blog Content"
        wire:model.debounce.500ms="blog.content"
        :caption="$autosavedAt ? ('Auto saved at ' . format_date($autosavedAt, 'time-full')) : ''"
    />

    @if ($errors->any())
        <x-alert :errors="$errors->all()"/>
    @endif

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>

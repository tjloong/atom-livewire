<x-form header="Blog SEO">
    <x-form.slug
        label="Slug"
        wire:model.defer="blog.slug" 
        prefix="blogs/"
        placeholder="Leave empty to auto generate"
    />

    <x-form.slug
        label="Redirect Slug"
        wire:model.defer="blog.redirect_slug" 
        prefix="blogs/"
    />

    <x-form.seo wire:model.defer="blog.seo"/>

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>

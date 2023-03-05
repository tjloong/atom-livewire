<x-form header="Blog SEO">
    <x-form.group>
        <x-form.slug wire:model.defer="blog.slug" prefix="blogs/" placeholder="Leave empty to auto generate"/>
        <x-form.slug wire:model.defer="blog.redirect_slug" prefix="blogs/"/>
        <x-form.seo wire:model.defer="blog.seo"/>
    </x-form.group>
</x-form>

<form wire:submit.prevent="save" class="max-w-lg">
    <x-box>
        <div class="p-5">
            <x-slot name="header">
                Blog SEO
            </x-slot>
            
            <x-input.slug wire:model.defer="blog.slug" prefix="blogs/" placeholder="Leave empty to auto generate">
                Slug
            </x-input.slug>

            <x-input.slug wire:model.defer="blog.redirect_slug" prefix="blogs/">
                Redirect Slug
            </x-input.slug>

            <x-input.seo wire:model.defer="blog.seo"/>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" color="green" icon="check">
                Save Blog SEO
            </x-button>
        </x-slot>
    </x-box>
</form>

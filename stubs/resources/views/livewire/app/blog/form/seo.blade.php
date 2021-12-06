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

            <x-input.text wire:model.defer="blog.seo.title" caption="Recommended title length is 50 ~ 60 characters">
                Meta Title
            </x-input.text>

            <x-input.textarea wire:model.defer="blog.seo.description" caption="Recommended description length is 155 ~ 160 characters">
                Meta Description
            </x-input.textarea>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" color="green" icon="check">
                Save Blog SEO
            </x-button>
        </x-slot>
    </x-box>
</form>

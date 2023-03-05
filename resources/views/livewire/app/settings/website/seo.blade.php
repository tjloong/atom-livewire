<x-form header="Website SEO">
    <x-form.group>
        <x-form.text 
            label="Meta Title"
            wire:model.defer="settings.seo_title" caption="Recommended title length is 50 ~ 60 characters"
        />
    
        <x-form.textarea 
            label="Meta Description"
            wire:model.defer="settings.seo_description" caption="Recommended description length is 155 ~ 160 characters"
        />
    
        <x-form.text 
            label="Meta Image URL"
            wire:model.defer="settings.seo_image"
        />
    </x-form.group>
</x-form>
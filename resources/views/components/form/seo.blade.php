<x-form.group :label="$attributes->get('label', 'SEO')">
    <x-form.text wire:model.defer="seo.title" label="Meta Title" caption="Recommended title length is 50 ~ 60 characters"/>
    <x-form.textarea wire:model.defer="seo.description" label="Meta Description" caption="Recommended description length is 155 ~ 160 characters"/>
    <x-form.text wire:model.defer="seo.image" label="Meta Image URL"/>
</x-form.group>

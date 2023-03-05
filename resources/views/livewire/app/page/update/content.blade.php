<x-form>
    <x-form.group>
        <x-form.field label="Page Name" :value="$page->name"/>
        <x-form.text label="Page Title" wire:model.defer="page.title"/>
        <x-form.field label="Page Slug" :value="'/'.$page->slug"/>
        <x-form.richtext label="Page Content" wire:model.defer="page.content"/>
    </x-form.group>
</x-form>

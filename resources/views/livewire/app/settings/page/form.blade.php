<x-form>
    <x-form.group>
        <x-form.field label="Page Name" :value="$page->name"/>
        <x-form.text wire:model.defer="page.title" label="Page Title"/>
        <x-form.text wire:model.defer="page.slug" label="Page Slug" prefix="/"/>
        <x-form.richtext wire:model.defer="page.content" label="Page Content"/>
    </x-form.group>
</x-form>
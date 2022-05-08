<x-form>
    <x-form.field label="Page Name">
        {{ $page->name }}
    </x-form.field>

    <x-form.text
        label="Page Title"
        wire:model.defer="page.title"
    />

    <x-form.slug
        label="Page Slug"
        wire:model.defer="page.slug" 
        prefix="/" 
        required
    />

    <x-form.richtext
        label="Page Content"
        wire:model.defer="page.content"
    />

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>

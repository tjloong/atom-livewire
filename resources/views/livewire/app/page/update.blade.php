<x-form.drawer class="max-w-screen-lg" wire:close="close()">
@if ($page)
    <x-slot:heading title="{!! $page->name !!}" :status="count(config('atom.locales')) > 1 ? $page->locale : null"></x-slot:heading>

    <x-group cols="2">
        <x-form.text label="app.label.title" wire:model.defer="page.title"/>
        <x-form.slug label="app.label.slug" wire:model.defer="page.slug"/>
    </x-group>

    <x-group>
        <x-form.editor label="app.label.content" wire:model.defer="page.content"/>
    </x-group>
@endif
</x-form.drawer>
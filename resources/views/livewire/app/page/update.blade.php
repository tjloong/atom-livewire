<x-form.drawer class="max-w-screen-lg">
@if ($page)
    <x-slot:heading 
        title="{!! $page->name !!}"
        :status="count(config('atom.locales')) > 1 ? ['blue' => $page->locale] : null"></x-slot:heading>

    <x-form.group cols="2">
        <x-form.text label="app.label.title" wire:model.defer="page.title"/>
        <x-form.slug label="app.label.slug" wire:model.defer="page.slug"/>
    </x-form.group>

    <x-form.group>
        <x-form.editor label="app.label.content" wire:model.defer="page.content"/>
    </x-form.group>
@endif
</x-form.drawer>
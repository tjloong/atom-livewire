<x-page wire:close="$emit('closePage')" class="max-w-screen-lg">
@if ($page)
    <x-heading xl
        title="{!! $page->name !!}"
        :status="count(config('atom.locales')) > 1 ? $page->locale : null">
    </x-heading>

    <x-form>
        <x-inputs>
            <x-input label="app.label.title" wire:model.defer="page.title"/>
            <x-input label="app.label.slug" wire:model.defer="page.slug"/>
            <x-editor label="app.label.content" wire:model.defer="page.content"/>
        </x-inputs>
    </x-form>
@endif
</x-page>